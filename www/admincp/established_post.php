<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 14:51
 */
$REL_TPL->stdhead('Список штатных единиц');

if($_GET['action'] == "add"){

    //получаем список подразделений
    $res_dep=sql_query("SELECT `id`,`name_department` FROM `department` WHERE id_parent = '0';")  or sqlerr(__FILE__, __LINE__);
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
    $uid_post = (int)$_POST['uid_post'];
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
    }

    sql_query("INSERT INTO established_post (uid_post,date_entry,id_administrative_manager,id_functional_manager,id_location_city,id_department,id_direction,id_mvz, id_position, draft, transfer) VALUES ('".$uid_post."','".$date_entry."','".$id_administrative_manager."','".$id_functional_manager."','".$id_city."','".$id_department."','".$id_direction."','".$id_mvz."','".$id_position."','".$draft."','".$transfer."');") or sqlerr(__FILE__, __LINE__);

}
elseif($_GET['action'] == "edit"){

    $res=sql_query("SELECT established_post.*,rck.id as id_rck,mvz.id as id_mvz FROM established_post LEFT JOIN mvz ON mvz.id = established_post.id_mvz LEFT JOIN rck ON rck.id = mvz.id_rck WHERE established_post.id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такая штатная единица отсутствует в базе","no");
    }
    $data = mysql_fetch_array($res);

    //получаем список подразделений
    $res_dep=sql_query("SELECT `id`,`name_department` FROM `department` WHERE id_parent = '0';")  or sqlerr(__FILE__, __LINE__);
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
    $REL_TPL->assignByRef('data_direction',$data_direction);
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->assignByRef('data_mvz',$data_mvz);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->assignByRef('data_employee',$data_employee);
    $REL_TPL->assignByRef('data_position',$data_position);

    $REL_TPL->output("action", "admincp", "established_post");


}
elseif($_POST['action'] == "edit"){
    $uid_post = (int)$_POST['uid_post'];
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
    }

    sql_query("UPDATE `established_post` SET `uid_post`='".$uid_post."', `date_entry`='".$date_entry."', `id_administrative_manager`='".$id_administrative_manager."',`id_functional_manager`='".$id_functional_manager."',`id_location_city`='".$id_city."',`id_department`='".$id_department."',`id_direction`='".$id_direction."',`id_mvz`='".$id_mvz."', `id_position`='".$id_position."', `draft`='".$draft."', `transfer`='".$transfer."' WHERE id='".$_POST['id']."';") or sqlerr(__FILE__, __LINE__);
}

if(!$_GET['action']){
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
");
    $i=0;
    while($row=mysql_fetch_array($res)){
        $data_established_post[$i] = $row;
        $data_established_post[$i]['date_entry'] = mkprettytime($row['date_entry'],false);


        $i++;
    }

    $REL_TPL->assignByRef('data_established_post',$data_established_post);
    $REL_TPL->output("index", "admincp", "established_post");
}

$REL_TPL->stdfoot();