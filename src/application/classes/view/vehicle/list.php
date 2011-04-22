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

	public function vehicles()
	{
		$vehicles = array();

		foreach ($this->user->vehicles->find_all() as $vehicle)
		{
			$vehicles[] = array(
				'license_plate' => $vehicle->license_plate,
				'state'         => $vehicle->state,
				'date_added'    => date('M jS, g:i a', $vehicle->date_added),
				//'delete_url'    => URL::site('vehicle/remove/'.$vehicle->id),
			);
		}

		return $vehicles;
	}
} // End View_Vehicle_List