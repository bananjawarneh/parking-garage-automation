<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Defines different session flags.
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
	const NEW_VEHICLE      = 'new_vehicle';
	const EDIT_RESERVATION = 'edit_reservation';
	const NO_VEHICLE       = 'no_vehicle';
} // End Session