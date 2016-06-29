<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 17:47
 */


$REL_TPL->stdhead('Список МВЗ');

//если мы добавляем в список
if($_GET['action']=="add"){
    //получаем список рцк
    $res_p=sql_query("SELECT `id`,`name_rck` FROM `rck`")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_rck[] = $row_p;
    }
    $action	="add";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->output("action", "admincp", "mvz");

}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `mvz` (`name_mvz`,`id_rck`,`added`) VALUES (".sqlesc($_POST['name_mvz']).",'".(int)$_POST['id_rck']."','".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','МВЗ добавлен');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT `name_mvz`,`id_rck` FROM `mvz` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такой МВЗ отсутствует в базе","no");
    }
    $data_mvz = mysql_fetch_array($res);

    $res_p=sql_query("SELECT `id`,`name_rck` FROM `rck`")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_rck[] = $row_p;
    }

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_mvz',$data_mvz);
    $REL_TPL->assignByRef('data_rck',$data_rck);

    $REL_TPL->output("action", "admincp", "mvz");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `mvz` SET `name_mvz` = ".sqlesc($_POST['name_mvz']).", `last_update` = '".time()."',`id_rck` = '".(int)$_POST['id_rck']."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','МВЗ изменен');
}



// если нет никакиз активных действий
if (!$_GET['action']){
    $res=sql_query("SELECT * FROM `mvz`;")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","МВЗ базе не обнаружен","no");
    }

    //получаем список рцк
    $sub_res=sql_query("SELECT `id`,`name_rck` FROM `rck`;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
       $data_rck[$subrow['id']]= $subrow['name_rck'];
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_mvz[$i]=$row;
        //форматируем время
        $data_mvz[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_mvz[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        $data_mvz[$i]['name_rck'] =  $data_rck[$row['id_rck']];
        $i++;
    }


    $REL_TPL->assignByRef('data_mvz',$data_mvz);
    $REL_TPL->output("index", "admincp", "mvz");
}
$REL_TPL->stdfoot();