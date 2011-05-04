<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Parking mode.
 *
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_Parking extends ORM
{
	protected $_table_name = 'parking';
	
	protected $_belongs_to = array(
		'reservation' => array('model' => 'reservation'),
	);

	public function close_parking()
	{
		if ($this->departure_time === NULL)
		{
			$this->set('departure_time', time())->save();
		}

		return TRUE;
	}
} // End Model_Parking