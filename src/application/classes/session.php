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
	const NEW_USER = 'new_user';
	const NEW_RESERVATION  = 'new_reservation';
	const EDIT_RESERVATION = 'edit_reservation';
	const CANCEL_RESERVATION = 'cancel_reservation';
	const NEW_VEHICLE = 'new_vehicle';
	const NO_VEHICLE  = 'no_vehicle';
	const SUCCESSFUL_USER_CONFIRMATION = 'successful_user_confirmation';
	const FAILED_USER_CONFIRMATION     = 'failed_user_confirmation';
	const SUCCESSFUL_RESEND_USER_CONFIRMATION = 'successful_resend_user_confirmation';
	const FAILED_RESEND_USER_CONFIRMATION     = 'failed_resend_user_confirmation';
} // End Session