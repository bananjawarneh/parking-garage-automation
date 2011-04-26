<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Reservation model.
 *
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_Reservation extends ORM
{
	/** Min time difference between current time and start time, when creating */
	const CURRENT_TIME_START_TIME_GAP = 1800;

	/** Max time difference between current time and start time (12 weeks) */
	const CURRENT_TIME_START_TIME_MAX_GAP = 7257600;

	/** Min time difference between start and end of reservation (min length) */
	const START_TIME_END_TIME_GAP = 1800;

	/** Min time between current time and end time, when updating */
	const CURRENT_TIME_END_TIME_GAP = 1800;

	/** Can only extend/descrese reservations in discrete time increments */
	const EXTENSION_TIME_BLOCK = 1800;

	protected $_belongs_to = array(
		'user' => array('model' => 'user'),
	);

	protected $_has_one = array(
		'vehicle' => array('model' => 'vehicle'),
	);

	protected $_created_column = array(
		'column' => 'date_added',
		'format' => TRUE,
	);

	protected $_updated_column = array(
		'column' => 'last_edited',
		'format' => TRUE,
	);

	protected $_sorting = array(
		'start_time' => 'ASC',
		'end_time'   => 'ASC',
	);

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim', array(':value')),
			),
		);
	}

	public function labels()
	{
		$labels = array();

		foreach ($this->_object as $field => $value)
		{
			$labels[$field] = str_replace('_', ' ', ucfirst($field));
		}

		return $labels;
	}

	/**
	 * User must not be empty and must exist.
	 * Start time must not be empty, and must fall on a half hour.
	 * End time must not be empty, must be far enough from the start time, and
	 * must fall on a half hour.
	 *
	 * If creating a new reservation, start_time must be far enough in the future,
	 * but not TOO far.
	 * When editing a reservation, end_time must be far enough in the future.
	 *
	 * @return array
	 * @todo   check for an abundance of pre existing reservations for the reqeusted duration
	 */
	public function rules()
	{
		$rules = array(
			'user_id' => array(
				array('not_empty'),
				array(array(ORM::factory('user'), 'exists'), array(':value', 'id')),
			),
			'vehicle_id' => array(
				array(array(ORM::factory('vehicle'), 'exists'), array(':value', 'id')),
			),
			'start_time' => array(
				array('not_empty'),
				array('Model_Reservation::on_half_hour', array(':validation', ':field')),
			),
			'end_time' => array(
				array('not_empty'),
				array('Model_Reservation::min_reservation_length', array(':validation')),
				array('Model_Reservation::on_half_hour', array(':validation', ':field')),
			),
		);

		if ( ! $this->loaded())
		{
			// Create rules
			$rules['start_time'][] = array('Model_Reservation::min_time_before_start', array(':validation'));
			$rules['start_time'][] = array('Model_Reservation::max_time_before_start', array(':validation'));
		}
		else
		{
			// Update rules
			$rules['end_time'][] = array('Model_Reservation::min_time_before_end', array(':validation'));
		}

		return $rules;
	}

	/**
	 * Sets the end time using the start time and duration. Transforms any time
	 * given to a timestamp.
	 *
	 * @see ORM::values
	 */
	public function values(array $values, array $expected = NULL)
	{
		if (isset($values['date']) AND isset($values['time']))
		{
			// Build start time from date and time
			$values['start_time'] = $values['date'].' '
			                      . $values['time']['hour'].':'.$values['time']['minute']
			                      . $values['time']['meridian'];
		}

		if(isset($values['start_time']))
		{
			if ($values['start_time'] !== (int) $values['start_time'])
			{
				// Convert to timestamp
				$values['start_time'] = strtotime($values['start_time']);
			}
		}

		if (isset($values['end_time']))
		{
			if ($values['end_time'] !== (int) $values['end_time'])
			{
				// Convert to timestamp
				$values['end_time'] = strtotime($values['end_time']);
			}
		}

		if (isset($values['start_time'], $values['duration']) AND ! isset($values['end_time']))
		{
			// Build end time from start time and duration
			$values['end_time'] = $values['start_time'] + (int) $values['duration'];
		}

		if (isset($values['extension']) AND ! isset($values['end_time']))
		{
			$values['end_time'] = $this->end_time + ($values['extension'] * self::EXTENSION_TIME_BLOCK);
		}

		if (isset($values['vehicle_id']) AND empty($values['vehicle_id']))
		{
			$values['vehicle_id'] = NULL;
		}

		return parent::values($values, $expected);
	}

	/**
	 * Validates and saves a new reservation record. Also checks for an optional
	 * recurring reservation.
	 *
	 * @param  array $values
	 * @param  bool  $belongs_to_recurrence whether this belongs to a chain of
	 *                                      recurrences, or is the parent reservation
	 * @return bool
	 */
	public function create_reservation(array $values, $belongs_to_recurrence = FALSE)
	{
		$this->values($values, array(
			'user_id',
			'vehicle_id',
			'start_time',
			'end_time',
		));

		if ($belongs_to_recurrence === FALSE)
		{
			// Validate optional recurrence
			$this->create(self::recurring_validation($values));

			if (isset($values['recurrence']) AND ! empty($values['recurrence']))
			{
				// This is indeed a recurring reservation
				$this->create_recurring_reservations($values);
			}
		}
		else
		{
			// Treat it as a regular one time reservation
			$this->create();
		}

		return TRUE;
	}

	/**
	 * Validates and updates this reservation. The only edit that can be made
	 * to a reservation is changing the length, either increasing or shortening
	 * it.
	 *
	 * @param  array $values
	 * @param  bool  $parent_reservation whether this is the parent reservation
	 * @return bool
	 */
	public function update_reservation(array $values, $parent_reservation = TRUE)
	{
		$this->values($values, array(
			'end_time',
		))
		->update();

		if ($this->recurring)
		{
			$next_reservation = ORM::factory('reservation')
				->where('previous_id', '=', $this->id)
				->where('recurring', '=', TRUE)
				->find();
			
			if (Arr::get($values, 'update_all') == 1)
			{
				if ($next_reservation->loaded())
				{
					// Update all reservations in recurrence
					$next_reservation->update_reservation($values, FALSE);
				}
			}
			else
			{
				if ($next_reservation->loaded())
				{
					// Close the chain
					$next_reservation
						->set('previous_id', $this->previous_id)
						->save();
				}

				// Break out of recurrence
				$this->set('recurring', FALSE)->save();
			}
		}

		if ($parent_reservation)
		{
			// Break all ties with previous reservations in the recurrence
			$this->set('previous_id', NULL)->save();
		}

		return TRUE;
	}

	/**
	 * Cancels this reservation by changing its status.
	 *
	 * @param  array $values
	 * @return bool
	 * @todo   take recurring reservations into account
	 */
	public function cancel_reservation(array $values, $parent_reservation = TRUE)
	{
		if ($parent_reservation)
		{
			$validation = Validation::factory($this->as_array())
				->rule('start_time', array($this, 'min_time_before_cancel'), array(':validation'));

			$this->set('active', FALSE)->update($validation);
		}
		
		if ($this->recurring)
		{
			$next_reservation = ORM::factory('reservation')
				->where('previous_id', '=', $this->id)
				->where('recurring', '=', TRUE)
				->find();

			if (Arr::get($values, 'cancel_all') == 1)
			{
				if ($next_reservation->loaded())
				{
					// Re run the function
					$next_reservation->cancel_reservation($values, FALSE);

					// Delete the entire chain
					$next_reservation->delete();
				}
			}
			else
			{
				if ($next_reservation->loaded())
				{
					// Close the chain
					$next_reservation
						->set('previous_id', $this->previous_id)
						->save();
				}

				// Break out of recurrence
				$this->set('recurring', FALSE)->save();
			}
		}

		if ($parent_reservation)
		{
			// Break all ties with previous reservations in the recurrence
			$this->set('previous_id', NULL)->save();
		}

		return TRUE;
	}

	/**
	 * Adds extra validation to recurring validations.
	 * If a recurrence is set, a valid end date for recurrence must be set.
	 *
	 * @param  array $values
	 * @return Validation
	 */
	protected static function recurring_validation(array $values)
	{
		return Validation::factory($values)
			->rules('recurrence', array(
				array('digit'),
			))
			->rules('end_recurrence', array(
				array('Model_Reservation::end_recurrence_exists', array(':validation')),
			));
	}

	/**
	 * If recurrence is set, so must end_recurrence field.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public static function end_recurrence_exists(Validation $array)
	{
		if (isset($array['recurrence']) AND ! empty($array['recurrence']))
		{
			if ( ! isset($array['end_recurrence']) OR strtotime($array['end_recurrence']) === FALSE)
			{
				// Empty or invalid end time for recurrence
				$array->error('end_recurrence', 'not_empty');
			}

			if ( ! Date::max_span(time(), $array['end_recurrence'], self::CURRENT_TIME_START_TIME_MAX_GAP))
			{
				// Too far in the future
				$array->error('end_recurrence', 'max_time_before_start');
			}
		}
	}

	/**
	 * Reservations must be a minimum length of time.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public static function min_reservation_length(Validation $array)
	{
		// Check time between start time and end time
		if ( ! Date::min_span($array['start_time'], $array['end_time'], self::START_TIME_END_TIME_GAP))
		{
			$array->error('end_time', 'min_reservation_length');
		}
	}

	/**
	 * Reservations must be made far enough in advance.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public static function min_time_before_start(Validation $array)
	{
		// Check the time between current time and start time
		if ( ! Date::min_span(time(), $array['start_time'], self::CURRENT_TIME_START_TIME_GAP))
		{
			$array->error('start_time', 'min_time_before_start');
		}
	}

	/**
	 * Reservations can be made no further than a few months in advance.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public static function max_time_before_start(Validation $array)
	{
		// Check the time between current time and start time
		if ( ! Date::max_span(time(), $array['start_time'], self::CURRENT_TIME_START_TIME_MAX_GAP))
		{
			$array->error('start_time', 'max_time_before_start');
		}
	}

	/**
	 * Reservations can only be edited enough time prior to their ending.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public static function min_time_before_end(Validation $array)
	{
		// Check time between current time and end time
		if ( ! Date::min_span(time(), $array['end_time'], self::CURRENT_TIME_END_TIME_GAP))
		{
			$array->error('end_time', 'min_time_before_end');
		}
	}

	/**
	 * Reservations can only be cancelled enough time prior to their starting.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public function min_time_before_cancel(Validation $array)
	{
		// Check time between current time and start time
		if ( ! Date::min_span(time(), $array['start_time'], self::CURRENT_TIME_START_TIME_GAP))
		{
			$array->error('start_time', 'min_time_before_cancel');
		}
	}

	/**
	 * Ensures that the time chosen falls on a half hour, and isnt just any
	 * free form time. i.e, 8:30, and not 8:29.
	 *
	 * @param  Validation $array
	 * @param  string     $field
	 * @return void
	 */
	public static function on_half_hour(Validation $array, $field)
	{
		$time_block = 1800;

		if ($array[$field] % $time_block !== 0)
		{
			// Not a multiple of 1800 seconds (30 minutes)
			$array->error($field, 'on_half_hour');
		}
	}

	/**
	 * Sets this reservation as recurring and creates multiple duplicates.
	 *
	 * @param  array $values
	 * @return bool
	 */
	private function create_recurring_reservations(array $values)
	{
		$recurrence = $values['recurrence'];
		$max_date   = strtotime($values['end_recurrence']);
		$values     = array(
			'user_id'    => $this->user_id,
			'vehicle_id' => $this->vehicle_id,
		);
		$previous_id = $this->id;

		// Add one more day to account for the last day of the recurrence
		$max_date += Date::DAY;

		// Dont make more than 84 reservations at a time, which is 12 weeks for daily
		for ($i = 1; $i <= 84; $i++)
		{
			$shift = ($i * $recurrence);

			// Shift the reservation time slot
			$values['start_time'] = $this->start_time + $shift;
			$values['end_time']   = $this->end_time   + $shift;
			
			if ($values['start_time'] >= $max_date)
			{
				break;
			}

			// Link this reservation to the one before it (linked list)
			$reservation = ORM::factory('reservation')
				->set('recurring', TRUE)
				->set('previous_id', $previous_id);
			$reservation->create_reservation($values, TRUE);

			$previous_id = $reservation->id;
		}

		$this->set('recurring', TRUE)->save();

		return TRUE;
	}
} // End Model_Reservation