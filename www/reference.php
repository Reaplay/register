<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 20:42
     */

    require_once("include/connect.php");

    dbconn();
    //loggedinorreturn();
    if (!$_GET['type']) {
        stderr("Ошибка","Данной страницы не существует");
    }
    elseif($_GET['type'] == 'department'){
        require_once("elements/reference/department.php");
    }
    elseif($_GET['type'] == 'direction'){
        require_once("elements/reference/direction.php");
    }
    elseif($_GET['type'] == 'employee'){
        require_once("elements/reference/employee.php");
    }
    elseif($_GET['type'] == 'established_post'){
        require_once("elements/reference/established_post.php");
    }
    elseif($_GET['type'] == 'mvz'){
        require_once("elements/reference/mvz.php");
    }
    elseif($_GET['type'] == 'position'){
        require_once("elements/reference/position.php");
    }









