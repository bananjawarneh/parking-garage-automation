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
	protected $_styles = array(
		array(
			'href' => 'media/css/ie.css',
			'media' => 'screen, projection',
			'condition' => 'IE',
		),
		array(
			'href' => 'media/css/style.css',
			'media' => 'screen, projection',
		),
	);

	protected $_scripts = array(
		'media/js/jquery-1.5.1.min.js',
	);

	protected $_meta_tags = array(
		'description' => 'Rutgers Software Engineering Project (Spring 2011)',
		'keywords'    => 'parking, garage, automation',
	);

	protected $_notifications = array();

	public function styles()
	{
		return $this->_styles;
	}

	public function scripts()
	{
		$scripts = array();

		foreach ($this->_scripts as $script)
		{
			$scripts[] = array('src' => $script);
		}

		return $scripts;
	}

	public function meta_tags()
	{
		$meta_tags = array();

		foreach ($this->_meta_tags as $name => $content)
		{
			$meta_tags[] = array('name' => $name, 'content' => $content);
		}

		return $meta_tags;
	}

	public function notifications()
	{
		$notifications = array();

		foreach ($this->_notifications as $notification)
		{
			$notifications[] = array('notification' => $notification);
		}

		return $notifications;
	}

	/**
	 * Builds the list of errors, if any exist.
	 *
	 * @return string
	 * @todo   Is this really the best way to do this?
	 */
	public function errors()
	{
		if (empty($this->errors))
		{
			return;
		}

		$errors = '<div class="error">'
			    . '<b>Please correct these errors</b>'
		        . '<ul>';

		foreach ($this->errors as $error)
		{
			if (is_array($error))
			{
				foreach ($error as $e)
				{
					$errors .= '<li>'.$e.'</li>';
				}
			}
			else
			{
				$errors .= '<li>'.$error.'</li>';
			}
		}
		
		$errors .= '</ul>'
		         . '</div>';

		return $errors;
	}

// -----------------------------------------------------------------------------

	/**
	 * Base layout template.
	 *
	 * @var string
	 */
	protected $_layout = 'base';

	/**
	 * Whether to render template within base layout template.
	 *
	 * @var bool
	 */
	public $render_layout = TRUE;

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
	 * Determines the template based on the name of the view class.
	 *
	 * @return string
	 */
	public function render()
	{
		$this->base_url = URL::base(FALSE, TRUE);

		return parent::render();
	}
} // End View_Base