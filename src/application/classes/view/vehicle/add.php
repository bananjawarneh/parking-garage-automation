<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Add vehicle view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Vehicle_Add extends View_Base
{
	public $title = 'Register Vehicle';

	public $form = array(
		'license_plate' => NULL,
		'state'         => NULL,
	);

	/**
	 * Returns an array of US states, for vehicle license plates.
	 *
	 * @return array
	 */
	public function states()
	{
		$states = array();

		foreach (Form_Data::states() as $k => $v)
		{
			$states[] = array(
				'value' => $k,
				'name'  => $v,
				'selected' => ($k == $this->form['state']),
			);
		}

		return $states;
	}

	public function render()
	{
		if (Session::instance()->get_once(Session::NO_VEHICLE))
		{
			$this->notifications[] = 'You must register a vehicle prior to making a reservation.';
		}
		
		return parent::render();
	}
} // End View_Vehicle_Add