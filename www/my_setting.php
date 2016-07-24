<?php

require_once("include/connect.php");

dbconn();
loggedinorreturn();

if($_POST['change_password'] == "yes") {
    //$msg=array('class','text');
    if(!$_POST['old_password'] OR !$_POST['new_password'] OR !$_POST['re_new_password']){
        $msg['class'] = "warning";
        $msg['text'] = "Заполнены не все поля с паролем";
    }
    elseif($_POST['new_password'] != $_POST['re_new_password']){
        $msg['class'] = "warning";
        $msg['text'] = "Новые пароли не совпадают";
    }
    else{
        $res = sql_query("SELECT `secret`,`passhash` FROM users WHERE id = ".$CURUSER['id']."");
        $row = @mysql_fetch_array($res);
        if ($row["passhash"] != md5($row["secret"] . $_POST['old_password'] . $row["secret"])){
            $msg['class'] = "warning";
            $msg['text'] = "Вы ввели не правильный пароль";
        }
        else{
            $secret = mksecret();
            $password = md5($secret . $_POST['new_password'] . $secret);
            sql_query("UPDATE `users` SET `secret` = '".$secret."', `passhash` =  '".$password."', `last_update` = '".time()."' WHERE `id` ='".$CURUSER['id']."';")  or sqlerr(__FILE__, __LINE__);
            $msg['class'] = "success";
            $msg['text'] = "Пароль успешно изменен";
        }
    }

}
    if($_POST['change_setting'] == "yes") {
        if($_POST['notify']){
            sql_query("UPDATE `users` SET `notifs` = '' WHERE `id` ='".$CURUSER['id']."';")  or sqlerr(__FILE__, __LINE__);
            $GLOBALS["CURUSER"]['notifs']='';
        }
        else{
            sql_query("UPDATE `users` SET `notifs` = 'no_notify' WHERE `id` ='".$CURUSER['id']."';")  or sqlerr(__FILE__, __LINE__);
            $GLOBALS["CURUSER"]['notifs']='no_notify';
        }
        $msg['class'] = "success";
        $msg['text'] = "Настройки изменены";
    }
    if($_POST['change_register_user'] == "yes") {
        $count = (int)$_POST['count_filter_user'];
        for ($i = 1; $i <= $count; $i++) {
            if (!in_array($i, $_POST['register_user'])) {
                $id = (int)$i;
                if($user_view)
                    $user_view .= ",";

                $user_view .= $id;
            }
        }
        sql_query("UPDATE `users` SET `register_user` = '".$user_view."' WHERE `id` ='".$CURUSER['id']."';")  or sqlerr(__FILE__, __LINE__);

    }

$REL_TPL->stdhead("Настройки");
    if ($CURUSER['register_user']) {

        $array_user_view = explode (",", $CURUSER['register_user']);
        foreach ($array_user_view as $array) {
            $user_view[$array] = $array;
        }

        $REL_TPL->assignByRef ('user_view', $user_view);
    }
$REL_TPL->assignByRef('msg',$msg);


$REL_TPL->output("my_setting","basic");
$REL_TPL->stdfoot();
?>
