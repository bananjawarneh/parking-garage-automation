<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Recognized vehicle in simulation.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_Recognized extends View_Simulation_Base
{
	public $reservations = array();

	private $_ran = FALSE;

	/**
	 * Returns an array of all reservations that either started in the past 30
	 * minutes, or start in the next 30 minutes.
	 */
	public function reservations()
	{
		if ($this->_ran)
		{
			return $this->reservations;
		}

		$close_reservations = $this->_user->reservations
			->where('start_time', '>=', time() - 1800)
			->where('start_time', '<=', time() + 1800)
			->find_all();

		foreach ($close_reservations as $reservation)
		{
			$duration = Date::span($reservation->end_time, $reservation->start_time, 'hours,minutes');
			
			$this->reservations[] = array(
				'id'         => $reservation->id,
				'start_time' => date('l M jS, g:i a', $reservation->start_time),
				'end_time'   => date('l M jS, g:i a', $reservation->end_time),
				'duration'   => $duration['hours'].'h '.$duration['minutes'].'m',
			);
		}

		$this->_ran = TRUE;

		return $this->reservations;
	}

	/**
	 * Flag to check if reservations exist before trying to display them.
	 *
	 * @return bool
	 */
	public function reservations_exist()
	{
		return ! empty($this->reservations);
	}

	public function render()
	{
		// Perform the search for reservations
		$this->reservations();

		return parent::render();
	}
} // End View_Simulation_Recognized