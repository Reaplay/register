<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 23.05.2016
 * Time: 17:11
 */

    function prepeared_data($input_data){
        //переводим данные
        $data['id_uid_post'] = (int)$input_data['id_uid_post'];
        $data['date_employment']	= unix_time($input_data['date_employment']);
        $data['date_transfer']	= unix_time($input_data['date_transfer']);
        $data['id_employee_model'] = (int)$input_data['id_employee_model'];
        $data['id_functionality'] = (int)$input_data['id_functionality'];
        $data['id_strategic_poject'] = (int)$input_data['id_strategic_poject'];
        $data['name_employee'] = trim($input_data['name_employee']);
        if(validemail($input_data['email'])) {
            $data['email'] = trim($input_data['email']);
        }


        if((int)$input_data['id_place'] >0 AND (int)$input_data['id_city'] >0 AND (int)$input_data['id_address'] >0) {
            $data['id_place'] = (int)$input_data['id_place'];
        }

        return $data;
    }


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
    $REL_TPL->assignByRef('id',$_GET['id']);
    $REL_TPL->assignByRef("data_ep",$data_ep);
    $REL_TPL->assignByRef("data_city",$data_city);
    $REL_TPL->assignByRef("data_functionality",$data_functionality);
    $REL_TPL->assignByRef("data_strategic_poject",$data_strategic_poject);
    $REL_TPL->assignByRef("data_employee_model",$data_employee_model);
    $REL_TPL->output("action", "admincp", "employee");
}
elseif($_POST['action'] == "add"){

    $data = prepeared_data($_POST);

    sql_query("INSERT INTO employee (id_uid_post,name_employee,email,date_transfer,date_employment,id_location_place,id_strategic_project,id_employee_model,id_functionality,added)
    VALUES ('".$data['id_uid_post']."','".$data['name_employee']."','".$data['email']."','".$data['date_transfer']."','".$data['date_employment']."','".$data['id_place']."','".$data['id_strategic_poject']."','".$data['id_employee_model']."','".$data['id_functionality']."', '".time()."');") or sqlerr(__FILE__, __LINE__);

}
elseif($_GET['action'] == "edit"){

    $action	="edit";
$id = (int)$_GET['id'];
    //получаем данные сотрудника
    $res=sql_query("
SELECT employee.*, location_city.name_city, location_address.name_address, location_place.floor, location_place.place, location_place.room, functionality.name_functionality, strategic_project.id as id_strategic_poject, location_address.id as id_location_address, location_city.id as id_location_city,
established_post.id as id_uid_post
FROM `employee`
LEFT JOIN functionality ON functionality.id = employee.id_functionality
LEFT JOIN established_post ON established_post.id = employee.id_uid_post
LEFT JOIN  strategic_project ON strategic_project.id = employee.id_strategic_project
LEFT JOIN location_place ON location_place.id = employee.id_location_place
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN location_city ON location_city.id = location_address.id_city WHERE employee.is_deleted = 0 AND employee.id = $id;")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Люди в  базе не обнаружены","no");
    }
    $data = mysql_fetch_array($res);
    if($data['date_employment']){
        $data['date_employment'] = date('d/m/Y',$data['date_employment']);
    }
    else
        $data['date_employment'] = '';
    if($data['date_transfer']) {
        $data['date_transfer'] = date ('d/m/Y', $data['date_transfer']);
    }
    else
        $data['date_transfer']='';

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
    //получаем список адресов
    $res_address=sql_query("SELECT `id`,`name_address` FROM `location_address` WHERE id_city = ".$data['id_location_city'].";")  or sqlerr(__FILE__, __LINE__);
    while ($row_address = mysql_fetch_array($res_address)){
        $data_address[] = $row_address;
    }
    //получаем список мест
    $res_place=sql_query("SELECT `id`,floor,room,place FROM `location_place` WHERE id_address = ".$data['id_location_address']."")  or sqlerr(__FILE__, __LINE__);
    $i = 0;
    while ($row_place = mysql_fetch_array($res_place)){
        $data_place[$i]['id'] =  $row_place['id'];
        $data_place[$i]['name_place'] =  "Этаж ".$row_place['floor'].", комната ".$row_place['room'].", место ".$row_place['place'];
        $i++;
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




    $REL_TPL->assignByRef('id',$_GET['id']);
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("data_ep",$data_ep);
    $REL_TPL->assignByRef("data",$data);
    $REL_TPL->assignByRef("data_city",$data_city);
    $REL_TPL->assignByRef("data_address",$data_address);
    $REL_TPL->assignByRef("data_place",$data_place);
    $REL_TPL->assignByRef("data_functionality",$data_functionality);
    $REL_TPL->assignByRef("data_strategic_poject",$data_strategic_poject);
    $REL_TPL->assignByRef("data_employee_model",$data_employee_model);
    $REL_TPL->output("action", "admincp", "employee");

}
elseif($_POST['action'] == "edit"){

    $res=sql_query("SELECT employee.added, id, id_parent_ee FROM employee WHERE employee.id = '".$_POST['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такой сотрудник отсутствует в базе","no");
    }
    $row = mysql_fetch_array($res);
    if($row['id_parent_ep'])
        $id_parent_ee = $row['id_parent_ee'];
    else
        $id_parent_ee = $row['id'];

    $data = prepeared_data($_POST);
//копируем в таблицу ревизий
    sql_query("INSERT INTO revision_employee
(`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`)
SELECT
`id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change` FROM employee WHERE employee.id =  '".$_POST['id']."'") or sqlerr(__FILE__, __LINE__);
    //обновляем
    sql_query("UPDATE `employee` SET

 `id_uid_post` = '".$data['id_uid_post']."',
 `name_employee` = '".$data['name_employee']."',
 `email` = '".$data['email']."',
 `date_transfer` = '".$data['date_transfer']."',
 `date_employment` = '".$data['date_employment']."',
 `id_location_place` = '".$data['id_place']."',
 `id_strategic_project` = '".$data['id_strategic_poject']."',
 `id_employee_model` = '".$data['id_employee_model']."',
 `id_functionality` = '".$data['id_functionality']."',
 `last_update` = '".time()."',
 `id_parent_ee` = '".$id_parent_ee."',
 `revision` = `revision` + 1,
 `id_user_change` = '".$CURUSER['id']."'

 WHERE `id` ='".$_POST['id']."';") or sqlerr(__FILE__, __LINE__);

   /* sql_query("INSERT INTO employee (id_uid_post,name_employee,email,date_transfer,date_employment,id_location_place,id_strategic_project,id_employee_model,id_functionality,added, id_parent_ee)
    VALUES ('".$data['id_uid_post']."','".$data['name_employee']."','".$data['email']."','".$data['date_transfer']."','".$data['date_employment']."','".$data['id_place']."','".$data['id_strategic_poject']."','".$data['id_employee_model']."','".$data['id_functionality']."', '".time()."', '".$id_parent_ee."');") or sqlerr(__FILE__, __LINE__);
*/
  //  $new_id = mysql_insert_id();
    // отмечаем старую на удаление
  //  sql_query("UPDATE `employee` SET last_update = '".time()."', is_deleted = '1' WHERE id ='".$_POST['id']."' ;") or sqlerr(__FILE__, __LINE__);
    // обновляем привязанные записи к сотруднику
   // sql_query("UPDATE `direction` SET id_employee = '".$new_id."' WHERE id_employee ='".$_POST['id']."' ;") or sqlerr(__FILE__, __LINE__);
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