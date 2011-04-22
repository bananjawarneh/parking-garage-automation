<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'start_time' => array(
		'min_time_before_start' => 'Reservation must be made at least 30 minutes in advance',
		'max_time_before_start' => 'Reservations can only be made for 12 weeks in the future',
	),
	'end_time' => array(
		'min_reservation_length' => 'Reservations must be atleast 30 minutes long',
		'min_time_before_end'    => 'Reservation can only be edited 30 minutes prior to ending',
	),
);