<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 23.05.2016
 * Time: 17:11
 */


$REL_TPL->stdhead('Список людей');


if($_GET['action'] == "add"){
    $action	="add";

    //получаем ШЭ
    $res_ep=sql_query("SELECT `id`,`uid_post` FROM `established_post` WHERE uid_post != 0 AND is_deleted = 0")  or sqlerr(__FILE__, __LINE__);
    while ($row_ep = mysql_fetch_array($res_ep)){
        $data_ep[] = $row_ep;
    }

    //получаем список городов
    $res_city=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);
    while ($row_city = mysql_fetch_array($res_city)){
        $data_city[] = $row_city;
    }
    //получаем функционала
    $res_functionality=sql_query("SELECT `id`,`name_functionality` FROM `functionality`")  or sqlerr(__FILE__, __LINE__);
    while ($row_functionality = mysql_fetch_array($res_functionality)){
        $data_functionality[] = $row_functionality;
    }

    //получаем стратегический проект
    $res_project=sql_query("SELECT `id`,`name_project` FROM `strategic_project`")  or sqlerr(__FILE__, __LINE__);
    while ($row_project = mysql_fetch_array($res_project)){
        $data_strategic_poject[] = $row_project;
    }

    //получаем модель
    $res_model=sql_query("SELECT `id`,`name_model` FROM `employee_model`")  or sqlerr(__FILE__, __LINE__);
    while ($row_model = mysql_fetch_array($res_model)){
        $data_employee_model[] = $row_model;
    }

    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("data_ep",$data_ep);
    $REL_TPL->assignByRef("data_city",$data_city);
    $REL_TPL->assignByRef("data_functionality",$data_functionality);
    $REL_TPL->assignByRef("data_strategic_poject",$data_strategic_poject);
    $REL_TPL->assignByRef("data_employee_model",$data_employee_model);
    $REL_TPL->output("action", "admincp", "employee");
}





if(!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'employee');

    $res=sql_query("
SELECT employee.*, location_city.name_city, location_address.name_address, location_place.floor, location_place.place, location_place.room, functionality.name_functionality
FROM `employee`
LEFT JOIN functionality ON functionality.id = employee.id_functionality
LEFT JOIN location_place ON location_place.id = employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN location_city ON location_city.id = location_address.id_city WHERE employee.is_deleted = 0 ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Люди в  базе не обнаружены","no");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_employee[$i]=$row;
        $data_employee[$i]['date_employment']=mkprettytime($row['date_employment'],false);
        $data_employee[$i]['date_transfer'] = mkprettytime($row['date_transfer'], false);

        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->output("index", "admincp", "employee");
}

$REL_TPL->stdfoot();