<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simulation controller, allows the user to simulate actually parking in the lot.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Simulation extends Controller_Base
{
	protected $_session = array(
		'recognized' => FALSE,
		'vehicle'    => array(),
	);

	public function before()
	{
		parent::before();

		// Read the contents of the current session
		$this->_session = Arr::overwrite($this->_session,
			Session::instance()->get(Session::SIMULATION, array()));
	}

	/**
	 * Allows an administrator to clear out the garage. Whether or not this
	 * is likely to happen in an actual garage is still unknown, but for
	 * simulation, this may come in handy. Must be logged in as an admin first.
	 */
	public function action_cleargarage()
	{
		if ( ! Auth::instance()->logged_in(Model_Role::ADMIN))
		{
			// Dont have the proper credentials
			$this->request->redirect('simulation');
		}

		$this->view = Kostache_Layout::factory('simulation/cleargarage');

		if (isset($_POST['confirm']))
		{
			ORM::factory('garage')->clear_garage();

			// Show a success message
			Session::instance()->set(Session::CLEAR_GARAGE, TRUE);

			$this->request->redirect('simulation/display');
		}
	}

	/**
	 * Displays the contents of the garage. I.e, how many people are in the
	 * garage, what spots are taken, and what spots are open.
	 */
	public function action_display()
	{
		$this->view = Kostache_Layout::factory('simulation/display');
	}

	/**
	 * Displays the welcome message to the user. Asks them for vehicle license
	 * plate information.
	 */
	public function action_index()
	{
		// Start fresh
		Session::instance()->delete(Session::SIMULATION);

		$this->view = Kostache_Layout::factory('simulation/index');

		if (isset($_POST['check']))
		{
			$validation = Validation::factory($_POST)
				->rule('license_plate', 'not_empty')
				->rule('state', 'not_empty');

			if( ! $validation->check())
			{
				$this->view->errors[] = 'Please enter your vehicle information';
				return;
			}
			
			// Use the automatic filtering to normalize license plate
			$form = ORM::factory('vehicle')->values($_POST);

			if (Model_Garage::vehicle_exists($form->license_plate, $form->state))
			{
				$this->_session['vehicle'] = $form->as_array();

				Session::instance()->set(Session::SIMULATION, $this->_session);
				
				// Vehicle is already in the garage, allow user to exit
				$this->request->redirect('simulation/exit');
			}

			$vehicle = ORM::factory('vehicle')
				->where('license_plate', '=', $form->license_plate)
				->where('state', '=', $form->state)
				->find();

			if ($vehicle->loaded())
			{
				// Recognized vehicle
				$this->_session['recognized'] = TRUE;
				$this->_session['vehicle']    = $vehicle->as_array();
				$redirect_to = 'simulation/recognized';
			}
			else
			{
				// Unrecognized vehicle
				$this->_session['recognized'] = FALSE;
				$this->_session['vehicle']    = $form->as_array();
				$redirect_to = 'simulation/walkin';
			}

			Session::instance()->set(Session::SIMULATION, $this->_session);
			$this->request->redirect($redirect_to);
		}
	}

	/**
	 * Handles recognized vehicles (i.e, registered users).
	 */
	public function action_recognized()
	{
		$user = ORM::factory('user', Arr::get($this->_session['vehicle'], 'user_id'));

		if ( ! $user->loaded())
		{
			// A mistake was made
			$this->request->redirect('simulation');
		}

		$this->view = Kostache_Layout::factory('simulation/recognized')
			->set('_user', $user);
	}

	/**
	 * User exits the garage.
	 */
	public function action_exit()
	{
		$license_plate = Arr::get($this->_session['vehicle'], 'license_plate');
		$state = Arr::get($this->_session['vehicle'], 'state');

		if ($license_plate === NULL OR $state === NULL OR
			! Model_Garage::vehicle_exists($license_plate, $state))
		{
			// Must be here by mistake
			$this->request->redirect('simulation');
		}

		$this->view = Kostache_Layout::factory('simulation/exit')
			->set('vehicle', $this->_session['vehicle'])
			->bind('garage', $garage)
			->bind('parking', $parking);

		$garage = ORM::factory('garage')
			->where('license_plate', '=', $license_plate)
			->where('state', '=', $state)
			->find();
		
		$parking = $garage->parking;

		if (isset($_POST['confirm']))
		{
			// Clears spot, and closes parking
			$garage->clear_spot();

			$this->view->set('exited', TRUE);
		}
	}

	/**
	 * Handles walkins.
	 */
	public function action_walkin()
	{
		if (empty($this->_session['vehicle']))
		{
			// Must be here by mistake
			$this->request->redirect('simulation');
		}

		$this->view = Kostache_Layout::factory('simulation/walkin')
			->set('vehicle', $this->_session['vehicle']);
	}

	/**
	 * Handles the actually parking, whether for a reservation or a walk-in.
	 */
	public function action_park()
	{
		if (empty($this->_session['vehicle']))
		{
			// Must be here by mistake
			$this->request->redirect('simulation');
		}

		$this->view = Kostache_Layout::factory('simulation/park');

		// Data for the parking table
		$parking_info = array(
			'arrival_time' => time(),
		);

		// Data for the garage table
		$garage_info = Arr::overwrite(array(
			'license_plate' => NULL,
			'state' => NULL,
		), $this->_session['vehicle']);

		if ($this->_session['recognized'])
		{
			// Recognized user
			$parking_info['user_id'] = Arr::get($this->_session['vehicle'], 'user_id');
			$parking_info['vehicle_id'] = Arr::get($this->_session['vehicle'], 'id');

			if (isset($_POST['reservation']))
			{
				// Here for a reservation
				$parking_info['reservation_id'] = $_POST['reservation_id'];
				$this->view->set('reservation', TRUE);
			}
		}

		$parking = ORM::factory('parking')->values($parking_info);

		// Save the price plan, to know what to charge
		$parking->price_plan_id = Model_PricePlan::active_price_plan()->id;

		// Choose an open spot
		$garage = ORM::factory('garage');

		if (Model_Garage::vehicle_exists($garage_info['license_plate'], $garage_info['state']))
		{
			// Vehicle is already in garage, redirect
			$this->view->set('vehicle_exists', TRUE);
			return;
		}
		else if ( ! $garage->open_spot())
		{
			// No spots open
			$this->view->set('no_spots', TRUE);
			return;
		}

		// Mark spot as taken
		$garage->values($garage_info)->set('open', FALSE);

		if ($parking->save() AND $garage->set('parking_id', $parking->id)->save())
		{
			// Show a success message, and show what spot the user is now parked in
			Session::instance()->set(Session::NEW_PARKING, $garage->as_array());

			$this->request->redirect('simulation/display');
		}
	}
} // End Controller_Simulation