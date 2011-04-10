<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Custom error handling.
 *
 * @package   Park-a-Lot
 * @category  Exception
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Kohana_Exception extends Kohana_Kohana_Exception
{
	/**
	 * Exception classes, and the actions responsible for them.
	 *
	 * @todo Add more exceptions to get more refined error messages.
	 * @var  array
	 */
	protected static $exceptions = array(
		'HTTP_EXCEPTION_404' => 404,
		'DEFAULT'            => 500,
	);
	
	/**
	 * Handles exceptions differently when in production.
	 *
	 * <h4>Preconditions</h4>
	 * <ul>
	 *     <li>A route for an error controller, named "errors</li>
	 *     <li>The error controller should have an action for each
	 * </ul>
	 * 
	 * @param  Exception $e
	 * @return bool
	 */
	public static function handler(Exception $e)
	{
		if (Kohana::$environment !== Kohana::PRODUCTION)
		{
			// Safe to show
			return parent::handler($e);
		}

		$error = array(
			'action' => Arr::get(self::$exceptions, strtoupper(get_class($e)),
				self::$exceptions['DEFAULT']),
		);

		// Sub request for custom error
		echo Request::factory(Route::url('error', $error))
			->execute()
			->send_headers()
			->body();

		if (is_object(Kohana::$log))
		{
			// Log the exception
			Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e))->write();
		}

		return TRUE;
	}
} // End Library_Kohana_Exception