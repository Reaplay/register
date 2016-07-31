<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:18
 */
if (!defined("IN_SITE")) die('Direct access to this file not allowed.');
define ("UC_GUEST", -1);
define ("UC_USER", 0); // пользователь


define ("UC_ADMINISTRATOR", 100); // админ


/**
 * Returns username with a color by user class
 * @param int $class id of user class
 * @param string $username username to be colored
 * @return string Colored username
 */
function get_user_class_color($class, $username)
{
	
	switch ($class)
	{
	
		case UC_ADMINISTRATOR:
			return "<span  title=\"Administrator\">" . $username . "</span>";/*style=\"color:green\"*/
			break;

		case UC_USER:
			return "<span title=\"Пользователь\">" . $username . "</span>";
			break;
		case UC_GUEST:
			return "<i>Гость</i>";
			break;

	}
	return "$username";
}

/**
 * Returns user class name
 * @param int $class class id
 * @return string class name
 */
function get_user_class_name($class) {
switch ($class) {
		case UC_USER: return "Пользователь";



		case UC_ADMINISTRATOR: return "Администратор";

		case UC_GUEST: return "Гость";
	}
	return "";
}

/**
 * Checks that id of user class is valid
 * @param int $class id of class
 * @return boolean
 */
function is_valid_user_class($class) {
	return (is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_ADMINISTRATOR) || $class==-1;
}


	function check_access_group($type,$page){
		global $REL_GROUP, $CURUSER;
		$data = strripos($REL_GROUP[$CURUSER['user_group']][$type],$page);

		if ($data === false) {
			return false;
		}
		else
			return true;

	}

?>