<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Garage admin controller, allows admin to view garage usage history and set
 * prices for the customers.
 * 
 * @package   Park-a-Lot
 * @category  
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Admin_Garage extends Controller_Admin
{
	public function action_usage()
	{
		$this->view = Kostache_Layout::factory('admin/garage/usage');
	}

	/**
	 * Displays a form to either create a new price plan, or activate an
	 * existing price plan.
	 */
	public function action_price()
	{
		$this->view = Kostache_Layout::factory('admin/garage/price');

		if ( ! empty($_POST))
		{
			$price_plan = ORM::factory('priceplan');

			try
			{
				if (isset($_POST['existing_price_plan']))
				{
					$price_plan->where('id', '=', $_POST['price_plan'])->find();

					if ($price_plan->loaded())
					{
						// Activate the existing plan
						$price_plan->activate();

						// Show a success message
						Session::instance()->set(Session::PRICE_PLAN_ACTIVATED, TRUE);

						$this->request->redirect(Route::url('user_profile'));
					}
				}
				else if (isset($_POST['set_price_plan']))
				{
					if ($price_plan->create_price_plan($_POST))
					{
						// Show a success message
						Session::instance()->set(Session::PRICE_PLAN_ACTIVATED, TRUE);

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
	}
} // End Controller_Admin_Garage