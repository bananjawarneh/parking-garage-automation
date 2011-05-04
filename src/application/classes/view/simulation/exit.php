<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Exit garage simulation.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_Exit extends View_Simulation_Base
{
	public $garage;
	public $parking;
	public $exited = FALSE;

	public function info()
	{
		$info = array();

		if ($this->parking->departure_time !== NULL)
		{
			$end_time = $this->parking->departure_time;
			$this->exited = TRUE;

			// Set departure time
			$info['departure_time'] = date('M jS, g:i a', $this->parking->departure_time);
		}
		else
		{
			$end_time = time();
		}

		$seconds  = $end_time - $this->parking->arrival_time;
		$duration = Date::span(0, $seconds, 'hours,minutes');

		if ($this->parking->user_id === NULL)
		{
			$price_plan = ORM::factory('priceplan', $this->parking->price_plan_id);

			if ($price_plan->loaded())
			{
				// Show cost for unregistered users
				$info['cost'] = ($seconds / 3600) * $price_plan->guest_price;
				$info['cost'] = number_format($info['cost'], 2);
			}
		}
		
		$info += array(
			'license_plate' => $this->garage->license_plate,
			'state'         => $this->garage->state,
			'arrival_time'  => date('M jS, g:i a', $this->parking->arrival_time),
			'duration'      => $duration['hours'].'h '.$duration['minutes'].'m',
		);

		return $info;
	}
} // End View_Simulation_Exit