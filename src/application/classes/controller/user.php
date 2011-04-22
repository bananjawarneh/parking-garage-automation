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
	 * Displays and receives a registration form. Redisplays the form data and
	 * shows any errors if there are any. Upon success, redirects to user profile.
	 */
	public function action_register()
	{
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::url('user_profile'));
		}
		
		$this->view = Kostache_Layout::factory('user/register');

		if (isset($_POST['register']))
		{
			try
			{
				if ($this->_user->create_user($_POST))
				{
					// Start a new user session
					$this->_user->force_login();

					// Show a welcome message to new users
					Session::instance()->set(Session::NEW_USER, TRUE);

					$this->request->redirect(Route::url('user_profile'));
				}
				else
				{
					// TODO internal error, email did not send
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
	 * Displays and receives a login form. Redisplays the form with data and
	 * errors if they exist. Upon success, the user is redirected to their user
	 * profile.
	 */
	public function action_login()
	{
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('user/login');

		// Login method takes a reference, dont transform the POST array
		if (isset($_POST['login']) AND $post = $_POST)
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
	 * Logs the user out, and redirects to the login page.
	 */
	public function action_logout()
	{
		Auth::instance()->logout(TRUE);

		$this->request->redirect('user/login');
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

		$this->view = Kostache_Layout::factory('user/profile');
	}

	/**
	 * Displays a message to the user that they cannot perform any actions
	 * until they confirm their account.
	 */
	public function action_unconfirmed()
	{
		if (Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			$this->request->redirect(Route::url('user_profile'));
		}
		
		$this->view = Kostache_Layout::factory('user/unconfirmed');
	}

	/**
	 * Resends user confirmation, and redirects to users profile page.
	 */
	public function action_resend_confirmation()
	{
		if ( ! Auth::instance()->logged_in())
		{
			$this->request->redirect('user/login?return_to='.$this->request->uri());
		}
		else if (Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			$this->request->redirect(Route::url('user_profile'));
		}

		if ($this->_user->send_email('confirm_registration'))
		{
			Session::instance()->set(Session::SUCCESSFUL_RESEND_USER_CONFIRMATION, TRUE);
		}
		else
		{
			Session::instance()->set(Session::FAILED_RESEND_USER_CONFIRMATION, TRUE);
		}

		$this->request->redirect(Route::url('user_profile'));
	}

	/**
	 * Confirms user registration, and redirects to users profile page.
	 */
	public function action_confirm_registration()
	{
		if ( ! Auth::instance()->logged_in())
		{
			$this->request->redirect('user/login?return_to='.$this->request->uri().URL::query($_GET));
		}
		else if (Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			$this->request->redirect(Route::url('user_profile'));
		}

		if ($this->_user->confirm_create_user($_GET))
		{
			Session::instance()->set(Session::SUCCESSFUL_USER_CONFIRMATION, TRUE);
		}
		else
		{
			Session::instance()->set(Session::FAILED_USER_CONFIRMATION, TRUE);
		}

		$this->request->redirect(Route::url('user_profile'));
	}
} // End Controller_User