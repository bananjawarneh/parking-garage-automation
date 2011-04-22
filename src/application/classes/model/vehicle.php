<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Vehicle model.
 * 
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_Vehicle extends ORM
{
	public $_belongs_to = array(
		'reservation' => array('model' => 'vehicle'),
		'user'        => array('model' => 'user'),
	);

	protected $_created_column = array(
		'column' => 'date_added',
		'format' => TRUE,
	);

	/**
	 * Store all license plates in upper case, replacing all spaces with dashes.
	 *
	 * @return array
	 */
	public function filters()
	{
		return array(
			TRUE => array(
				array('trim', array(':value')),
			),
			'license_plate' => array(
				array('strtoupper', array(':value')),
				array('preg_replace', array('/[\s_\/-]/', '-', ':value')),
			),
		);
	}

	public function labels()
	{
		$labels = array();

		foreach ($this->_object as $field => $value)
		{
			$labels[$field] = str_replace('_', ' ', ucfirst($field));
		}

		return $labels;
	}

	/**
	 * User must not be empty, and must exist.
	 * License plate must not be empty, must be between 3 and 8 characters, can
	 * only consist of letters, numbers, and dashes, and must not already be taken.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			'user_id' => array(
				array('not_empty'),
				array(array(ORM::factory('user'), 'exists'), array(':value', 'id')),
			),
			'license_plate' => array(
				array('not_empty'),
				array('min_length', array(':value', 3)),
				array('max_length', array(':value', 8)),
				array('regex', array(':value', '#^[A-Za-z0-9/_-]+$#')),
				array(array($this, 'vehicle_available'), array(':validation')),
			),
			'state' => array(
				array('not_empty'),
			),
		);
	}

	/**
	 * Validates and adds a new vehicle to the database.
	 *
	 * @param  array
	 * @return bool
	 */
	public function create_vehicle(array $values)
	{
		$vehicle = $this->values($values, array(
			'user_id',
			'license_plate',
			'state',
		))
		->create();

		return $vehicle;
	}

	/**
	 * Checks if a vehicle is available, by checking state and license plate.
	 *
	 * @param  Validation
	 * @return bool
	 */
	public function vehicle_available(Validation $array)
	{
		return $this->available($array['license_plate'], 'license_plate', array(
			'state' => $array['state'],
		));
	}
} // End Vehicle