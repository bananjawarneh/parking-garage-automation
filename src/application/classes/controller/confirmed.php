<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Confirmed controller, ensures that a user is confirmed before going further.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class Controller_Confirmed extends Controller_Member
{
	public function before()
	{
		parent::before();

		if ( ! Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			// Redirect to show a message
			$this->request->redirect('user/unconfirmed');
		}
	}
} // Controller_Confirmed