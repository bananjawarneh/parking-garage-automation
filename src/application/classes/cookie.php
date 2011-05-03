<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package    Park-a-Lot
 * @subpackage Cookie
 * @author     Abdul Hassan
 * @copyright  (c) 2011 The authors
 * @license    see LICENSE
 */
class Cookie extends Kohana_Cookie
{
	// A salt is required to set cookies
	public static $salt = 'Parka L0t Garage Syst3m 2013';
} // End Cookie