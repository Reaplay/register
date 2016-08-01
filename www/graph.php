<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 07.06.2016
     * Time: 22:15
     */

    require_once ("include/connect.php");

    dbconn();

    if (!$_GET['action']) {
        stderr("Ошибка","Данной страницы не существует");
    }
    elseif($_GET['action'] == 'count_rck'){
        require_once("elements/graph/count_rck.php");
    }
    elseif($_GET['action'] == 'count_it_block'){
        require_once("elements/graph/count_it_block.php");
    }

