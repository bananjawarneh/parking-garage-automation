<?php defined('SYSPATH') or die('No direct script access.');
/**
 * List vehicles view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Vehicle_List extends View_Base
{
	public $title = 'List Registered Vehicles';

	/**
	 * Returns an array of all this users vehicles.
	 *
	 * @return array
	 */
	public function vehicles()
	{
		$vehicles = array();

		foreach ($this->user->vehicles->find_all() as $vehicle)
		{
			$vehicles[] = array(
				'license_plate' => $vehicle->license_plate,
				'state'         => $vehicle->state,
				'date_added'    => date('M jS, g:i a', $vehicle->date_added),
				'delete_url'    => URL::site('vehicle/remove/'.$vehicle->id),
			);
		}

		return $vehicles;
	}

	/**
	 * Flag to check whether vehicles exist before trying to display them.
	 *
	 * @return bool
	 */
	public function vehicles_exist()
	{
		return (bool) $this->user->vehicles->count_all();
	}
} // End View_Vehicle_List