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
	public $title = 'Edit Reservation';

	public $reservation_id;

	public $reservation;

	public $ask_confirmation = FALSE;

	public $form = array(
		'extension' => 2,
	);

	public function action()
	{
		return 'reservation/edit/'.$this->reservation_id;
	}

	public function reservation()
	{
		$duration = Date::span($this->reservation->end_time, $this->reservation->start_time, 'hours,minutes');

		return array(
			'start_time' => date('M jS, g:i a', $this->reservation->start_time),
			'end_time'   => date('M jS, g:i a', $this->reservation->end_time),
			'duration'   => $duration['hours'].'h '.$duration['minutes'].'m',
			'recurring'  => ($this->reservation->recurring) ? 'Y' : 'N',
		);
	}

	public function recurring()
	{
		return (bool) $this->reservation->recurring;
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
				'selected' => ($i == $this->form['extension']),
			);
		}

		return $extensions;
	}

	public function can_cancel()
	{
		return Date::min_span(time(), $this->reservation->start_time,
			Model_Reservation::CURRENT_TIME_START_TIME_GAP);
	}

	public function render()
	{
		$this->reservation = ORM::factory('reservation')->where('id', '=', $this->reservation_id)->find();

		return parent::render();
	}
} // End View_Reservation_Edit