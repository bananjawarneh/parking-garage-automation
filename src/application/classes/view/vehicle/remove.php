<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Remove vehicle view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Vehicle_Remove extends View_Base
{
	public $title = 'Remove Vehicle';

	public $vehicle_id;

	public $vehicle;

	public function vehicle()
	{
		return array(
			'license_plate' => $this->vehicle->license_plate,
			'state'         => $this->vehicle->state,
		);
	}

	public function render()
	{
		$this->vehicle = ORM::factory('vehicle')->where('id', '=', $this->vehicle_id)->find();

		return parent::render();
	}
} // End View_Vehicle_Remove