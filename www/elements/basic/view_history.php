<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 04.09.2016
     * Time: 21:53
     */




    $date_history_start = unix_time($_GET['date_history']);
    //изменения за день
    $date_history_end = $date_history_start + 60*60*24 - 1;
// получение менеджеров

$id = (int)$_GET['id'];

/*новый код*/
    /*ДАННЫЕ ПО СОТРУДНИКАМ*/
    $res_emp = sql_query("SELECT id_uid_post, revision, last_update, added FROM employee WHERE id = '".$id."';") or sqlerr(__FILE__, __LINE__);
    $row_emp = mysql_fetch_array($res_emp);

    // если дата просматриваемой истории меньше чем текущая дата изменений и какие-то изменения были, то значит смотрим исторические данные
    if($row_emp['last_update'] > 0 AND $row_emp['last_update']> $date_history_start AND $row_emp['added'] < $date_history_start){
        $emp_table = 'revision_employee';
        $emp_id = 'id_employee';
        // если ревизия текущих данных уже вторая, то берем по дате добавления из истории
        if($row_emp['revision'] == 2){
            $emp_update = "added";
        }
        //иначе смотрим дату изменения
        else{
            $emp_update = "last_update";
        }


    }
    //иначе текущие данные
    elseif($row_emp['last_update']<= $date_history_start AND $row_emp['added'] < $date_history_start){
        $emp_table = 'employee';
        $emp_id = 'id';
        // если ревизия первая, то берем по дате добавления
        if($row_emp['revision'] == 1){
            $emp_update = "added";
        }
        //иначе смотрим дату изменения
        else{
            $emp_update = "last_update";
        }

    }
    else{
        stderr("Ошибка","Данные по сотруднику не найдены ");
    }
    //делаем запрос к истории, что бы узнать к какой ШЕ была прикреплена запись
    $res_emp_h = sql_query("
SELECT id_uid_post, name_employee AS em_name, date_employment, date_transfer, id_functionality, fte, $emp_table.id as eid, $emp_table.last_update as r_emp_lu,
location_place.place, location_place.floor, location_place.room, location_place.id_address AS lp_ia, location_place.ready, location_place.date_ready, location_place.reservation, location_place.date_reservation, location_place.occupy, location_place.date_occupy,
employee_model.name_model
FROM $emp_table
LEFT JOIN location_place ON location_place.id = $emp_table.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN employee_model ON employee_model.id = $emp_table.id_employee_model
WHERE $emp_table.$emp_id = '".$id."' AND if($emp_table.last_update >0, $emp_table.last_update <= '".$date_history_start."' , $emp_table.added <= '".$date_history_start."')
ORDER BY $emp_table.`revision` DESC LIMIT 1;
")  or sqlerr(__FILE__, __LINE__);

    $emp_history = mysql_fetch_array($res_emp_h);
    /*ДАННЫЕ ПО СОТРУДНИКАМ*/

    /*ДАННЫЕ ПО ШЕ*/
    $res_esp = sql_query("SELECT id, revision, last_update, added FROM established_post WHERE id = '".$emp_history['id_uid_post']."';") or sqlerr(__FILE__, __LINE__);
    $row_esp = mysql_fetch_array($res_esp);


    // если дата просматриваемой истории меньше чем текущая дата изменений и какие-то изменения были, то значит смотрим исторические данные
    if($row_esp['last_update'] > 0 AND $row_esp['last_update']> $date_history_start AND $row_esp['added'] < $date_history_start){
        $esp_table = 'revision_established_post';
        $esp_id = 'id_established_post';
        // если ревизия текущих данных уже вторая, то берем по дате добавления из истории
        if($row_esp['revision'] == 2){
            $esp_update = "added";
        }
        //иначе смотрим дату изменения
        else{
            $esp_update = "last_update";
        }
    }
    //иначе текущие данные
    elseif($row_esp['last_update']<= $date_history_start AND $row_esp['added'] < $date_history_start){
        $esp_table = 'established_post';
        $esp_id = 'id';
        // если ревизия первая, то берем по дате добавления
        if($row_esp['revision'] == 1){
            $esp_update = "added";
        }
        //иначе смотрим дату изменения
        else{
            $esp_update = "last_update";
        }
    }
    else{
        stderr("Ошибка","Данные по ШЕ не найдены");
    }
    /*ДАННЫЕ ПО ШЕ*/





    $res_esp_h = sql_query("
SELECT *,
position.name_position,
name_block,
location_city.name_city,
direction.name_direction,direction.id_employee AS id_curator,direction.id as did, (SELECT employee.name_employee FROM employee WHERE employee.id = direction.id_employee) AS name_curator,
mvz.name_mvz,
rck.name_rck
FROM $esp_table
LEFT JOIN `position` ON position.id = ".$esp_table.".id_position
LEFT JOIN `block` ON block.id = ".$esp_table.".id_block
LEFT JOIN location_city ON location_city.id = ".$esp_table.".id_location_city
LEFT JOIN direction ON direction.id = ".$esp_table.".id_direction
LEFT JOIN mvz ON mvz.id = ".$esp_table.".id_mvz
LEFT JOIN rck ON rck.id = mvz.id_rck
WHERE $esp_table.$esp_id = '".$row_esp['id']."' AND if($esp_table.last_update >0, $esp_table.last_update <= '".$date_history_start."' , $esp_table.added <= '".$date_history_start."')
ORDER BY $esp_table.`revision` DESC LIMIT 1;
")  or sqlerr(__FILE__, __LINE__);


    $esp_history = mysql_fetch_array($res_esp_h);

    /*ОБРАБОТКА ДАННЫХ*/
    $data_employee[0] = array_merge($emp_history, $esp_history);
    //преобразовываем даты в красивый вид
    $data_employee[0]['date_ready'] = mkprettytime ($emp_history['date_ready'], false);
    $data_employee[0]['date_reservation'] = mkprettytime ($emp_history['date_reservation'], false);
    $data_employee[0]['date_occupy'] = mkprettytime ($emp_history['date_occupy'], false);
    $data_employee[0]['date_entry'] = mkprettytime ($esp_history['date_entry'], false);
    $data_employee[0]['date_employment'] = mkprettytime ($emp_history['date_employment'], false);
    $data_employee[0]['date_transfer'] = mkprettytime ($emp_history['date_transfer'], false);
    $data_employee[0]['emp_last_update'] = mkprettytime ($emp_history['last_update'], false);
    $data_employee[0]['esp_last_update'] = mkprettytime ($esp_history['last_update'], false);
//$row['date_'] = mkprettytime($row['date_']);


//$row['name_rck'] = set_rck($row['id_rck']);
//выбираем функционального рук-ля
    $data_employee[0]['data_f_m'] = select_manager ($esp_history['id_functional_manager']);
//выбираем административного рук-ля
    $data_employee[0]['data_a_m'] = select_manager ($esp_history['id_administrative_manager']);
// делаем кликабельной дирекцию
    $data_employee[0]['name_direction'] = "<a href='register.php?type=short&direction=".$esp_history['did']."'>".$esp_history['name_direction']."</a>";
//$row['id_department'];
    //если задано подразделение
if($esp_history['id_department']){
        $sub_res = sql_query ("SELECT department.id, department.name_department, /*department.id_parent,*/ type_office.name_office as type_office, department.level  FROM department LEFT JOIN type_office ON type_office.id = department.id_type_office WHERE department.id IN (" . $esp_history['id_department'] . ")") or sqlerr(__FILE__, __LINE__);
        //   $i=0;
        while ($sub_row = mysql_fetch_array ($sub_res)) {
            $department[$sub_row['level']] = '<a href="register.php?type=short&department='.$sub_row['id'].'">'.$sub_row['name_department'].'</a>';
        }

        ksort ($department);
        /* foreach ($department as $val) {
             if ($data_department)
                 $data_department .= ", ";
             $data_department .= $val;
         }*/
        $data_employee[0]['department'] = $department;
    }
//определеяем где сидит функц. рукль;
    if($esp_history['id_administrative_manager']) {

        $cur_res = sql_query ("SELECT name_rck FROM rck LEFT JOIN employee ON employee.id = " . $esp_history['id_administrative_manager'] . " LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE rck.id = established_post.id_rck") or sqlerr(__FILE__, __LINE__);
        $cur_row = mysql_fetch_array ($cur_res);
        $data_employee[0]['rck_curator'] = $cur_row['name_rck'];
    }
//разбиваем на массив
    $functionality = explode (",", $esp_history['id_functionality']);
    foreach ($functionality as $value) {
        $id_functionality = $value;
        for ($value; $value != 0; $i++) {

            $sub_res = sql_query ("SELECT id, name_functionality, id_parent FROM functionality WHERE id = '" . $value . "'") or sqlerr(__FILE__, __LINE__);
            $sub_row = mysql_fetch_array ($sub_res);
            if (!$sub_row['id_parent']) {
                $list_functionality['group'][$sub_row['id']] = $sub_row['name_functionality'];
            } else
                $list_functionality['function'][$sub_row['id']] = $sub_row['name_functionality'];

            $value = $sub_row['id_parent'];

        }


    }



    /*ОБРАБОТКА ДАННЫХ*/

    $REL_TPL->stdhead("Просмотр");
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->assignByRef('data_f_m',$data_f_m);
    $REL_TPL->assignByRef('data_a_m',$data_a_m);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->assignByRef('list_functionality',$list_functionality);
    $REL_TPL->assignByRef('date_history',$_GET['date_history']);
    $REL_TPL->output("view","basic");
    $REL_TPL->stdfoot();



    die();
    /*конец кода*/






  /*  $res_emp = sql_query("SELECT established_post.revision, established_post.last_update,revision_employee.last_update as re_last_update FROM established_post LEFT JOIN revision_employee ON revision_employee.id_employee = '".$id."' WHERE established_post.id = revision_employee.id_uid_post AND if(revision_employee.last_update >0, revision_employee.last_update >= '".$date_history_start."' AND revision_employee.last_update <= '".$date_history_end."', revision_employee.added >= '".$date_history_start."' AND revision_employee.added <= '".$date_history_end."')  ") or sqlerr(__FILE__, __LINE__);*/
    $res_emp = sql_query("SELECT established_post.revision, established_post.last_update,revision_employee.last_update as re_last_update FROM established_post LEFT JOIN revision_employee ON revision_employee.id_employee = '".$id."' WHERE established_post.id = revision_employee.id_uid_post AND if(revision_employee.last_update >0, revision_employee.last_update <= '".$date_history_start."' , revision_employee.added <= '".$date_history_start."' )  ") or sqlerr(__FILE__, __LINE__);

    $row_emp = mysql_fetch_array($res_emp);
    if($row_emp['revision'] == 1) {
        $table_emp = 'established_post';
        $left_join = "LEFT JOIN revision_employee ON revision_employee.id_uid_post = ".$table_emp.".id";
    }
    elseif($row_emp['last_update'] <= $row_emp['re_last_update']){
        $table_emp = 'established_post';
        $left_join = "LEFT JOIN revision_employee ON revision_employee.id_uid_post = ".$table_emp.".id";
    }
    else {
        $table_emp = 'revision_established_post';
        $left_join = "LEFT JOIN revision_employee ON revision_employee.id_uid_post = ".$table_emp.".id_established_post";
    }

$res=sql_query("
SELECT ".$table_emp.".*,
position.name_position,
name_block,
revision_employee.name_employee AS em_name, revision_employee.date_employment, revision_employee.date_transfer, revision_employee.id_functionality, revision_employee.fte, revision_employee.id as eid, revision_employee.last_update as r_emp_lu,
location_city.name_city,
location_place.place, location_place.floor, location_place.room, location_place.id_address AS lp_ia, location_place.ready, location_place.date_ready, location_place.reservation, location_place.date_reservation, location_place.occupy, location_place.date_occupy,
location_address.name_address, location_address.id_city AS la_id_city,
direction.name_direction,direction.id_employee AS id_curator,direction.id as did, (SELECT employee.name_employee FROM employee WHERE employee.id = direction.id_employee) AS name_curator,
mvz.name_mvz,
rck.name_rck,
employee_model.name_model
FROM ".$table_emp."
LEFT JOIN `position` ON position.id = ".$table_emp.".id_position
LEFT JOIN `block` ON block.id = ".$table_emp.".id_block
$left_join
LEFT JOIN location_city ON location_city.id = ".$table_emp.".id_location_city
LEFT JOIN location_place ON location_place.id = revision_employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN direction ON direction.id = ".$table_emp.".id_direction
LEFT JOIN mvz ON mvz.id = ".$table_emp.".id_mvz
LEFT JOIN rck ON rck.id = mvz.id_rck
LEFT JOIN employee_model ON employee_model.id = revision_employee.id_employee_model
WHERE revision_employee.id_employee = $id

AND if(revision_employee.last_update >0, revision_employee.last_update <= '".$date_history_start."' , revision_employee.added <= '".$date_history_start."')

/*AND if(revision_employee.last_update >0, revision_employee.last_update >= '".$date_history_start."' AND revision_employee.last_update <= '".$date_history_end."', revision_employee.added >= '".$date_history_start."' AND revision_employee.added <= '".$date_history_end."')*/
AND ".$table_emp.".last_update <= '".$date_history_start."'
/*AND ".$table_emp.".last_update <= revision_employee.last_update*/
ORDER BY revision_employee.`last_update` DESC LIMIT 1;
/*ORDER BY ".$table_emp.".`last_update` DESC LIMIT 1;*/


/*AND if(".$table_emp.".last_update >0, ".$table_emp.".last_update >= '".$date_history_start."' AND ".$table_emp.".last_update <= '".$date_history_end."', ".$table_emp.".added >= '".$date_history_start."' AND ".$table_emp.".added <= '".$date_history_end."')*/


/*AND ((revision_employee.last_update > '".$date_history_start."' AND revision_employee.last_update < '".$date_history_end."') OR (revision_employee.added > '".$date_history_start."' AND revision_employee.added < '".$date_history_end."'))
AND ((revision_established_post.last_update > '".$date_history_start."' AND revision_established_post.last_update < '".$date_history_end."') OR (revision_established_post.added > '".$date_history_start."' AND revision_established_post.added < '".$date_history_end."'))*/
/*WHERE established_post.id='".$_GET['id']."'*/
") or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Ничего не найдено");
    }

    $i=0;
//$row = array();
while($row = mysql_fetch_array($res)) {
    $data_employee[$i] = $row;


//преобразовываем даты в красивый вид
    $data_employee[$i]['date_ready'] = mkprettytime ($row['date_ready'], false);
    $data_employee[$i]['date_reservation'] = mkprettytime ($row['date_reservation'], false);
    $data_employee[$i]['date_occupy'] = mkprettytime ($row['date_occupy'], false);
    $data_employee[$i]['date_entry'] = mkprettytime ($row['date_entry'], false);
    $data_employee[$i]['date_employment'] = mkprettytime ($row['date_employment'], false);
    $data_employee[$i]['date_transfer'] = mkprettytime ($row['date_transfer'], false);
    $data_employee[$i]['r_emp_lu'] = mkprettytime ($row['r_emp_lu'], false);
    $data_employee[$i]['rep_lu'] = mkprettytime ($row['last_update'], false);
//$row['date_'] = mkprettytime($row['date_']);


//$row['name_rck'] = set_rck($row['id_rck']);
//выбираем функционального рук-ля
    $data_employee[$i]['data_f_m'] = select_manager ($row['id_functional_manager']);
//выбираем административного рук-ля
    $data_employee[$i]['data_a_m'] = select_manager ($row['id_administrative_manager']);
// делаем кликабельной дирекцию
    $data_employee[$i]['name_direction'] = "<a href='register.php?type=short&direction=".$data_employee[$i]['did']."'>".$data_employee[$i]['name_direction']."</a>";
//$row['id_department'];

    $sub_res = sql_query ("SELECT department.id, department.name_department, /*department.id_parent,*/ type_office.name_office as type_office, department.level  FROM department LEFT JOIN type_office ON type_office.id = department.id_type_office WHERE department.id IN (" . $row['id_department'] . ")");
    //   $i=0;
    while ($sub_row = mysql_fetch_array ($sub_res)) {
        $department[$sub_row['level']] = '<a href="register.php?type=short&department='.$sub_row['id'].'">'.$sub_row['name_department'].'</a>';
    }

    ksort ($department);
    /* foreach ($department as $val) {
         if ($data_department)
             $data_department .= ", ";
         $data_department .= $val;
     }*/
    $data_employee[$i]['department'] = $department;
//определеяем где сидит функц. рукль;
    if($row['id_administrative_manager']) {

        $cur_res = sql_query ("SELECT name_rck FROM rck LEFT JOIN employee ON employee.id = " . $row['id_administrative_manager'] . " LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE rck.id = established_post.id_rck");
        $cur_row = mysql_fetch_array ($cur_res);
        $data_employee[$i]['rck_curator'] = $cur_row['name_rck'];
    }
//разбиваем на массив
    $functionality = explode (",", $row['id_functionality']);
    foreach ($functionality as $value) {
        $id_functionality = $value;
        for ($value; $value != 0; $i++) {

            $sub_res = sql_query ("SELECT id, name_functionality, id_parent FROM functionality WHERE id = '" . $value . "'");
            $sub_row = mysql_fetch_array ($sub_res);
            if (!$sub_row['id_parent']) {
                $list_functionality['group'][$sub_row['id']] = $sub_row['name_functionality'];
            } else
                $list_functionality['function'][$sub_row['id']] = $sub_row['name_functionality'];

            $value = $sub_row['id_parent'];

        }


    }
    $i++;
}
/*
if($department) {
    $data_department = array_reverse ($department);
}*/

$REL_TPL->stdhead("Просмотр");
$REL_TPL->assignByRef('data_employee',$data_employee);
$REL_TPL->assignByRef('data_f_m',$data_f_m);
$REL_TPL->assignByRef('data_a_m',$data_a_m);
$REL_TPL->assignByRef('data_department',$data_department);
$REL_TPL->assignByRef('list_functionality',$list_functionality);
$REL_TPL->assignByRef('date_history',$_GET['date_history']);
$REL_TPL->output("view","basic");
$REL_TPL->stdfoot();