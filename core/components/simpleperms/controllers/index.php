<?php
// Block access... just in case
if (!defined('MODX_CORE_PATH')) { die('Unauthorized'); }

/** 
 * SimplePerms controller for CMP
 *
 */

define('CMP_MGR_URL', MODX_MANAGER_URL.'?a='.$_GET['a']);
define('CMP_PATH', dirname(dirname(__FILE__)).'/');

if (!$modx->loadClass('ModxCmp',CMP_PATH.'model/simpleperms/',true,true)) {
	die('Could not load class ModxCmp');
}
if (!$modx->loadClass('SimplePerms',CMP_PATH.'model/simpleperms/',true,true)) {
	die('Could not load class SimplePerms');
}

$Page = new ModxCmp($modx);

$action = 'index';
if (isset($_GET['action']) && !empty($_GET['action'])) {
	$action = $_GET['action'];
} 

return $Page->$action();
 