<?php

namespace PHPixie\Auth\Login;

/**
 * Password login provider using salted password hashes.
 */
class Password extends Provider {

	/**
	 * Field in the users table where the users
	 * login is stored.
	 * @var string
	 */
	protected $login_field;
<<<<<<< HEAD
	
=======

>>>>>>> refs/remotes/origin/master
	/**
	 * Field in the users table where the users
	 * password is stored.
	 * @var string
	 */
	protected $password_field;
<<<<<<< HEAD
	
=======

>>>>>>> refs/remotes/origin/master
	/**
	 * Hash algorithm to use. If not set
	 * the passwords are saved without hashing.
	 * @var string
	 */
	protected $hash_method;
<<<<<<< HEAD
	
=======

>>>>>>> refs/remotes/origin/master
	/**
	 * Name of the login provider
	 * @var string
	 */
	protected $name = 'Password';
<<<<<<< HEAD
	
	/**
	 * Constructs password login provider for the specified configuration.
	 * 
=======

	/**
	 * Constructs password login provider for the specified configuration.
	 *
>>>>>>> refs/remotes/origin/master
	 * @param \PHPixie\Pixie $pixie Pixie dependency container
	 * @param \PHPixie\Pixie\Service $service Service instance that this login provider belongs to.
	 * @param string $config Name of the configuration
	 */
	public function __construct($pixie, $service, $config) {
		parent::__construct($pixie, $service, $config);
		$this->login_field = $pixie->config->get($this->config_prefix."login_field");
		$this->password_field = $pixie->config->get($this->config_prefix."password_field");
		$this->hash_method = $pixie->config->get($this->config_prefix."hash_method",'md5');
	}
<<<<<<< HEAD
	
	/**
	 * Attempts to log the user in using his login and password
	 * 
=======

	/**
	 * Attempts to log the user in using his login and password
	 *
>>>>>>> refs/remotes/origin/master
	 * @param string $login Users login
	 * @param string $password Users password
	 * @return bool If the user exists.
	 */
	public function login($login, $password) {
		$user = $this->service->user_model()
						->where($this->login_field, $login)
						->find();
		if($user->loaded()){
			$password_field = $this->password_field;
			$challenge = $user->$password_field;
<<<<<<< HEAD
			
=======

>>>>>>> refs/remotes/origin/master
			if($this->hash_method){
				$salted = explode(':', $challenge);
				$password = hash($this->hash_method, $password.$salted[1]);
				$challenge = $salted[0];
			}
			if ($challenge == $password) {
				$this->set_user($user);
				return true;
			}
		}
		return false;
	}

	/**
	 * Hashes the password using the configured method
<<<<<<< HEAD
	 * 
=======
	 *
>>>>>>> refs/remotes/origin/master
	 * @param string $password Password to hash
	 * @return string Hashed password
	 */
	public function hash_password($password){
		if(!$this->hash_method)
			return $password;
		$salt = uniqid(rand());
		return hash($this->hash_method, $password.$salt).':'.$salt;
<<<<<<< HEAD
	}	
=======
	}
>>>>>>> refs/remotes/origin/master
}
