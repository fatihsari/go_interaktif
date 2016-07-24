<?php
/**
 * Created by PhpStorm.
 * User: musaatalay
 * Date: 05.11.2015
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
$App = null;


$Orders = array();

$bTarih = date("Y-m-d")." 00:00:00";
$sTarih = date("Y-m-d")." 23:59:59";

/*if(@isset($_REQUEST["btarih"])){
    $bTarih = @$_REQUEST["btarih"];
}
if(@isset($_REQUEST["starih"])){
    $sTarih = @$_REQUEST["starih"];
}*/

if(@isset($_REQUEST["btarih"])&&@isset($_REQUEST["starih"])){

    $start = str_replace("/", "-", $_POST["btarih"]);
    $stop = str_replace("/", "-", $_POST["starih"]);

    $explodeStart = explode("-", $start);
    $explodeStop = explode("-", $stop);

    $bTarih = $explodeStart[2]."-".$explodeStart[1]."-".$explodeStart[0]." 00:00:00";
    $sTarih = $explodeStop[2]."-".$explodeStop[1]."-".$explodeStop[0]." 23:59:59";

}

$Filter = "WHERE (`islem_tarihi` BETWEEN '".$bTarih."' AND '".$sTarih."') ";

$ApiUser = $db->get_row("SELECT * FROM `api_users` WHERE `name` = '".@$_GET["api_name"]."'");

$Api_Applications = "AND (";

$ApiApplications = $db->get_results("SELECT * FROM `api_applications` WHERE `user_id` = '".$ApiUser->id."'");

foreach($ApiApplications AS $ApiApplication){

    //$Api_Applications .= $ApiApplication->id.",";
    $Api_Applications .= "`private_api` = '".$ApiApplication->id."' OR ";

}

$Api_Applications = rtrim($Api_Applications, " OR ").")";

//$Api_Applications = "AND `private_api` IN(".rtrim($Api_Applications, ",").")";

if(@isset($_REQUEST["application_name"])){
    $_getApplication = $db->get_row("SELECT * FROM `api_applications` WHERE `name` = '".@$_REQUEST["application_name"]."'");
    $App = $_getApplication->name;
    $Api_Applications = "AND `private_api` = '".$_getApplication->id."'";;
}

$Filter .= $Api_Applications;

if(@isset($_GET["status"])){
    if(@is_string($_GET["status"])){
        $Filter .= " AND `siparis_durumu` = '" . @$_GET["status"] . "'";
    }else if(@is_array($_GET["status"])){
        $isx = 0;
        foreach(@$_GET["status"] as $status){
            $det = "AND (";
            if($isx>=1){
                $det = "OR";
            }
            $Filter .= " ".$det." `siparis_durumu` = '" . $status . "'";
            $isx++;
        }
        $Filter .= ")";
    }
}

if(@isset($_GET["type"])){
    if(@is_string($_GET["type"])){
        $Filter .= " AND `siparis_tipi` = '" . @$_GET["type"] . "'";
    }else if(@is_array($_GET["type"])){
        $isx = 0;
        foreach(@$_GET["type"] as $type){
            $det = "AND (";
            if($isx>=1){
                $det = "OR";
            }
            $Filter .= " ".$det." `siparis_tipi` = '" . $type . "'";
            $isx++;
        }
        $Filter .= ")";
    }
}

$Orders = $db->get_results("SELECT * FROM `siparisler` ".$Filter);

$_headApplication = null;

if(!empty($App)){
    $_headApplication =  " <b>Uygulama:</b> {$App}";
}

$p1 = str_replace("-", "/", date("d-m-Y", strtotime($bTarih)));
$p2 = str_replace("-", "/", date("d-m-Y", strtotime($sTarih)));

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="" class="panel-close">&times;</a>
            <a href="" class="minimize">&minus;</a>
        </div><!-- panel-btns -->
        <h3 class="panel-title"><b>Durum:</b> <?=@$durum->name;?> <b>Api:</b> <?=@$_GET["api_name"];?><?=@$_headApplication;?></h3>
    </div>
    <div class="panel-body">

        <link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-btns">
                        <a href="" class="panel-close">&times;</a>
                        <a href="" class="minimize">&minus;</a>
                    </div>
                    <h4 class="panel-title">Tarih </h4>
                </div>
                <div class="panel-body">
                    <form action="" method="post">


                        <div class="input-group col-md-4" style="float:left; width:200px; ">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" name="btarih" value="<?=$p1;?>" placeholder="Başlangıç" id="date"
                                   class="form-control date"/>
                        </div>

                        <div class="input-group col-md-4" style="float:left; margin-left:20px; width:200px;">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" name="starih" value="<?=$p2;?>" placeholder="Bitiş" id="date"
                                   class="form-control date"/>
                        </div>

                        <div class="input-group col-md-4" style="float:left; margin-left:20px; width:200px;">
                            <input type="submit" value="Filtrele" class="btn btn-info">
                        </div>
                    </form>

                </div>
            </div>
            <!-- panel -->
        </div><!-- col-md-6 -->

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

        <script src="js/moment.js"></script>
        <script src="js/moment-tr.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.min.js"></script>
        <script>
            $('.date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        </script>

    </div><!-- panel-body -->
</div><!-- panel -->

