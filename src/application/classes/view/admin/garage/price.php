<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admins set price view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Admin_Garage_Price extends View_Admin_Base
{
	public $title = 'Set Price Plan';

	public $form = array(
		'member_price'  => NULL,
		'guest_price'   => NULL,
		'discount_rate' => NULL,
		'minimim_price' => NULL,
	);

	public function price_plans()
	{
		$price_plans = array();

		foreach (ORM::factory('priceplan')->find_all() as $plan)
		{
			$price_plans[] = array(
				'value'         => $plan->id,
				'member_price'  => $plan->member_price,
				'guest_price'   => $plan->guest_price,
				'discount_rate' => ($plan->discount_rate * 100).'%',
				'min_price'     => $plan->min_price,
			);
		}

		return $price_plans;
	}

	public function price_plans_exist()
	{
		return (bool) ORM::factory('priceplan')->count_all();
	}
} // End View_Admin_Garage_Price