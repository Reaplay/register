<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:40
 */

require_once("include/connect.php");

dbconn();

logoutcookie();

unset($CURUSER);
$REL_TPL->assignByRef('CURUSER',$CURUSER);

$REL_TPL->stdhead("Выход из системы");
stdmsg("Выход из системы успешно произведен","<a href=\"".$REL_CONFIG['defaultbaseurl']."\">Продолжить</a>");
$REL_TPL->stdfoot();

?>