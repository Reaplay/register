<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 26.06.2016
     * Time: 19:38
     */

    require_once("include/connect.php");

    dbconn();

if($_GET['s']){

    if ((int)$_GET['s'] >0) {
        $search = "established_post.uid_post LIKE '%".(int)$_GET['s']."%'";
    }
    else{
        $search = "employee.name_employee LIKE '%".$_GET['s']."%'";
    }


    $res = sql_query("
SELECT established_post.*,
position.name_position,
employee.name_employee,employee.id_employee as e_id,
location_city.name_city
FROM established_post
LEFT JOIN `position` ON position.id = established_post.id_position
LEFT JOIN employee ON employee.id_uid_post = established_post.id
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
WHERE $search AND established_post.current = 1 AND employee.current = 1 LIMIT 0 , 30")  or sqlerr(__FILE__,__LINE__);

    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Ничего не найдено");
    }

    while ($row = mysql_fetch_array($res)){
        $data_search[]=$row;
    }


    $REL_TPL->assignByRef('data_search',$data_search);

}
    $REL_TPL->stdhead("Поиск");
    $REL_TPL->output("search","basic");
    $REL_TPL->stdfoot();