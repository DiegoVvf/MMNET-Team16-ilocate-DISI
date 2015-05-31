<?php
/**
 * UserIdentity.php
 *
 * This class represents a user identity and this is able to authenticate a user.
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/22/12
 * Time: 8:36 PM
 *
 *
 */
class UserIdentity extends CUserIdentity {
	/**
	 * @var integer id of logged user
	 */
	private $_id;

	/**
	 * Authenticates username and password
	 * @return boolean CUserIdentity::ERROR_NONE if successful authentication
	 */
	public function authenticate() {
		
		if($this->username == 'admin') {
			if($this->password == 'admin') {
				$this->errorCode=self::ERROR_NONE;
				//Yii::app()->user->setState('isAdmin', true);
				return !$this->errorCode;
			}
		}
		
		$attribute = strpos($this->username, '@') ? 'email' : 'username';
		$user = Customer::model()->find(array('condition' => $attribute . '=:loginname', 'params' => array(':loginname' => $this->username)));
		// print_r($user->username . ' ' . $user->email . ' ' . $user->password);
		// Yii::app()->end();
		if ($user === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if (!$user->verifyPassword($this->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} else {
			//$user->regenerateValidationKey();
			$this->_id = $user->id;
			$this->username = $user->username;
			//$this->setState('vkey', $user->validation_key);
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	/**
	 * Creates an authenticated user with no passwords for registration
	 * process (checkout)
	 * @param string $username
	 * @return self
	 */
	public static function createAuthenticatedIdentity($id, $username) {
		$identity = new self($username, '');
		$identity->_id = $id;
		$identity->errorCode = self::ERROR_NONE;
		return $identity;
	}

	/**
	 *
	 * @return integer id of the logged user, null if not set
	 */
	public function getId() {
		return $this->_id;
	}
}