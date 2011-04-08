<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract base template controller.
 *
 * @package   Park-a-Lot
 * @category  Controller
 * @author    Abdul Hassan
 * @copyright (c) The authors
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
	 * Sets the contents of the view object as the response.
	 *
	 * @return void
	 */
	public function after()
	{
		if ($this->view !== NULL)
		{
			$this->response->body($this->view->render());
		}

		return parent::after();
	}
} // End Controller_Base