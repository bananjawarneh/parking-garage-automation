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
	 * Displays all reservations belonging to the logged in user, or filters
	 * by a given day or reservation status.
	 *
	 * @param int $day timestamp of the day to show reservations for
	 */
	public function action_list($day = NULL)
	{
		if ($day !== NULL)
		{
			$day = (int) $day;

			if ($day === 0)
			{
				// Somethinf fishy, dont filter by day
				$day = NULL;
			}
		}

		$this->view = Kostache_Layout::factory('reservation/list')
			->set('day', $day)
			->set('filter', Arr::get($_GET, 'f'));
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
					// Show success message
					Session::instance()->set(Session::NEW_RESERVATION, TRUE);

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
	 * Displays the edit reservation page, which allows a user to both extend
	 * and cancel a reservation.
	 *
	 * @param int $reservation_id the reservation to edit
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
				if (isset($_POST['update']))
				{
					if ($reservation->update_reservation($_POST))
					{
						// Show success message
						Session::instance()->set(Session::EDIT_RESERVATION, TRUE);

						$this->request->redirect(Route::url('user_profile'));
					}
				}
				else if (Arr::get($_POST, 'cancel') == 'Yes')
				{
					// Cancel reservation
					if ($reservation->cancel_reservation($_POST))
					{
						// Show success message
						Session::instance()->set(Session::CANCEL_RESERVATION, TRUE);

						$this->request->redirect(Route::url('user_profile'));
					}
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->view->set('form', $_POST);
				$this->view->set('errors', $e->errors('models'));
			}
		}
		else if (isset($_GET['cancel']))
		{
			// Ask for a confirmation before cancelling anything
			$this->view->ask_confirmation = TRUE;
		}
	}
} // End Controller_Reservation