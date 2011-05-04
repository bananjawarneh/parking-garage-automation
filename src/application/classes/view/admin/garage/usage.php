<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin's garage usage-history view.
 * 
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Admin_Garage_Usage extends View_Admin_Base
{
	public $title = 'View Garage Usage';

	public function statistics()
	{
		$parking = ORM::factory('parking')->find_all();

		// Track overstays, understays, etc.
		$total = $total_res = $overstays = $understays = 
		$on_time = $no_shows = $average_duration = 0;

		// Track durations
		$durations = range(0, 25);

		foreach ($parking as $park)
		{
			if ($park->departure_time === NULL)
			{
				// Not done parking
				continue;
			}

			if ($park->reservation->loaded())
			{
				// Reservation attached, make some calculations
				
				if ($park->departure_time > ($park->reservation->end_time + Model_Reservation::OVERSTAY_PERIOD))
				{
					 // Overstay
					$overstays++;
				}
				else if ($park->departure_time < ($park->reservation->end_time - Model_Reservation::UNDERSTAY_PERIOD))
				{
					// Understay
					$understays++;
				}
				else
				{
					$on_time++;
				}

				$total_res++;
			}

			$average_duration += ($park->departure_time - $park->arrival_time);

			$total++;
		}

		// Look for past reservations
		$reservations = ORM::factory('reservation')
			->where('end_time', '<=', time())
			->find_all();

		foreach ($reservations as $res)
		{
			if ( ! $res->parking->loaded())
			{
				// A parking record does not exist
				$no_shows++;
			}
		}

		$average_duration = Date::span(0, ($average_duration / $total), 'hours,minutes,seconds');

		$stats = array(
			'total'              => $total,
			'total_reservations' => $total_res,
			'total_walkins'      => ($total - $total_res),
			'total_no_shows'     => $no_shows,
			'average_duration'   => $average_duration['hours'].'h '.$average_duration['minutes'].'m '.$average_duration['seconds'].'s',
		);

		if ($total_res > 0)
		{
			$stats += array(
				'overstay_percentage'  => 100 * $overstays / $total_res,
				'understay_percentage' => 100 * $understays / $total_res,
				'ontime_percentage'    => 100 * $on_time / $total_res,
			);
		}

		return $stats;
	}

	public function statistics_exist()
	{
		return (bool) ORM::factory('parking')->where('departure_time', '!=', NULL)->count_all();
	}
} // End View_Admin_Garage_Usage