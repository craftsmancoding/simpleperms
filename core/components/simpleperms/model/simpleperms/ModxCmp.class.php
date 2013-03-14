<?php
/**
 * Handles drawing of MODX CMP pages in the MODX manager.  Each public function here
 * represents a page that you can specify via the &action URL parameter.
 *
 *
 */
class ModxCmp {

	public $modx;
	public $simplepers;
	
	// Page properties (like placeholders) used on manager pages
	public $props = array(
		'pagetitle' => '',
		'msg' => '',
		'content' => '',
	);

	//------------------------------------------------------------------------------
	//! MAGIC
	//------------------------------------------------------------------------------
	/** 
	 * Manager page air-traffic control
	 */
	public function __call($method, $args) {
	
		if (!method_exists($this, $method)) {
			return $this->show404();
		}
		
		$this->$method();
	}

	/**
	 * Some general gatekeeping
	 */
	public function __construct($modx) {
		$this->modx = &$modx;
		$this->modx->lexicon->load('simpleperms:default');

		$this->props['pagetitle'] = 'Simple Permissions';

		$this->simpleperms = new SimplePerms($modx);
	}
	
	//------------------------------------------------------------------------------
	//! PRIVATE	
	//------------------------------------------------------------------------------

	/**
	 * Used for errors, warnings, and success messages
	 *
	 * @param string $msg
	 * @param string $type error|warning|success
	 */
	private function _get_msg($msg, $type='error') {
		return $this->_load($type, array('msg'=>$msg));
	}
	
	/**
	 * Loads a controller file.
	 * @param	string	$file
	 */
	private function _load($file, $data=array()) {
		extract($data);
		ob_start();
		include(CMP_PATH.'views/'.$file.'.php');
		return ob_get_clean();
	}
	
	/** 
	 * Relies on $this->props
	 */
	private function _render() {
		
		extract($this->props);
		
		ob_start();
		include(CMP_PATH.'views/templates/mgr_page.php');
		return ob_get_clean();
	}


	//------------------------------------------------------------------------------
	//! PUBLIC PAGES
	//------------------------------------------------------------------------------	
	/**
	 * Default controller.  Other controllers route to here.
	 *
	 * @return string HTML page	 
	 */
	public function index($user_type=null) {
		
		if ($user_type) {
			$this->props['pagetitle'] = $this->modx->lexicon('create_'.$user_type);
		}
		else {
			$this->props['pagetitle'] = $this->modx->lexicon('create_user');
		}
			
		$pagedata = array();
		$pagedata['user_type'] = $user_type;	
		$pagedata['action'] = '';	
		if (isset($_GET['action']) && method_exists($this, $_GET['action'])) {
			$pagedata['action'] = $_GET['action'];	
		}
		
		$pagedata['selected'] = array(
			'admin' => '',
			'editor' => '',
			'author' => '',
			'subscriber' => ''
		);
		if ($user_type) {
			$pagedata['selected'][$user_type] = ' selected="selected"';
		}
		$pagedata['username'] = '';
		$pagedata['email'] = '';
		$pagedata['password1'] = '';
		$pagedata['password2'] = '';

		$errors = array();
		if (!empty($_POST)) {
			$req = array('username','email','password1','password2','user_type');
			foreach ($req as $r) {
				if (empty($_POST[$r])) {
					$errors[] = $this->modx->lexicon(
						'required_field', array('field'=>$this->modx->lexicon($r))
					);
				}
			}
			// Passwords match?
			if ($_POST['password1'] != $_POST['password2']) {
				$errors[] = $this->modx->lexicon('passwords_must_match');
			}
			// Valid Username?
			if (!empty($_POST['username'])) {
				if (preg_match('/[^A-Za-z0-9_\-\.@]/',$_POST['username'])) {
					$errors[] = $this->modx->lexicon('invalid_username');
					$_POST['username'] = preg_replace('/[^A-Za-z0-9_\-\.@]/','', $_POST['username']);
				}
				else {
					// Username unique?
					$User = $this->modx->getObject('modUser', array('username'=>$_POST['username']));
					if ($User) {
						$errors[] = $this->modx->lexicon('username_exists');
					}
				}
			}
			
			// Valid Email?
			if(!empty($_POST['email'])) {
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$errors[] = $this->modx->lexicon('invalid_email');
				}
				else {
					// email unique?
					$Profile = $this->modx->getObject('modUserProfile', array('email'=>$_POST['email']));
					if ($Profile) {
						$errors[] = $this->modx->lexicon('email_exists');
					}
				}
			}
			
			$user_type = $_POST['user_type'];
			
			
			// Format errors
			if (!empty($errors)) {
				$error_str = '';
				foreach($errors as $e) {
					$error_str .= '<li>'.$e.'</li>';
				}
				$error_str = '<ul>'.$error_str.'</ul>';
				$this->props['msg'] = $this->_get_msg($error_str, 'error');
				
				// Repopulate Form
				$pagedata['username'] = strip_tags($_POST['username']);
				$pagedata['email'] = strip_tags($_POST['email']);
				$user_type = $_POST['user_type'];
				if (isset($pagedata['selected'][$user_type])) {
					$pagedata['selected'][$user_type] = ' selected="selected"';
				}
				$pagedata['password1'] = htmlspecialchars($_POST['password1']);
				$pagedata['password2'] = htmlspecialchars($_POST['password2']);
			}
			// Success!
			else {
				if($this->simpleperms->create_user($user_type,$_POST)) {
					$this->props['msg'] = $this->_get_msg(
						$this->modx->lexicon('user_created_successfully'), 'success');
				}
				else {
					$this->props['msg'] = $this->_get_msg(
						$this->modx->lexicon('user_creation_failed'), 'warning');
				}
			}
		}

		
		$this->props['content'] = $this->_load('create_user_form', $pagedata);
		
		return $this->_render();
		
	}
	
	
	/**
	 * Our little error page for invalid actions.
	 *
	 * @return string HTML page
	 */
	public function show404() {
		$this->props['pagetitle'] = 'Invalid Action';
		$this->props['msg'] = $this->_get_msg('The action you are requesting could not be found. <a href="'.CMP_MGR_URL.'">Back</a>','error');
		return $this->_render();
	}


	/**
	 *
	 */
	public function admin() {
		return $this->index(__FUNCTION__);
	}

	/**
	 *
	 */
	public function author() {
		return $this->index(__FUNCTION__);
	}

	/**
	 *
	 */
	public function editor() {
		return $this->index(__FUNCTION__);
	}	
	
	/**
	 *
	 */
	public function subscriber() {
		return $this->index(__FUNCTION__);
	}
}
/*EOF*/