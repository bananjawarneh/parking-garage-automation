<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Display garage simulation. Open/taken spots, etc.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_Display extends View_Simulation_Base
{
	public $map;
	public $total = 0;
	public $taken = 0;
	public $open = 0;

	protected function _map()
	{
		$spots = ORM::factory('garage')->find_all();
		$this->total = count($spots);

		$map = '';

		$i = 100;

		foreach ($spots as $spot)
		{
			if ($spot->row_num == 1 AND $spot->col_num == 1)
			{
				// Start a new table
				$map .= '<table class="garage-map">';
				$map .= '<tr><th colspan="100">Level '.$spot->level_num.'</th></tr>';
			}
			else if ($spot->col_num == 1)
			{
				// Start a new row
				$map .= '<tr>';
			}

			if ($spot->open)
			{
				$class = 'open';
				$this->open++;
			}
			else
			{
				$class = 'taken';
				$this->taken++;
			}

			$map .= '<td class="'.$class.'">';
			$map .= $i;
			$map .= '</td>';

			if ($spot->row_num == Model_Garage::ROWS AND $spot->col_num == Model_Garage::COLS)
			{
				// Close table
				$map .= '</table>';
			}
			else if ($spot->col_num == Model_Garage::COLS)
			{
				// Close row
				$map .= '</tr>';
			}

			$i++;
		}

		return $map;
	}

	public function render()
	{
		$this->map = $this->_map();

		if ($spot = Session::instance()->get_once(Session::NEW_PARKING))
		{
			$spot_num  = $spot['level_num'];
			$spot_num .= $spot['row_num'] - 1;
			$spot_num .= $spot['col_num'] - 1;

			$notification = 

			$this->notifications[] = "You've successfully parked in our garage. "
								   . "You're vehicle is now in spot #$spot_num. ";
			$this->notifications[] = 'To exit the garage, just type in your vehicle '
								   . 'information at the welcome screen the same '
								   . 'way you did when you entered the garage.';
		}

		return parent::render();
	}
} // End View_Simulation_Display