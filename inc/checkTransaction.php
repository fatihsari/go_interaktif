<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 11.09.2015
 * Time: 01:05
 */

ob_start();
session_start();
ini_set("display_errors", 0);
error_reporting(0);

include("include.php");

$_DATA = $_POST;

$CargoStatusCodes = array(
    0 => null,
    1 => "Çıkış Şubesinde",
    2 => "Yolda",
    3 => "Teslimat Şubesinde",
    4 => "Teslimatta",
    5 => "Parçalı Teslimat",
    6 => "Teslim Edildi",
    7 => "Yönlendirildi"
);

$CargoStatusColors = array(
    0 => "#A7C32E",
    1 => "#A7C32E",
    2 => "#8A9750",
    3 => "#1E9852",
    4 => "#1A8922",
    5 => "#3F9F44",
    6 => "#158745",
    7 => "#327CCB"
);

$TurkishChars = array(
    "sensation" => array("çe", "Çe", "ğıı", "Ğıı", "ıı", "İi", "öö", "Öö", "şşş", "Şşş", "üü", "Üü"),
    "hex"   => array("ç", "Ç", "ğ", "Ğ", "ı", "İ", "ö", "Ö", "ş", "Ş", "ü", "Ü"),
    "hexadecimal"   => array("ç", "Ç", "ğ", "Ğ", "ı", "İ", "ö", "Ö", "ş", "Ş", "ü", "Ü"),
    "universal"     => array("c", "C", "g", "G", "i", "I", "o", "O", "s", "S", "u", "U"),
    "html"          => array("&#231;", "&#199;", "&#287;", "&#286;", "&#305;", "&#304;", "&#246;", "&#214;", "&#351;", "&#350;", "&#252;", "&#220;"),
    "unicode"       => array("&#xe7;", "&#xc7;", "&#x11f;", "&#x11e;", "&#x131;", "&#x130;", "&#xf6;", "&#xd6;", "&#x15f;", "&#x15e;", "&#xfc;", "&#xdc;")
);

require_once "KargoAPIService/library/Configuration.php";
require_once "KargoAPIService/library/Exception.php";
require_once "KargoAPIService/library/CSocket.php";
require_once "KargoAPIService/library/CSoap.php";
require_once "KargoAPIService/library/APISoap.php";
require_once "KargoAPIService/library/APIXml.php";
require_once "KargoAPIService/services/ArasKargo.php";

$WebService = true;

$Updating = false;

$Order = $db->get_row("SELECT * FROM `siparisler` WHERE `cargoKey` = '".$_DATA["cargoKey"]."'");

$CargoAPIAccount = $db->get_row("SELECT * FROM `kargo_apis` WHERE `id` = '".$Order->kargo_account."'");

$ArasConf = new \KargoAPIService\Library\Configuration(array(
    "host" => $CargoAPIAccount->service_host,
    "target" => $CargoAPIAccount->service,
    "port" => $CargoAPIAccount->service_port,
    "timeout" => 60,
    "XmlServicesType" => $CargoAPIAccount->service_type
));

$ArasConf->username($CargoAPIAccount->service_user)->password($CargoAPIAccount->service_pass)->customerKey($CargoAPIAccount->customer_key);

$ArasService = new \KargoAPIService\Service\ArasCargo($ArasConf);

$ArasService->WebServices();

$WebServiceResponse = $ArasService->WebServices()->push(array(
    "QueryType" => 9,
    "IntegrationCode" => $_DATA["cargoKey"]
))->exec();

if ($WebServiceResponse === null) {

    $WebService = false;

}

if (is(array($WebServiceResponse, "isErr"), true)) {

    $WebService = false;

}

if($WebService){

    $SubeAdi = str_replace($TurkishChars["hex"], $TurkishChars["unicode"], $WebServiceResponse->branch());

    $Islem = str_replace($TurkishChars["hex"], $TurkishChars["unicode"], $WebServiceResponse->process());

    $IslemTarihi = $WebServiceResponse->date("Y-m-d H:i:s");

    $Aciklama = str_replace($TurkishChars["hex"], $TurkishChars["unicode"], $WebServiceResponse->description());

    $LastStatusCheck = date("Y-m-d H:i:s");

    $CheckTransactionExist = $db->get_results("SELECT * FROM `kargo_durumlari` WHERE `siparis_id`='{$_DATA["siparis_id"]}'");

    if(count($CheckTransactionExist)>0){

        $Query = "UPDATE `kargo_durumlari` SET `sube_adi`='{$SubeAdi}',  `islem`='{$Islem}',  `islem_tarihi`='{$IslemTarihi}',  `aciklama`='{$Aciklama}' WHERE `siparis_id`='{$_DATA["siparis_id"]}'";

    }else{

        $Query = "INSERT INTO `kargo_durumlari` (`siparis_id`, `sube_adi`, `islem`, `islem_tarihi`, `aciklama`) VALUES ('{$_DATA["siparis_id"]}', '{$SubeAdi}', '{$SubeAdi}', '{$IslemTarihi}', '{$Aciklama}')";

    }

    $Updating = $db->query($Query);

}else{

    $SubeAdi = "İşlenmemiş";

    $Islem = "İşlenmemiş";

    $IslemTarihi = "İşlenmemiş";

    $Aciklama = "İşlenmemiş";

}

header("Content-Type: application/json; charset=utf8;");

exit(json_encode(array(
    "status" => $WebService,
    "data" => array(
        "sube"      => $SubeAdi,
        "islem"     => $Islem,
        "tarih"     => $IslemTarihi,
        "aciklama"  => $Aciklama
    )
)));