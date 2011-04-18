<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Defines different session flags.
 *
 * @todo is this the right place to define these constants?
 *
 * @package    Park-a-Lot
 * @subpackage Session
 * @author     Abdul Hassan
 * @copyright  (c) 2011 The authors
 * @license    see LICENSE
 */
abstract class Session extends Kohana_Session
{
	const NEW_USER         = 'new_user';
	const NEW_RESERVATION  = 'new_reservation';
	const EDIT_RESERVATION = 'edit_reservation';
} // End Session