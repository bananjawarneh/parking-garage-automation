<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Reservation controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Reservation extends Controller_Member
{
	/**
	 * Displays a create reservation form which the user fills out. Upon
	 * succesful creation, the page redirects to the user profile page where a
	 * notification of success is shown.
	 */
	public function action_create()
	{
		$this->view = Kostache_Layout::factory('reservation/create');

		if ( ! empty($_POST))
		{
			try
			{
				if ($this->_user->add_reservation($_POST))
				{
					// Show success message on user profile
					Session::instance()->set('created_reservation', TRUE);

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
} // End Controller_Reservation