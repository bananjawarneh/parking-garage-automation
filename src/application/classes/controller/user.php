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
					// Start a new user session
					$this->_user->force_login();

					// Show a welcome message to new users
					Session::instance()->set('new_user', TRUE);

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
	 * Allows user to log in to their profile.
	 */
	public function action_login()
	{
		if (Auth::instance()->logged_in())
		{
			// Lets not waste our time
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('user/login');

		if ( ! empty($_POST) AND $post = $_POST)
		{
			if ($this->_user->login($post))
			{
				// Redirect to the return_to address if set, otherwise the user profile
				$this->request->redirect(Arr::get($_POST, 'return_to', Route::url('user_profile')));
			}
			else
			{
				$this->view->set('form', $_POST);
				$this->view->set('errors', $post->errors(''));
			}
		}
	}

	/**
	 * Users profile only displays data about the user.
	 */
	public function action_profile()
	{
		if ( ! Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::url('login'));
		}

		$this->view = Kostache_Layout::factory('user/profile')
			->set('user', $this->_user);
	}
} // End Controller_User