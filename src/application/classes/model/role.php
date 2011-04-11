<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Role model.
 *
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_Role extends Model_Auth_Role
{
	const LOGIN     = 'login';
	const CONFIRMED = 'confirmed';
	const ADMIN     = 'admin';
} // End Model_Role
