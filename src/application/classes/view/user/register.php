<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User registration view
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class View_User_Register extends View_Base
{
	public $title = 'Register';
	
	public $form = array(
		'first_name' => NULL,
		'last_name'  => NULL,
		'email'      => NULL,
	);

	public $user_agreement = 'This user agreement needs to be updated to
		resemble the actual user agreement of the site. Lorem ipsum dolor sit
		amet, consectetur adipiscing elit. Sed rhoncus accumsan nibh, ut consectetur
		elit bibendum id. Fusce tristique lacinia tortor, id tincidunt risus ullamcorper
		sed. Ut porttitor, libero a pretium feugiat, magna turpis fermentum ligula,
		vel bibendum arcu felis commodo sem. Integer ac enim sed nisi sollicitudin
		dignissim volutpat nec justo. Vestibulum lacinia elementum turpis, eget
		vulputate sapien auctor et. Suspendisse potenti. Maecenas luctus congue
		porttitor. Cras consectetur quam id orci malesuada ultricies. Morbi commodo
		ligula a erat auctor eu varius nibh blandit. Suspendisse potenti. Donec
		et lorem mauris, pulvinar auctor felis.';

	/**
	 * Replace all spaces and breaks with a single space in the user agreement.
	 *
	 * @return string
	 */
	public function user_agreement()
	{
		return preg_replace('/\s+/', ' ', $this->user_agreement);
	}
} // End View_User_Register