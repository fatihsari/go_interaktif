<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 9.09.2015
 * Time: 21:21
 */
?>
<?php if (!defined("idokey")) {
    exit();
} ?>
<?php

$drm = (int)$_GET["drm"];
$tip = (int)$_GET["tp"];
$gd = (int)$_GET["g"];

//if(empty($drm)){header("Location:index.php");}

$durum = $db->get_row("SELECT * FROM siparis_durumlari where durum_id='" . $drm . "' ");

header("Content-Type: text/html; charset=utf8;");

?>

<script type="text/javascript">
    function _alert($t, $s){
        console.log($t);
        alert($s.attr('command'));
    }
    function refreshKargo($e, $siparis_id, $cargoKey){

        var $el = $e;
        var $tr = $el.parents("tr");
        var $td = $el.parents("td");

        $tr.find('td.transaction').attr('align', 'center').find('font').html('<img src="images/ajax-loader.gif">');

        $.post('inc/checkTransaction.php', {siparis_id: $siparis_id, cargoKey: $cargoKey}, function($response){

            $style = '';

            if(!$response.status){
                $style = 'color:orange; font-family:sans-serif;';
            }

            $tr.find('.transaction.sube').attr('align', 'left').find('font').attr('style', $style).empty().html($response.data.sube);
            $tr.find('.transaction.islem').attr('align', 'left').find('font').attr('style', $style).empty().html($response.data.islem);
            $tr.find('.transaction.tarih').attr('align', 'left').find('font').attr('style', $style).empty().html($response.data.tarih);
            $tr.find('.transaction.aciklama').attr('align', 'left').find('font').attr('style', $style).empty().html($response.data.aciklama);

        });

    }
</script>

<meta charset="UTF-8">

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="" class="panel-close">&times;</a>
            <a href="" class="minimize">&minus;</a>
        </div>
        <!-- panel-btns -->
        <h3 class="panel-title"> <?= $durum->name ?> Bekleyen Kargo Listesi</h3>
    </div>
    <div class="panel-body">





    </div>
    <!-- panel-body -->
</div><!-- panel -->

<div class="table-responsive">
    <table class="table table-bordered" id="table1">
        <thead>
        <tr>
            <th>#</th>
            <th width="10%">Ad Soyad</th>
            <th>Ürün</th>
            <th>Fiyat</th>
            <th>İll/İlce</th>
            <th>Son Durumu</th>
            <th>Takip Kodu</th>
            <th>Nerede</th>
            <th>Son İşlem</th>
            <th>İşlem T.</th>
            <th>Açıkl.</th>
        </tr>
        </thead>
        <tbody>


        <?php
        ob_start();
        ini_set("display_errors", 1);
        error_reporting(0);

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

        $Siparisler = $db->get_results("SELECT * FROM `siparisler` WHERE `kargo_post` = 1 AND `cargoKey` > 1 AND  (`siparis_durumu` = 7) AND `satis_tarihi` >= '2015-09-01 00:00:01' ORDER BY `paket_datetime` ASC");

        $x = 1;

        foreach ($Siparisler as $Siparis) {

            $KargoDurumu = $db->get_row("SELECT * FROM  `kargo_durumlari` WHERE `siparis_id` = '".$Siparis->siparis_id."'");

            if($KargoDurumu==null){

                $KargoDurumu = new stdClass();
                $KargoDurumu->sube_adi = "<font  style=\"color:orange; font-family:sans-serif;\">İşlenmemiş</font>";
                $KargoDurumu->islem = "<font  style=\"color:orange; font-family:sans-serif;\">İşlenmemiş</font>";
                $KargoDurumu->islem_tarihi = "<font  style=\"color:orange; font-family:sans-serif;\">İşlenmemiş</font>";
                $KargoDurumu->aciklama = "<font  style=\"color:orange; font-family:sans-serif;\">İşlenmemiş</font>";

            }

            $Sube           = "<font>".$KargoDurumu->sube_adi."</font>";
            $Islem          = "<font>".$KargoDurumu->islem."</font>";
            $IslemTarihi    = "<font>".$KargoDurumu->islem_tarihi."</font>";
            $Aciklama       = "<font>".$KargoDurumu->aciklama."</font>";

            echo '
                  <tr class="odd gradeX">
                  <th scope="row">' . $x . '</th>
                    <td>' . $Siparis->ad_soyad . '<br> <a href="?ido=siparis&id=' . $Siparis->siparis_id . '" target="_blank"><font style="color:green;">Sip Kod :' . $Siparis->siparis_id . '</font></a><br /><font style="color:#7A6A46;">Tel No : ' . $Siparis->Telefon_no . '</font> </td>
                    <td>' . $Siparis->urunun_adi . '</td>
                    <td><b style="color:green">' . $Siparis->fiyat . ' ₺</b></td>
                    <td>' . $Siparis->il . ' / ' . $Siparis->ilce . '<br> ' . $Siparis->update_date . '</td>
                    <td style="color:' . $CargoStatusColors[$Siparis->kargo_durumu] . '">' . $Siparis->kargo_durum_mesaji . '<br /><font style="color:#636e7b">' . date("d-m-Y", strtotime($Siparis->satis_tarihi)) . '</font></td>
                    <td><b click="call alert Bu&nbsp;foksiyon&nbsp;henüz&nbsp;kullanıma&nbsp;açık&nbsp;değil!!!" command="modal" on="click" to="inc/findKargo.php?t=' . $Siparis->kargo_takip_no . '&i=' . $Siparis->cargoKey . '" style="color:#4D8CDE; cursor:pointer;">' . $Siparis->kargo_takip_no . '</b></td>
                    <td class="transaction sube" style="font-family:sans-serif;">' . $Sube . '</td>
                    <td class="transaction islem" style="font-family:sans-serif;">' . $Islem . '<br> <a command="call" call="refreshKargo" param="this '.$Siparis->siparis_id.' '.$Siparis->cargoKey.'" on="click" style="color:#A7C32E; cursor:pointer; float: left;">Yenile</a> </td>
                    <td class="transaction tarih" style="font-family:sans-serif;">' . $IslemTarihi . '</td>
                    <td class="transaction aciklama" style="font-family:sans-serif;">' . $Aciklama . '</td>
                    ';

            $x++;

        }


        ?>


        </tbody>
    </table>
</div>
<!-- table-responsive -->

<script type="text/javascript" src="js/command.js"></script>