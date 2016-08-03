<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 03.08.2016
     * Time: 23:13
     */

    function smarty_function_chart_flot($params, &$smarty){

        $return = "<script type=\"text/javascript\">
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
				}*/
var data_pie = [
			" . $params['data'] . "
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
		</script>";
        return $return;
    }