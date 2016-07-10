<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 14:51
 */

    function prepeared_data($input_data){
        //переводим данные
        $data['uid_post'] = (int)$input_data['uid_post'];
        $data['date_entry']	= unix_time($input_data['date_entry']);
        $data['id_administrative_manager'] = (int)$input_data['id_administrative_manager'];
        $data['id_functional_manager'] = (int)$input_data['id_functional_manager'];
        $data['id_city'] = (int)$input_data['id_city'];
        $data['id_block'] = (int)$input_data['id_block'];
        $array_department[] = (int)$input_data['id_department_0'];
        $array_department[] = (int)$input_data['id_department_1'];
        $array_department[] = (int)$input_data['id_department_2'];
        $array_department[] = (int)$input_data['id_department_3'];
        $array_department[] = (int)$input_data['id_department_4'];

        foreach($array_department as $department){
            if($department) {
                if($id_department)
                    $id_department .=",";

                $id_department .= $department;
            }
        }
        $data['id_department'] = $id_department;

        $data['id_direction'] = (int)$input_data['id_direction'];

        $data['id_mvz'] = (int)$input_data['id_mvz'];
        $data['id_rck'] = (int)$input_data['id_rck'];
        $data['id_position'] = (int)$input_data['id_position'];
        $data['draft'] = (int)$input_data['draft'];
        if($input_data['transfer'] == "on"){
            $data['transfer'] = '1';
        }
        else{
            $data['transfer'] = '0';
        }

        return $data;
    }


$REL_TPL->stdhead('Список штатных единиц');

if($_GET['action'] == "add"){

    //получаем список блоков
    $res_block=sql_query("SELECT `id`,`name_block` FROM `block` ;")  or sqlerr(__FILE__, __LINE__);
    while ($row_block = mysql_fetch_array($res_block)){
        $data_block[] = $row_block;
    }

    //получаем список подразделений
    $res_dep=sql_query("SELECT `id`,`name_department`,`level` FROM `department` ")  or sqlerr(__FILE__, __LINE__);
    while ($row_dep = mysql_fetch_array($res_dep)){
        $data_department[] = $row_dep;
    }
    //получаем список дирекций
    $res_dir=sql_query("SELECT `id`,`name_direction` FROM `direction`;")  or sqlerr(__FILE__, __LINE__);
    while ($row_dir = mysql_fetch_array($res_dir)){
        $data_direction[] = $row_dir;
    }
    //получаем список городов
    $res_city=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);
    while ($row_city = mysql_fetch_array($res_city)){
        $data_city[] = $row_city;
    }

    //получаем РЦК
    $res_rck=sql_query("SELECT `id`,`name_rck` FROM `rck`")  or sqlerr(__FILE__, __LINE__);
    while ($row_rck = mysql_fetch_array($res_rck)){
        $data_rck[] = $row_rck;
    }

    //получаем список пользователей к которым можно прикрепить как к руклю
    $res_emp = sql_query("SELECT employee.id, employee.name_employee
FROM employee
LEFT JOIN established_post ON established_post.uid_post = employee.id_uid_post
LEFT JOIN position ON position.id = established_post.id_position
WHERE position.is_head = 1");
    while ($row_emp = mysql_fetch_array($res_emp)){
        $data_employee[] = $row_emp;
    }

    //получаем должности
    $res_position=sql_query("SELECT `id`,`name_position` FROM `position`")  or sqlerr(__FILE__, __LINE__);
    while ($row_position = mysql_fetch_array($res_position)){
        $data_position[] = $row_position;
    }

    $action	="add";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data_block',$data_block);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->assignByRef('data_direction',$data_direction);
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->assignByRef('data_position',$data_position);
    /*
    $REL_TPL->assignByRef('',);
    $REL_TPL->assignByRef('',);
    $REL_TPL->assignByRef('',);*/
    $REL_TPL->output("action", "admincp", "established_post");

}
elseif($_POST['action'] == "add"){

    //переводим данные
   /* $uid_post = (int)$_POST['uid_post'];
    $date_entry	= unix_time($_POST['date_entry']);
    $id_administrative_manager = (int)$_POST['id_administrative_manager'];
    $id_functional_manager = (int)$_POST['id_functional_manager'];
    $id_city = (int)$_POST['id_city'];
    $id_block = (int)$_POST['id_block'];
    $array_department[] = (int)$_POST['id_department_0'];
    $array_department[] = (int)$_POST['id_department_1'];
    $array_department[] = (int)$_POST['id_department_2'];
    $array_department[] = (int)$_POST['id_department_3'];
    $array_department[] = (int)$_POST['id_department_4'];

    foreach($array_department as $department){
        if($department) {
            if($id_department)
                $id_department .=",";

            $id_department .= $department;
        }
    }


    $id_direction = (int)$_POST['id_direction'];

    $id_mvz = (int)$_POST['id_mvz'];
    $id_rck = (int)$_POST['id_rck'];
    $id_position = (int)$_POST['id_position'];
    $draft = (int)$_POST['draft'];
    if($_POST['transfer'] == "on"){
        $transfer = '1';
    }
    else{
        $transfer = '0';
    }*/

    $data = prepeared_data($_POST);
//uid_post,date_entry,id_administrative_manager,id_functional_manager,id_location_city,id_department,id_direction,id_mvz, id_position, draft, transfer
    //'".$uid_post."','".$date_entry."','".$id_administrative_manager."','".$id_functional_manager."','".$id_city."','".$id_department."','".$id_direction."','".$id_mvz."','".$id_position."','".$draft."','".$transfer."'

    sql_query("INSERT INTO established_post (uid_post, id_position, id_block, id_department, id_direction, id_rck, id_mvz, date_entry, added, id_location_city, id_functional_manager, id_administrative_manager, draft, transfer) VALUES ('".$data['uid_post']."', '".$data['id_position']."', '".$data['id_block']."', '".$data['id_department']."', '".$data['id_direction']."', '".$data['id_rck']."', '".$data['id_mvz']."', '".$data['date_entry']."', '".time()."', '".$data['id_city']."', '".$data['id_functional_manager']."', '".$data['id_administrative_manager']."', '".$data['draft']."', '".$data['transfer']."');") or sqlerr(__FILE__, __LINE__);

}
elseif($_GET['action'] == "edit"){

    $res=sql_query("SELECT established_post.*,rck.id as id_rck,mvz.id as id_mvz FROM established_post LEFT JOIN mvz ON mvz.id = established_post.id_mvz LEFT JOIN rck ON rck.id = mvz.id_rck WHERE established_post.id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такая штатная единица отсутствует в базе","no");
    }
    $data = mysql_fetch_array($res);
    $data['date_entry'] = mkprettytime($data['date_entry'],false);
    $data['id_department'] = explode(",",$data['id_department']);
    //получаем список блоков
    $res_block=sql_query("SELECT `id`,`name_block` FROM `block` ;")  or sqlerr(__FILE__, __LINE__);
    while ($row_block = mysql_fetch_array($res_block)){
        $data_block[] = $row_block;
    }

    //получаем список подразделений
    $res_dep=sql_query("SELECT `id`,`name_department`,`level` FROM `department`;")  or sqlerr(__FILE__, __LINE__);
    while ($row_dep = mysql_fetch_array($res_dep)){
        $data_department[] = $row_dep;
    }
    //получаем список дирекций
    $res_dir=sql_query("SELECT `id`,`name_direction` FROM `direction`;")  or sqlerr(__FILE__, __LINE__);
    while ($row_dir = mysql_fetch_array($res_dir)){
        $data_direction[] = $row_dir;
    }
    //получаем список городов
    $res_city=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);
    while ($row_city = mysql_fetch_array($res_city)){
        $data_city[] = $row_city;
    }

    //получаем РЦК
    $res_rck=sql_query("SELECT `id`,`name_rck` FROM `rck`")  or sqlerr(__FILE__, __LINE__);
    while ($row_rck = mysql_fetch_array($res_rck)){
        $data_rck[] = $row_rck;
    }
    //получаем МВЗ
    $res_mvz=sql_query("SELECT `id`,`name_mvz` FROM `mvz`")  or sqlerr(__FILE__, __LINE__);
    while ($row_mvz = mysql_fetch_array($res_mvz)){
        $data_mvz[] = $row_mvz;
    }

    //получаем должности
    $res_position=sql_query("SELECT `id`,`name_position` FROM `position`")  or sqlerr(__FILE__, __LINE__);
    while ($row_position = mysql_fetch_array($res_position)){
        $data_position[] = $row_position;
    }

    //получаем список пользователей к которым можно прикрепить как к руклю
    $res_emp = sql_query("SELECT employee.id, employee.name_employee
FROM employee
LEFT JOIN established_post ON established_post.uid_post = employee.id_uid_post
LEFT JOIN position ON position.id = established_post.id_position
WHERE position.is_head = 1");
    while ($row_emp = mysql_fetch_array($res_emp)){
        $data_employee[] = $row_emp;
    }

    $action	="edit";
    $REL_TPL->assignByRef('id',$_GET['id']);
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data',$data);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->assignByRef('data_block',$data_block);
    $REL_TPL->assignByRef('data_direction',$data_direction);
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->assignByRef('data_mvz',$data_mvz);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->assignByRef('data_position',$data_position);

    $REL_TPL->output("action", "admincp", "established_post");


}
elseif($_POST['action'] == "edit"){

    $res=sql_query("SELECT established_post.added, id, id_parent_ep FROM established_post WHERE established_post.id = '".$_POST['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такая штатная единица отсутствует в базе","no");
    }
    $row = mysql_fetch_array($res);
    if($row['id_parent_ep'])
        $id_parent_ep = $row['id_parent_ep'];
    else
        $id_parent_ep = $row['id'];

    /*$uid_post = (int)$_POST['uid_post'];
    $date_entry	= unix_time($_POST['date_entry']);
    $id_administrative_manager = (int)$_POST['id_administrative_manager'];
    $id_functional_manager = (int)$_POST['id_functional_manager'];
    $id_city = (int)$_POST['id_city'];
    $id_department_1 = (int)$_POST['id_department_1'];
    $id_department_2 = (int)$_POST['id_department_2'];
    $id_department_3 = (int)$_POST['id_department_3'];
    $id_department_4 = (int)$_POST['id_department_4'];

    if($id_department_4){$id_department = $id_department_4;}
    elseif($id_department_3){$id_department = $id_department_3;}
    elseif($id_department_2){$id_department = $id_department_2;}
    elseif($id_department_1){$id_department = $id_department_1;}

    $id_direction = (int)$_POST['id_direction'];

    $id_mvz = (int)$_POST['id_mvz'];
    $id_rck = (int)$_POST['id_rck'];
    $id_position = (int)$_POST['id_position'];
    $draft = (int)$_POST['draft'];
    if($_POST['transfer'] == "on"){
        $transfer = '1';
    }
    else{
        $transfer = '0';
    }*/

    $data = prepeared_data($_POST);
    //добавляем новую запись
    sql_query("INSERT INTO established_post (uid_post, id_position, id_block, id_department, id_direction, id_rck, id_mvz, date_entry, added, id_location_city, id_functional_manager, id_administrative_manager, draft, transfer, last_update, id_parent_ep) VALUES ('".$data['uid_post']."', '".$data['id_position']."', '".$data['id_block']."', '".$data['id_department']."', '".$data['id_direction']."', '".$data['id_rck']."', '".$data['id_mvz']."', '".$data['date_entry']."', '".$row['added']."', '".$data['id_city']."', '".$data['id_functional_manager']."', '".$data['id_administrative_manager']."', '".$data['draft']."', '".$data['transfer']."', '".time()."', '".$id_parent_ep."');") or sqlerr(__FILE__, __LINE__);
    $new_id = mysql_insert_id();
    // отмечаем старую на удаление
    sql_query("UPDATE `established_post` SET last_update = '".time()."', is_deleted = '1' WHERE id ='".$_POST['id']."' ;");
    // обновляем привязанных к нему сотрудников
    sql_query("UPDATE `employee` SET id_uid_post = '".$new_id."' WHERE id_uid_post ='".$_POST['id']."' ;");


   /* sql_query("UPDATE `established_post` SET `uid_post`='".$uid_post."', `date_entry`='".$date_entry."', `id_administrative_manager`='".$id_administrative_manager."',`id_functional_manager`='".$id_functional_manager."',`id_location_city`='".$id_city."',`id_department`='".$id_department."',`id_direction`='".$id_direction."',`id_mvz`='".$id_mvz."', `id_position`='".$id_position."', `draft`='".$draft."', `transfer`='".$transfer."' WHERE id='".$_POST['id']."';") or sqlerr(__FILE__, __LINE__);*/
}

if(!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'established_post');

    $res = sql_query("
SELECT
established_post.*,
 position.name_position, department.name_department, direction.name_direction, mvz.name_mvz, location_city.name_city,
 (SELECT name_employee FROM employee WHERE employee.id = established_post.id_functional_manager) as functional_manager,
 (SELECT name_employee FROM employee WHERE employee.id = established_post.id_administrative_manager) as administrative_manager
FROM established_post
LEFT JOIN position ON position.id = established_post.id_position
LEFT JOIN department ON department.id = established_post.id_department
LEFT JOIN direction ON direction.id = established_post.id_direction
LEFT JOIN mvz ON mvz.id = established_post.id_mvz
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
WHERE established_post.is_deleted = 0
".$paginator['limit']."
");
    $i=0;
    while($row=mysql_fetch_array($res)){
        $data_established_post[$i] = $row;
        $data_established_post[$i]['date_entry'] = mkprettytime($row['date_entry'],false);


        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_established_post',$data_established_post);
    $REL_TPL->output("index", "admincp", "established_post");
}

$REL_TPL->stdfoot();