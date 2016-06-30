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
LEFT JOIN mvz ON mvz.id = established_post.id_mvz

";

    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_employee'],'employee',$left_join, $where);
// получаем основной список сотрудников
    $res=sql_query("
SELECT
established_post.id,established_post.uid_post,established_post.id_position, established_post.id_functional_manager, established_post.id_administrative_manager, established_post.draft,established_post.transfer, established_post.date_entry, established_post.id_direction, established_post.id_department, established_post.id_block,
employee.name_employee,employee.id as e_id, employee.id_functionality, employee.date_transfer, employee.date_employment, employee.fte,
direction.name_direction,
rck.name_rck,
mvz.name_mvz,
location_city.name_city,
location_address.name_address,
location_place.floor,location_place.room,location_place.place,location_place.ready, location_place.date_ready, location_place.reservation, location_place.date_reservation, location_place.occupy,location_place.date_occupy

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
    $res_direction = sql_query("SELECT direction.id, employee.name_employee FROM direction LEFT JOIN employee ON employee.id = direction.id_employee");
    while ($row_direction = mysql_fetch_array($res_direction)) {
        $array_direction[$row_direction['id']] = $row_direction['name_employee'];
    }
    //получаем список должностей
    $res_position = sql_query("SELECT id, name_position FROM `position`");
    while ($row_position = mysql_fetch_array($res_position)) {
        $array_position[$row_position['id']] = $row_position['name_position'];
    }

    //получаем список подразделений
    $res_department = sql_query("SELECT id, name_department, level FROM `department`");
    while ($row_department = mysql_fetch_array($res_department)) {
        $array_department[$row_department['id']]['name_department'] = $row_department['name_department'];
        $array_department[$row_department['id']]['level'] = $row_department['level'];
    }

    //получаем список блоков
    $res_block = sql_query("SELECT id, name_block FROM `block`");
    while ($row_block = mysql_fetch_array($res_block)) {
        $array_block[$row_block['id']] = $row_block['name_block'];
    }


    $i=0;

    while ($row = mysql_fetch_array($res)){

        $data_employee[$i]=$row;
        $data_employee[$i]['date_employment']=mkprettytime($row['date_employment'],false);
        $data_employee[$i]['date_transfer'] = mkprettytime($row['date_transfer'], false);
        $data_employee[$i]['date_entry'] = mkprettytime($row['date_entry'], false);


        $data_employee[$i]['date_transfer'] = mkprettytime($row['date_transfer'], false);
        $data_employee[$i]['date_employment'] = mkprettytime($row['date_employment'], false);

        $data_employee[$i]['date_ready'] = mkprettytime($row['date_ready'], false);
        $data_employee[$i]['date_occupy'] = mkprettytime($row['date_occupy'], false);
        $data_employee[$i]['date_reservation'] = mkprettytime($row['date_reservation'], false);
        //выбираем функционального рук-ля
        $data_employee[$i]['func_mgr'] = select_manager($row['id_functional_manager']);
        //выбираем административного рук-ля
        $data_employee[$i]['adm_mgr'] = select_manager($row['id_administrative_manager']);
       // узнаем ID родителя функции
        $id_parent =  $array_functionality[$row['id_functionality']]['id_parent'];
        //прописываем название  функции
        $data_employee[$i]['group_functionality'] = $array_functionality[$id_parent]['name'];
        $data_employee[$i]['name_functionality'] = $array_functionality[$row['id_functionality']]['name'];
        $data_employee[$i]['name_position'] = $array_position[$row['id_position']];
        $data_employee[$i]['name_cur_direction'] = $array_direction[$row['id_direction']];
        $data_employee[$i]['name_block'] = $array_block[$row['id_block']];
        //обрабатываем подраздления
        $department = explode(",",$row['id_department']);
        unset($data_department);
        foreach($department as $dep) {
            $data_department[$array_department[$dep]['level']] = $array_department[$dep]['name_department'];

        }

        $data_employee[$i]['department'] = $data_department;

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