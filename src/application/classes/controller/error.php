<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Error controller for displaying different error messages to users.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Error extends Controller_Base
{
	/**
	 * Determines what actions to take and sets the header code.
	 */
	public function before()
	{
		parent::before();

		if (Request::$current === Request::$initial)
		{
			// Page was explicitly requested, Dont let user access it.
			$this->request->action(404);
		}

		$this->response->status( (int) $this->request->action());
	}

	/**
	 * Handles 404 "Not Found" errors.
	 */
	public function action_404()
	{
		$this->view = Kostache_Layout::factory('error');
		$this->view->title = 'Page Not Found';
	}

	/**
	 * Handles 500 "Internal Error" errors.
	 */
	public function action_500()
	{
		$this->view = Kostache_Layout::factory('error');
		$this->view->title = 'Internal Server';
	}
} // End Controller_Error