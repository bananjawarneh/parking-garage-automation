<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'member_price' => array(
		'member_lte_guest'     => 'Members cannot have a higher rate than guests',
		'price_plan_available' => 'That price plan already exists',
	),
	'discount_rate' => array(
		'max_discount_rate' => ':field must be below 1.00 i.e, 100%',
	),
	'min_price' => array(
		'discount_rate_set' => 'A minimum price is required when supplying a discount rate',
	),
);