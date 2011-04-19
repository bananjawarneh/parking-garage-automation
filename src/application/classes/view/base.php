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
	 * Stylesheets to add to the page.
	 *
	 * <code>
	 * $styles[] = array(
     *     'href'  => 'path/to/file',
     *     'media' => 'media type to bind to',
     *     'condition' => 'condition to be met before rendering',
     * );
	 * </code>
	 *
	 * @var array
	 */
	public $styles = array();

	/**
	 * Javascript files to add to the page.
	 *
	 * @var array
	 */
	public $scripts = array();

	/**
	 * Inline javascript to add.
	 *
	 * @var string
	 */
	public $inline_js;

	/**
	 * Builds an array of iteratable javascript files.
	 *
	 * @return array
	 */
	public function scripts()
	{
		return $this->build_array($this->scripts, 'src');
	}

	/**
	 * Builds an array of presentable notifications.
	 *
	 * @return array
	 */
	public function notifications()
	{
		if (Auth::instance()->logged_in() AND ! Auth::instance()->logged_in(Model_Role::CONFIRMED))
		{
			// Add this as the last notification
			$this->notifications[] = 'Your account has not yet been confirmed.
				Please check your email for instructions to confirm your registration. '
				. HTML::anchor('user/resend_confirmation', 'Resend Instructions');
		}

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
	 * @param  array
	 * @param  string
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