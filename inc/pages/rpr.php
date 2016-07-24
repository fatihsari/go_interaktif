<?php
/**
 * Created by PhpStorm.
 * User: musaatalay
 * Date: 04.11.2015
 * Time: 18:17
 */
?>
<?php
if (!defined("idokey")) {
    exit();
}
?>
<?php

$drm = @$_GET["status"];

if (empty($drm)) {
    header("Location:index.php");
}

$durum = $db->get_row("SELECT * FROM `siparis_durumlari` WHERE `durum_id` = '".$drm."'");
$tip = null;


$Orders = array();

$bTarih = date("Y-m-d")." 00:00:00";
$sTarih = date("Y-m-d")." 23:59:59";

if(@isset($_REQUEST["btarih"])){
    $bTarih = @$_REQUEST["btarih"];
}
if(@isset($_REQUEST["starih"])){
    $sTarih = @$_REQUEST["starih"];
}

$Filter = "WHERE (`islem_tarihi` BETWEEN '".$bTarih."' AND '".$sTarih."')";

if(@isset($_GET["status"])){
    $Filter .= " AND `siparis_durumu` = '" . @$_GET["status"] . "'";
}

if(@isset($_GET["type"])){
    $_getTip = $db->get_row("SELECT * FROM `siparis_tipleri` WHERE `siparis_tipi` = '".@$_GET["type"]."'");
    $tip = $_getTip->name;
    $Filter .= " AND `siparis_tipi` = '" . @$_GET["type"] . "'";
}

$Orders = $db->get_results("SELECT * FROM `siparisler` ".$Filter);

$_headTip = null;

if(!empty($tip)){
    $_headTip =  " <b>Tip:</b> {$tip}";
}

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="" class="panel-close">&times;</a>
            <a href="" class="minimize">&minus;</a>
        </div><!-- panel-btns -->
        <h3 class="panel-title"><b>Durum:</b> <?=@$durum->name;?><?=@$_headTip;?></h3>
    </div>
    <div class="panel-body">


        <div class="table-responsive">
            <table class="table" id="table1">
                <thead>
                <tr>
                    <th>Ad Soyad</th>
                    <th>Telefon</th>
                    <th>Ürün</th>
                    <th>Fiyat</th>
                    <th>Kayıt Tarihi</th>
                    <th>il/ilce</th>

                    <th>Durumu</th>
                    <th>Kilit</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>

                <?php

                foreach ($Orders as  $Order) {


                    echo '
                  <tr class="odd gradeX">
                    <td>'.$Order->ad_soyad.'<br> <font style="color:green">Sip Kod : '.$Order->siparis_id.'</font> </td>
                    <td>'.$Order->Telefon_no.'</td>
                    <td>'.$Order->urunun_adi.'</td>
                    <td>'.$Order->fiyat.'</td>
                    <td>'.$Order->kayit_tarihi.'</td>
                    <td>'.$Order->il.' / '.$Order->ilce.'<br> '.$Order->update_date.'</td>
                    <td > '.$durum->name.'

                    ';

                    if($drm==9 or $drm==7){echo "<br>".date("d-m-Y", strtotime($Order->satis_tarihi));}
                    echo'

                    </td>
                    ';

                    if($drm==9 or $drm==7){


                        $kilitci= 'Satış<br><font style="color:green"> '.personel("name_surname",$Order->personel).'</font>';


                    }else{


                        if($Order->kilit==1){
                            $kilitci =personel("name_surname",$Order->kilit_pers);
                        }else{
                            $kilitci="-";
                        }
                    }



                    echo'
                    <td > '.$kilitci.'</td>
                    <td >

                    <a href="pages.php?ido=siparis&id='.$Order->siparis_id.'" class="btn btn-info">Görüntüle</a>
                    <a style="display:none" href="pages.php?ido=siparis_listesi&drm='.$drm.'&tp='.$tip.'&id='.$Order->siparis_id.'&g=4" class="btn btn-danger">Geçersiz</a>

';

                    echo '


                    </td>
                 </tr>
                 ';
                }


                ?>


                </tbody>
            </table>
        </div><!-- table-responsive -->



    </div><!-- panel-body -->
</div><!-- panel -->
