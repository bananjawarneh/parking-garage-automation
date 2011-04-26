<?php defined('SYSPATH') or die('No direct script access.');
/**
 * List reservations view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Reservation_List extends View_Base
{
	public $title = 'View All Reservations';

	public $day;

	public $filter;

	public function filters()
	{
		$current_uri = Request::current()->uri();
		
		return array(
			'past'      => $current_uri.URL::query(array('f' => 'past')),
			'current'   => $current_uri.URL::query(array('f' => 'current')),
			'future'    => $current_uri.URL::query(array('f' => 'future')),
			'cancelled' => $current_uri.URL::query(array('f' => 'cancelled')),
		);
	}
	
	/**
	 * Returns an array of some of this users reservations.
	 *
	 * @return array
	 */
	public function reservations()
	{
		$reservations = array();

		$_reservations = $this->user->reservations;

		if ($this->day !== NULL)
		{
			$_reservations
				->where('start_time', '>', $this->day)
				->where('start_time', '<', $this->day + Date::DAY);
		}

		if ($this->filter !== NULL)
		{
			switch ($this->filter)
			{
				case 'past':
					$_reservations
						->where('end_time', '<', time())
						->where('active', '=', TRUE);
				break;

				case 'current':
					$_reservations
						->where('start_time', '<', time())
						->where('end_time', '>', time())
						->where('active', '=', TRUE);
				break;

				case 'future':
					$_reservations
						->where('start_time', '>', time())
						->where('active', '=', TRUE);
				break;

				case 'cancelled':
					$_reservations
						->where('active', '=', FALSE);
				break;

				default:
					// Invalid filter
					$this->filter = NULL;
				break;
			}
		}

		if ($this->filter !== NULL)
		{
			$this->filter = ucfirst($this->filter);
		}

		foreach ($_reservations->find_all() as $reservation)
		{
			$duration = Date::span($reservation->end_time, $reservation->start_time, 'hours,minutes');

			if ($reservation->active == FALSE)
			{
				$class = 'cancelled';
			}
			else if ($reservation->start_time > time())
			{
				$class = 'future';
			}
			else if ($reservation->end_time < time())
			{
				$class = 'past';
			}
			else
			{
				$class = 'current';
			}

			$reservations[] = array(
				'active'     => $reservation->active,
				'start_time' => date('l M jS, g:i a', $reservation->start_time),
				'end_time'   => date('l M jS, g:i a', $reservation->end_time),
				'duration'   => $duration['hours'].'h '.$duration['minutes'].'m',
				'recurring'  => $reservation->recurring,
				'edit_url'   => URL::site('reservation/edit/'.$reservation->id),
				'class'      => $class,
				'editable'   => Date::min_span(time(), $reservation->end_time,
					Model_Reservation::CURRENT_TIME_END_TIME_GAP),
			);
		}

		return $reservations;
	}

	public function reservations_exist()
	{
		return (bool) $this->user->reservations->count_all();
	}

	public function day()
	{
		if ($this->day !== NULL)
		{
			return date('l F jS, Y', $this->day);
		}
	}
} // End View_Reservation_List