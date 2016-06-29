<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 15:38
 */


require "include/connect.php";
dbconn();
//loggedinorreturn();

//httpauth();

if(get_user_class() < UC_ADMINISTRATOR){
	stderr("Ошибка","У вас нет доступа к данной странице");
}

$REL_TPL->stdhead('Administrator control panel');


$REL_TPL->output("admincp","basic");
$REL_TPL->stdfoot();
?>