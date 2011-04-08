<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Home controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) The authors
 */
class Controller_Home extends Controller_Base
{
	public function action_index()
	{
		$this->view = Kostache_Layout::factory('home/index');
	}
} // End Controller_Home