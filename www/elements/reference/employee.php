<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 21:22
     */

    // формируем переход между страниц и прочие данные

//статус
    if($_GET['status'] == "1"){
        $where .= " AND established_post.uid_post != ''";
        $add_link .= "&status=1";
    }
    elseif($_GET['status'] == "2"){
        $where .= " AND established_post.uid_post = ''";
        $add_link .= "&status=2";
    }
    //функция
    if((int)$_GET['function'] >0){
        $where .= " AND employee.id_functionality = ".$_GET['function']."";
        $add_link .= "&function=".$_GET['function'];
    }
//город
    if((int)$_GET['city'] >0){
        $where .= " AND established_post.id_location_city = ".$_GET['city']."";
        $add_link .= "&city=".$_GET['city'];
    }
    //должность
    if((int)$_GET['position'] >0){
        $where .= " AND established_post.id_position = ".$_GET['position']."";
        $add_link .= "&position=".$_GET['position'];
    }
    $left_join = "LEFT JOIN functionality ON functionality.id = employee.id_functionality
LEFT JOIN location_place ON location_place.id = employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN location_city ON location_city.id = location_address.id_city
LEFT JOIN established_post ON established_post.id = employee.id_uid_post

";

    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_employee'],'employee',$left_join,$where);

    $res=sql_query("
SELECT employee.*, location_city.name_city, location_address.name_address, location_place.floor, location_place.place, location_place.room, functionality.name_functionality
FROM `employee`
$left_join
WHERE employee.is_deleted = 0 $where
".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Люди в  базе не обнаружены");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_employee[$i]=$row;
        $data_employee[$i]['date_employment']=mkprettytime($row['date_employment'],false);
        $data_employee[$i]['date_transfer'] = mkprettytime($row['date_transfer'], false);

        $i++;
    }

$res_func = sql_query("SELECT id, name_functionality FROM functionality WHERE id_parent != 0");
    while ($row_func = mysql_fetch_array($res_func)){
        $data_functions .='<option value="'.$row_func['id'].'">'.$row_func['name_functionality'].'</option>';

    }
    $res_city = sql_query("SELECT id, name_city FROM location_city");
    while ($row_city = mysql_fetch_array($res_city)){
        $data_city .='<option value="'.$row_city['id'].'">'.$row_city['name_city'].'</option>';

    }
    $res_position = sql_query("SELECT id, name_position FROM `position`");
    while ($row_position = mysql_fetch_array($res_position)){
        $data_position .='<option value="'.$row_position['id'].'">'.$row_position['name_position'].'</option>';

    }




    $REL_TPL->assignByRef('paginator',$paginator);
    //доп. данные для перехода сортировки и фильтров
    $REL_TPL->assignByRef('add_link',$add_link);
    $REL_TPL->assignByRef('add_sort',$sort['link']);
    $REL_TPL->assignByRef('sort',$sort);

    $REL_TPL->stdhead("Список персонала");
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->assignByRef('data_functions',$data_functions);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->assignByRef('data_position',$data_position);
    $REL_TPL->output("employee", "reference");
    $REL_TPL->stdfoot();