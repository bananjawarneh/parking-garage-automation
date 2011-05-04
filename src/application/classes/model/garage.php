<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Garage model.
 *
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_Garage extends ORM
{
	/** Number of levels in the garage */
	const LEVELS = 3;

	/** Number of rows on each level */
	const ROWS = 10;

	/** Number of columns in each row */
	const COLS = 10;

	protected $_table_name = 'garage';

	protected $_belongs_to = array(
		'parking' => array('model' => 'parking'),
	);
	
	protected $_sorting = array(
		'level_num' => 'ASC',
		'row_num'   => 'ASC',
		'col_num'   => 'ASC',
	);

	/**
	 * Checks if a certain vehicle is already in the garage.
	 *
	 * @param  string $license_plate
	 * @param  string $state
	 * @return bool
	 */
	public static function vehicle_exists($license_plate, $state)
	{
		return ORM::factory('garage')
			->where('license_plate', '=', $license_plate)
			->where('state', '=', $state)
			->find()
			->loaded();
	}

	public function open_spot()
	{
		$this->where('open', '=', TRUE)->find();

		return $this->loaded();
	}

	public function clear_spot()
	{
		$this->values(array(
			'license_plate' => NULL,
			'state'         => NULL,
			'open'          => TRUE,
		))
		->update();

		if ($this->parking->loaded())
		{
			$this->parking->close_parking();
		}

		return TRUE;
	}

	public function clear_garage()
	{
		foreach (ORM::factory('garage')->where('open', '=', FALSE)->find_all() as $spot)
		{
			$spot->clear_spot();
		}

		return TRUE;
	}
} // End Model_Garage