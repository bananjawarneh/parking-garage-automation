<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simulate a walkin.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_WalkIn extends View_Simulation_Base
{
	public $vehicle = array();

	public function registered_user()
	{
		$user_id = Arr::get($this->vehicle, 'user_id');
		$user = ORM::factory('user', $user_id);

		return $user->loaded();
	}
} // End View_Simulation_WalkIn