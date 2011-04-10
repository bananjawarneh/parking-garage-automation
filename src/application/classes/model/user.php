<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model
 *
 * @package   Park-a-Lot
 * @category  Model
 * @author    Abdul Hassan
 * @copyright (c) 2011 The authors
 * @license   see LICENSE
 */
class Model_User extends ORM
{
	protected $_has_many = array(
		'roles'       => array('model' => 'role', 'through' => 'roles_users'),
		'user_tokens' => array('model' => 'user_token'),
	);

	protected $_created_column = array(
		'column' => 'registration_date',
		'format' => TRUE,
	);

	/**
	 * Password should be encrypted before its stored.
	 *
	 * @return array
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash')),
			),
		);
	}

	/**
	 * First name must not be empty, and between 2 and 30 characters long.
	 * Last name must not be empty, and between 2 and 40 chararacters long.
	 * Email must not be empty, proper email format, between 4 and 127 characters,
	 * and must be available.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			'first_name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 30)),
			),
			'last_name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 40)),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 127)),
				array(array($this, 'available'), array(':value', ':field')),
			),
		);
	}

	/**
	 * Validates user data. Upon success, data is stored in the database and a
	 * confirmation email is sent out to the user.
	 *
	 * @uses   ORM::create To validate and insert data.
	 * @param  array $values
	 * @return bool
	 */
	public function create_user(array $values)
	{
		// Valid password/password_confirmation required
		$password_validation = self::password_validation($values);

		$this->values($values, array(
			'first_name',
			'last_name',
			'email',
			'password',
		))
		->create($password_validation);

		return $this->send_email('confirm_registration');
	}

	/**
	 * Sends different emails to the user.
	 *
	 * @param  string $type The type of email to send.
	 * @param  array  $data Any extra data to add to the email.
	 * @return bool
	 */
	public function send_email($type, array $data = array())
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot send email using unloaded :model model.',
				array(':model' => $this->_object_name));
		}

		// Depends on the type of email
		$subject = $message = NULL;

		// Data to pass to the email message
		$data += array('user' => $this->as_array());

		switch ($type)
		{
			case 'confirm_registration':
				$subject = 'Park-a-Lot Registration';
				$message = Kostache::factory('email/confirm/registration')
					->set('confirmation_url', $this->confirmation_url('confirm_registration'));
			break;

			default: // Invalid type
				throw new Kohana_Exception('Invalid email type: :type', array(':type' => $type));
			break;
		}

		// Render HTML
		$message = $message->set($data)->render();

		return Email::factory($subject, $message, 'text/html')
			->to($this->email, $this->first_name.' '.$this->last_name)
			->from(Kohana::config('site.emails.outgoing'), 'Park a Lot')
			->send();
	}

	/**
	 * Builds a confirmation url, which must be validated to complete a previous
	 * action.
	 *
	 * @param  string $action User action to redirect to.
	 * @return string
	 */
	protected function confirmation_url($action)
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot build a confirmation url using an unloaded :model model.',
				array(':model' => $this->_object_name));
		}

		// ex: http://site.com/user/confirm_registration?id=34&token=12323kl...
		$url = Route::url('default', array('action' => $action))
			 . URL::query(array(
				 'id'    => $this->id,
				 'time'  => time(),
				 'token' => $this->generate_token(),
			   ));

		// Build full url, with protocol
		return URL::site($url, TRUE);
	}

	/**
	 * Generates a token, used for different purposes.
	 *
	 * @return string
	 */
	protected function generate_token()
	{
		return Auth::instance()->hash($this->email);
	}

	/**
	 * Returns a validation object for password validation.
	 *
	 * Password must not be empty, between 6 and 15 characters, and must contain
	 * at least 1 character and 1 number.
	 *
	 * @param  array $values
	 * @return Validation
	 */
	protected static function password_validation(array $values)
	{
		return Validation::factory($values)
			->rules('password', array(
				array('not_empty'),
				array('min_length', array(':value', 6)),
				array('max_length', array(':value', 15)),
				array('regex', array(':value', '/^(?=.*[0-9])(?=.*[a-zA-Z]).+$/')),
			))
			->rules('password_confirm', array(
				array('matches', array(':validation', ':field', 'password')),
			));
	}

	protected function unique_key($value)
	{
		return Valid::email($value) ? 'email' : 'id';
	}
} // End Model_User