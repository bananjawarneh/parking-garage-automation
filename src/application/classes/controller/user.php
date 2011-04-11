<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_User extends Controller_Base
{
	/**
	 * Users fill out a registration form. Upon succesful submission, a user is
	 * created and the user is redirected to a welcoming message.
	 */
	public function action_register()
	{
		if (Auth::instance()->logged_in())
		{
			// Cant register twice
			$this->request->redirect(Route::url('user_profile'));
		}
		
		$this->view = Kostache_Layout::factory('user/register');

		if ( ! empty($_POST))
		{			
			try
			{
				if ($this->_user->create_user($_POST))
				{
					// Log the user in
					$this->_user->force_login();

					// TODO set a flag to show a welcome message on profile page
					$this->request->redirect(Route::url('user_profile'));
				}
				else
				{
					// internal error
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
	 * Users profile is read only. Only used to display data about the user.
	 */
	public function action_profile(){}
} // End Controller_User