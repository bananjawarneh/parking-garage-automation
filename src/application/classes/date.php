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
	 * @param  mixed any date format, preferably after the first timestamp
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
} // End Date