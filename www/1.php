<?php

    require_once ("include/connect.php");

    dbconn();

    $res_emp=sql_query("SELECT established_post.uid_post FROM established_post WHERE established_post.`current` = 1 ;") or sqlerr(__FILE__, __LINE__);

    while($row_emp = mysql_fetch_array($res_emp)){

        print $row_emp['uid_post']."<br>";
    }