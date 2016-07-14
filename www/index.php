<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:14
 */

require_once ("include/connect.php");

dbconn();

$REL_TPL->stdhead("Главная страница");
    $_GET['type'] = "short";
    require_once("elements/register/register_short.php");
//$REL_TPL->stdfoot();

