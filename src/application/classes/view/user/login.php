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

	public $tips = array(
		'When creating a reservation, theres no need to type a start time and end time.
			Just type in a relative date. For example, when reserving a spot for
			next monday, just type in "next monday".',
	);

	public function random_tip()
	{
		return $this->tips[array_rand($this->tips)];
	}

	public function return_to()
	{
		return Arr::get($_GET, 'return_to');
	}
} // End View_User_Login