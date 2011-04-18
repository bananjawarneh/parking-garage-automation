<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Edit reservation view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Reservation_Edit extends View_Base
{
	public $reservation_id;

	public $form = array(
		'extension' => NULL,
	);

	public function action()
	{
		return 'reservation/edit/'.$this->reservation_id;
	}

	public function reservation()
	{
		$reservation = ORM::factory('reservation')->where('id', '=', $this->reservation_id)->find();

		$duration = Date::span($reservation->end_time, $reservation->start_time, 'hours,minutes');

		return array(
			'start_time' => date('M jS, g:i a', $reservation->start_time),
			'end_time'   => date('M jS, g:i a', $reservation->end_time),
			'duration'   => $duration['hours'].'h '.$duration['minutes'].'m',
		);
	}

	public function extensions()
	{
		$extensions = array();

		for ($i = -6; $i <= 6; $i++)
		{
			if ($i === 0)
			{
				continue;
			}

			$seconds = $i * 30 * 60;
			$span = Date::span($seconds, 0, 'hours,minutes');
			$name = ($i > 0) ? 'Increase by' : 'Decrease by';

			$extensions[] = array(
				'value' => $i,
				'name'  => $name.' '.str_pad($span['hours'], 2, 0, STR_PAD_LEFT).':'.str_pad($span['minutes'], 2, 0, STR_PAD_LEFT),
				'selected' => ($seconds == $this->form['extension']),
			);
		}

		return $extensions;
	}
} // End View_Reservation_Edit