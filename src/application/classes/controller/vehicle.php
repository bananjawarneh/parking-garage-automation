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

	/**
	 * Confirms that the user wants to delete the vehicle.
	 *
	 * @param int $vehicle_id vehicle to delete
	 */
	public function action_remove($vehicle_id = NULL)
	{
		$vehicle = $this->_user->vehicles->where('id', '=', $vehicle_id)->find();

		if ( ! $vehicle->loaded())
		{
			// Fishy business here
			$this->request->redirect(Route::url('user_profile'));
		}

		$this->view = Kostache_Layout::factory('vehicle/remove')
			->set('vehicle_id', $vehicle_id);

		if (isset($_POST['remove']))
		{
			if ($_POST['remove'] == 'Yes' AND $vehicle->delete())
			{
				// Show success message on user profile
				Session::instance()->set(Session::REMOVE_VEHICLE, TRUE);

				$this->request->redirect(Route::url('user_profile'));
			}

			$this->request->redirect(Route::url('user_profile'));
		}
	}
} // End Controller_Vehicle