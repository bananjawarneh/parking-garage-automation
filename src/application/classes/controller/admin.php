<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin controller, ensures user is admin before going further.
 * 
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class Controller_Admin extends Controller_Confirmed
{
	public function before()
	{
		parent::before();

		if ( ! Auth::instance()->logged_in(Model_Role::ADMIN))
		{
			// Redirect to user login, return back to the correct page afterwards
			$this->request->redirect(Route::url('user_profile'));
		}
	}
} // End Controller_Admin