<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 10.07.2016
     * Time: 23:14
     */
    require_once ("include/connect.php");

    dbconn();

    if (!check_access_group('load_data','sap')){
        stderr("Ошибка","Доступ запрещен");

    }
    $REL_TPL->stdhead("Загрузка данных");

    //функция для выбора данных и засовывания их в массив
    function select_data_base($table,$column,$where=''){
        $res=sql_query("SELECT id, $column FROM $table $where;")  or sqlerr(__FILE__, __LINE__);

        while($row = mysql_fetch_array($res)){

            $data[$row['id']]=$row[$column];
        }
        if(!count($data))
            $data = array();
        return $data;
    }
    //функция обработки для простых данных где только название и время добавляется
  /*  function processing_data($data,$id=''){
        $data_array = array_unique ($data);
        foreach ($data_array as $array) {
            if (!trim ($array))
                continue;

            $array = str_replace ("\"\"", "'", $array);
            $array = str_replace ("\"", "", $array);
            $array = str_replace ("'", "\"", $array);

            if ($return) {
                $return .= ", ";
            }
            if($id){
                $data_id = ", '".$id."'";
            }
            $return .= "('$array',".time()."$data_id)";


        }
        return $return;
    }*/
    // ищем в массиве такие же записи, если нет, то подготавливаем к добавлению
  /*  function search_in_array($array,$data){
        foreach($data as $value) {
            if(!$data)
                continue;

            $array = str_replace ("\"\"", "'", $array);
            $array = str_replace ("\"", "", $array);
            $array = str_replace ("'", "\"", $array);

            if (!array_search ($value, $array)) {
                $return .= trim ($value)."<br>";
            }
        }
        return $return;
    }*/
    function prepeared_data($array,$data,$id=''){
        GLOBAL $date_data;
        $unique_data = array_unique($data);

        unset($return);

        foreach($unique_data as $value) {
            if(!$value)
                continue;

            $value = str_replace ("\"\"", "'", $value);
            $value = str_replace ("\"", "", $value);
            $value = str_replace ("'", "\"", $value);

            if (!array_search ($value, $array)) {
                $value = trim($value);
                if($id){
                    $data_id = ", '".$id."'";
                }

                if($return)
                    $return .= ",";

                $return .= "('$value',".$date_data."$data_id)";
             //   $return .= trim ($value)."<br>";
            }
        }
        return $return;


    }

    function search_deaprtment_array($array, $data, $level){

        if (!$data)
            return '';
        $data =  str_replace ("\"", "", $data);
        foreach ($array as $item) {
            if($item[$level]['name'] != $data)
                return "'".$data."', '".$level."'";
        }
    }

    if($_GET['type']=='upload_client' AND $_POST['step'] == 1) {
        //начинаем проверку загрузку файла
        $allowedExts = array("CSV", "csv");
        //$extension = end(explode(".", $_FILES["attachment"]["name"]));
        $tmp_files = explode (".", $_FILES["attachment"]["name"]);
        $extension = end ($tmp_files);
        $allowedType = array("csv/plain", "application/vnd.ms-excel", "text/csv");
        // загружаем файло application/vnd.ms-excel
        if (in_array ($_FILES["attachment"]["type"], $allowedType) && ($_FILES["attachment"]["size"] < 1800000) && in_array ($extension, $allowedExts)) {
            if ($_FILES["attachment"]["error"] > 0) {
                echo "Return Code: " . $_FILES["attachment"]["error"] . "<br>";
            } else {
                if (get_user_class () > UC_ADMINISTRATOR) {
                    echo "Upload: " . $_FILES["attachment"]["name"] . "<br>";
                    echo "Type: " . $_FILES["attachment"]["type"] . "<br>";
                    echo "Size: " . ($_FILES["attachment"]["size"] / 1024) . " kB<br>";
                    echo "Temp file: " . $_FILES["attachment"]["tmp_name"] . "<br>";
                }
                $time = time ();
                move_uploaded_file ($_FILES["attachment"]["tmp_name"], "upload/upload_sap_" . $time . ".csv");
                //echo "Файл загружен: " . "upload/r_" . $time.".txt <br />";
            }
        } //если не прошло по типу/размеру
        else {
            if (get_user_class () > UC_ADMINISTRATOR) {
                //print_r ($_FILES["attachment"]);
                print '<div class="alert alert-bordered-dotted margin-bottom-30"></span>
		<h4><strong>Информация по загрузке</strong></h4>
	<p>Upload: ' . $_FILES["attachment"]["name"] . '<br>
	Type: ' . $_FILES["attachment"]["type"] . '<br>
	Size: ' . ($_FILES["attachment"]["size"] / 1024) . ' kB<br>
	</p></div>';
            }

            stderr ("Ошибка", "Не подходящий файл", "no");

        }

        // загружаем файло в переменную
        $mass = file ("upload/upload_sap_" . $time . ".csv");
        // первую строчку пропускаем, т.к. там просто инфа



    }
    if($_GET['step'] > 1 OR $_POST['name'] > 1){
        if($_GET['name'])
            $time = $_GET['name'];
        elseif($_POST['name'])
            $time = $_POST['name'];
        else
            stderr("Ошибка","Некорректные данные");
        $mass = file ("upload/upload_sap_" . $time . ".csv");



    }
    if($_GET['type']=='upload_client' OR $_GET['step']){
        if($_POST['date_data']){
            // как конец даты
            $date_data = unix_time($_POST['date_data']) + 60*60*24 - 1;
        }
        elseif($_GET['date_data']){
            $date_data = (int)$_GET['date_data'];
        }
        else {

            $date_data = time ();
        }
    }

    $t = 2;
    $reserve_name = "Вакансия";

    //первый шаг. обработка справочников. первый шаг. ищем данные которых нет в базе
    if($_POST['step'] == '1') {

        //получаем все справочники по виду ID=>ИМЯ
        $data_block = select_data_base("block","name_block");
        //$data_department = select_data_base("department","name_department");
        $data_direction = select_data_base("direction","name_direction");
        //$data_employee = select_data_base("employee","name_employee");
        $data_employee_model = select_data_base("employee_model","name_model");
        $data_uid_post = select_data_base("established_post","uid_post");
        $data_functionality = select_data_base("functionality","name_functionality");
        $data_location_address = select_data_base("location_address","name_address");
        $data_location_city = select_data_base("location_city","name_city");
       // $data_location_place = select_data_base("location_place","floor, room, place, date_ready");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_position = select_data_base("position","name_position");
        $data_rck = select_data_base("rck","name_rck");
        $data_strategic_project = select_data_base("strategic_project","name_project");
        $data_type_office = select_data_base("type_office","name_office");

        //ищем подразделения так, т.к. они вложенные
        $res_dep = sql_query("SELECT id, name_department, level FROM department") or sqlerr(__FILE__, __LINE__);
        while($row_dep = mysql_fetch_array($res_dep)){
            $data_department[$row_dep['id']]['level'] = $row_dep['level'];
            $data_department[$row_dep['id']]['name'] = $row_dep['name_department'];
        }

//print_r ($data_department);
       /* $name = "тест";
        print search_deaprtment_array($data_department,$name, 1);
    die();*/

       //сначала формируем список основных данных, которые загонять будем в базу
        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            // получаем данные для справочников. формируем массив

            // идентификатор штатной должности
         /*   if((int)$data['0']> 0)
                $array_uid_post[] = (int)$data['0'];*/

            /*ОБРАБАТЫВАЕМ ПОЗИЦИИ/НАЗВАНИЕ ДОЛЖНОСТЕЙ*/
            $array_position[] = trim ($data['2']);

            /*ОБРАБАТЫВАЕМ ПОДРАЗДЕЛЕНИЯ И ТИПЫ ОФИСОВ*/
            $array_block[] = trim($data['3']);
            $array_type_office[] = trim($data['5']);

            $array_department[] = search_deaprtment_array($data_department,trim($data['4']), 0);
            $array_department[] = search_deaprtment_array($data_department,trim($data['6']), 1);
            $array_department[] = search_deaprtment_array($data_department,trim($data['7']), 2);
            $array_department[] = search_deaprtment_array($data_department,trim($data['8']), 3);
            $array_department[] = search_deaprtment_array($data_department,trim($data['9']), 4);
            /*
            $array_department[][0] = trim($data['4']);
            $array_department[][1] = trim($data['6']);
            $array_department[][2] = trim($data['7']);
            $array_department[][3] = trim($data['8']);
            $array_department[][4] = trim($data['9']);
            */
            /*ОБРАБАТЫВАЕМ ДИРЕКЦИИ*/
            $array_direction[] = trim($data['10']);
            /*ОБРАБАТЫВАЕМ ФУНКЦИОНАЛ*/
            $array_functionality_group[] = trim($data['19']);
            $array_functionality_function[] = trim($data['20']);
            /*ОБРАБАТЫВАЕМ СТРАТЕГИЧЕСКИЕ ПРОЕКТЫ*/
            $array_project[] = trim($data['21']);
            /*ОБРАБАТЫВАЕМ РЦК*/
            $array_rck[] = trim($data['22']);
            /*ОБРАБАТЫВАЕМ МВЗ*/
            $array_mvz[] = trim($data['23']);

            /*ОБРАБАТЫВАЕМ МОДЕЛЬ*/
            $array_model[] = trim($data['24']);

            /*ОБРАБАТЫВАЕМ НАЗВАНИЯ ГОРОДОВ*/
            $array_city[] = trim($data['33']);
            /*ОБРАБАТЫВАЕМ АДРЕСА*/
            $array_address[] = trim($data['34']);
            /*ОБРАБАТЫВАЕМ МЕСТА*/
            $array_place[$i]['floor'] = trim($data['35']);
            $array_place[$i]['room'] = trim($data['36']);
            $array_place[$i]['place'] = trim($data['37']);


        }

        /*$result_position = array_unique($array_postion);
        $insert_position = search_in_array($data_position,$result_position);*/

    //    $insert_uid_post = prepeared_data($data_uid_post,$array_uid_post);
        //те что без ссылки на какие-то другие данные
        $insert_position = prepeared_data($data_position,$array_position);
        $insert_block = prepeared_data($data_block,$array_block);
        $insert_type_office = prepeared_data($data_type_office,$array_type_office);
        $insert_functionality_group = prepeared_data($data_functionality,$array_functionality_group);

        $insert_project = prepeared_data($data_strategic_project,$array_project);

        $insert_rck = prepeared_data($data_rck,$array_rck);
        $insert_mvz = prepeared_data($data_mvz,$array_mvz);

        $insert_model = prepeared_data($data_employee_model,$array_model);
        $insert_city = prepeared_data($data_location_city,$array_city);


        // те что ссылаются на другие данные
        $insert_direction = prepeared_data($data_direction,$array_direction);
        // 99999 что бы можно было найти в базе новые данные
        $insert_functionality_function = prepeared_data($data_functionality,$array_functionality_function, 99999);
        $insert_address = prepeared_data($data_location_address,$array_address, 99999);


        // подготавливаем подразделения к добавлению
        $array_department = array_unique($array_department);
        foreach($array_department as $str_dep){
            if (!$str_dep)
                continue;

            if($insert_department)
                $insert_department .= ",";

            $insert_department .= "(".$str_dep.", '".$date_data."' )";
        }


        /*дописать код добавления в базу того, что выше*/
        if ($insert_block)
            sql_query("INSERT INTO block (name_block, added) VALUES ".$insert_block."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_department)
            sql_query("INSERT INTO department (name_department,level, added) VALUES ".$insert_department."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_direction)
            sql_query("INSERT INTO direction (name_direction, added) VALUES ".$insert_direction."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_model)
            sql_query("INSERT INTO employee_model (name_model,added) VALUES ".$insert_model."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_position)
            sql_query("INSERT INTO position (name_position, added) VALUES ".$insert_position."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_functionality_group)
            sql_query("INSERT INTO functionality (`name_functionality`, added) VALUES ".$insert_functionality_group.";") or sqlerr(__FILE__, __LINE__);

        if ($insert_functionality_function)
            sql_query("INSERT INTO functionality (`name_functionality`, added, id_parent) VALUES ".$insert_functionality_function.";") or sqlerr(__FILE__, __LINE__);

        if ($insert_address)
            sql_query("INSERT INTO location_address (`name_address`, added, id_city) VALUES ".$insert_address."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_city)
            sql_query("INSERT INTO location_city (`name_city`, added) VALUES ".$insert_city."; ") or sqlerr(__FILE__, __LINE__);

        //sql_query("INSERT INTO location_place (floor, room, place, ready, date_ready, reservation, date_reservation, occupy, date_occupy, added) VALUES ".$insert_place.";") or sqlerr(__FILE__, __LINE__);

        //sql_query("INSERT INTO mvz (`name_mvz`, added) VALUES ".$insert_mvz."; ") or sqlerr(__FILE__, __LINE__);


        if ($insert_rck)
            sql_query("INSERT INTO rck (`name_rck`, added) VALUES ".$insert_rck."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_mvz)
            sql_query("INSERT INTO mvz (`name_mvz`, added) VALUES ".$insert_mvz."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_type_office)
             sql_query("INSERT INTO type_office (`name_office`, added) VALUES ".$insert_type_office."; ") or sqlerr(__FILE__, __LINE__);

        if ($insert_project)
              sql_query("INSERT INTO strategic_project (`name_project`, added) VALUES ".$insert_project.";") or sqlerr(__FILE__, __LINE__);



        $REL_TPL->stdmsg('Выполнено','Добавление новых данных в справочник завершено. Шаг 1 завершен. Происходит создание новых связей в справочнике. Не перезагружайте страницу');
        $step = 2;

        safe_redirect("upload_sap.php?step=2&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }

    // шаг два. связываем несвязанные данные справочника
    if($_GET['step'] == '2') {

        // связываем функции, подразделения, мвз, адреса
        /*ДОБАВИТЬ ПРОВЕРКУ НА ИЗМЕНЕННЫЕ ДАННЫЕ. ВДРУГ ФУНКЦИЯ ИЗМЕНИЛА ГРУППУ*/

        //получаем даные по функционалу
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent = 99999");
        $data_functionality_group = select_data_base("functionality","name_functionality","WHERE id_parent = 0");

        //получаем данные по МВЗ и РЦК
        $data_mvz = select_data_base("mvz","name_mvz", "WHERE id_rck = 0");
        $data_rck = select_data_base("rck","name_rck");

        // получаем данные по городам и адресам
        $data_city = select_data_base("location_city","name_city");
        $data_address = select_data_base("location_address","name_address", "WHERE id_city != 99999");
        $data_address_new = select_data_base("location_address","name_address", "WHERE id_city = 99999");
        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place ;")  or sqlerr(__FILE__, __LINE__);

        while($row = mysql_fetch_array($res)){

            $data_place[$row['id']]=$row['floor'].",".$row['room'].",".$row['place'].",".$row['date_ready'];

        }
        $added_place = $date_data;

        //получаем список внештатников
    /*    $res_vn=sql_query("SELECT established_post.id as ep_id, employee.id as emp_id FROM `employee` LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE established_post.uid_post = 0 AND established_post.current = 1 AND employee.current = 1;")  or sqlerr(__FILE__, __LINE__);*/
        $res_vn=sql_query("SELECT established_post.id as ep_id, employee.id as emp_id FROM `established_post` LEFT JOIN employee ON employee.id_uid_post = established_post.id WHERE established_post.uid_post = 0 AND established_post.current = 1 AND (employee.current = 1 OR employee.current IS NULL);")  or sqlerr(__FILE__, __LINE__);
        while($row_vn = mysql_fetch_array($res_vn)) {
            if($row_vn['ep_id']) {
                if ($del_id_ep)
                    $del_id_ep .= ",";
                $del_id_ep .= $row_vn['ep_id'];
            }
            if($row_vn['emp_id']) {
                if ($del_id_emp)
                    $del_id_emp .= ",";
                $del_id_emp .= $row_vn['emp_id'];
            }
        }
        if($del_id_ep)
            sql_query("UPDATE `established_post` SET `last_update` = '".$date_data."', `date_end` =  '".$date_data."',  `current` = '0'  WHERE `established_post`.`id` IN (".$del_id_ep.")")  or sqlerr(__FILE__, __LINE__);
        if($del_id_emp)
            sql_query("UPDATE `employee` SET `last_update` = '".$date_data."', `date_end` =  '".$date_data."',  `current` = '0' WHERE `employee`.`id` IN (".$del_id_emp.")")  or sqlerr(__FILE__, __LINE__);

        //объявляем массивы
        $used_functionality = array();
        $used_mvz = array();
        $used_address = array();
        $used_place = array();

        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            //поиск функции среди прикрепленных к группе их обновление
            if(count($data_functionality)) {
                $id_functionality = (int)array_search (trim ($data['20']), $data_functionality);

                if ($id_functionality > 0 AND array_search ($id_functionality, $used_functionality) === false) {
                    $id_function_group = (int)array_search (trim ($data['19']), $data_functionality_group);
                    sql_query ("UPDATE `functionality` SET `id_parent` = '" . $id_function_group . "' WHERE `id` = $id_functionality;") or sqlerr (__FILE__, __LINE__);
                    $used_functionality[] = $id_functionality;
                }
            }
            /*
             *
             *  if((array_search($id_functionality,$used_functionality) === false) AND $id_functionality > 0){
                sql_query ("UPDATE `functionality` SET `id_parent` = '".$id_functionality_group."' WHERE `id` = $id_functionality;") or sqlerr(__FILE__, __LINE__);
                $used_functionality[] = $id_functionality;
            }
             *
             * */



            /*ОБРАБАТЫВАЕМ МВЗ*/
            if(count($data_mvz)) {
                $id_rck = (int)array_search ($data['22'], $data_rck);
                $id_mvz = (int)array_search (trim ($data['23']), $data_mvz);


                // если нашли и все они определены как надо
                if ($id_rck != 0 AND $id_mvz != 0 AND (array_search ($id_mvz, $used_mvz) === false)) {
                    sql_query ("UPDATE `mvz` SET `id_rck` = '" . $id_rck . "' WHERE `id` = $id_mvz;") or sqlerr (__FILE__, __LINE__);
                    //добавляем в массив, что бы исключить из следующего обновления
                    $used_mvz[] = $id_mvz;

                }
            }

        /* СВЯЗЫВАЕМ ЛОКАЦИИ. МЕСТА И АДРЕСА С ГОРОДАМИ*/
            $id_city = (int)array_search(trim($data['33']),$data_city);

            $id_location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);

            $id_address = (int)array_search(trim($data['34']),$data_address);
            if(!$id_address AND $id_address !=0) {
                $id_address = (int)array_search (trim($data['34']), $data_address_new);

                if ((array_search ($id_address, $used_address) === false) AND $id_address > 0) {
                    sql_query ("UPDATE `location_address` SET `id_city` = '" . $id_city . "' WHERE `id` = $id_address;") or sqlerr (__FILE__, __LINE__);
                    $used_address[] = $id_city;
                }
            }
            if( $id_address > 0 AND !$id_location_place){
                $floor = (int)$data['35'];
                $room = trim($data['36']);
                $place = trim($data['37']);

                if($data['38'] == "Да"){
                    $ready = 1;
                }
                else
                    $ready = 0;

                $date_ready = unix_time($data['39']);

                if($data['40'] == "Да"){
                    $reservation = 1;
                }
                else{
                    $reservation = 0;
                }

                $date_reservation = unix_time($data['41']);

                if($data['42'] == "Да"){
                    $occupy = 1;
                }
                else{
                    $occupy = 0;
                }

                $date_occupy = unix_time($data['44']);
                $current_data_place = "('".$id_address."','". $floor."','".$room."','".$place."','".$ready."','".$date_ready."','".$reservation."','".$date_reservation."','".$occupy."','". $date_occupy."','".$added_place."')";

                if(array_search($current_data_place,$used_place) === false) {
                    $used_place[] = $current_data_place;
                    sql_query("INSERT INTO location_place (id_address,floor, room, place, ready, date_ready, reservation, date_reservation, occupy, date_occupy, added) VALUES ".$current_data_place.";") or sqlerr(__FILE__, __LINE__);
                }
                //sql_query ("UPDATE `location_place` SET `id_address` = '".$id_address."' WHERE `id` = $id_location_place;") or sqlerr(__FILE__, __LINE__);
               // sql_query("INSERT INTO location_place (floor, room, place, ready, date_ready, reservation, date_reservation, occupy, date_occupy, added) VALUES (".trim($data['35']).",".trim($data['36']).",".trim($data['37']).",".trim($data['']).",".trim($data['']).",".trim($data['']).",".trim($data['']).",".trim($data['']).",".trim($data['']).");") or sqlerr(__FILE__, __LINE__);
            }




        }



        $REL_TPL->stdmsg('Выполнено','Связывание новых данных справочника выполнено (1/4). Шаг 2 завершен. Не перезагружайте страницу');
        $step = 3;
        safe_redirect("upload_sap.php?step=3&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }

   // шаг три. Ищем новые ШЕ, добавляем их
    if($_GET['step'] == '3') {


        $res_ep=sql_query("SELECT id, uid_post FROM established_post WHERE uid_post !=0 AND `current` = 1;")  or sqlerr(__FILE__, __LINE__);
        while($row_ep = mysql_fetch_array($res_ep)){
            $data_ep[$row_ep['id']] = $row_ep['uid_post'];
        }

        $res_max_id_ep = sql_query("SELECT MAX(id_ep)  FROM established_post") or sqlerr(__FILE__, __LINE__);
        $max_id_ep = mysql_fetch_row($res_max_id_ep)[0]+1;


        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            $uid_post = (int)$data['0'];
            if($uid_post > 0)
                $array_uid_post[] = $uid_post;



        }

        $array_uid_post = array_unique($array_uid_post);

        //ищем в среди ШЕ из файла те, что нет в базе и добавляем
        foreach($array_uid_post as $uid_post){

            if (array_search ($uid_post, $data_ep) === false) {

                sql_query("INSERT INTO `established_post` (uid_post,revision,added,date_start,id_ep) VALUES('".$uid_post."', '999999','".$date_data."','".$date_data."','".$max_id_ep."')") or sqlerr(__FILE__, __LINE__);
                $max_id_ep++;
            }
        }
        //ищем в среди ШЕ из базы те, что отсутствуют в файле и отмечаем как удаленные
     /*   foreach($data_ep as $key => $uid_post){

            if (array_search ($uid_post, $array_uid_post) === false) {

              //  sql_query("INSERT INTO `established_post` (uid_post,revision,added) VALUES('".$uid_post."', '999999','".$date_data."')") or sqlerr(__FILE__, __LINE__);

                sql_query("INSERT INTO revision_established_post (`id_established_post`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`) SELECT `id`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`  FROM established_post WHERE established_post.id =  '".$key."';") or sqlerr(__FILE__, __LINE__);
                //обновляем
                sql_query("UPDATE `established_post` SET `is_deleted` = '1', `revision` = `revision` + 1 WHERE `id` ='".$key."';") or sqlerr(__FILE__, __LINE__);




            }
        }*/

        $REL_TPL->stdmsg('Выполнено','Связывание новых данных справочника выполнено (2/4). Шаг 3 завершен. Не перезагружайте страницу');
        $step = 4;
        safe_redirect("upload_sap.php?step=4&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }
    //шаг четрые добавляем новых сотрудников
    if($_GET['step'] == '4') {

        $res_emp=sql_query("SELECT id, name_employee, date_employment FROM employee WHERE `current` = 1;")  or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){
            $data_employee[$row_emp['id']]=$row_emp['name_employee'].",".$row_emp['date_employment'];
        }

        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);
        while($row = mysql_fetch_array($res)){
            $data_place[$row['id']]=$row['floor'].",".$row['room'].",".$row['place'].",".$row['date_ready'];
        }

        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent != 0");
        $data_project = select_data_base("strategic_project","name_project");
        $data_model = select_data_base("employee_model","name_model");
        $data_established_post = select_data_base("established_post","uid_post");


        $res_max_id_emp = sql_query("SELECT MAX(id_employee)  FROM employee") or sqlerr(__FILE__, __LINE__);
        $max_id_emp = mysql_fetch_row($res_max_id_emp)[0] +1;


    for ($i = $t; $i < count ($mass); $i++) {

        $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
        $data = str_getcsv($mass[$i],";");


        $employee = trim($data['1']);
        $date_employment = unix_time($data['31']);
        $id_employee = (int)array_search($employee.",".$date_employment,$data_employee);

        if ($id_employee <1 ) {
            //если вдруг у сотрудника не была задана дата приема

            $id_employee = (int)array_search ($employee . "," . 0, $data_employee);
        }

      /*  if(trim($data['1'] != "Киприянов Павел Игоревич"))
            continue;
        else {
            print "123a";
            print $id_employee;
            print_r($data_employee);
            die();
        }*/

        if($id_employee < 1 AND $employee != $reserve_name) {

            $date_transfer = unix_time ($data['32']);
            if($data['28']){
                $fte = str_replace(",",".",$data['28']);

            }
            else{
                $fte = 0;
            }

            $id_location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
            $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
            $id_project = (int)array_search(trim($data['21']),$data_project);
            if($data_model)
                $id_model = (int)array_search(trim($data['24']),$data_model);
            $id_established_post = (int)array_search($data['0'],$data_established_post);

            sql_query ("INSERT INTO `employee` (name_employee, id_uid_post, id_location_place, id_functionality, id_strategic_project, id_employee_model, date_employment,  date_transfer, fte, added, revision, id_employee, date_start) VALUES('".$employee."', '".$id_established_post."', '".$id_location_place."','".$id_functionality."','".$id_project."','".$id_model."','".$date_employment."','".$date_transfer."','".$fte."','".$date_data."', '1', '".$max_id_emp."', '".$date_data."')") or sqlerr (__FILE__, __LINE__);

        }




    }



    $REL_TPL->stdmsg('Выполнено','Связывание новых данных справочник (3/4). Шаг 4 завершен. Не перезагружайте страницу');
    $step = 5;
        safe_redirect("upload_sap.php?step=5&name=".$time."&date_data=".$date_data."",1);

    $REL_TPL->stdfoot();
    die();
}

    if($_GET['step'] == '5') {


        $data_established_post = select_data_base("established_post","uid_post","WHERE revision = 999999");
        $data_position = select_data_base("position","name_position");
        $data_block = select_data_base("block","name_block");
     //   $data_department = select_data_base("department","name_department");

        //ищем подразделения так, т.к. они вложенные
        $res_dep = sql_query("SELECT id, name_department, level FROM department") or sqlerr(__FILE__, __LINE__);
        while($row_dep = mysql_fetch_array($res_dep)){
            $data_department[$row_dep['id']]['level'] = $row_dep['level'];
            $data_department[$row_dep['id']]['name'] = $row_dep['name_department'];
        }

        $data_direction = select_data_base("direction","name_direction");
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_city = select_data_base("location_city","name_city");
        //получаем существующие UID POST к которым привязаны люди
        $res_emp=sql_query("SELECT employee.name_employee, established_post.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE established_post.`current` = 1 AND employee.`current` = 1  ;") or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){

            $data_emp[$row_emp['id']]=$row_emp['name_employee'];
        }
        $used_established_post = array();


        for ($i = $t; $i < count ($mass); $i++) {

            if(count($data_established_post) == 0)
                break;


            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            $id_established_post = (int)array_search($data['0'],$data_established_post);

            if ($id_established_post <1)
                continue;

            $id_position = (int)array_search($data['2'],$data_position);
            $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);

            unset($array_department);
         //   $array_department[] = (int)array_search(str_replace ("\"", "", $data['3']),$data_department);
        /*    $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']),$data_department);*/

            $array_department[] = search_deaprtment_array($data_department,trim($data['4']), 0);
            $array_department[] = search_deaprtment_array($data_department,trim($data['6']), 1);
            $array_department[] = search_deaprtment_array($data_department,trim($data['7']), 2);
            $array_department[] = search_deaprtment_array($data_department,trim($data['8']), 3);
            $array_department[] = search_deaprtment_array($data_department,trim($data['9']), 4);

            $id_department = "";
            foreach ($array_department as $array) {
                if($array < 1)
                    continue;

                if($id_department)
                    $id_department .= ",";

                $id_department .= $array;
            }

            $id_direction = (int)array_search($data['10'],$data_direction);

            $id_rck = (int)array_search($data['22'],$data_rck);
            $id_mvz = (int)array_search($data['23'],$data_mvz);
            $id_city = (int)array_search($data['33'],$data_city);
            $id_functional_manager = (int)array_search(trim($data['14']),$data_emp);
            $id_administrative_manager = (int)array_search(trim($data['17']),$data_emp);
            $date_entry = unix_time ($data['29']);
            // смотрим драфт или нет. если нет, то ставим факт
            if ((int)$data['26'] == 1) {
                $draft = 0;
            } else {
                $draft = 1;
            }
            // передана ли ш.е.
            if ($data['30'] == "Да") {
                $transfer = 1;
            } else {
                $transfer = 0;
            }

            /*ОБРАБАТЫВАЕМ ШТАТНЫЕ ДОЛЖНОСТИ*/
            if(array_search($id_established_post,$used_established_post) === false){

                sql_query ("UPDATE `established_post` SET `id_position` = '".$id_position."',`id_block` = '".$id_block."', `id_department` = '".$id_department."',`id_direction` = '".$id_direction."',`id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `id_location_city` = '".$id_city."', `id_administrative_manager` = '".$id_administrative_manager."', `id_functional_manager` = '".$id_functional_manager."', `date_entry` = '".$date_entry."', `draft` = '".$draft."', `transfer` = '".$transfer."', `revision` = '1', `added` = '".$date_data."'
                 WHERE `id` = $id_established_post;")  or sqlerr(__FILE__, __LINE__);
                $used_established_post[] = $id_established_post;
            }


//date_entry draft transfer revision
        }



        $REL_TPL->stdmsg('Выполнено','Связывание новых данных справочника завершено (4/4). Шаг 5 завершен. Происходит обновление шатных единиц и сотрудников. Не перезагружайте страницу');
        $step = 6;
        safe_redirect("upload_sap.php?step=6&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }

    //Проверка ШЕ и сотрудников на изменения. Если есть, то копия в таблицу ревизий и обновление записей
    if($_GET['step'] == '6') {


        /*
         * сначала формируем справочники по которым будем проставлять значения
         *
         * */
        //выбираем всех сотрудников
        $res_emp=sql_query("SELECT * FROM employee WHERE is_deleted = 0 AND `current` = 1;") or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){
            //так формируем массив, что бы не попали полные тезки. вероятность того, что 2 человека с одним ФИО будут приняты в один день - маловероятна
            $array_emp[$row_emp['name_employee'].$row_emp['date_employment']]=$row_emp;
            $array_all_emp[] = $row_emp['id'];
        }

        //выбираем всех штатников
        $res_esp=sql_query("SELECT * FROM established_post WHERE established_post.uid_post !=0 AND established_post.is_deleted = 0 AND `current` = 1;") or sqlerr(__FILE__, __LINE__);

        while($row_esp = mysql_fetch_array($res_esp)){
            $array_esp[$row_esp['uid_post']]=$row_esp;
            $array_all_esp[] = $row_esp['id'];
        }

        //загружаем справочники
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent != 0");
        $data_project = select_data_base("strategic_project","name_project");
        $data_model = select_data_base("employee_model","name_model");

        $data_position = select_data_base("position","name_position");
        $data_block = select_data_base("block","name_block");
        //    $data_department = select_data_base("department","name_department");
        $data_direction = select_data_base("direction","name_direction");
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_city = select_data_base("location_city","name_city");

        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);
        while($row = mysql_fetch_array($res)) {
            $data_place[$row['id']] = $row['floor'] . "," . $row['room'] . "," . $row['place'] . "," . $row['date_ready'];
        }

        //ищем подразделения так, т.к. они вложенные
        $res_dep = sql_query("SELECT id, name_department, level FROM department") or sqlerr(__FILE__, __LINE__);
        while($row_dep = mysql_fetch_array($res_dep)){

            $data_department[$row_dep['id']] = $row_dep['name_department'].$row_dep['level'];

        }

        //получаем существующие UID POST к которым привязаны люди
        $res_mgr=sql_query("SELECT employee.name_employee, established_post.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE established_post.`current` = 1 AND employee.`current` = 1 ;") or sqlerr(__FILE__, __LINE__);

        while($row_mgr = mysql_fetch_array($res_mgr)){

            $data_mgr[$row_mgr['id']]=$row_mgr['name_employee'];
        }


        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            //пропускаем всех внештатников
            $uid_post = (int)$data['0'];
            if($uid_post < 1){
                continue;
            }


            //ищем в массивах ID принадлежащие людям и ШЕ
            $data_emp = $array_emp[trim($data['1']).unix_time ($data['31'])];
            if(!$data_emp)
                $data_emp = $array_emp[trim($data['1']).unix_time (0)];

            $data_esp = $array_esp[trim($data['0'])];



            $ss = 10;
            //  print_r ($data_emp);
            //проверяем что сотрудник есть, если да, то проверяем данные
            if($data_emp AND trim($data['1']) != $reserve_name AND $ss = 50){

                $update = 0;

                $array_used_emp[] = $data_emp['id'];

                //прописываем данные для удобства
                $location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
                $date_transfer = unix_time ($data['32']);
                $date_employment = unix_time ($data['31']);
                $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
                $fte = str_replace(",",".",$data['28']);
                $id_project = (int)array_search(trim($data['21']),$data_project);
                if ($data_model)
                    $id_employee_model = (int)array_search(trim($data['24']),$data_model);

                if($data_esp['id'] != $data_emp['id_uid_post']){
                    $update = 1;
                }
                if($location_place != $data_emp['id_location_place']){
                    $update = 2;
                }
                if($date_transfer != $data_emp['date_transfer']){
                    $update = 4;
                }
                if($id_functionality != $data_emp['id_functionality']){
                    $update = 5;
                }
                if($fte != $data_emp['fte']){
                    $update = 6;
                }
                if($id_project != $data_emp['id_strategic_project']){
                    $update = 7;
                }
                if($id_employee_model != $data_emp['id_employee_model']){
                    $update = 8;
                }
                if($data_emp['vacancy'] != 0){
                    $update = 9;
                }
                if($data_emp['date_employment'] != $date_employment){
                    $update = 10;
                }

                // если были многочисленные изменения, то обновляем запись, плюс копируем в ревизии
                if($update !=0){


                    sql_query("

                        INSERT INTO employee (
                        `name_employee`, `id_uid_post`, `id_location_place`,
                        `email`, `date_employment`, `date_transfer`,
                        `id_functionality`, `added`, `last_update`,
                        `fte`, `id_strategic_project`,
                        `id_employee_model`,  `revision`,
                        `id_user_change`, `id_employee`,
                         `date_start`)
                        VALUES (
                        '".$data_emp['name_employee']."', '".$data_esp['id']."','".$location_place."',
                        '".$data_emp['email']."','".$date_employment."','".$date_transfer."',
                        '".$id_functionality."','".$data_emp['added']."','".$date_data."',
                        '".$fte."','".$id_project."',
                        '".$id_employee_model."','".$data_emp['revision']."' + 1,
                        '".$CURUSER['id']."','".$data_emp['id_employee']."',
                        '".$date_data."'
                        );"

                    ) or sqlerr(__FILE__, __LINE__);

                    /*  sql_query("INSERT INTO employee
(
`name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `id_employee`)
SELECT
`name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`,`id_employee` FROM employee WHERE employee.id =  '".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);*/
                    //  $last_id = mysql_insert_id();
                    /*  sql_query("UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."', `date_start` = '".$date_data."'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);*/
                    //обновляем
                    sql_query("UPDATE `employee` SET `last_update` = '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0', `date_end` = '".$date_data."'   WHERE `id` ='".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);


                    /*
                     * sql_query("INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change` FROM employee WHERE employee.id =  '".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);
                     * */
                    /*   sql_query("UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."'  WHERE `id` ='".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);*/
                    /*   $query .= "INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `vacancy`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `vacancy` FROM employee WHERE employee.id =  '".$data_emp['id']."';";
                       //обновляем
                       $query .= "UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."', `vacancy` = 0  WHERE `id` ='".$data_emp['id']."';";*/

                }

            }


            //отмечаем какие ИД мы уже использовали. те что нет, отмечаем в базе как старые
          //  $array_used_esp[] = $data_esp['id'];
            //НЕ РАБОТАЕТ!!!!!!! ВЫКЛЮЧЕНО
            /*КОСЯК ОБНОВЛЕНИЯ ПРИВЯЗКИ СОТРУДНИКА*/
            $ss = 10;

            if((int)$data_esp['id'] >0 ){

                $update = 0;
                $array_used_esp[] = $data_esp['id'];

                $id_position = (int)array_search(trim($data['2']),$data_position);
                $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);

                unset($array_department);
                // $array_department[] = (int)array_search(str_replace ("\"", "", $data['3']),$data_department);
                /* $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']),$data_department);
                 $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']),$data_department);
                 $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']),$data_department);
                 $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']),$data_department);
                 $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']),$data_department);*/
                $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']."0"),$data_department);
                $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']."1"),$data_department);
                $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']."2"),$data_department);
                $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']."3"),$data_department);
                $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']."4"),$data_department);
                $id_department = "";
                foreach ($array_department as $array) {
                    if($array < 1)
                        continue;

                    if($id_department)
                        $id_department .= ",";

                    $id_department .= $array;
                }

                $id_direction = (int)array_search(trim($data['10']),$data_direction);
                $id_rck = (int)array_search(trim($data['22']),$data_rck);
                $id_mvz = (int)array_search(trim($data['23']),$data_mvz);
                $date_entry = unix_time ($data['29']);
                $id_location_city = (int)array_search(trim($data['33']),$data_city);

                $id_functional_manager = (int)array_search(trim($data['14']),$data_mgr);
                $id_administrative_manager = (int)array_search(trim($data['17']),$data_mgr);

                if ((int)$data['26'] == 1) {
                    $draft = 0;
                } else {
                    $draft = 1;
                }
                if ($data['30'] == "Да") {
                    $transfer = 1;
                } else {
                    $transfer = 0;
                }

                if($id_position != $data_esp['id_position']){
                    $update = 10;
                }
                if($id_block != $data_esp['id_block']){
                    $update = 11;
                }
                if($id_department != $data_esp['id_department']){
                    $update = 12;
                }

                if($id_direction != $data_esp['id_direction']){
                    $update = 13;
                }
                if($id_rck != $data_esp['id_rck']){
                    $update = 14;
                }
                if($id_mvz != $data_esp['id_mvz']){
                    $update = 15;
                }
                if($date_entry != $data_esp['date_entry']){
                    $update = 16;
                }
                if($id_location_city != $data_esp['id_location_city']){
                    $update = 17;
                }

                if($id_functional_manager != $data_esp['id_functional_manager']){
                    $update = 18;
                }
                if($id_administrative_manager != $data_esp['id_administrative_manager']){
                    $update = 19;
                }

                if($draft != $data_esp['draft']){
                    $update = 20;
                }
                if($transfer != $data_esp['transfer']){
                    $update = 21;
                }


                if($update !=0){
                    $last_id = 0;
                    sql_query("
INSERT INTO established_post (`uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`,`id_ep`)SELECT `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`,`id_ep`  FROM established_post WHERE established_post.id =  '".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                    $last_id = mysql_insert_id();

                    sql_query("UPDATE `established_post` SET  `id_position` = '".$id_position."', `id_block` = '".$id_block."', `id_department` = '".$id_department."', `id_direction` = '".$id_direction."', `id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `date_entry` = '".$date_entry."', `last_update` = '".$date_data."', `id_location_city` = '".$id_location_city."', `id_functional_manager` = '".$id_functional_manager."', `id_administrative_manager` = '".$id_administrative_manager."', `draft` = '".$draft."', `transfer` = '".$transfer."',`is_deleted` = '0', `revision` = `revision` + 1, `date_start` = '".$date_data."'  WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                    sql_query("UPDATE `established_post` SET `is_deleted` = 1,`last_update` = '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0', `date_end` = '".$date_data."'  WHERE `id` ='".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                    /*   sql_query("INSERT INTO revision_established_post (`id_established_post`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`) SELECT `id`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`  FROM established_post WHERE established_post.id =  '".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                       //обновляем
                       sql_query("UPDATE `established_post` SET `id_position` = '".$id_position."', `id_block` = '".$id_block."', `id_department` = '".$id_department."', `id_direction` = '".$id_direction."', `id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `date_entry` = '".$date_entry."', `last_update` = '".$date_data."', `id_location_city` = '".$id_location_city."', `id_functional_manager` = '".$id_functional_manager."', `id_administrative_manager` = '".$id_administrative_manager."', `draft` = '".$draft."', `transfer` = '".$transfer."',`is_deleted` = '0', `revision` = `revision` + 1 WHERE `id` ='".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);*/

                }


            }


        }
        $array_not_used_esp = array_diff($array_all_esp,$array_used_esp);
        $array_not_used_emp = array_diff($array_all_emp,$array_used_emp);

        foreach($array_not_used_emp as $a){
            if((int)$a >0){
                $last_id = 0;

             //   sql_query("INSERT INTO employee(`name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `id_employee`) SELECT `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`,`id_employee` FROM employee WHERE employee.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
            //    $last_id = mysql_insert_id();
            //    sql_query("UPDATE `employee` SET `is_deleted` = 1,`revision` = `revision` +1,`last_update` = '".$date_data."',  `date_start` = '".$date_data."', `date_end` =  '".$date_data."', `id_user_change` = '".$CURUSER['id']."'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                //обновляем
           //     sql_query("UPDATE `employee` SET  `last_update` = '".$date_data."', `date_end` =  '".$date_data."',  `current` = '0'   WHERE `id` ='".$a."';") or sqlerr(__FILE__, __LINE__);

                /*
                sql_query("INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change` FROM employee WHERE employee.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
                sql_query("UPDATE employee SET is_deleted = 1, `revision` = `revision` + 1 WHERE id = '".$a."';");*/


            }
        }
        /*  sql_query($query) or sqlerr(__FILE__, __LINE__);
          unset($query);*/
        foreach($array_not_used_esp as $a){
            if((int)$a >0){
                $last_id = 0;
                sql_query("INSERT INTO established_post (`uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`,`id_ep`)SELECT `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`,`id_ep`  FROM established_post WHERE established_post.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
                $last_id = mysql_insert_id();
                sql_query("UPDATE `established_post` SET `is_deleted` = 1,`revision` = `revision` +1,`last_update` = '".$date_data."',  `date_start` = '".$date_data."', `date_end` =  '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                //обновляем
                sql_query("UPDATE `established_post` SET   `last_update` = '".$date_data."', `date_end` =  '".$date_data."', `current` = '0'    WHERE `id` = '".$a."';") or sqlerr(__FILE__, __LINE__);

                /*
                sql_query("INSERT INTO revision_established_post (`id_established_post`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,
     `revision`) SELECT `id`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`  FROM established_post WHERE established_post.id =  '".$a."'") or sqlerr(__FILE__, __LINE__);

                sql_query("UPDATE established_post SET is_deleted = 1, `revision` = `revision` + 1 WHERE id = '".$a."';");
*/
            }
        }
        // print $query;
        // sql_query($query) or sqlerr(__FILE__, __LINE__);

        //   die();
        $REL_TPL->stdmsg('Выполнено','Шаг 6a завершен. Данные обновлены (1/3)');
        $step = "6b";
        safe_redirect("upload_sap.php?step=6b&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }


    if($_GET['step'] == '6b') {


/*
 * сначала формируем справочники по которым будем проставлять значения
 *
 * */
        //выбираем всех сотрудников
        $res_emp=sql_query("SELECT * FROM employee WHERE is_deleted = 0 AND `current` = 1;") or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){
            //так формируем массив, что бы не попали полные тезки. вероятность того, что 2 человека с одним ФИО будут приняты в один день - маловероятна
            $array_emp[$row_emp['name_employee'].$row_emp['date_employment']]=$row_emp;
            $array_all_emp[] = $row_emp['id'];
        }

    //выбираем всех штатников
        $res_esp=sql_query("SELECT * FROM established_post WHERE established_post.uid_post !=0 AND established_post.is_deleted = 0 AND `current` = 1;") or sqlerr(__FILE__, __LINE__);

        while($row_esp = mysql_fetch_array($res_esp)){
            $array_esp[$row_esp['uid_post']]=$row_esp;
            $array_all_esp[] = $row_esp['id'];
        }

       //загружаем справочники
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent != 0");
        $data_project = select_data_base("strategic_project","name_project");
        $data_model = select_data_base("employee_model","name_model");

        $data_position = select_data_base("position","name_position");
        $data_block = select_data_base("block","name_block");
    //    $data_department = select_data_base("department","name_department");
        $data_direction = select_data_base("direction","name_direction");
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_city = select_data_base("location_city","name_city");

        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);
        while($row = mysql_fetch_array($res)) {
            $data_place[$row['id']] = $row['floor'] . "," . $row['room'] . "," . $row['place'] . "," . $row['date_ready'];
        }

        //ищем подразделения так, т.к. они вложенные
        $res_dep = sql_query("SELECT id, name_department, level FROM department") or sqlerr(__FILE__, __LINE__);
        while($row_dep = mysql_fetch_array($res_dep)){

            $data_department[$row_dep['id']] = $row_dep['name_department'].$row_dep['level'];

        }

        //получаем существующие UID POST к которым привязаны люди
        $res_mgr=sql_query("SELECT employee.name_employee, established_post.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE established_post.`current` = 1 AND employee.`current` = 1 ;") or sqlerr(__FILE__, __LINE__);

        while($row_mgr = mysql_fetch_array($res_mgr)){

            $data_mgr[$row_mgr['id']]=$row_mgr['name_employee'];
        }


        for ($i = $t; $i < count ($mass); $i++) {

                $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
                $data = str_getcsv($mass[$i],";");

                //пропускаем всех внештатников
                $uid_post = (int)$data['0'];
                if($uid_post < 1){
                    continue;
                }


                //ищем в массивах ID принадлежащие людям и ШЕ
                $data_emp = $array_emp[trim($data['1']).unix_time ($data['31'])];
                if(!$data_emp)
                    $data_emp = $array_emp[trim($data['1']).unix_time (0)];

                $data_esp = $array_esp[trim($data['0'])];




              //  print_r ($data_emp);
                //проверяем что сотрудник есть, если да, то проверяем данные
                if($data_emp AND trim($data['1']) != $reserve_name){

                    $update = 0;

                    $array_used_emp[] = $data_emp['id'];

                    //прописываем данные для удобства
                    $location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
                    $date_transfer = unix_time ($data['32']);
                    $date_employment = unix_time ($data['31']);
                    $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
                    $fte = str_replace(",",".",$data['28']);
                    $id_project = (int)array_search(trim($data['21']),$data_project);
                    if ($data_model)
                        $id_employee_model = (int)array_search(trim($data['24']),$data_model);

                    if($data_esp['id'] != $data_emp['id_uid_post']){
                        $update = 1;
                    }
                    if($location_place != $data_emp['id_location_place']){
                        $update = 2;
                    }
                    if($date_transfer != $data_emp['date_transfer']){
                        $update = 4;
                    }
                    if($id_functionality != $data_emp['id_functionality']){
                        $update = 5;
                    }
                    if($fte != $data_emp['fte']){
                        $update = 6;
                    }
                    if($id_project != $data_emp['id_strategic_project']){
                        $update = 7;
                    }
                    if($id_employee_model != $data_emp['id_employee_model']){
                        $update = 8;
                    }
                    if($data_emp['vacancy'] != 0){
                        $update = 9;
                    }
                    if($data_emp['date_employment'] != $date_employment){
                        $update = 10;
                    }

                    // если были многочисленные изменения, то обновляем запись, плюс копируем в ревизии
                    if($update !=0){


                        sql_query("

                        INSERT INTO employee (
                        `name_employee`, `id_uid_post`, `id_location_place`,
                        `email`, `date_employment`, `date_transfer`,
                        `id_functionality`, `added`, `last_update`,
                        `fte`, `id_strategic_project`,
                        `id_employee_model`,  `revision`,
                        `id_user_change`, `id_employee`,
                         `date_start`)
                        VALUES (
                        '".$data_emp['name_employee']."', '".$data_esp['id']."','".$location_place."',
                        '".$data_emp['email']."','".$date_employment."','".$date_transfer."',
                        '".$id_functionality."','".$data_emp['added']."','".$date_data."',
                        '".$fte."','".$id_project."',
                        '".$id_employee_model."','".$data_emp['revision']."' + 1,
                        '".$CURUSER['id']."','".$data_emp['id_employee']."',
                        '".$date_data."'
                        );"

) or sqlerr(__FILE__, __LINE__);

                      /*  sql_query("INSERT INTO employee
(
 `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `id_employee`)
SELECT
`name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`,`id_employee` FROM employee WHERE employee.id =  '".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);*/
                      //  $last_id = mysql_insert_id();
                      /*  sql_query("UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."', `date_start` = '".$date_data."'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);*/
                        //обновляем
                        sql_query("UPDATE `employee` SET `last_update` = '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0', `date_end` = '".$date_data."'   WHERE `id` ='".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);


/*
 * sql_query("INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change` FROM employee WHERE employee.id =  '".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);
 * */
                     /*   sql_query("UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."'  WHERE `id` ='".$data_emp['id']."';") or sqlerr(__FILE__, __LINE__);*/
                     /*   $query .= "INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `vacancy`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `vacancy` FROM employee WHERE employee.id =  '".$data_emp['id']."';";
                        //обновляем
                        $query .= "UPDATE `employee` SET `id_uid_post` = '".$data_esp['id']."',  `id_location_place` = '".$location_place."',  `date_transfer` = '".$date_transfer."', `id_functionality` = '".$id_functionality."', `fte` = '".$fte."',  `id_strategic_project` = '".$id_project."',  `id_employee_model` = '".$id_employee_model."',  `last_update` = '".$date_data."',  `revision` = `revision` + 1,  `id_user_change` = '".$CURUSER['id']."', `vacancy` = 0  WHERE `id` ='".$data_emp['id']."';";*/

                    }

                }


            //отмечаем какие ИД мы уже использовали. те что нет, отмечаем в базе как старые
           // $array_used_esp[] = $data_esp['id'];
            //НЕ РАБОТАЕТ!!!!!!! ВЫКЛЮЧЕНО
            /*КОСЯК ОБНОВЛЕНИЯ ПРИВЯЗКИ СОТРУДНИКА*/
                $ss = 10;

                if((int)$data_esp['id'] >0 AND $ss = 50){

                    $update = 0;
                    $array_used_esp[] = $data_esp['id'];

                    $id_position = (int)array_search(trim($data['2']),$data_position);
                    $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);

                    unset($array_department);
                   // $array_department[] = (int)array_search(str_replace ("\"", "", $data['3']),$data_department);
                   /* $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']),$data_department);*/
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']."0"),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']."1"),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']."2"),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']."3"),$data_department);
                    $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']."4"),$data_department);
                    $id_department = "";
                    foreach ($array_department as $array) {
                        if($array < 1)
                            continue;

                        if($id_department)
                            $id_department .= ",";

                        $id_department .= $array;
                    }

                    $id_direction = (int)array_search(trim($data['10']),$data_direction);
                    $id_rck = (int)array_search(trim($data['22']),$data_rck);
                    $id_mvz = (int)array_search(trim($data['23']),$data_mvz);
                    $date_entry = unix_time ($data['29']);
                    $id_location_city = (int)array_search(trim($data['33']),$data_city);

                    $id_functional_manager = (int)array_search(trim($data['14']),$data_mgr);
                    $id_administrative_manager = (int)array_search(trim($data['17']),$data_mgr);

                    if ((int)$data['26'] == 1) {
                        $draft = 0;
                    } else {
                        $draft = 1;
                    }
                    if ($data['30'] == "Да") {
                        $transfer = 1;
                    } else {
                        $transfer = 0;
                    }

                    if($id_position != $data_esp['id_position']){
                        $update = 10;
                    }
                    if($id_block != $data_esp['id_block']){
                        $update = 11;
                    }
                    if($id_department != $data_esp['id_department']){
                        $update = 12;
                    }

                    if($id_direction != $data_esp['id_direction']){
                        $update = 13;
                    }
                    if($id_rck != $data_esp['id_rck']){
                        $update = 14;
                    }
                    if($id_mvz != $data_esp['id_mvz']){
                        $update = 15;
                    }
                    if($date_entry != $data_esp['date_entry']){
                        $update = 16;
                    }
                    if($id_location_city != $data_esp['id_location_city']){
                        $update = 17;
                    }

                    if($id_functional_manager != $data_esp['id_functional_manager']){
                        $update = 18;
                    }
                    if($id_administrative_manager != $data_esp['id_administrative_manager']){
                        $update = 19;
                    }

                    if($draft != $data_esp['draft']){
                        $update = 20;
                    }
                    if($transfer != $data_esp['transfer']){
                        $update = 21;
                    }


                    if($update !=0){
                        $last_id = 0;
                        sql_query("
INSERT INTO established_post (`uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`,`id_ep`)SELECT `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`,`id_ep`  FROM established_post WHERE established_post.id =  '".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                        $last_id = mysql_insert_id();
                        // обновляем записи сотрудников, с привязкой к новой ШЕ
                        sql_query("UPDATE employee SET `id_uid_post` = '".$last_id."' WHERE `id_uid_post` = '".$data_esp['id']."' AND current = 1;");
                        sql_query("UPDATE `established_post` SET  `id_position` = '".$id_position."', `id_block` = '".$id_block."', `id_department` = '".$id_department."', `id_direction` = '".$id_direction."', `id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `date_entry` = '".$date_entry."', `last_update` = '".$date_data."', `id_location_city` = '".$id_location_city."', `id_functional_manager` = '".$id_functional_manager."', `id_administrative_manager` = '".$id_administrative_manager."', `draft` = '".$draft."', `transfer` = '".$transfer."',`is_deleted` = '0', `revision` = `revision` + 1, `date_start` = '".$date_data."'  WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                        sql_query("UPDATE `established_post` SET `is_deleted` = 1,`last_update` = '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0', `date_end` = '".$date_data."'  WHERE `id` ='".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                     /*   sql_query("INSERT INTO revision_established_post (`id_established_post`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`) SELECT `id`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`  FROM established_post WHERE established_post.id =  '".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);
                        //обновляем
                        sql_query("UPDATE `established_post` SET `id_position` = '".$id_position."', `id_block` = '".$id_block."', `id_department` = '".$id_department."', `id_direction` = '".$id_direction."', `id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `date_entry` = '".$date_entry."', `last_update` = '".$date_data."', `id_location_city` = '".$id_location_city."', `id_functional_manager` = '".$id_functional_manager."', `id_administrative_manager` = '".$id_administrative_manager."', `draft` = '".$draft."', `transfer` = '".$transfer."',`is_deleted` = '0', `revision` = `revision` + 1 WHERE `id` ='".$data_esp['id']."';") or sqlerr(__FILE__, __LINE__);*/

                    }


                }


        }
        $array_not_used_esp = array_diff($array_all_esp,$array_used_esp);
        $array_not_used_emp = array_diff($array_all_emp,$array_used_emp);

        foreach($array_not_used_emp as $a){
            if((int)$a >0){
                $last_id = 0;

                sql_query("INSERT INTO employee(`name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`, `id_employee`) SELECT `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`,`id_employee` FROM employee WHERE employee.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
                $last_id = mysql_insert_id();
                sql_query("UPDATE `employee` SET `is_deleted` = 1,`revision` = `revision` +1,`last_update` = '".$date_data."',  `date_start` = '".$date_data."', `date_end` =  '".$date_data."', `id_user_change` = '".$CURUSER['id']."'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                //обновляем
                sql_query("UPDATE `employee` SET  `last_update` = '".$date_data."', `date_end` =  '".$date_data."',  `current` = '0'   WHERE `id` ='".$a."';") or sqlerr(__FILE__, __LINE__);

                /*
                sql_query("INSERT INTO revision_employee (`id_employee`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change`) SELECT `id`, `name_employee`, `id_uid_post`, `id_location_place`, `email`, `date_employment`, `date_transfer`, `id_functionality`, `added`, `last_update`, `fte`, `is_deleted`, `id_strategic_project`, `id_employee_model`, `id_parent_ee`, `revision`, `id_user_change` FROM employee WHERE employee.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
                sql_query("UPDATE employee SET is_deleted = 1, `revision` = `revision` + 1 WHERE id = '".$a."';");*/


            }
        }
      /*  sql_query($query) or sqlerr(__FILE__, __LINE__);
        unset($query);*/
        foreach($array_not_used_esp as $a){
            if((int)$a >0){
                $last_id = 0;
              //  sql_query("INSERT INTO established_post (`uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,`revision`,`id_ep`)SELECT `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`,`id_ep`  FROM established_post WHERE established_post.id =  '".$a."';") or sqlerr(__FILE__, __LINE__);
             //   $last_id = mysql_insert_id();
              //  sql_query("UPDATE `established_post` SET `is_deleted` = 1,`revision` = `revision` +1,`last_update` = '".$date_data."',  `date_start` = '".$date_data."', `date_end` =  '".$date_data."', `id_user_change` = '".$CURUSER['id']."', `current` = '0'   WHERE `id` ='".$last_id."';") or sqlerr(__FILE__, __LINE__);
                //обновляем
              //  sql_query("UPDATE `established_post` SET   `last_update` = '".$date_data."', `date_end` =  '".$date_data."', `current` = '0'    WHERE `id` = '".$a."';") or sqlerr(__FILE__, __LINE__);

                /*
                sql_query("INSERT INTO revision_established_post (`id_established_post`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`,
     `revision`) SELECT `id`, `uid_post`, `id_position`, `id_block`, `id_department`, `id_direction`, `id_rck`, `id_mvz`, `date_entry`, `added`, `last_update`, `id_location_city`, `id_functional_manager`, `id_administrative_manager`, `draft`, `transfer`, `is_deleted`, `id_parent_ep`, `revision`  FROM established_post WHERE established_post.id =  '".$a."'") or sqlerr(__FILE__, __LINE__);

                sql_query("UPDATE established_post SET is_deleted = 1, `revision` = `revision` + 1 WHERE id = '".$a."';");
*/
            }
        }
       // print $query;
       // sql_query($query) or sqlerr(__FILE__, __LINE__);

     //   die();
        $REL_TPL->stdmsg('Выполнено','Шаг 7 завершен. Данные обновлены (1/3)');
        $step = 7;
        safe_redirect("upload_sap.php?step=7&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }


    //обновлние почты
    if($_GET['step'] == '7') {

        $data_employee_rc = select_data_base("employee","name_employee");
        $used_id_functional_manager = array();
        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");



            $id_functional_manager = (int)array_search(trim($data['14']),$data_employee_rc);

            if((array_search($id_functional_manager,$used_id_functional_manager) === false) AND $id_functional_manager > 0){
                sql_query ("UPDATE `employee` SET `email` = '".$data['15']."' WHERE `id` = $id_functional_manager AND current = 1;") or sqlerr(__FILE__, __LINE__);
                $used_id_functional_manager[] = $id_functional_manager;
            }



        }



        $REL_TPL->stdmsg('Выполнено','Шаг 8 завершен. Данные обновлены (3/3). Не перезагружайте страницу.');
        $step = 8;
        safe_redirect("upload_sap.php?step=8&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }

//внештатники
    if($_GET['step'] == '8') {

        $res_max_id_ep = sql_query("SELECT MAX(id_ep)  FROM established_post") or sqlerr(__FILE__, __LINE__);
        $max_id_ep = mysql_fetch_row($res_max_id_ep)[0]+1;

        $res_max_id_emp = sql_query("SELECT MAX(id_employee)  FROM employee") or sqlerr(__FILE__, __LINE__);
        $max_id_emp = mysql_fetch_row($res_max_id_emp)[0] +1;

        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);
        while($row = mysql_fetch_array($res)){
            $data_place[$row['id']]=$row['floor'].",".$row['room'].",".$row['place'].",".$row['date_ready'];
        }

        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent != 0");
        $data_project = select_data_base("strategic_project","name_project");
        $data_model = select_data_base("employee_model","name_model");




        //получаем существующие UID POST к которым привязаны люди
        $res_emp=sql_query("SELECT employee.name_employee, established_post.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post WHERE established_post.`current` = 1 AND employee.`current` = 1  ;") or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){

            $data_emp[$row_emp['id']]=$row_emp['name_employee'];
        }


        $data_position = select_data_base("position","name_position");
        $data_block = select_data_base("block","name_block");
        //   $data_department = select_data_base("department","name_department");

        //ищем подразделения так, т.к. они вложенные
        $res_dep = sql_query("SELECT id, name_department, level FROM department") or sqlerr(__FILE__, __LINE__);
        while($row_dep = mysql_fetch_array($res_dep)){
            $data_department[$row_dep['id']]['level'] = $row_dep['level'];
            $data_department[$row_dep['id']]['name'] = $row_dep['name_department'];
        }

        $data_direction = select_data_base("direction","name_direction");
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_city = select_data_base("location_city","name_city");


        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
            $data = str_getcsv($mass[$i],";");

            if ((int)$data['0'] >0)
                continue;
            if(!trim($data['1']) OR strlen(trim($data['1'])) < 2)
                continue;

            $employee = trim($data['1']);
            $date_employment = unix_time($data['31']);

            $date_transfer = unix_time ($data['32']);
            if($data['28']){
                $fte = str_replace(",",".",$data['28']);
            }
            else{
                $fte = 0;
            }

            $id_location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
            $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
            $id_project = (int)array_search(trim($data['21']),$data_project);
            if($data_model)
                $id_model = (int)array_search(trim($data['24']),$data_model);
            $id_established_post = $max_id_ep;


                sql_query ("INSERT INTO `employee` (name_employee, id_uid_post, id_location_place, id_functionality, id_strategic_project, id_employee_model, date_employment,  date_transfer, fte, added, revision, id_employee, date_start,id_user_change) VALUES('".$employee."', '".$id_established_post."', '".$id_location_place."','".$id_functionality."','".$id_project."','".$id_model."','".$date_employment."','".$date_transfer."','".$fte."','".$date_data."', '1', '".$max_id_emp."', '".$date_data."', '".$CURUSER['id']."');") or sqlerr (__FILE__, __LINE__);




            $id_position = (int)array_search($data['2'],$data_position);
            $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);

            unset($array_department);


            $array_department[] = search_deaprtment_array($data_department,trim($data['4']), 0);
            $array_department[] = search_deaprtment_array($data_department,trim($data['6']), 1);
            $array_department[] = search_deaprtment_array($data_department,trim($data['7']), 2);
            $array_department[] = search_deaprtment_array($data_department,trim($data['8']), 3);
            $array_department[] = search_deaprtment_array($data_department,trim($data['9']), 4);

            $id_department = "";
            foreach ($array_department as $array) {
                if($array < 1)
                    continue;

                if($id_department)
                    $id_department .= ",";

                $id_department .= $array;
            }

            $id_direction = (int)array_search($data['10'],$data_direction);

            $id_rck = (int)array_search($data['22'],$data_rck);
            $id_mvz = (int)array_search($data['23'],$data_mvz);
            $id_city = (int)array_search($data['33'],$data_city);
            $id_functional_manager = (int)array_search(trim($data['14']),$data_emp);
            $id_administrative_manager = (int)array_search(trim($data['17']),$data_emp);
            $date_entry = unix_time ($data['29']);
            // смотрим драфт или нет. если нет, то ставим факт
            if ((int)$data['26'] == 1) {
                $draft = 0;
            } else {
                $draft = 1;
            }
            // передана ли ш.е.
            if ($data['30'] == "Да") {
                $transfer = 1;
            } else {
                $transfer = 0;
            }


        /*    sql_query ("UPDATE `established_post` SET `id_position` = '".$id_position."',`id_block` = '".$id_block."', `id_department` = '".$id_department."',`id_direction` = '".$id_direction."',`id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `id_location_city` = '".$id_city."', `id_administrative_manager` = '".$id_administrative_manager."', `id_functional_manager` = '".$id_functional_manager."', `date_entry` = '".$date_entry."', `draft` = '".$draft."', `transfer` = '".$transfer."', `revision` = '1', `added` = '".$date_data."'
                 WHERE `id` = $id_established_post;")  or sqlerr(__FILE__, __LINE__);
*/
            sql_query("INSERT INTO `established_post`
(uid_post,revision,added,date_start,id_ep,id_position,id_block,id_department,id_direction,id_rck,id_mvz,id_location_city,id_administrative_manager,id_functional_manager,date_entry,draft,transfer)
VALUES
('0', '1','".$date_data."','".$date_data."','".$id_established_post."','".$id_position."','".$id_block."','".$id_department."','".$id_direction."','".$id_rck."','".$id_mvz."','".$id_city."','".$id_administrative_manager."','".$id_functional_manager."','".$date_entry."','".$draft."','".$transfer."')") or sqlerr(__FILE__, __LINE__);


            $max_id_ep++;
            $max_id_ep++;
        }



        $REL_TPL->stdmsg('Выполнено','Шаг 9 завершен. Работа завершена.');
       // $step = 5;
//        safe_redirect("upload_sap.php?step=5&name=".$time."&date_data=".$date_data."",1);

        $REL_TPL->stdfoot();
        die();
    }

    $REL_TPL->output("upload_sap","upload");
    $REL_TPL->stdfoot();

    /*
         if($_GET['step'] == '4') {



            for ($i = $t; $i < count ($mass); $i++) {

                $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
                $data = str_getcsv($mass[$i],";");



            }



            $REL_TPL->stdmsg('Выполнено','Связывание новых данных справочник (3/3). Шаг 4 завершен. Не перезагружайте страницу');
            $step = 5;
    //        safe_redirect("upload_sap.php?step=5&name=".$time."&date_data=".$date_data."",1);

            $REL_TPL->stdfoot();
            die();
        }
     */