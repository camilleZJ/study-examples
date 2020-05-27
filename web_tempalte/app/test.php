<?php
// echo date("Y-m-d 00:00:00",strtotime("-1 day")) . '<br />';
// echo date("Y-m-d 00:00:00");

$data =array(
	0 => array("uid" => "80061905","bird_total" => 1,"bridge_total" => "11","car_total" => 10),
	1 => array("uid" => "80062161","bird_total" => 2,"bridge_total" => "22","car_total" => 20),
	2 => array("uid" => "80061905","bird_total" => 3,"bridge_total" => "33","car_total" => 30),
	3 => array("uid" => "80061905","bird_total" => 4,"bridge_total" => "44","car_total" => 40),
	4 => array("uid" => "80061905","bird_total" => 5,"bridge_total" => "44","car_total" => 50),
	5 => array("uid" => "80061905","bird_total" => 6,"bridge_total" => "44","car_total" => 60)
);
echo '----------1---------<br />';
echo "<pre>";print_r($data);
foreach ($data as &$v)
{
	$tid = $v['bird_total'];
}
echo '----------2---------<br />';
echo "<pre>";print_r($data);
foreach ($data as $k => $v)
{
	if ($v['uid'] == '80062161')
	{
		$i = $k+1;
	}
}
echo '----------3---------<br />';
echo "<pre>";print_r($data);