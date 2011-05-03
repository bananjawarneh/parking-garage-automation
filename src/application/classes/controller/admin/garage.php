<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Manage garage controller, serves the administrator interface to set prices,
 * view garage usage, etc.
 * 
 * @package   Park-a-Lot
 * @category  
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Controller_Admin_Garage extends Controller_Admin
{
	/**
	 * Displays garage usage statistics. Percentage of overstays, understays,
	 * and no shows. Also allows the admin to see when peak hours are.
	 */
	public function action_usage()
	{
		$this->view = Kostache_Layout::factory('admin/garage/usage');
	}

	/**
	 * Allows garage managers set price plans. Price plans can either be newly
	 * created, or recycled (reused).
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
						// Activate the price plan
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