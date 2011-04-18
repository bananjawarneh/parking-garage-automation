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
	public $styles = array(
		array(
			'href'  => 'media/css/calendar.css',
			'media' => 'all',
		),
	);
	
	public function title()
	{
		return $this->full_name();
	}

	public function full_name()
	{
		return $this->user->first_name.' '.$this->user->last_name;
	}

	/**
	 * Returns a calendar highlighting this months reservations.
	 *
	 * @return string
	 */
	public function calendar()
	{
		$reservations = array(
			'this_month' => $this->user->reservations
				->where('start_time', '>', time())
				->where('start_time', '<', mktime(0, 0, 0, date('n') + 1, 0))
				->find_all(),
			'next_month' => $this->user->reservations
				->where('start_time', '>', mktime(0, 0, 0, date('n') + 1, 0))
				->where('start_time', '<', mktime(0, 0, 0, date('n') + 2, 0))
				->find_all(),
		);

		return $this->reservations_by_month($reservations['this_month'], date('n'))
			. $this->reservations_by_month($reservations['next_month'], date('n') + 1);
	}

	/**
	 * Plots a calendar full of reservations for a given month.
	 *
	 * @param  MySQL_Result
	 * @param  int
	 * @return string
	 */
	public function reservations_by_month($reservations, $month)
	{
		$days = array();

		foreach ($reservations as $reservation)
		{
			$day = date('j', $reservation->start_time);

			$days[$day] = array(
				0 => '#',     // URL to link to
				1 => 'event', // Class to add to calendar cell
			);
		}

		if (date('M j Y') === date('M j Y', mktime(0, 0, 0, $month)))
		{
			// Highlight todays date
			$days[date('j')][1] = 'today';
		}

		return Date::calendar(date('Y'), $month, $days);
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
				'edit_url'   => URL::site('reservation/edit/'.$reservation->id),
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
		$this->partial('calendar_reservations', 'partials/calendar_reservations');

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