<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 14.08.2016
     * Time: 20:44
     */

    $id = (int)$_GET['id'];

    $res = sql_query("SELECT added, last_update, revision FROM employee WHERE id = ".$id." " )  or sqlerr(__FILE__, __LINE__);
    $row = mysql_fetch_array($res);

    if($row['last_update']){
        $date = mkprettytime($row['last_update'],true);
    }
    else
        $date = mkprettytime($row['added'],true);

    print "Текущая ревизия ".$row['revision']." от ".$date;
    print "<br /> Доступные ревизии<br />";
    $res = sql_query("SELECT added, last_update, revision FROM revision_employee WHERE id = ".$id." " )  or sqlerr(__FILE__, __LINE__);
    while($row = mysql_fetch_array($res)){

        if($row['last_update']){
            $date = mkprettytime($row['last_update'],true);
        }
        else
            $date = mkprettytime($row['added'],true);


        print "Ревизия ".$row['revision']." от ".$date."<br />";


    }