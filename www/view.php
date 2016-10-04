<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 17.05.2016
 * Time: 10:49
 */

require_once ("include/connect.php");

dbconn();
if($_GET['id']){
    if(!is_valid_id($_GET['id'])){
        stderr("Ошибка","Не правильный идентификатор");
    }
    $WHERE = "established_post.id='".$_GET['id']."'";
}
elseif($_GET['uid']){
    if(!is_valid_id($_GET['uid'])){
        stderr("Ошибка","Не правильный идентификатор");
    }
    $WHERE = "established_post.uid_post='".$_GET['uid']."'";
}
elseif($_GET['eid']){
    if(!is_valid_id($_GET['eid'])){
        stderr("Ошибка","Не правильный идентификатор");
    }
    $WHERE = "employee.id='".$_GET['eid']."' AND employee.is_deleted = 0";
}
    else
        stderr("Ошибка","Не правильный идентификатор");

// получение менеджеров




$res=sql_query("
SELECT established_post.*,
position.name_position,
name_block,
employee.name_employee AS em_name, employee.date_employment, employee.date_transfer, employee.id_functionality, employee.fte, employee.id as eid,
location_city.name_city,
location_place.place, location_place.floor, location_place.room, location_place.id_address AS lp_ia, location_place.ready, location_place.date_ready, location_place.reservation, location_place.date_reservation, location_place.occupy, location_place.date_occupy,
location_address.name_address, location_address.id_city AS la_id_city,
direction.name_direction,direction.id_employee AS id_curator,direction.id as did, (SELECT employee.name_employee FROM employee WHERE employee.id = direction.id_employee) AS name_curator,
mvz.name_mvz,
rck.name_rck,
employee_model.name_model
FROM established_post
LEFT JOIN `position` ON position.id = established_post.id_position
LEFT JOIN `block` ON block.id = established_post.id_block
LEFT JOIN employee ON employee.id_uid_post = established_post.id
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
LEFT JOIN location_place ON location_place.id = employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN direction ON direction.id = established_post.id_direction
LEFT JOIN mvz ON mvz.id = established_post.id_mvz
LEFT JOIN rck ON rck.id = mvz.id_rck
LEFT JOIN employee_model ON employee_model.id = employee.id_employee_model
WHERE $WHERE AND established_post.is_deleted = 0
/*WHERE established_post.id='".$_GET['id']."'*/
") or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Ничего не найдено");
    }

    $i=0;
$row = array();
while($row = mysql_fetch_array($res)) {
    $data_employee[$i] = $row;


//преобразовываем даты в красивый вид
    $data_employee[$i]['date_ready'] = mkprettytime ($row['date_ready'], false);
    $data_employee[$i]['date_reservation'] = mkprettytime ($row['date_reservation'], false);
    $data_employee[$i]['date_occupy'] = mkprettytime ($row['date_occupy'], false);
    $data_employee[$i]['date_entry'] = mkprettytime ($row['date_entry'], false);
    $data_employee[$i]['date_employment'] = mkprettytime ($row['date_employment'], false);
    $data_employee[$i]['date_transfer'] = mkprettytime ($row['date_transfer'], false);
//$row['date_'] = mkprettytime($row['date_']);


//$row['name_rck'] = set_rck($row['id_rck']);
//выбираем функционального рук-ля
    $data_employee[$i]['data_f_m'] = select_manager ($row['id_functional_manager']);
//выбираем административного рук-ля
    $data_employee[$i]['data_a_m'] = select_manager ($row['id_administrative_manager']);
// делаем кликабельной дирекцию
    $data_employee[$i]['name_direction'] = "<a href='register.php?type=short&direction=".$data_employee[$i]['did']."'>".$data_employee[$i]['name_direction']."</a>";
//$row['id_department'];
if($row['id_department']) {
    $sub_res = sql_query ("SELECT department.id, department.name_department, /*department.id_parent,*/ type_office.name_office as type_office, department.level  FROM department LEFT JOIN type_office ON type_office.id = department.id_type_office WHERE department.id IN (" . $row['id_department'] . ")");
    //   $i=0;
    while ($sub_row = mysql_fetch_array ($sub_res)) {
        $department[$sub_row['level']] = '<a href="register.php?type=short&department=' . $sub_row['id'] . '">' . $sub_row['name_department'] . '</a>';
    }

    ksort ($department);
    /* foreach ($department as $val) {
         if ($data_department)
             $data_department .= ", ";
         $data_department .= $val;
     }*/
    $data_employee[$i]['department'] = $department;
}
//определеяем где сидит функц. рукль;
    $cur_res = sql_query ("SELECT name_rck FROM rck LEFT JOIN employee ON employee.id = " . $row['id_administrative_manager'] . " LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE rck.id = established_post.id_rck");
    $cur_row = mysql_fetch_array ($cur_res);
    $data_employee[$i]['rck_curator'] = $cur_row['name_rck'];

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
$REL_TPL->output("view","basic");
$REL_TPL->stdfoot();