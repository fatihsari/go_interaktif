<?php

	include_once "db/shared/ez_sql_core.php";
	include_once "db/ez_sql_mysql.php";

	$vt_kullanici="oothitit_new";
    $vt_parola="idokey1+-+";
    $vt_isim="oothitit_new";
    $vt_sunucu="localhost";
 
    $db = new ezSQL_mysql($vt_kullanici,$vt_parola,$vt_isim,$vt_sunucu);
	$db->query("SET NAMES 'utf8'");
	$db->query("SET CHARACTER SET utf8");
	$db->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
	


 
?>
