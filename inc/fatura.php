<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 13.08.2015
 * Time: 00:55
 */

ob_start();
ini_set("display_errors", "On");
error_reporting(E_ALL);
header("Content-Type: text/html; charset=utf8");

require_once "config.php";
require_once "Price.Convertor.php";

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">


    <style type="text/css">
        body {
            margin: 0px;
            padding: 0px;
        }

        .fatura {
            overflow: auto;
            height: 705px;
            padding-top: 38px;

            /*border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: #000;*/

        }

        .fatura_genel {
            float: left;
            height: 595px;

        }

        .fatura_musteri_bilgi {
            height: 460px;
            margin-top: 120px;
            float: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: normal;
            color: #000;
            padding-top: 2px;
            padding-right: 14px;

            padding-bottom: 2px;
            padding-left: 14px;

        }

        .list {
            float: right;
            margin-right: 15px;
            padding-right: 15px;

        }

        .list li {
            height: 16px;
            line-height: 16px;
        }

        .odeme {
            height: 20px;
            width: 150px;
            margin-right: auto;
            margin-left: 20px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 20px;
            font-weight: bold;
            color: #000;
        }

        .fatura_musteri_bilgi ul {
            margin: 0px;
            padding: 0px;
        }

        .fatura_musteri_bilgi ul li {
            list-style-type: none;
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .fatura_icerik {

            padding-bottom: 2px;
            overflow: auto;
            margin-top: 15px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            height: 155px;
            overflow: auto;
        }

        .temizle {
            clear: both;
        }

        .fatura_icerik table {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        .fatura_ara_toplam {
            overflow: auto;
            margin-bottom: 2px;
            margin-left: 25px;
        }
    </style>
</head>
<body style="font-size:12px; font-family:Verdana; margin:0px 0px 0px 0px"
      onload="setTimeout(function(){window.print(); /*window.close();*/}, 500);">

<?php

foreach (@$_GET["data"] AS $index => $value) {

    $FetchOrders = $db->get_results("SELECT * FROM siparisler WHERE siparis_id = '" . $value . "'");

    $FetchOrder = $FetchOrders[0];

    $FetchOrder->fiyat = ((float)$FetchOrder->fiyat + (float)$FetchOrder->indirim);

    if($FetchOrder->fiyat<=0){
        $FetchOrder->indirim = 0;
    }

    ?>

    <div class="fatura" style="margin-left: 38px;">
        <div class="fatura_genel">
            <div class="fatura_musteri_bilgi" style="width: 290px; padding-left: 7.5px; ">
                <ul style="height:82px;font-family:tahoma; font-size:10px;margin-top: 13px;">
                    <li style="margin-top: 5px;  font-size:10px;"><b><span></span> <span style="margin-left: 0px;">Sip No: <?= $FetchOrder->siparis_id; ?></span></b>
                    </li>
                    <li style="margin-top:7px;  font-size:10px; font-family:Tahoma">
                        <strong style="font-family:Tahoma"><?= $FetchOrder->ad_soyad; ?> T.C:</strong></li>
                    <li style="margin-top:2px; width:270px; font-size:10px">
                        <?= $FetchOrder->adres; ?><br/>
                        Tel :<?= $FetchOrder->Telefon_no; ?></br >
                        <strong><?= $FetchOrder->ilce; ?>/<?= $FetchOrder->il; ?></strong>
                    </li>
                </ul>

                <ul class="list" style="clear:both;margin-right:22px; margin-top: -6px;">
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                    <li><?= date("H:i"); ?></li>
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                </ul>
                <div class="temizle"></div>
                <div class="fatura_icerik">
                    <table width="270" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr height="20">
                            <?php
                                $Matrah = ($FetchOrder->fiyat / (0.08+1));
                                $KDV = ($Matrah * (0.08));
                                //$KDVsiz = ($FetchOrder->fiyat - ($Matrah * 0.08));
                            ?>
                            <td width="145" style="font-size:9px"> <?= $FetchOrder->urunun_adi; ?></td>
                            <td width="50" align="center"><?= $FetchOrder->urun_adeti; ?> Adet</td>
                            <td width="30" align="center">%8</td>
                            <td width="40" align="center"><?= number_format($Matrah, 2);?></td>
                            <td width="40" align="center"><b style="display: none;"><?= $FetchOrder->fiyat; ?></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="fatura_ara_toplam">
                    <div style="float:left; width:100px;">&nbsp;
                        <img width="70" height="70"
                             src="https://chart.googleapis.com/chart?cht=qr&chl=<?= $FetchOrder->siparis_id; ?>&choe=UTF-8&chs=70x70&chld=H|3"/>
                    </div>
                    <div style="float:left; width:140px;">
                        <table width="140" border="0" style="font-size:9px;">
                            <tbody>
                            <tr>
                                <td width="100">KDV Matrah</td>
                                <td width="50"><?= number_format($Matrah, 2);?> TL</td>
                            </tr>

                            <tr>
                                <td>Toplam KDV</td>
                                <td><?= number_format($KDV, 2);?> TL</td>
                            </tr>

                            <tr>
                                <td width="100">İndirim</td>
                                <td width="50"><?= $FetchOrder->indirim; ?> TL</td>
                            </tr>
                            <tr>
                                <td><strong>Genel Toplam</strong></td>
                                <td><strong><?= (float) ($FetchOrder->fiyat - $FetchOrder->indirim); ?> TL</strong></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="temizle"></div>
                Yalnız
                #<?php
                    if((float)$FetchOrder->fiyat<=0){
                        echo "sıfırlira";
                    }else{
                        echo \Price\Convertor::convertToAlphanumeric((float) ($FetchOrder->fiyat - $FetchOrder->indirim));
                    }
                ?>#
            </div>


            <div class="fatura_musteri_bilgi" style="width: 270px; margin-left: 33px;">

                <!-- start -->

                <ul style="height:80px; font-family:tahoma; font-size:9px;margin-top: 13px;">
                    <li style="margin-top: 5px; font-size:10px"><b><span></span> <span
                                style="margin-left: 0px;">Sip No: <?= $FetchOrder->siparis_id; ?></span></b>
                    </li>
                    <li style="margin-top:7px; font-size:10px; font-family:Tahoma">
                        <strong><?= $FetchOrder->ad_soyad; ?> T.C:</strong></li>
                    <li style="margin-top:2px; font-size:9px">
                        <?= $FetchOrder->adres; ?><br/>
                        Tel :<?= $FetchOrder->Telefon_no; ?></br >
                        <strong><?= $FetchOrder->ilce; ?>/<?= $FetchOrder->il; ?></strong>
                    </li>
                </ul>

                <ul class="list" style="clear:both; float:right; margin-top: -2px;">
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                    <li><?= date("H:i"); ?></li>
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                </ul>
                <div class="temizle"></div>
                <div class="fatura_icerik">
                    <table width="270" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr height="20">
                            <td width="145" style="font-size:9px"> <?= $FetchOrder->urunun_adi; ?></td>
                            <td width="50" align="center"><?= $FetchOrder->urun_adeti; ?> Adet</td>
                            <td width="30" align="center">%8</td>
                            <td width="40" align="center"><?= number_format($Matrah, 2);?></td>
                            <td width="40" align="center"><b style="display: none;"><?= $FetchOrder->fiyat; ?></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="fatura_ara_toplam">
                    <div style="float:left; width:100px;">&nbsp;

                        <img width="70" height="70"
                             src="https://chart.googleapis.com/chart?cht=qr&chl=<?= $FetchOrder->siparis_id; ?>&choe=UTF-8&chs=70x70&chld=H|3"/>

                    </div>
                    <div style="float:left; width:140px;">
                        <table width="140" border="0" style="font-size:9px;">
                            <tbody>
                            <tr>
                                <td width="100">KDV Matrah</td>
                                <td width="50"><?= number_format($Matrah, 2);?> TL</td>
                            </tr>
                            <tr>
                                <td>Toplam KDV</td>
                                <td><?= number_format($KDV, 2);?> TL</td>
                            </tr>
                            <tr>
                                <td width="100">İndirim</td>
                                <td width="50"><?= $FetchOrder->indirim; ?> TL</td>
                            </tr>
                            <tr>
                                <td><strong>Genel Toplam</strong></td>
                                <td><?= (float) ($FetchOrder->fiyat - $FetchOrder->indirim); ?> TL</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="temizle"></div>
                Yalnız
                #<?php
                if((float)$FetchOrder->fiyat<=0){
                    echo "sıfırlira";
                }else{
                    echo \Price\Convertor::convertToAlphanumeric((float) ($FetchOrder->fiyat - $FetchOrder->indirim));
                }
                ?>#
                <!-- Stop -->


            </div>


            <div class="fatura_musteri_bilgi"
                 style="width:270px; margin-left: 49.5px; padding-left: 15px; padding-right:0px">

                <!-- start -->

                <ul style="height:78px;  font-family:tahoma; font-size:8px;margin-top: 13px;">
                    <li style="margin-top:2px; font-size:10px"><b><span></span> <span
                                style="margin-left: 0px;">Sip No: <?= $FetchOrder->siparis_id; ?></span></b>
                    </li>
                    <li style="margin-top:7px;  font-size:10px; font-family:Tahoma">
                        <strong><?= $FetchOrder->ad_soyad; ?> T.C:</strong></li>
                    <li style="margin-top:2px;  font-size:9px">
                        <?= $FetchOrder->adres; ?><br/>
                        Tel :<?= $FetchOrder->Telefon_no; ?></br >
                        <strong><?= $FetchOrder->ilce; ?>/<?= $FetchOrder->il; ?></strong>
                    </li>
                </ul>

                <ul class="list" style="clear:both; float:right; margin-top: 0px;">
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                    <li><?= date("H:i"); ?></li>
                    <li><?= date("d.m.Y", strtotime($FetchOrder->satis_tarihi)); ?></li>
                </ul>
                <div class="temizle"></div>
                <div class="fatura_icerik">
                    <table width="270" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr height="20">
                            <td width="145" style="font-size:9px"> <?= $FetchOrder->urunun_adi; ?></td>
                            <td width="50" align="center"><?= $FetchOrder->urun_adeti; ?> Adet</td>
                            <td width="30" align="center">%8</td>
                            <td width="40" align="center"><?= number_format($Matrah, 2);?></td>
                            <td width="40" align="center"><b style="display: none;"><?= $FetchOrder->fiyat; ?></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="fatura_ara_toplam">
                    <div style="float:left; width:100px;">&nbsp;
                        <img width="70" height="70"
                             src="https://chart.googleapis.com/chart?cht=qr&chl=<?= $FetchOrder->siparis_id; ?>&choe=UTF-8&chs=70x70&chld=H|3"/>


                    </div>
                    <div style="float:left; width:140px;">
                        <table width="140" border="0" style="font-size:9px;">
                            <tbody>
                            <tr>
                                <td width="100">KDV Matrah</td>
                                <td width="50"><?= number_format($Matrah, 2);?> TL</td>
                            </tr>
                            <tr>
                                <td>Toplam KDV</td>
                                <td><?= number_format($KDV, 2);?> TL</td>
                            </tr>
                            <tr>
                                <td width="100">İndirim</td>
                                <td width="50"><?= $FetchOrder->indirim; ?> TL</td>
                            </tr>
                            <tr>
                                <td><strong>Genel Toplam</strong></td>
                                <td><?=(float) ($FetchOrder->fiyat - $FetchOrder->indirim); ?> TL</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="temizle"></div>
                Yalnız
                #<?php
                if((float)$FetchOrder->fiyat<=0){
                    echo "sıfırlira";
                }else{
                    echo \Price\Convertor::convertToAlphanumeric((float) ($FetchOrder->fiyat - $FetchOrder->indirim));
                }
                ?>#
                <!-- Stop -->


            </div>


        </div>
    </div>

    <?php

}

?>

</body>
</html>
