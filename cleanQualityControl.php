<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 28.08.2015
 * Time: 03:45
 */

ob_start();
session_start();
ini_set("display_errors", 1);
error_reporting(0);

include("inc/include.php");

$diff = "-2";

$Diss = $db->query("UPDATE `siparisler` SET `kal_kontrol`=1, `Kal_onay_user`=109, `kalite_date`='".date("Y-m-d H:i:s")."' WHERE `kal_kontrol` = 0 AND (`satis_tarihi` BETWEEN '".date("Y-m-d", strtotime($diff." day", strtotime(date("Y-m-d"))))." 00:00:00' AND '".date("Y-m-d")." 00:00:00') AND `siparis_durumu` = 7");