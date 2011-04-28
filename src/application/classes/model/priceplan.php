<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Price plan model.
 * 
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_PricePlan extends ORM
{
	protected $_table_name = 'price_plans';

	protected $_created_column = array(
		'column' => 'date_added',
		'format' => TRUE,
	);

	/**
	 * Member price must not be empty, must be a decimal number with 2 decimals,
	 * cannot be more than the guest price.
	 * Guest price must not be empty, must be a decimal number with 2 decimals.
	 * Discount rate must be a decimal with 2 decimal points and must be less
	 * than 1 (percentage).
	 * Min price must be a decimal with 2 decimal points, and is required if a
	 * discount rate is set.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			'member_price' => array(
				array('not_empty'),
				array('decimal', array(':value', 2)),
				array(array($this, 'member_lte_guest'), array(':validation')),
				array(array($this, 'price_plan_available'), array(':validation')),
			),
			'guest_price' => array(
				array('not_empty'),
				array('decimal', array(':value', 2)),
			),
			'discount_rate' => array(
				array('decimal', array(':value', 2, 0)),
				array(array($this, 'max_discount_rate'), array(':validation')),
			),
			'min_price' => array(
				array('decimal', array(':value', 2)),
				array(array($this, 'discount_rate_set'), array(':validation')),
			),
		);
	}

	/**
	 * Validates and creates a new price plan.
	 *
	 * @param  array $values
	 * @return bool
	 */
	public function create_price_plan(array $values)
	{
		$this->values($values, array(
			'member_price',
			'guest_price',
			'discount_rate',
			'min_price',
		))
		->create();

		return $this->activate();
	}

	/**
	 * Activates this record, and deactivates all others.
	 *
	 * @return bool
	 */
	public function activate()
	{
		// Activate this one
		$this->set('active', TRUE)->update();
		
		// Deactivate all others
		DB::update('price_plans')
			->set(array('active' => FALSE))
			->where('id', '!=', $this->id)
			->execute();

		return TRUE;
	}

	/**
	 * Ensures the member price is not greater than the guest price.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public function member_lte_guest(Validation $array)
	{
		if ($array['member_price'] > $array['guest_price'])
		{
			$array->error('member_price', 'member_lte_guest');
		}
	}

	/**
	 * Ensures that a min price is set if a discount rate is set.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public function discount_rate_set(Validation $array)
	{
		if (Valid::not_empty($array['discount_rate']) AND ! Valid::not_empty($array['min_price']))
		{
			$array->error('min_price', 'discount_rate_set');
		}
	}

	/**
	 * Ensures that the discount rate is below 1 (100%).
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public function max_discount_rate(Validation $array)
	{
		if ($array['discount_rate'] >= 1.00)
		{
			$array->error('discount_rate', 'max_discount_rate');
		}
	}

	/**
	 * Checks if the price plan being created already exists.
	 *
	 * @param  Validation $array
	 * @return void
	 */
	public function price_plan_available(Validation $array)
	{
		return $this->available(DB::expr($array['member_price']), 'member_price', array(
			'guest_price'   => DB::expr($array['guest_price']),
			'discount_rate' => DB::expr($array['discount_rate']),
			'min_price'     => DB::expr($array['min_price']),
		));
	}
} // End Model_PricePlan