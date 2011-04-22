<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract base template controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class Controller_Base extends Controller
{
	/**
	 * Kostache view object.
	 *
	 * @var Kostache_Layout
	 */
	protected $view;

	/**
	 * The user object of the viewing user. May or not be loaded, depending on
	 * whether the user is logged in or not.
	 *
	 * @var ORM
	 */
	protected $_user;

	/**
	 * Insantiates the user object, either loaded with data for the currently
	 * logged in user, or an empty user object.
	 *
	 * @return void
	 */
	public function before()
	{
		// Get currently logged in user, or create a new user instance
		$this->_user = Auth::instance()->get_user(ORM::factory('user'));

		return parent::before();
	}

	/**
	 * Sets the contents of the view object as the response.
	 *
	 * @return void
	 */
	public function after()
	{
		if ($this->view !== NULL)
		{
			$this->response->body($this->view->set('user', $this->_user)->render());
		}

		return parent::after();
	}
} // End Controller_Base