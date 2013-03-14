<?php
/**
 *
 *
 */
class SimplePerms {
	
	public $modx;
	public $errors = array();
	
	//------------------------------------------------------------------------------
	//! MAGIC
	//------------------------------------------------------------------------------
	public function __construct($modx) {
		$this->modx = &$modx;
	}
	
	//------------------------------------------------------------------------------
	//! PRIVATE
	//------------------------------------------------------------------------------
	/**
	 * Create an admin user
	 */
	private function _create_admin($data) {
	
		// User group for the new user (Administrator User Group = 1)
		$UserGroup = $this->modx->getObject('modUserGroup', array('name'=>'Administrator'));
		if (!$UserGroup) {
			$this->errors[] = 'ERROR: Could not find Administrator user group.';
			return false;			
		}
		$user_group = $UserGroup->get('id');
		
		// Role for the new user (Super User Role = 2)
		// But we look it up just in case.
		$UserGroupRole = $this->modx->getObject('modUserGroupRole',array('name'=>'Super User'));
		if (!$UserGroupRole) {
			$this->errors[] = 'ERROR: Could not find the Super User role.';
			return false;
		}
		$user_role = $UserGroupRole->get('id');
	
		$user = $this->modx->newObject('modUser');
		$profile = $this->modx->newObject('modUserProfile');
		
		$user->set('username',$data['username']);
		$user->set('active',1);
		$user->set('password', $data['password1']);
		
		$profile->set('email', $data['email']);
		$profile->set('internalKey',0);
		$user->addOne($profile,'Profile');
		
		// save user
		if (!$user->save()) {
			$this->errors[] = 'ERROR: Could not save user.';
			return false;
		}
		
		// Add User to a User Group
		$Member = $this->modx->newObject('modUserGroupMember');
		$Member->set('user_group', $user_group); 
		$Member->set('member', $user->get('id'));
		// Grant the user a role within that group
		$Member->set('role', $user_role); 
		$Member->set('rank', 0);
	
		return true;
	}

	/**
	 * Create an editor user
	 */
	private function _create_editor($data) {
		return true;
	}

	/**
	 * Create an author user
	 */
	private function _create_author($data) {
		return true;
	}

	/**
	 * Create an subscriber user
	 */
	private function _create_subscriber($data) {
		return true;
	}


	//------------------------------------------------------------------------------
	//! PUBLIC
	//------------------------------------------------------------------------------
	/**
	 *
	 * @param string $user_type admin|editor|author|subscriber
	 */
	public function create_user($user_type,$data) {
		switch($user_type) {
			case 'admin':
				return $this->_create_admin($data);
				break;
			case 'editor':
				return $this->_create_editor($data);
				break;
			case 'author':
				return $this->_create_author($data);
				break;
			case 'subscriber':
				return $this->_create_subscriber($data);
				break;
			default:
				return false;
		}
	}
	
}
/*EOF*/