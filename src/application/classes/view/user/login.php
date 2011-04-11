<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User login view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_User_Login extends View_Base
{
	public $title = 'Login';

	public $form = array(
		'email' => NULL,
	);

	/**
	 * Helpful tips to show the user.
	 *
	 * @var array
	 */
	public $tips = array(
		'When creating a reservation, theres no need to type in a date for start
			time or end time, plain english works just fine. For example, when
			reserving a spot for tomorrow, just type "tomorrow".',
		'When creating or cancelling a reservation, you must do so at least
			30 minutes prior to your expected time of arrival. When extending a
			reservation, that must be done at least 30 minutes before your
			reservation ends.',
		'Need parking for more than one day? Just create one reservation and set
			the dates you want the reservation to reoccur.',
	);

	/**
	 * Returns a random tip to show the user at login.
	 *
	 * @return string
	 */
	public function random_tip()
	{
		return $this->tips[array_rand($this->tips)];
	}

	public function return_to()
	{
		return Arr::get($_GET, 'return_to');
	}
} // End View_User_Login