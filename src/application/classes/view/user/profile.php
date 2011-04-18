<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User profile view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_User_Profile extends View_Base
{
	public function title()
	{
		return $this->full_name();
	}

	public function full_name()
	{
		return $this->user->first_name.' '.$this->user->last_name;
	}

	/**
	 * Returns an array of some of this users reservations.
	 *
	 * @return array
	 */
	public function reservations()
	{
		$reservations = array();
		
		foreach ($this->user->reservations->find_all() as $reservation)
		{
			$duration = Date::span($reservation->end_time, $reservation->start_time, 'hours,minutes');
			
			$reservations[] = array(
				'start_time' => date('M jS, g:i a', $reservation->start_time),
				'end_time'   => date('M jS, g:i a', $reservation->end_time),
				'duration'   => $duration['hours'].'h '.$duration['minutes'].'m',
				'recurring'  => $reservation->recurring,
			);
		}

		return $reservations;
	}

	/**
	 * Adds notifications to the template.
	 *
	 * @return string
	 */
	public function render()
	{
		$this->partial('reservations', 'partials/reservations');

		if (Session::instance()->get_once(Session::NEW_USER))
		{
			$this->notifications[] = 'Welcome to Park a Lot, the answer to your
				parking needs. Check out our '.HTML::anchor(NULL, 'FAQ');
		}

		if (Session::instance()->get_once(Session::NEW_RESERVATION))
		{
			$this->notifications[] = 'Your reservation has been created successfully.';
		}

		return parent::render();
	}
} // End View_User_Profile