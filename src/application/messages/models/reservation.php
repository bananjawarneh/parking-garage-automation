<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'start_time' => array(
		'min_time_before_start' => 'Reservation must be made at least '
		                        .  Date::span(0, Model_Reservation::CURRENT_TIME_START_TIME_GAP, 'minutes')
		                        .  ' minutes in advance',
		'max_time_before_start' => 'Reservations can only be made for '
		                        .  Date::span(0, Model_Reservation::CURRENT_TIME_START_TIME_MAX_GAP, 'weeks')
		                        .  ' weeks in the future',
	),
	'end_time' => array(
		'min_reservation_length' => 'Reservations must be atleast '
		                         .  Date::span(0, Model_Reservation::START_TIME_END_TIME_GAP, 'minutes')
		                         .  ' minutes long',
		'min_time_before_end' => 'Reservation can only be edited '
		                      .  Date::span(0, Model_Reservation::CURRENT_TIME_END_TIME_GAP, 'minutes')
		                      .  ' minutes prior to ending',
	),
);