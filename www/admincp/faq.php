<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 25.04.2016
     * Time: 23:09
     */

    if($_GET['id'] and !is_valid_id($_GET['id'])){
        stderr("Ошибка","Ошибка ID вопроса");		//запись в лог
    }

    $REL_TPL->stdhead('FAQ');

    if($_GET['action']=="disable"){
        sql_query("UPDATE `faq` SET `status` = '1' WHERE `id` =".$_GET['id'].";");
        $REL_TPL->stdmsg('Выполнено','Вопрос отключен');

    }
    if($_GET['action']=="enable"){
        sql_query("UPDATE `faq` SET `status` = '0' WHERE `id` =".$_GET['id'].";");
        $REL_TPL->stdmsg('Выполнено','Вопрос включен');

    }
    if($_POST['action']=="edit"){

        sql_query("UPDATE `faq` SET `text` = ".sqlesc($_POST['text']).", `title` = ".sqlesc($_POST['title']).", `edited` = '".time()."', `type` = '".$_POST['type']."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);

        $REL_TPL->stdmsg('Выполнено','Вопрос изменен');
    }
    if($_POST['action']=="add"){

        sql_query("INSERT INTO `faq` (`text`,`title`,`type`, `added`) VALUES (".sqlesc($_POST['text']).",".sqlesc($_POST['title']).",".sqlesc($_POST['type']).",".time().");");
        $REL_TPL->stdmsg('Выполнено','Вопрос добавлен');
    }
    if($_GET['action']=="edit"){
        $res=sql_query("SELECT * FROM `faq` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
        if(mysql_num_rows($res) == 0){
            stderr("Ошибка","Такой вопрос отсутствует в базе","no");
        }

        $data_faq = mysql_fetch_array($res);

        $action	="edit";

        $REL_TPL->assignByRef("action",$action);
        $REL_TPL->assignByRef("id",$_GET['id']);
        $REL_TPL->assignByRef('data_faq',$data_faq);
        $REL_TPL->output("faq_add_edit","admincp");

    }
    elseif($_GET['action']=="add"){
        $res_p=sql_query("SELECT * FROM `faq`")  or sqlerr(__FILE__, __LINE__);

        $action	="add";
        $REL_TPL->assignByRef("action",$action);

        $REL_TPL->output("faq_add_edit","admincp");
    }
    else {
        $res = sql_query ("SELECT * FROM `faq`;") or sqlerr (__FILE__, __LINE__);
        if (mysql_num_rows ($res) == 0) {
            stderr ("Ошибка", "Вопросы не найдены. <a href='action_admin.php?module=faq&action=add'>Добавить?</a>","no");
        }
        $i=0;
        while ($row = mysql_fetch_array ($res)) {
            $data_faq[$i]=$row;
            if ($row['added']) {
                $data_faq[$i]['added'] = mkprettytime ($row['added'], false);
            }
            else{
                $data_faq[$i]['added'] = "N/A";
            }

            if($row['edited']) {
                $data_faq[$i]['edited'] = mkprettytime ($row['edited'], false);
            }
            else{
                $data_faq[$i]['edited'] = "N/A";
            }
            $i++;
        }

        $REL_TPL->assignByRef('data_faq',$data_faq);
        $REL_TPL->output("faq_index","admincp");
    }

    $REL_TPL->stdfoot();
