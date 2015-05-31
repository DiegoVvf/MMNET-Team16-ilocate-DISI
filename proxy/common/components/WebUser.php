<?php

class WebUser extends CWebUser
{
	public $loginUrl=null;
	
	public function getIsAdmin()
	{
		return ( $this->getName() == 'admin' );
   	}
	
	public function getIsRegistered()
	{
		return ( !$this->isGuest && !$this->isAdmin );
	}
	
	public function getIsGuest()
	{
		return ($this->name == 'Guest');
	}

}
