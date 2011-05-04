<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base view for simulation.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Simulation_Base extends View_Base
{
	public $title = 'Simulation';

	public function render()
	{
		$this->partial('simulation_content', $this->_detect_template());

		$this->template('simulation/base');

		return parent::render();
	}
} // End View_Simulation_Base