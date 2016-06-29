<?php

    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 23.06.2016
     * Time: 22:27
     */

    require_once ("include/connect.php");

    dbconn();

    $left_join = "LEFT JOIN functionality ON functionality.id = employee.id_functionality
LEFT JOIN location_place ON location_place.id = employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN location_city ON location_city.id = location_address.id_city
LEFT JOIN established_post ON established_post.id = employee.id_uid_post
LEFT JOIN direction ON direction.id = established_post.id_direction
LEFT JOIN rck ON rck.id = established_post.id_rck

";

    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_employee'],'employee',$left_join, $where);
// получаем основной список сотрудников
    $res=sql_query("
SELECT established_post.id,established_post.uid_post, employee.name_employee,employee.id as e_id, established_post.id_functional_manager, direction.name_direction, established_post.id_administrative_manager, employee.id_functionality, rck.name_rck, established_post.draft, location_city.name_city

FROM `employee`
$left_join
WHERE employee.is_deleted = 0  $where
".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Люди в  базе не обнаружены");
    }

    //получаем список групп функций
    $res_functionality = sql_query("SELECT id, name_functionality, id_parent FROM functionality");
    while ($row_functionality = mysql_fetch_array($res_functionality)) {
        $array_functionality[$row_functionality['id']]['name'] = $row_functionality['name_functionality'];
        $array_functionality[$row_functionality['id']]['id_parent'] = $row_functionality['id_parent'];
    }
    //получаем список дирекций
    $res_direction = sql_query("SELECT id, name_direction FROM direction");
    while ($row_direction = mysql_fetch_array($res_direction)) {
        $array_direction[$row_direction['id']] = $row_direction['name_direction'];
    }

    $i=0;

    while ($row = mysql_fetch_array($res)){

        $data_employee[$i]=$row;
        $data_employee[$i]['date_employment']=mkprettytime($row['date_employment'],false);
        $data_employee[$i]['date_transfer'] = mkprettytime($row['date_transfer'], false);
        //выбираем функционального рук-ля
        $data_employee[$i]['func_mgr'] = select_manager($row['id_functional_manager']);
        //выбираем административного рук-ля
        $data_employee[$i]['adm_mgr'] = select_manager($row['id_administrative_manager']);
       // узнаем ID родителя функции
        $id_parent =  $array_functionality[$row['id_functionality']]['id_parent'];
        //прописываем название группы функции
        $data_employee[$i]['name_functionality'] = $array_functionality[$id_parent]['name'];
       // $data_employee[$i]['name_direction'] = $array_direction[$row['id_direction']];


        $i++;

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
    if($_GET['type'] == "short")
        $REL_TPL->output("register_short", "register");
    elseif($_GET['type'] == "full")
        $REL_TPL->output("register_full", "register");
    $REL_TPL->stdfoot();