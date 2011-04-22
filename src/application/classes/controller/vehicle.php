<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Vehicle controller.
 * 
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Vehicle extends Controller_Confirmed
{
	/**
	 * Displays a create vehicle form for the user to fill out. Then, after
	 * a succesful validation, the page redirects to the user profile page.
	 * Upon failure, the page redisplays the errors.
	 */
	public function action_add()
	{
		$this->view = Kostache_Layout::factory('vehicle/add');

		if ( ! empty($_POST))
		{
			try
			{
				if ($this->_user->add_vehicle($_POST))
				{
					// Show success message on user profile
					Session::instance()->set(Session::NEW_VEHICLE, TRUE);

					$this->request->redirect(Route::url('user_profile'));
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->view->set('form', $_POST);
				$this->view->set('errors', $e->errors(''));
			}
		}
	}

	/**
	 * Lists all vehicles registered with the logged in users account.
	 */
	public function action_list()
	{
		$this->view = Kostache_Layout::factory('vehicle/list');
	}
} // End Controller_Vehicle