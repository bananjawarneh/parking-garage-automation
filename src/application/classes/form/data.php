<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form data container.
 * 
 * @package   Park-a-Lot
 * @category  Helper
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Form_Data
{
	/**
	 * Returns an array of US states.
	 *
	 * @return array
	 */
	public static function states()
	{
		return array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District of Columbia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' =>	'Iowa',
			'KS' =>	'Kansas',
			'KY' =>	'Kentucky',
			'LA' =>	'Louisiana',
			'ME' =>	'Maine',
			'MD' =>	'Maryland',
			'MA' =>	'Massachusetts',
			'MI' =>	'Michigan',
			'MN' =>	'Minnesota',
			'MS' =>	'Mississippi',
			'MO' =>	'Missouri',
			'MT' =>	'Montana',
			'NE' =>	'Nebraska',
			'NV' =>	'Nevada',
			'NH' =>	'New Hampshire',
			'NJ' =>	'New Jersey',
			'NM' =>	'New Mexico',
			'NY' =>	'New York',
			'NC' =>	'North Carolina',
			'ND' =>	'North Dakota',
			'OH' =>	'Ohio',
			'OK' =>	'Oklahoma',
			'OR' =>	'Oregon',
			'PA' =>	'Pennsylvania',
			'PR' => 'Puerto Rico',
			'RI' =>	'Rhode Island',
			'SC' =>	'South Carolina',
			'SD' =>	'South Dakota',
			'TN' =>	'Tennessee',
			'TX' =>	'Texas',
			'UT' =>	'Utah',
			'VT' =>	'Vermont',
			'VA' =>	'Virginia',
			'WA' =>	'Washington',
			'WV' =>	'West Virginia',
			'WI' =>	'Wisconsin',
			'WY' =>	'Wyoming',
		);
	}
} // End Form_Data