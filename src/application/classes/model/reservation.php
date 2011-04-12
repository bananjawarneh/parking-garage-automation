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
			$values['end_time'] = $values['start_time'] + (int) $values['duration'];
		}

		return parent::values($values, $expected);
	}

	/**
	 * Validates the data given, and upon success, saves it in the database.
	 *
	 * @param  array $value
	 * @return bool
	 */
	public function create_reservation(array $values)
	{
		$this->values($values, array(
			'user_id',
			'start_time',
			'end_time',
		))
		->create();

		if (isset($values['recurrence']) AND is_numeric($values['recurrence']))
		{
			$this->create_recurring_reservations($values);
		}

		return TRUE;
	}

	/**
	 * Sets this reservation as recurring and creates multiple duplicates.
	 *
	 * @param  array $values
	 * @return bool
	 * @todo   although theres a max of 30 reservations, ensure that an end_recurrence date is set
	 */
	protected function create_recurring_reservations(array $values)
	{
		$recurrence = (int) $values['recurrence'];
		$max_date   = strtotime($values['end_recurrence']);

		// Avoid any infinite loops
		unset($values['recurrence']);

		// Each recurring reservation should be tied to the one before it
		$previous_id = $this->id;

		// Dont make more than 30 reservations at a time
		for ($i = 1; $i <= 30; $i++)
		{
			// Shift the start time by the recurrence rate
			$values['start_time'] = $this->start_time + ($i * $recurrence);

			if ($values['start_time'] >= $max_date)
			{
				break;
			}

			// Link this reservation to the one before it
			$reservation = ORM::factory('reservation')
				->set('recurring', TRUE)
				->set('previous_id', $previous_id);
			$reservation->create_reservation($values);

			$previous_id = $reservation->id;
		}

		$this->set('recurring', TRUE)->save();

		return TRUE;
	}
} // End Model_Reservation