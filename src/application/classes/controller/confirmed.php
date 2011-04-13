<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User must be logged in and a confirmed member to gian further access.
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
			// Reroute to unconfirmed user action
			$this->request->redirect('user/unconfirmed');
		}
	}
} // Controller_Confirmed