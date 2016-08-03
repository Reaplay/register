<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 01.08.2016
     * Time: 20:08
     */

/*
 * Кол-во сотрудников РЦК (слайд №4). Для начала без динамики. Далее, когда будет храниться история состояний, нужно будет сделать этот отчет с динамикой.
 */


        $res_direction = sql_query("SELECT id, name_direction FROM direction WHERE is_deleted = 0");
        while ($row_direction = mysql_fetch_array($res_direction)) {
            $data_direction[$row_direction['id']] = $row_direction['name_direction'];
        }


    if($_GET['id_direction']){
    $id_direction = (int)$_GET['id_direction'];
        $res = sql_query ("
SELECT SUM(1) as num, location_city.name_city
FROM employee
LEFT JOIN established_post ON established_post.id = employee.id_uid_post
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
/*LEFT JOIN direction ON direction.id = established_post.id_direction*/
WHERE  employee.is_deleted = 0 AND established_post.id_direction = '".$id_direction."'
GROUP BY location_city.name_city
ORDER BY location_city.name_city ");
        $i=0;
        while ($row = mysql_fetch_array ($res)) {
            $data_num = $row['num'];
            if (!$row['name_city'])
                $data_name = "N/A";
            else
                $data_name = $row['name_city'];
            if ($chart_flot['data'])
                $chart_flot['data'] .= ",";
            $chart_flot['data'] .= '{ label: "' . $data_name . '",  data: [[1,' . $data_num . ']]}';

            $data_city[$i]['name_city'] = $row['name_city'];
            $data_city[$i]['num'] = $row['num'];
            $i++;
        }

    /*    $add_js = "
    	<script type=\"text/javascript\">
			loadScript(plugin_path + \"chart.flot/jquery.flot.min.js\", function(){
				loadScript(plugin_path + \"chart.flot/jquery.flot.resize.min.js\", function(){
					loadScript(plugin_path + \"chart.flot/jquery.flot.time.min.js\", function(){
						loadScript(plugin_path + \"chart.flot/jquery.flot.fillbetween.min.js\", function(){
							loadScript(plugin_path + \"chart.flot/jquery.flot.orderBars.min.js\", function(){
								loadScript(plugin_path + \"chart.flot/jquery.flot.pie.min.js\", function(){
									loadScript(plugin_path + \"chart.flot/jquery.flot.tooltip.min.js\", function(){



									/*	var data_pie = [];
				var series = Math.floor(Math.random() * 10) + 1;
				for (var i = 0; i < series; i++) {
					data_pie[i] = {
						label : \"Series\" + (i + 1),
						data : Math.floor(Math.random() * 100) + 1
					}
				}*//*
var data_pie = [
			" . $data_f . "
		];
				jQuery.plot(jQuery(\"#flot-pie\"), data_pie, {
					series : {
						pie : {
							show : true,
							innerRadius : 0.5,
							radius : 1,
							label : {
								show : true,
								//radius : 2 / 3,
								formatter : function(label, series) {
									return '<div style=\"font-size:11px;text-align:center;padding:4px;color:black;\">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
								},
								threshold : 0.1
							}
						}
					},
					legend : {
						show : true,
						noColumns : 1, // number of colums in legend table
						labelFormatter : null, // fn: string -> string
						labelBoxBorderColor : \"#000\", // border color for the little label boxes
						container : null, // container (as jQuery object) to put legend in, null means default on top of graph
						position : \"ne\", // position of default legend container within plot
						margin : [5, 10], // distance from grid edge to default legend container within plot
						backgroundColor : \"#efefef\", // null means auto-detect
						backgroundOpacity : 1 // set to 0 to avoid background
					},
					grid : {
						hoverable : true,
						clickable : true
					},
				});

									});
								});
							});
						});
					});
				});
			});
		</script>



    ";*/
        $REL_TPL->assignByRef('data_city',$data_city);
        $REL_TPL->assignByRef('chart_flot',$chart_flot);
    }
    $REL_TPL->stdhead("График распределения сотрудников");
    $REL_TPL->assignByRef('data_direction',$data_direction);
   // $REL_TPL->assignByRef('data_direction',$data_direction);
   // $REL_TPL->assignByRef('data_es',$data_es);
 //   $REL_TPL->assignByRef('data_no_es',$data_no_es);
    $REL_TPL->output("count_direction", "graph");

    //$REL_TPL->stdfoot($add_js);
    $REL_TPL->stdfoot();