<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract base view.
 *
 * @package   Park-a-Lot
 * @category  View
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
abstract class View_Base extends Kostache_Layout
{
	/**
	 * Base layout template.
	 *
	 * @var string
	 */
	protected $_layout = 'base';

	/**
	 * Whether to render template within the base layout.
	 *
	 * @var bool
	 */
	public $render_layout = TRUE;

	/**
	 * Commonly used partials.
	 *
	 * @var array
	 */
	protected $_partials = array(
		'notifications' => 'partials/notifications',
		'errors'        => 'partials/errors',
	);

	/**
	 * Notifications. Should be added to.
	 *
	 * @var array
	 */
	public $notifications = array();

	/**
	 * Error messages. Should be added to.
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * The logged in user.
	 *
	 * @var ORM
	 */
	public $user;

	/**
	 * Builds an array of presentable notifications.
	 *
	 * @return array
	 */
	public function notifications()
	{
		return $this->build_array($this->notifications, 'notification');
	}
	
	/**
	 * Checks if errors exist before attempting to display them.
	 *
	 * @return bool
	 */
	public function errors_exist()
	{
		return ( ! empty($this->errors));
	}
	
	/**
	 * Builds an array of presentable errors.
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->build_array($this->errors, 'error');
	}

	/**
	 * Returns data about the logged in user.
	 *
	 * @return array
	 */
	public function user()
	{
		if ($this->user !== NULL)
		{
			return $this->user->as_array();
		}
	}

	/**
	 * Determines if the user is logged in.
	 *
	 * @return bool
	 */
	public function logged_in()
	{
		return Auth::instance()->logged_in();
	}

	/**
	 * Displays profiler stats if not in production.
	 *
	 * @return mixed
	 */
	public function profiler()
	{
		if (Kohana::$environment !== Kohana::PRODUCTION)
		{
			return View::factory('profiler/stats')->render();
		}
	}

	/**
	 * Returns the base url of the site.
	 *
	 * @return string
	 */
	public function base_url()
	{
		return URL::base(TRUE, FALSE);
	}

	/**
	 * Builds a Mustache ready array.
	 *
	 * @param  array  $array
	 * @param  string $name
	 * @return array
	 */
	protected function build_array($array, $name)
	{
		$return = array();

		foreach ($array as $value)
		{
			if (is_array($value))
			{
				foreach ($value as $val)
				{
					$return[] = array($name => $val);
				}
			}
			else
			{
				$return[] = array($name => $value);
			}
		}

		return $return;
	}
} // End View_Base