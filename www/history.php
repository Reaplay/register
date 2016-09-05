<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 14.08.2016
     * Time: 20:42
     */

    require_once ("include/connect.php");

    dbconn();

    if(!$_GET['id'] or !is_valid_id($_GET['id'])) {
        stderr ("Ошибка", "Не возможно отобразить историю");
    }
    if($_GET['date_history']){
        require_once("elements/basic/view_history.php");
    }
    else{
        require_once("elements/basic/history.php");

    }