<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 08.08.2016
     * Time: 23:00
     */

    require_once ("include/connect.php");
    dbconn();
// подключаем библиотеку
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/classes/PHPExcel/PHPExcel.php';

    if($_GET['action']=="light"){

        //задаем какие данные будут в первой строчке
        $name_column = array('идентификатор штатной должности',	'ФИО полностью', 'Дирекция ЦО',	'ФИО ФР', 'ФИО АР','ЦО/РЦК/РП','Модель (функциональная/сервисная)', 'Штат?', 'драфт', 'факт','Город');
        //задаем  буквы для столбцов
        foreach (range('A', 'Z') as $character) {
            $letter[]=$character;
        }

        $res = sql_query("
SELECT established_post.uid_post, employee.name_employee, direction.name_direction, established_post.id_administrative_manager as idadm,
(SELECT employee.name_employee FROM established_post LEFT JOIN employee ON employee.id_uid_post = established_post.id WHERE established_post.id = idadm) as adm_mgr,
established_post.id_functional_manager as idfcm,
(SELECT employee.name_employee FROM established_post LEFT JOIN employee ON employee.id_uid_post = established_post.id WHERE established_post.id = idfcm) as func_mgr, rck.name_rck, employee_model.name_model, established_post.draft, location_city.name_city
FROM established_post
LEFT JOIN employee ON employee.id_uid_post = established_post.id
LEFT JOIN direction ON direction.id = established_post.id_direction
LEFT JOIN rck ON rck.id = established_post.id_rck
LEFT JOIN employee_model ON employee_model.id = employee.id_employee_model
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
");


// Создаем как объект PHPExcel
        $objPHPExcel = new PHPExcel();

// свойства документа
        $objPHPExcel->getProperties()->setCreator("Портал Реестр")
           // ->setLastModifiedBy("Портал Реестр")
            ->setTitle("Краткая выгрузка реестра")
            //->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Автоматически сгенерированный список сотрудников с портала Реестр");
          //  ->setKeywords("office 2007 openxml php")
            //->setCategory("Test result file");


// добавляем данные
        /*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
*/
        foreach($name_column as $key => $name){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[$key]."1", $name);
        }
$i=2;
        while ($row = mysql_fetch_array($res)) {
            if( $row['uid_post'] >0)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[0].$i, $row['uid_post']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[1].$i, $row['name_employee']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[2].$i, $row['name_direction']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[3].$i, $row['func_mgr']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[4].$i, $row['adm_mgr']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[5].$i, $row['name_rck']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[6].$i, $row['name_model']);
            if($row['uid_post'])
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[7].$i, 'Да');
            if(!$row['draft'])
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[8].$i, 'Да');
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[9].$i, 'Да');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[10].$i, $row['name_city']);
     $i++;
        }

// Miscellaneous glyphs, UTF-8
       /* $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/

// Даем имя личту
        $objPHPExcel->getActiveSheet()->setTitle('Реестр');


// делаем активным первый лист,
        $objPHPExcel->setActiveSheetIndex(0);


// выводим для клиента как Excel2007
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="light_register.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;




    }