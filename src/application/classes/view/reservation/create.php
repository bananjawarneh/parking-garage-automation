<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Create reservation view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Reservation_Create extends View_Base
{
	public $title = 'Create a new reservation';

	public $styles = array(
		array(
			'href'  => 'media/css/calendar.css',
			'media' => 'all',
		),
	);

	public $scripts = array(
		'media/js/calendar.js',
	);

	public $inline_js = "
		$(document).ready(function(){
			start_time = new Epoch('epoch_popup', 'popup', document.getElementById('start_time'), false);
			end_recurrence = new Epoch('epoch_popup', 'popup', document.getElementById('end_recurrence'), false);
		});";

	public $form = array(
		'start_time' => NULL,
		'duration'   => NULL,
		'recurrence' => NULL,
		'end_recurrence' => NULL,
	);

	/**
	 * Returns an array of reservation durations. 30 minute increments from
	 * 30 minutes to 24 hours.
	 *
	 * @return array
	 */
	public function durations()
	{
		$duration = array();
		
		foreach (range(1, 48) as $i)
		{
			$seconds = $i * 30 * 60;
			$span = Date::span($seconds, 0, 'hours,minutes');

			$duration[] = array(
				'value' => $seconds,
				'name'  => str_pad($span['hours'], 2, 0, STR_PAD_LEFT).':'.str_pad($span['minutes'], 2, 0, STR_PAD_LEFT),
				'selected' => ($seconds == $this->form['duration']),
			);
		}

		return $duration;
	}

	/**
	 * Returns an array of acceptable recurrence periods. Daily, weekly, etc.
	 *
	 * @return array
	 */
	public function recurrences()
	{
		return array(
			array(
				'value' => Date::DAY,
				'name'  => 'Daily',
			),
			array(
				'value' => Date::DAY * 7,
				'name'  => 'Weekly',
			),
			array(
				'value' => Date::DAY * 30,
				'name'  => 'Monthly (28 days)',
			),
		);
	}
} // View_Reservation_Create