<?php
/*
echo "<BR>";换行
var_dump($arr);
var_export($arr,true);如果是false结果同var_dump

*/
$animal_name = array("dog","cat","sleep");
echo $animal_name[0].$animal_name[1].$animal_name[2]."<BR>";
$animal_voice = array(
  'dog' => "wangwang",
  'cat' => "miaomiao",
  'sleep' => "miemie");
echo $animal_name[0] . "'s voice is ".$animal_voice[$animal_name[0]]."<BR>";
echo $animal_name[1] . "'s voice is ".$animal_voice[$animal_name[1]]."<BR>";
echo $animal_name[2] . "'s voice is ".$animal_voice[$animal_name[2]]."<BR>";
var_dump($animal_name);
echo "<BR>"
var_export($animal_name);
echo "<BR>"
var_export($animal_name,true);
?>
