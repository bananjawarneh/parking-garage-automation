<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extension to Kohanas ORM.
 *
 * @package    Park-a-Lot
 * @subpackage ORM
 * @author     Abdul Hassan
 * @copyright  (c) 2011 The authors
 * @license    see LICENSE
 */
class ORM extends Kohana_ORM
{
	/**
	 * Checks if a record exists with a given unique value.
	 *
	 * @param  mixed
	 * @return bool
	 * @uses   ORM::unique_key
	 */
	public function exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array('COUNT("id")', 'total'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute()
			->get('total');
	}

	/**
	 * Checks if a given unique value is available for use.
	 *
	 * @param  mixed
	 * @return bool
	 * @uses   ORM::exists
	 */
	public function available($value)
	{
		return ( ! $this->exists($value));
	}

	/**
	 * Determines the field by inspecting the value type. Other models should
	 * override this method to add their own checks.
	 *
	 * @param  mixed  unique value
	 * @return string field name to which the value is most likely a type of
	 *                e.g, john@email.com should return email
	 */
	protected function unique_key($value)
	{
		// Each model should override this method
		return 'id';
	}
} // End ORM