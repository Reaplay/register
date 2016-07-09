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
    $REL_TPL->assignByRef("action",$action);

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