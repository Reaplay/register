<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 9:51
 */

require_once ("include/connect.php");

dbconn();
    $REL_TPL->stdhead("Загрузка первых данных");

    //функция обработки для простых данных где только название и время добавляется
    function processing_data($data,$id=''){
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
    }
    //функция для выбора данных и засовывания их в массив
    function select_data_base($table,$column,$where=''){
        $res=sql_query("SELECT id, $column FROM $table $where;")  or sqlerr(__FILE__, __LINE__);

         while($row = mysql_fetch_array($res)){

             $data[$row['id']]=$row[$column];
         }
             return $data;
    }

    //убираем дубли в многомерном массиве
    function unique_multidim_array($array, $key_a='',$key_b='', $key_c='', $key_d='') {
        $temp_array = array();
        $i = 0;
        $key_array_a = array();
        $key_array_b = array();
        $key_array_c = array();
        $key_array_d = array();

        foreach($array as $val) {

            if (!in_array($val[$key_a], $key_array_a)) {
                $key_array_a[$i] = $val[$key_a];
                $key_array_b[$i] = $val[$key_b];
                $key_array_c[$i] = $val[$key_c];
                $key_array_d[$i] = $val[$key_d];
                $temp_array[$i] = $val;
            }
            elseif(!in_array($val[$key_b], $key_array_b)){
                $key_array_b[$i] = $val[$key_b];
                $key_array_c[$i] = $val[$key_c];
                $key_array_d[$i] = $val[$key_d];
                $temp_array[$i] = $val;
            }
            elseif(!in_array($val[$key_c], $key_array_c)){

                $key_array_c[$i] = $val[$key_c];
                $key_array_d[$i] = $val[$key_d];
                $temp_array[$i] = $val;
            }
            elseif(!in_array($val[$key_d], $key_array_d)){
                $key_array_d[$i] = $val[$key_d];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    // с какой строчки начинаем
    $t = 2;

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
                move_uploaded_file ($_FILES["attachment"]["tmp_name"], "upload/upload_reestr_" . $time . ".csv");
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
        $mass = file ("upload/upload_reestr_" . $time . ".csv");
        // первую строчку пропускаем, т.к. там просто инфа



    }
    if($_GET['step'] > 1 OR $_POST['name'] > 1){
        if($_GET['name'])
            $time = $_GET['name'];
        elseif($_POST['name'])
            $time = $_POST['name'];
        else
            stderr("Ошибка","Некорректные данные");
        $mass = file ("upload/upload_reestr_" . $time . ".csv");

    }
//добавляем основные данные
    if($_POST['step'] == '1') {

        sql_query("TRUNCATE `block`;");
        sql_query("TRUNCATE `department`;");
        sql_query("TRUNCATE `direction`;");
        sql_query("TRUNCATE `employee`;");
        sql_query("TRUNCATE `employee_model`;");
        sql_query("TRUNCATE `established_post`;");
        sql_query("TRUNCATE `functionality`;");
        sql_query("TRUNCATE `location_address`;");
        sql_query("TRUNCATE `location_city`;");
        sql_query("TRUNCATE `location_place`;");
        sql_query("TRUNCATE `mvz`;");
        sql_query("TRUNCATE `position`;");
        sql_query("TRUNCATE `rck`;");
        sql_query("TRUNCATE `strategic_project`;");
        sql_query("TRUNCATE `type_office`;");

        //сначала формируем список основных данных, которые загонять будем в базу
        for ($i = $t; $i < count ($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");

           // $data = explode (";", $mass[$i]);
            $data = str_getcsv($mass[$i],";");

            /*ОБРАБАТЫВАЕМ ШТАТНЫЕ ДОЛЖНОСТИ*/
            // идентификатор штатной должности
            $uid_post = (int)$data['0'];
          //  if ($uid_post > 0) {
                //дата ввода
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
                if($data['28']){
                    $fte = str_replace(",",".",$data['28']);

                }
                else{
                    $fte = 0;
                }

        //    }

            /*ОБРАБАТЫВАЕМ СОТРУДНИКОВ*/
            //ФИО
            $employee = $data['1'];
            // дата приема и перевода
            $date_employment = unix_time ($data['31']);
            $date_transfer = unix_time ($data['32']);

            /*ОБРАБАТЫВАЕМ ПОЗИЦИИ/НАЗВАНИЕ ДОЛЖНОСТЕЙ*/
            $array_postion[] = trim ($data['2']);

            /*ОБРАБАТЫВАЕМ ПОДРАЗДЕЛЕНИЯ И ТИПЫ ОФИСОВ*/
            $array_block[] = $data['3'];
            $array_type_office[] = $data['5'];
            $array_department[][0] = $data['4'];
            $array_department[][1] = $data['6'];
            $array_department[][2] = $data['7'];
            $array_department[][3] = $data['8'];
            $array_department[][4] = $data['9'];
            /*ОБРАБАТЫВАЕМ МОДЕЛЬ*/
            $array_model[] = $data['24'];
            /*ОБРАБАТЫВАЕМ РЦК*/
            $array_rck[] = $data['22'];
            /*ОБРАБАТЫВАЕМ МВЗ*/
            $array_mvz[$i] = $data['23'];
            /*ОБРАБАТЫВАЕМ НАЗВАНИЯ ГОРОДОВ*/
            $array_city[] = $data['33'];
            /*ОБРАБАТЫВАЕМ АДРЕСА*/
            $array_address[] = $data['34'];
            /*ОБРАБАТЫВАЕМ МЕСТА*/
            $array_place[$i]['floor'] = $data['35'];
            $array_place[$i]['room'] = $data['36'];
            $array_place[$i]['place'] = $data['37'];
            $array_place[$i]['ready'] = $data['38'];
            $array_place[$i]['date_ready'] = $data['39'];
            $array_place[$i]['reservation'] = $data['40'];
            $array_place[$i]['date_reservation'] = $data['41'];
            $array_place[$i]['occupy'] = $data['42'];
            $array_place[$i]['busy'] = $data['43'];
            $array_place[$i]['date_occupy'] = $data['44'];

            /*ОБРАБАТЫВАЕМ ДИРЕКЦИИ*/
            $array_direction[] = $data['10'];
            /*ОБРАБАТЫВАЕМ ФУНКЦИОНАЛ*/
            $array_functionality_group[] = $data['19'];
            $array_functionality_function[] = $data['20'];
            /*ОБРАБАТЫВАЕМ СТРАТЕГИЧЕСКИЕ ПРОЕКТЫ*/
            $array_project[] = $data['21'];

            //пропускаем первую строчку
            if ($i != $t) {
                if ($uid_post > 0) {
                    $insert_established_post .= ", ";
                    $insert_employee .= ", ";
                }
               // $insert_employee .= ", ";

            }
            if($insert_employee_no_ep AND $uid_post < 1){
                $insert_employee_no_ep .= ", ";
            }

            if ($uid_post > 0) {
                $insert_established_post .= "($uid_post, $date_entry, $draft, $transfer, " . time () . ")";
                $insert_employee .= "('" . $employee . "', '$date_employment' ,'$date_transfer',  $fte, " . time () . ")";
            }
            else{
                sql_query("INSERT INTO established_post (uid_post, date_entry, draft, transfer, added) VALUES ($uid_post, $date_entry, $draft, $transfer, " . time () . "); ") or sqlerr(__FILE__, __LINE__);
                $id_ep = mysql_insert_id();
                $insert_employee_no_ep .= "('" . $employee . "','".$id_ep."', '$date_employment' ,'$date_transfer',  $fte, " . time () . ")";
            }
            //$insert_employee .= "('" . $employee . "', '$date_employment' ,'$date_transfer',  $fte, " . time () . ")";


        }

        /*ОБРАБАТЫВАЕМ ПОЗИЦИИ/НАЗВАНИЕ ДОЛЖНОСТЕЙ*/
        $insert_position = processing_data ($array_postion);
        /*ОБРАБАТЫВАЕМ ПОДРАЗДЕЛЕНИЯ*/
        $insert_block = processing_data ($array_block);
        $date = time();
        foreach ($array_department as $sub_array_department) {
             foreach($sub_array_department as $key => $value){
                if(!$value)
                    continue;
                 $value = str_replace ("\"\"", "'", $value);
                 $value = str_replace ("\"", "", $value);
                 $value = str_replace ("'", "\"", $value);
                $sub_sub_array_department[] = "('".$key."', '".$value."', '".$date."')";
            }

        }
        $data_department = array_unique ($sub_sub_array_department);

        foreach ($data_department as $sub_data_department) {
            if($insert_department)
                $insert_department .= ", ";
            $insert_department .= $sub_data_department;
        }

        /*ОБРАБАТЫВАЕМ ТИПЫ ОФИСОВ*/
        $insert_type_office = processing_data ($array_type_office);
        /*  $type_office = array_unique($array_type_office);
          foreach($type_office as $array){
              if(!trim($array))
                  continue;

              if($insert_type_office){
                  $insert_type_office .=", ";
              }

              $insert_type_office .= "('$array',".time().")";

          }
      */
        /*ОБРАБАТЫВАЕМ МОДЕЛЬ*/
        $insert_model = processing_data ($array_model);
        /*ОБРАБАТЫВАЕМ РЦК*/
        $insert_rck = processing_data ($array_rck);
        /*ОБРАБАТЫВАЕМ МВЗ*/
        $insert_mvz = processing_data ($array_mvz);
        /*ОБРАБАТЫВАЕМ НАЗВАНИЯ ГОРОДОВ*/
        $insert_city = processing_data ($array_city);
        /*ОБРАБАТЫВАЕМ АДРЕСА*/
        $insert_address = processing_data ($array_address);
        /*ОБРАБАТЫВАЕМ МЕСТА*/

       $array_place = unique_multidim_array($array_place,'floor','room','place','date_ready');

        foreach($array_place as $place){
            $data_place['floor'] = (int)$place['floor'];
            $data_place['room'] = $place['room'];
            $data_place['place'] = $place['place'];
            $data_place['date_ready'] = unix_time($place['date_ready']);
            $data_place['date_reservation'] = unix_time($place['date_reservation']);
            $data_place['date_occupy'] = unix_time($place['date_occupy']);

            if($place['ready'] == "Да"){
                $data_place['ready'] = 1;
            }
            else{
                $data_place['ready'] = 0;
            }

            if($place['reservation'] == "Да"){
                $data_place['reservation'] = 1;
            }
            else{
                $data_place['reservation'] = 0;
            }


            if($place['busy'] == "Да"){
                $data_place['occupy'] = 1;
            }
            else{
                $data_place['occupy'] = 0;
            }



            if ($insert_place) {
                $insert_place .= ", ";
            }
            $insert_place .= "('". $data_place['floor']."','".$data_place['room']."','".$data_place['place']."','".$data_place['ready']."','".$data_place['date_ready']."','".$data_place['reservation']."','".$data_place['date_reservation']."','". $data_place['occupy']."','". $data_place['date_occupy']."','".time()."')";
        }


        /*ОБРАБАТЫВАЕМ ДИРЕКЦИИ*/
        $insert_direction = processing_data ($array_direction);
        /*ОБРАБАТЫВАЕМ ФУНКЦИОНАЛ*/
        $insert_functionality_group = processing_data($array_functionality_group);
        $insert_functionality_function = processing_data($array_functionality_function, 1);
        /*ОБРАБАТЫВАЕМ СТРАТЕГИЧЕСКИЕ ПРОЕКТЫ*/
        $insert_project = processing_data ($array_project);

        sql_query("INSERT INTO established_post (uid_post, date_entry, draft, transfer, added) VALUES ".$insert_established_post."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO employee (name_employee, date_employment, date_transfer, fte, added) VALUES ".$insert_employee."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO employee (name_employee, id_uid_post, date_employment, date_transfer, fte, added) VALUES ".$insert_employee_no_ep."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO employee_model (name_model,added) VALUES ".$insert_model."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO position (name_position, added) VALUES ".$insert_position."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO block (name_block, added) VALUES ".$insert_block."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO department (level, name_department, added) VALUES ".$insert_department."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO direction (name_direction, added) VALUES ".$insert_direction."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO type_office (`name_office`, added) VALUES ".$insert_type_office."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO rck (`name_rck`, added) VALUES ".$insert_rck."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO mvz (`name_mvz`, added) VALUES ".$insert_mvz."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO location_city (`name_city`, added) VALUES ".$insert_city."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO location_address (`name_address`, added) VALUES ".$insert_address."; ") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO location_place (floor, room, place, ready, date_ready, reservation, date_reservation, occupy, date_occupy, added) VALUES ".$insert_place.";") or sqlerr(__FILE__, __LINE__);

        sql_query("INSERT INTO functionality (`name_functionality`, added) VALUES ".$insert_functionality_group.";") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO functionality (`name_functionality`, added, id_parent) VALUES ".$insert_functionality_function.";") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT INTO strategic_project (`name_project`, added) VALUES ".$insert_project.";") or sqlerr(__FILE__, __LINE__);

        $REL_TPL->stdmsg('Выполнено','Добавление данных завершено. Шаг 1 завершен. Происходит создание связей (1/3). Не перезагружайте страницу');
        $step = 2;
        safe_redirect("upload.php?step=2&name=".$time."",1);

        $REL_TPL->stdfoot();
        die();
    }
//связываем данные
    if($_GET['step'] == '2'){
        // получаем нужные данные из базы ввиде массива
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_employee = select_data_base("employee","name_employee");
        $data_established_post = select_data_base("established_post","uid_post");
        $data_department = select_data_base("department","name_department");
        $data_block = select_data_base("block","name_block");
        $data_direction = select_data_base("direction","name_direction");
        $data_position = select_data_base("position","name_position");
        $data_city = select_data_base("location_city","name_city");
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent = 1");
        $data_type_office = select_data_base("type_office","name_office");

        $data_address = select_data_base("location_address","name_address");


        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);

        while($row = mysql_fetch_array($res)){

            $data_place[$row['id']]=$row['floor'].",".$row['room'].",".$row['place'].",".$row['date_ready'];

        }


        //обозначаем массивы для использования
        $used_mvz= array();
        $used_established_post = array();
        $used_employee = array();
        $used_id_functional_manager = array();
        $used_functionality = array();
        $used_address = array();
        $used_id_office = array();

        for ($i = $t; $i < count($mass); $i++) {



            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
           // $data = explode (";", $mass[$i]);
            $data = str_getcsv($mass[$i],";");

            // ищем в массивах соответствия имени и ИД
            $id_rck = (int)array_search($data['22'],$data_rck);
            $id_mvz = (int)array_search($data['23'],$data_mvz);
            $id_employee = (int)array_search($data['1'],$data_employee);
            $id_established_post = (int)array_search($data['0'],$data_established_post);
            // ищем вложенные подразделения
            $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);
            unset($array_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['3']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['4']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['6']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['7']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['8']),$data_department);
            $array_department[] = (int)array_search(str_replace ("\"", "", $data['9']),$data_department);
            $id_department = "";
            foreach ($array_department as $array) {
                if($array < 1)
                    continue;

                if($id_department)
                    $id_department .= ",";

                $id_department .= $array;
            }

            // обрабатываем ДО/ОО
           $id_type_office = (int)array_search($data['5'],$data_type_office);
           $id_office =  (int)array_search(str_replace ("\"", "", $data['6']),$data_department);

          /*  if(trim($data['9'])){
                $id_department = (int)array_search($data['9'],$data_department);
            }
            elseif(trim($data['8'])){
                $id_department = (int)array_search($data['8'],$data_department);
            }
            elseif(trim($data['7'])){
                $id_department = (int)array_search($data['7'],$data_department);
            }
            elseif(trim($data['6'])){
                $id_department = (int)array_search($data['6'],$data_department);
            }
            else{
                $id_department = '0';
            }
*/
            $id_direction = (int)array_search($data['10'],$data_direction);
            $id_position = (int)array_search($data['2'],$data_position);
            $id_city = (int)array_search($data['33'],$data_city);
            $id_functional_manager = (int)array_search(trim($data['14']),$data_employee);
           // $id_administrative_manager = (int)array_search(trim($data['17']),$data_employee);


            $id_address = (int)array_search(trim($data['34']),$data_address);
            $id_location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
           /* $id_ = (int)array_search($data[''],$data_);
            $id_ = (int)array_search($data[''],$data_);
            */
            /*ОБРАБАТЫВАЕМ МВЗ*/
            // если нашли и все они определены как надо
            if($id_rck !=0 AND $id_mvz !=0 AND (array_search($id_mvz,$used_mvz) === false)){
                sql_query ("UPDATE `mvz` SET `id_rck` = '".$id_rck."' WHERE `id` = $id_mvz;") or sqlerr(__FILE__, __LINE__);
                //добавляем в массив, что бы исключить из следующего обновления
                $used_mvz[] = $id_mvz;

            }

            /*ОБРАБАТЫВАЕМ ШТАТНЫЕ ДОЛЖНОСТИ*/
            if(array_search($id_established_post,$used_established_post) === false){

                sql_query ("UPDATE `established_post` SET `id_position` = '".$id_position."',`id_block` = '".$id_block."', `id_department` = '".$id_department."',`id_direction` = '".$id_direction."',`id_rck` = '".$id_rck."', `id_mvz` = '".$id_mvz."', `id_location_city` = '".$id_city."' WHERE `id` = $id_established_post;");
                $used_established_post[] = $id_established_post;
            }

            /*ОБРАБАТЫВАЕМ СОТРУДНИКОВ*/
            if(array_search($id_employee,$used_employee) === false){
                if((int)$data['0'] >0){
                sql_query ("UPDATE `employee` SET `id_uid_post` = '".$id_established_post."', `id_location_place` = '".$id_location_place."' WHERE `id` = $id_employee;") or sqlerr(__FILE__, __LINE__);
                }
                else {

                    sql_query ("UPDATE `employee` SET `id_functionality` = '".$id_functionality."',`id_location_place` = '".$id_location_place."' WHERE `id` = $id_employee;") or sqlerr(__FILE__, __LINE__);
                }
                $used_employee[] = $id_employee;
            }
            /*ОБРАБАТЫВАЕМ ИМЕЮЩИЕСЯ E-MAIL*/
            if((array_search($id_functional_manager,$used_id_functional_manager) === false) AND $id_functional_manager > 0){
                sql_query ("UPDATE `employee` SET `email` = '".$data['15']."' WHERE `id` = $id_functional_manager;") or sqlerr(__FILE__, __LINE__);
                $used_id_functional_manager[] = $id_functional_manager;
            }
            /*ОБРАБАТЫВАЕМ ТИПЫ ОФИСОВ*/
            if((array_search($id_office,$used_id_office) === false) AND $id_type_office > 0){
                sql_query ("UPDATE `department` SET `id_type_office` = '".$id_type_office."' WHERE `id` = $id_office;") or sqlerr(__FILE__, __LINE__);
                $used_id_office[] = $id_office;
            }

    }



        $REL_TPL->stdmsg('Выполнено','Добавление данных завершено. Шаг 2 завершен. Происходит создание связей (2/3). Не перезагружайте страницу');
        $step = 3;
        safe_redirect("upload.php?step=3&name=".$time."",1);

        $REL_TPL->assignByRef('step',$step);
        $REL_TPL->assignByRef('file',$time);
        $REL_TPL->output("upload","upload");
        $REL_TPL->stdfoot();
        die();


    }

    if($_GET['step'] == '3'){
        // получаем нужные данные из базы ввиде массива


        $data_established_post = select_data_base("established_post","uid_post");
        $data_city = select_data_base("location_city","name_city");
        $data_address = select_data_base("location_address","name_address");
        $data_direction = select_data_base("direction","name_direction");
        $data_block = select_data_base("block","name_block");
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent = 1");
        $data_functionality_group = select_data_base("functionality","name_functionality","WHERE id_parent = 0");
        $data_employee = select_data_base("employee","name_employee");
        $data_rck = select_data_base("rck","name_rck");
        $data_mvz = select_data_base("mvz","name_mvz");
        $data_department = select_data_base("department","name_department");
        $data_direction = select_data_base("direction","name_direction");
        $data_position = select_data_base("position","name_position");
        $data_city = select_data_base("location_city","name_city");

        $res=sql_query("SELECT id, floor, room, place, date_ready FROM location_place;")  or sqlerr(__FILE__, __LINE__);

        while($row = mysql_fetch_array($res)){

            $data_place[$row['id']]=$row['floor'].",".$row['room'].",".$row['place'].",".$row['date_ready'];

        }


        //обозначаем массивы для использования
        $used_established_post = array();
        $used_employee = array();
        $used_id_functional_manager = array();
        $used_functionality = array();
        $used_address = array();
        $used_direction = array();

        //получаем существующие UID POST к которым привязаны люди
        $res_emp=sql_query("SELECT employee.name_employee, established_post.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post;") or sqlerr(__FILE__, __LINE__);

        while($row_emp = mysql_fetch_array($res_emp)){

            $data_emp[$row_emp['id']]=$row_emp['name_employee'];
        }


        for ($i = $t; $i < count($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
          //  $data = explode (";", $mass[$i]);
            $data = "";
            $data = str_getcsv($mass[$i],";");

            $id_functional_manager = (int)array_search(trim($data['14']),$data_emp);
            $id_administrative_manager = (int)array_search(trim($data['17']),$data_emp);
            $id_city = (int)array_search($data['33'],$data_city);
            $id_address = (int)array_search(trim($data['34']),$data_address);
            $id_location_place = (int)array_search(trim("".$data['35'].",".$data['36'].",".$data['37'].",".unix_time($data['39']).""),$data_place);
          //  $id_employee = array_search(stristr($data['11'], ' ',true),$data_emp);

            $id_direction = (int)array_search(trim($data['10']),$data_direction);
            $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
            $id_functionality_group = (int)array_search(trim($data['19']),$data_functionality_group);
           // $id_employee_direction =0;
            $id_employee = 0;
            foreach($data_employee as $key => $value){
                if(stristr (trim($data['11']),' ',true) === stristr ($value,' ',true) AND strlen(trim($data['11'])) >3 )
                    $id_employee = $key;


            }

            if((int)$data['0'] >0){
                $id_established_post = (int)array_search($data['0'],$data_established_post);
                sql_query ("UPDATE `established_post` SET `id_administrative_manager` = '".$id_administrative_manager."', `id_functional_manager` = '".$id_functional_manager."' WHERE `id` = $id_established_post;") or sqlerr(__FILE__, __LINE__);
            }
            else{

                $id_ep = (int)array_search (trim ($data['1']), $data_emp);
                $id_mvz = (int)array_search($data['23'],$data_mvz);
                $id_rck = (int)array_search(trim($data['22']),$data_rck);
                // ищем вложенные подразделения
                //очищаем массив
                $id_block = (int)array_search(str_replace("\"","",$data['3']),$data_block);
                unset($array_department);
                $array_department[] = (int)array_search($data['3'],$data_department);
                $array_department[] = (int)array_search($data['4'],$data_department);
                $array_department[] = (int)array_search($data['6'],$data_department);
                $array_department[] = (int)array_search($data['7'],$data_department);
                $array_department[] = (int)array_search($data['8'],$data_department);
                $array_department[] = (int)array_search($data['9'],$data_department);
                $id_department = "";
                foreach ($array_department as $array) {
                    if($array < 1)
                        continue;

                    if($id_department)
                        $id_department .= ",";

                    $id_department .= $array;
                }

             /*   if(trim($data['9'])){
                    $id_department = (int)array_search($data['9'],$data_department);
                }
                elseif(trim($data['8'])){
                    $id_department = (int)array_search($data['8'],$data_department);
                }
                elseif(trim($data['7'])){
                    $id_department = (int)array_search($data['7'],$data_department);
                }
                elseif(trim($data['6'])){
                    $id_department = (int)array_search($data['6'],$data_department);
                }
                else{
                    $id_department = '0';
                }
                if((int)array_search($data['3'],$data_department) >0) {
                    $mass_department =(int)array_search($data['3'],$data_department);
                }*/


                $id_direction = (int)array_search($data['10'],$data_direction);
                $id_position = (int)array_search($data['2'],$data_position);
                $id_city = (int)array_search($data['33'],$data_city);


               /* if($data['1'] == "Худенко Семён Владимирович")
                {print  $id_ep = (int)array_search (trim ($data['1']), $data_emp);
                   // print_r($data_emp);
                    $id_ep = (int)array_search (trim ($data['1']), $data_emp);
                    print "UPDATE `established_post` SET `id_administrative_manager` = '".$id_administrative_manager."', `id_functional_manager` = '".$id_functional_manager."' WHERE `id` =$id_ep;";
                    die();
                }*/
                //UPDATE `established_post` SET `id_position` = '".$id_position."',`id_department` = '".$id_department."',`id_direction` = '".$id_direction."',`id_mvz` = '".$id_mvz."', `id_location_city` = '".$id_city."


                sql_query ("UPDATE `established_post` SET `id_administrative_manager` = '".$id_administrative_manager."', `id_functional_manager` = '".$id_functional_manager."', `id_position` = '".$id_position."',`id_block` = '".$id_block."', `id_department` = '".$id_department."',`id_direction` = '".$id_direction."',`id_rck` = '".$id_rck."',`id_mvz` = '".$id_mvz."', `id_location_city` = '".$id_city."' WHERE `id` =$id_ep;") or sqlerr(__FILE__, __LINE__);

            }

            /*ОБРАБАТЫВАЕМ АДРЕСА*/
            if((array_search($id_address,$used_address) === false) AND $id_address > 0){
                sql_query ("UPDATE `location_address` SET `id_city` = '".$id_city."' WHERE `id` = $id_address;") or sqlerr(__FILE__, __LINE__);
                $used_address[] = $id_city;
            }

            /*ОБРАБАТЫВАЕМ ПОДРАЗДЕЛЕНИЯ*/
         /*   if((array_search($id_address,$used_address) === false) AND $id_address > 0){
                sql_query ("UPDATE `location_address` SET `id_city` = '".$id_city."' WHERE `id` = $id_address;") or sqlerr(__FILE__, __LINE__);
                $used_address[] = $id_city;
            }*/

            /*ОБРАБАТЫВАЕМ МЕСТА*/

            if( $id_address > 0 AND $id_location_place){
                sql_query ("UPDATE `location_place` SET `id_address` = '".$id_address."' WHERE `id` = $id_location_place;") or sqlerr(__FILE__, __LINE__);
            }

            /*ОБРАБАТЫВАЕМ ДИРЕКЦИИ*/
            if((array_search($id_direction,$used_direction) === false) AND $id_direction > 0){
                sql_query ("UPDATE `direction` SET `id_employee` = '".$id_employee."' WHERE `id` = $id_direction;") or sqlerr(__FILE__, __LINE__);
                $used_direction[] = $id_direction;
            }


            /*ОБРАБАТЫВАЕМ ФУНКЦИОНАЛ*/

            if((array_search($id_functionality,$used_functionality) === false) AND $id_functionality > 0){
                sql_query ("UPDATE `functionality` SET `id_parent` = '".$id_functionality_group."' WHERE `id` = $id_functionality;") or sqlerr(__FILE__, __LINE__);
                $used_functionality[] = $id_functionality;
            }
          //  print "UPDATE `functionality` SET `id_parent` = '".$id_functionality_group."' WHERE `id` = $id_functionality;";


        }

        $REL_TPL->stdmsg('Выполнено','Добавление данных завершено. Шаг 3 завершен. Происходит создание связей (3/3). Не перезагружайте страницу');
        $step = 4;
        safe_redirect("upload.php?step=4&name=".$time."",1);

        $REL_TPL->assignByRef('step',$step);
        $REL_TPL->assignByRef('file',$time);
        $REL_TPL->output("upload","upload");
        $REL_TPL->stdfoot();
        die();
    }

    if($_GET['step'] == '4'){

        // получаем нужные данные из базы ввиде массива
        $data_functionality = select_data_base("functionality","name_functionality","WHERE id_parent != 0");
        $data_employee = select_data_base("employee","name_employee");
        $data_project = select_data_base("strategic_project","name_project");
        $data_model = select_data_base("employee_model","name_model");

        $used_employee = array();
        $used_functionality = array();


        for ($i = $t; $i < count($mass); $i++) {

            $mass[$i] = mb_convert_encoding ($mass[$i], 'utf-8', "cp1251");
          //  $data = explode (";", $mass[$i]);
            $data = str_getcsv($mass[$i],";");

            $id_employee = (int)array_search(trim($data['1']),$data_employee);

            $id_functionality = (int)array_search(trim($data['20']),$data_functionality);
            $id_project = (int)array_search(trim($data['21']),$data_project);
            $id_model = (int)array_search(trim($data['24']),$data_model);

            $update = "";
            if($id_functionality >0){
                $update = "`id_functionality` = '" . $id_functionality . "'";
            }
            if($id_project > 0){
                if($update){
                    $update .=", ";
                }
                $update .= "`id_strategic_poject` = '".$id_project."'";

            }
            if($id_model > 0){
                if($update){
                    $update .=", ";
                }
                $update .= "`id_employee_model` = '".$id_model."'";

            }
            if($update) {
                sql_query ("UPDATE `employee` SET $update WHERE `id` = $id_employee;") or sqlerr (__FILE__, __LINE__);
            }
        }
        $REL_TPL->stdmsg('Выполнено','Добавление данных завершено.');

        $REL_TPL->output("upload","upload");
        $REL_TPL->stdfoot();
        die();
    }







$REL_TPL->output("upload","upload");
$REL_TPL->stdfoot();