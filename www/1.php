<?php
$start = 7;
$gru_1 = 5;
$child_1 = 10;
$child_2 = 20;
$gru_2 = 40;
$child_3 = 20;
$sad_child = 0;

$have_ice = $start+$gru_1;
if($have_ice > $child_1){
	$have_ice = $have_ice - $child_1;
}
else
	$sad_child++;
if($have_ice > $child_2){
	$have_ice = $have_ice - $child_2;
}
else
	$sad_child++;
$have_ice = $have_ice+$gru_2;
if($have_ice > $child_3){
	$have_ice =$have_ice - $child_3;
}
else
	$sad_child++;

print "Осталось: ".$have_ice.". Грустных детей: ".$sad_child;

?>