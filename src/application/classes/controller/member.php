<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Member controller, ensures that a user is logged in before going further.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class Controller_Member extends Controller_Base
{
	public function before()
	{
		parent::before();

		if ( ! Auth::instance()->logged_in())
		{
			// Redirect to user login, return back to the correct page afterwards
			$this->request->redirect('user/login?return_to='.$this->request->uri());
		}
	}
} // End Controller_Member