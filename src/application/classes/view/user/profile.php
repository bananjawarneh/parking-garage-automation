<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User profile view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_User_Profile extends View_Base
{
	public function title()
	{
		return $this->user->first_name;
	}
} // End View_User_Profile