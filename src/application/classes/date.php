<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package   Park-a-Lot
 * @category  Helper
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class Date extends Kohana_Date
{
	/**
	 * Returns an array of am/pm to be used in a select list.
	 *
	 * @return array
	 */
	public static function meridians()
	{
		return array(
			'am' => 'am',
			'pm' => 'pm',
		);
	}
	
	/**
	 * Checks if there is enough time between two timestamps. The difference is
	 * not an absolute difference, so order of parameters counts.
	 *
	 * @param  mixed any date format
	 * @param  mixed any date format, preferably one after the first timestamp
	 * @param  int
	 * @return bool
	 */
	public static function min_span($time1, $time2, $min_span)
	{
		// Convert to timestamps if not already
		$time1 = ($time1 === (int) $time1) ? $time1 : strtotime($time1);
		$time2 = ($time2 === (int) $time2) ? $time2 : strtotime($time2);

		return (($time2 - $time1) >= $min_span);
	}

	/**
	 * Checks if there is too much time between two timestamps. The difference is
	 * not an absolute difference, so order of parameters counts.
	 *
	 * @param  mixed any date format
	 * @param  mixed any date format, preferably after the first timestamp
	 * @param  int
	 * @return bool
	 */
	public static function max_span($time1, $time2, $max_span)
	{
		// Convert to timestamps if not already
		$time1 = ($time1 === (int) $time1) ? $time1 : strtotime($time1);
		$time2 = ($time2 === (int) $time2) ? $time2 : strtotime($time2);

		return (($time2 - $time1) <= $max_span);
	}

	/**
	 * Returns an HTML calendar.
	 *
	 * @param  int
	 * @param  int
	 * @param  array
	 * @param  int
	 * @param  string
	 * @param  int
	 * @param  array
	 * @return string
	 */
	public static function calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array())
	{
		require_once Kohana::find_file('vendor', 'generate_calendar');

		return generate_calendar($year, $month, $days, $day_name_length, $month_href, $first_day, $pn);
	}
} // End Date