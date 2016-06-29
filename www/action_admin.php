<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:38
 */

require "include/connect.php";

dbconn();
if(get_user_class() < UC_ADMINISTRATOR){
	stderr("Ошибка","У вас нет доступа к данной странице");
}
if($_GET['module']){
	require_once("admincp/".$_GET['module'].".php");
}
?>