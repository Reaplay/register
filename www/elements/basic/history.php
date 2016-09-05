<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 14.08.2016
     * Time: 20:44
     */

    $id = (int)$_GET['id'];


    $res_emp = sql_query("SELECT id, id_uid_post,name_employee,added, last_update, revision FROM employee WHERE id = ".$id."; " )  or sqlerr(__FILE__, __LINE__);
    $row_emp = mysql_fetch_array($res_emp);

    if($row_emp['last_update']){
        $data_employee['update'] = mkprettytime($row_emp['last_update'],true);
    }
    else
        $data_employee['update'] = mkprettytime($row_emp['added'],true);

    $data_employee['name_employee'] = $row_emp['name_employee'];
    $data_employee['revision'] = $row_emp['revision'];
    $data_employee['id'] = $row_emp['id'];
   // $content .= "История струдника ".$row_emp['name_employee']."<br />";

    //$content .= "Текущая ревизия сотрудника №".$row_emp['revision']." от ".$date_emp."<br />";

    $res_esp = sql_query("SELECT added, last_update, revision FROM established_post WHERE id = '".$row_emp['id_uid_post']."';" )  or sqlerr(__FILE__, __LINE__);
    $row_esp = mysql_fetch_array($res_esp);

    if($row_esp['last_update']){
        $data_esp['update'] = mkprettytime($row_esp['last_update'],true);
    }
    else
        $data_esp['update'] = mkprettytime($row_esp['added'],true);

    $data_esp['revision'] = $row_esp['revision'];
   // $content .= "Текущая ревизия штатной единицы №".$row_esp['revision']." от ".$date_esp."<br />";



    $res_rev_emp = sql_query("SELECT added, last_update, revision FROM revision_employee WHERE id_employee = ".$id." " )  or sqlerr(__FILE__, __LINE__);
    while($row_rev_emp = mysql_fetch_array($res_rev_emp)){

        if($row_rev_emp['last_update']){
            $date_rev_emp = mkprettytime($row_rev_emp['last_update'],true);
        }
        else
            $date_rev_emp = mkprettytime($row_rev_emp['added'],true);


        $data_revision_emp .= "№".$row_rev_emp['revision']." от ".$date_rev_emp."<br />";


    }
    $res_rev_esp = sql_query("SELECT added, last_update, revision FROM revision_established_post WHERE id_established_post = '".$row_emp['id_uid_post']."'; " )  or sqlerr(__FILE__, __LINE__);
    while($row_rev_esp = mysql_fetch_array($res_rev_esp)){

        if($row_rev_esp['last_update']){
            $date_rev_esp = mkprettytime($row_rev_esp['last_update'],true);
        }
        else
            $date_rev_esp = mkprettytime($row_rev_esp['added'],true);


        $data_revision_esp .= "№".$row_rev_esp['revision']." от ".$date_rev_esp."<br />";


    }

    $REL_TPL->stdhead("Просмотр истории");
    $REL_TPL->assignByRef('data_emp',$data_employee);
    $REL_TPL->assignByRef('data_esp',$data_esp);
    $REL_TPL->assignByRef('data_revision_emp',$data_revision_emp);
    $REL_TPL->assignByRef('data_revision_esp',$data_revision_esp);

    $REL_TPL->output("history","basic");
    $REL_TPL->stdfoot();