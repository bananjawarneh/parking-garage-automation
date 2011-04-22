<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model.
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
		'roles'        => array('model' => 'role', 'through' => 'roles_users'),
		'reservations' => array('model' => 'reservation'),
		'user_tokens'  => array('model' => 'user_token'),
		'vehicles'     => array('model' => 'vehicle'),
	);

	protected $_created_column = array(
		'column' => 'registration_date',
		'format' => TRUE,
	);

	/**
	 * All data should be trimmed, and password should be encrypted before its
	 * stored.
	 *
	 * @return array
	 */
	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'password' => array(
				array(array(Auth::instance(), 'hash')),
			),
		);
	}

	public function labels()
	{
		$labels = array();

		foreach ($this->_object as $field => $value)
		{
			$labels[$field] = str_replace('_', ' ', ucfirst($field));
		}

		return $labels;
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

		// Allow this user to login
		$this->add('roles', ORM::factory('role', array('name' => Model_Role::LOGIN)));

		return $this->send_email('confirm_registration');
	}

	/**
	 * Validates and creates a new reservation. Binds it to this user upon
	 * success.
	 *
	 * @param  array $values
	 * @return bool
	 */
	public function add_reservation(array $values)
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot add a reservation to an unloaded :model model.',
				array(':model' => $this->_object_name));
		}

		// Override whatever was set
		$values['user_id'] = $this->id;

		return ORM::factory('reservation')
			->create_reservation($values);
	}

	/**
	 * Validates and saves a new vehicle, and binds it to this user.
	 *
	 * @param array $values
	 * @return bool
	 */
	public function add_vehicle(array $values)
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot add a vehicle to an unloaded :model model.',
				array(':model' => $this->_object_name));
		}

		// Override whatever was set
		$values['user_id'] = $this->id;

		return ORM::factory('vehicle')
			->create_vehicle($values);
	}

	/**
	 * Validates the login form, and logs the user in upon success.
	 *
	 * @param  array &$values transformed into a validation object
	 * @return bool
	 */
	public function login(array &$values)
	{
		if ($this->loaded())
		{
			throw new Kohana_Exception('Cannot log in using an already loaded :model model.',
				array(':model' => $this->_object_name));
		}

		$values = Validation::factory($values)
			->rule('email', 'not_empty')
			->rule('password', 'not_empty')
			->labels(array(
				'email'    => 'Email',
				'password' => 'Password',
			));

		if ( ! $values->check())
		{
			// Failed validation
			return FALSE;
		}

		// Try loading the user by email
		$this->where('email', '=', $values['email'])->find();

		if ( ! Auth::instance()->login($this, $values['password'], (bool) @$values['remember']))
		{
			// Invalid login
			$values->error('email', 'invalid');

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Logs this user in by force. Best used after user registration.
	 *
	 * @return void
	 */
	public function force_login()
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot log in an unloaded :model model.',
				array(':model' => $this->_object_name));
		}

		return Auth::instance()->force_login($this);
	}

	/**
	 * Sends different emails to the user.
	 *
	 * @param  string $type the type of email to send
	 * @param  array  $data any extra data to add to the email
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
	 * Confirms user registration.
	 *
	 * @param  array $params
	 * @return bool
	 */
	public function confirm_create_user(array $params)
	{
		if ( ! $this->check_confirmation($params))
		{
			return FALSE;
		}

		if ( ! $this->has('roles', ORM::factory('role', array('name' => Model_Role::CONFIRMED))))
		{
			// User is now confirmed
			$this->add('roles', ORM::factory('role', array('name' => Model_Role::CONFIRMED)));
		}

		return TRUE;
	}

	/**
	 * Validated the confirmation url.
	 *
	 * @param  array $params
	 * @return bool
	 */
	protected function check_confirmation(array $params)
	{
		if ( ! $this->loaded())
		{
			throw new Kohana_Exception('Cannot use unloaded :model to check confirmation.',
				array(':model' => $this->_object_name));
		}
		
		if ( ! isset($params['id'], $params['token']))
		{
			return FALSE;
		}

		if ($this->id !== $params['id'])
		{
			// User cannot confirm someone elses notice
			return FALSE;
		}

		return ($params['token'] === $this->generate_token());
	}

	/**
	 * Builds a confirmation url, which must be validated to complete a previous
	 * action.
	 *
	 * @param  string $action user controller action to redirect to
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
		$url = Route::url('default', array('controller' => 'user', 'action' => $action))
			 . URL::query(array(
				 'id'    => $this->id,
				 'time'  => time(),
				 'token' => $this->generate_token(),
			   ));

		// Build full url, with protocol
		return URL::site($url, TRUE);
	}

	/**
	 * Generates a confirmation token.
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
				array('matches', array(':validation', 'password', ':field')),
			))
			->labels(array(
				'password'         => 'Password',
				'password_confirm' => 'Password confirmation',
			));
	}

	protected function unique_key($value)
	{
		return Valid::email($value) ? 'email' : 'id';
	}

	/**
	 * Taken directly from Model_Auth_User.
	 *
	 * @return void
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = time();

			// Save the user
			$this->update();
		}
	}
} // End Model_User