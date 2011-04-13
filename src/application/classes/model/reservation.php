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
	protected $_belongs_to = array(
		'user' => array('model' => 'user'),
	);

	protected $_created_column = array(
		'column' => 'date_added',
		'format' => TRUE,
	);

	/**
	 * User must not be empty and must exist.
	 * Start time must not be empty.
	 * End time must not be empty.
	 *
	 * @return array
	 * @todo   this is far from enough rules
	 * @todo   check for an abundance of pre existing reservations for the reqeusted duration
	 */
	public function rules()
	{
		return array(
			'user_id' => array(
				array('not_empty'),
				array(array(ORM::factory('user'), 'exists'), array(':value', 'id')),
			),
			'start_time' => array(
				array('not_empty'),
			),
			'end_time' => array(
				array('not_empty'),
			),
		);
	}

	/**
	 * Sets the end time using the start time and duration. Transforms any time
	 * given to a timestamp.
	 *
	 * @param  array $values
	 * @param  array $expected
	 * @return ORM
	 */
	public function values(array $values, array $expected = NULL)
	{
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

		return parent::values($values, $expected);
	}

	/**
	 * Validates and saves a new reservation record. Also checks for an optional
	 * recurring reservation.
	 *
	 * @param  array $values
	 * @param  bool  $belongs_to_recurrence Whether this belongs to a chain of recurrences
	 * @return bool
	 */
	public function create_reservation(array $values, $belongs_to_recurrence = FALSE)
	{
		$this->values($values, array(
			'user_id',
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
		}
	}

	/**
	 * Sets this reservation as recurring and creates multiple duplicates.
	 *
	 * @param  array $values
	 * @return bool
	 * @todo   although theres a max of 30 reservations, ensure that an end_recurrence date is set
	 */
	private function create_recurring_reservations(array $values)
	{
		$recurrence = $values['recurrence'];
		$max_date   = strtotime($values['end_recurrence']);
		$values     = array(
			'user_id' => $this->user_id,
		);
		$previous_id = $this->id;

		// Dont make more than 28 reservations at a time?
		for ($i = 1; $i <= 28; $i++)
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