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
			// Lets not waste time
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
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->view->set('form', $_POST);
				$this->view->set('errors', $e->errors('models'));
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
			// Lets not waste time
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('user/login');

		if (isset($_POST['login']) AND $post = $_POST)
		{
			if ($this->_user->login($post))
			{
				// If set, redirect to the page that redirected here
				$this->request->redirect(Arr::get($_POST, 'return_to', Route::url('user_profile')));
			}
			else
			{
				$this->view->set('form', $_POST);
				$this->view->set('errors', $post->errors('login'));
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
			// Must be logged in so we know who to send email to
			$this->request->redirect('user/login?return_to='.$this->request->uri());
		}
		else if (Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			// Already confirmed, lets not waste time
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
			// Must be logged in first
			$this->request->redirect('user/login?return_to='.$this->request->uri().URL::query($_GET));
		}
		else if (Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			// Already confirmed. Lets not waste our time
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