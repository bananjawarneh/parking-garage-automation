<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simulation welcome view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_Index extends View_Simulation_Base
{
	public $title = 'Simulate Parking';

	public function states()
	{
		$states = array();

		foreach (Form_Data::states() as $k => $v)
		{
			$states[] = array(
				'value' => $k,
				'name'  => $v,
			);
		}

		return $states;
	}

	public function open_spots()
	{
		return (bool) DB::select(array(DB::expr('COUNT(id)'), 'total'))
			->from('garage')
			->where('open', '=', TRUE)
			->execute()
			->get('total');
	}
} // End View_Simulation_Index