<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 07.06.2016
     * Time: 22:15
     */

    require_once ("include/connect.php");

    dbconn();

    function multi_implode($sep, $array){
        foreach($array as $val){
            if(is_array($val))
                $_array[] = multi_implode($sep, $val);
            else
                $_array[] = '"'.$val.'"';
        }

        return implode($sep, $_array);
    }

$res = sql_query("
SELECT COUNT(*) as `count` , location_city.name_city
FROM established_post
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
GROUP BY location_city.name_city

");
    while ($row = mysql_fetch_array($res)){
        if(!$row['name_city']){
            $data['name_city'][] = "Неизвестно";
        }
        else {
            $data['name_city'][] = $row['name_city'];
        }
        $data['count'][] = $row['count'];
    }


    $name_city = multi_implode(',',$data['name_city']);
    $count = multi_implode(',',$data['count']);

    $res = sql_query("SELECT COUNT(*) FROM established_post WHERE uid_post = '0'");
    $count_no_uid_post = mysql_fetch_array($res);
    $res = sql_query("SELECT COUNT(*) FROM established_post WHERE uid_post != '0'");
    $count_uid_post = mysql_fetch_array($res);

        //$res = sql_query("SELECT SUM(1) as num, rck.name_rck FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post LEFT JOIN rck ON rck.id = established_post.id_rck WHERE established_post.uid_post !='0' GROUP BY rck.name_rck  ORDER BY rck.name_rck ");
    $res = sql_query("SELECT SUM(1) as num, rck.name_rck FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post LEFT JOIN rck ON rck.id = established_post.id_rck WHERE  employee.is_deleted = 0 GROUP BY rck.name_rck  ORDER BY rck.name_rck ");


   // $res = sql_query("SELECT SUM(1) as num, rck.name_rck,established_post.uid_post  FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post LEFT JOIN mvz ON mvz.id = established_post.id_mvz LEFT JOIN rck ON rck.id = mvz.id_rck GROUP BY rck.name_rck,established_post.uid_post");
    while ($row = mysql_fetch_array($res)) {
$data_num .= '"'.$row['num'].'",';
    if(!$row['name_rck'])
        $data_name .= '"N/A",';
    else
        $data_name .= '"'.$row['name_rck'].'",';

    }
    $data_color =  '"#F7464A","#46BFBD","#FDB45C","#949FB1","#4D5360","#FFFFFF",';

    $res=sql_query("SELECT SUM(1) as num, rck.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post LEFT JOIN rck ON rck.id = established_post.id_rck WHERE established_post.uid_post !=0 AND employee.is_deleted = 0 GROUP BY rck.name_rck  ORDER BY rck.name_rck");
    while ($row = mysql_fetch_array($res)) {
        $data_es[$row['id']]['id']= $row['id'];
        $data_es[$row['id']]['name']= $row['num'];
   }
    $res=sql_query("SELECT SUM(1) as num, rck.id FROM employee LEFT JOIN established_post ON established_post.id = employee.id_uid_post LEFT JOIN rck ON rck.id = established_post.id_rck WHERE established_post.uid_post =0 AND employee.is_deleted = 0  GROUP BY rck.name_rck  ORDER BY rck.name_rck");
    while ($row = mysql_fetch_array($res)) {
        $data_no_es[$row['id']]['id']= $row['id'];
        $data_no_es[$row['id']]['name']= $row['num'];
    }
    $res=sql_query("SELECT id, name_rck FROM rck WHERE is_deleted =0");
    while ($row = mysql_fetch_array($res)) {
        $data_rck[$row['id']]['id'] = $row['id'];
        $data_rck[$row['id']]['name']= $row['name_rck'];
    }

$add_js = "
<script type=\"text/javascript\">

loadScript(plugin_path + 'chart.chartjs/Chart.min.js', function() {

    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                   $data_num
                ],
                backgroundColor: [
                   $data_color
                ],
            }],
            labels: [
                $data_name
            ]
        },
        options: {
            responsive: true,
            display: true
        }
    };
    window.onload = function() {
        var ctx = document.getElementById(\"chart-area\").getContext(\"2d\");
        window.myPie = new Chart(ctx, config);
    };
    $('#randomizeData').click(function() {
        $.each(config.data.datasets, function(i, piece) {
            $.each(piece.data, function(j, value) {
                config.data.datasets[i].data[j] = randomScalingFactor();
                config.data.datasets[i].backgroundColor[j] = randomColor(0.7);
            });
        });
        window.myPie.update();
    });
    $('#addDataset').click(function() {
        var newDataset = {
            backgroundColor: [randomColor(0.7), randomColor(0.7), randomColor(0.7), randomColor(0.7), randomColor(0.7)],
            data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
        };
        config.data.datasets.push(newDataset);
        window.myPie.update();
    });
    $('#removeDataset').click(function() {
        config.data.datasets.splice(0, 1);
        window.myPie.update();
    });



});


</script>";
    $REL_TPL->stdhead("Графики");
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->assignByRef('data_es',$data_es);
    $REL_TPL->assignByRef('data_no_es',$data_no_es);
    $REL_TPL->output("index", "graph");

    $REL_TPL->stdfoot($add_js);

