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
class Controller_Reservation extends Controller_Confirmed
{
	/**
	 * Displays all of this users reservation, in paginated lists.
	 *
	 * @param int timestamp of the day to show reservations for
	 */
	public function action_list($day = NULL)
	{
		$this->view = Kostache_Layout::factory('reservation/list')
			->set('day', $day);
	}

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
					Session::instance()->set(Session::NEW_RESERVATION, TRUE);

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
	 * Displays the edit reservation page, which allows a user to both extend
	 * and cancel a reservation.
	 *
	 * @param int the reservation to edit
	 */
	public function action_edit($reservation_id = NULL)
	{
		$reservation = $this->_user->reservations->where('id', '=', $reservation_id)->find();

		if ( ! $reservation->loaded())
		{
			// Fishy business here
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('reservation/edit')
			->set('reservation_id', $reservation_id);

		if ( ! empty($_POST))
		{
			try
			{
				if ($reservation->update_reservation($_POST))
				{
					// Show success message on user profile
					Session::instance()->set(Session::EDIT_RESERVATION, TRUE);

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