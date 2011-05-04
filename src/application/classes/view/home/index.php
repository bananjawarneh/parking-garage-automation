<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Home view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Home_Index extends View_Base
{
	public $title = 'Home';

	public function price_plan()
	{
		return Model_PricePlan::active_price_plan()->as_array();
	}

	public function open_spots()
	{
		return ORM::factory('garage')
			->where('open', '=', TRUE)
			->count_all();
	}
} // End View_Home_Index