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
		'date' => NULL,
		'time' => array(
			'hour'     => NULL,
			'minute'   => NULL,
			'meridian' => NULL,
		),
		'duration'   => NULL,
		'recurrence' => NULL,
		'end_recurrence' => NULL,
	);

	/**
	 * Default the start time to exactly three hours in the future.
	 *
	 * @return array
	 */
	public function form()
	{
		if ($this->form['date'] == NULL)
		{
			// Show these as defaults
			$this->form['date'] = date('m/j/Y', time() + (3 * Date::HOUR));
			$this->form['time']['hour']     = date('g', time() + (3 * Date::HOUR));
			$this->form['time']['meridian'] = date('a', time() + (3 * Date::HOUR));
		}

		return $this->form;
	}

	/**
	 * Returns an array of hours.
	 *
	 * @return array
	 */
	public function hours()
	{
		$hours = array();

		foreach (Date::hours() as $k => $v)
		{
			$hours[] = array(
				'value' => $v,
				'name'  => $k,
				'selected' => ($v == $this->form['time']['hour']),
			);
		}

		return $hours;
	}

	/**
	 * Returns an array of minutes, in 30 minute blocks.
	 *
	 * @return array
	 */
	public function minutes()
	{
		$minutes = array();

		foreach (Date::minutes(30) as $k => $v)
		{
			$minutes[] = array(
				'value' => $v,
				'name'  => $v,
				'selected' => ($v == $this->form['time']['minute']),
			);
		}

		return $minutes;
	}

	/**
	 * Returns an array of meridians.
	 *
	 * @return array
	 */
	public function meridians()
	{
		$meridians = array();

		foreach (Date::meridians() as $k => $v)
		{
			$meridians[] = array(
				'value' => $k,
				'name'  => $v,
				'selected' => ($v == $this->form['time']['meridian']),
			);
		}

		return $meridians;
	}

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
				'selected' => (Date::DAY == $this->form['recurrence']),
			),
			array(
				'value' => Date::DAY * 7,
				'name'  => 'Weekly',
				'selected' => ((Date::DAY * 7) == $this->form['recurrence']),
			),
			array(
				'value' => Date::DAY * 28,
				'name'  => 'Monthly (28 days)',
				'selected' => ((Date::DAY * 28) == $this->form['recurrence']),
			),
		);
	}
} // View_Reservation_Create