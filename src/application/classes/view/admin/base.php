<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 * 
 * @package   Park-a-Lot
 * @category  
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_Admin_Base extends View_Base
{
	public function render()
	{
		$this->partial('admin_content', $this->_detect_template());

		$this->template('admin/base');

		return parent::render();
	}
} // End Base