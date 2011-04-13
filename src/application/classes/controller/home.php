<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Home controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Home extends Controller_Base
{
	public function action_index()
	{
		if (Auth::instance()->logged_in())
		{
			// Different home page when logged in
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('home/index');
	}
} // End Controller_Home