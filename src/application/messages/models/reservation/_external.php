<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'start_time' => array(
		'min_time_before_cancel' => 'Reservations can only be cancelled 30 minutes before they begin',
	),
	'end_recurrence' => array(
		'not_empty'             => 'You must provide an end date for your recurrence',
		'max_time_before_start' => 'Your end date for recurrence is too far in the future. 12 week max'
	),
);