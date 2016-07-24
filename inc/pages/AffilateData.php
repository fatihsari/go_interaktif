<?php if (!defined("idokey")) {
    exit();
} ?>

<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 8.10.2015
 * Time: 11:56
 */
?>
<?php

if ($_POST) {

    $start = str_replace("/", "-", $_POST["start"]);
    $stop = str_replace("/", "-", $_POST["stop"]);

    if(empty($start)||empty($stop)){
        if (empty($start)) {
            $start = date("Y-m-d");
        }
        if (empty($stop)) {
            $stop = date("Y-m-d");
        }
    }else{
        $explodeStart = explode("-", $start);
        $explodeStop = explode("-", $stop);

        $start = $explodeStart[2]."-".$explodeStart[1]."-".$explodeStart[0];
        $stop = $explodeStop[2]."-".$explodeStop[1]."-".$explodeStop[0];
    }

} else {
    $start = date("Y-m")."-01";
    $last_day = date("d", strtotime("+1 month -".date("d")." days"));
    $stop = date("Y-m")."-".$last_day;
}

$start_data = date("Y-m-d", strtotime($start));
$stop_data = date("Y-m-d", strtotime($stop));


$p1 = str_replace("-", "/", date("d-m-Y", strtotime($start_data)));
$p2 = str_replace("-", "/", date("d-m-Y", strtotime($stop_data)));


$personel = (int)$_GET["id"];
/*
if(empty($personel)){
  header("Location:pages.php?ido=personeller");
}
*/
?>

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
                    <input type="text" name="start" value="<?=$p1;?>" placeholder="Başlangıç" id="date"
                           class="form-control date"/>
                </div>

                <div class="input-group col-md-6" style="float:left; margin-left:20px; width:200px;">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input type="text" name="stop" value="<?=$p2;?>" placeholder="Bitiş" id="date"
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

<style type="text/css">
    .api-data-table th, .api-data-table tr{
        text-align: center;
    }
</style>
<div class="table-responsive">
    <h3> Satış İşlemleri</h3>
    <table class="table table-bordered api-data-table">
        <thead>
        <tr>
            <th rowspan="1" colspan="2">API</th>
            <th rowspan="1" colspan="2" style="text-align: center;">Toplam Satış</th>
            <th rowspan="1" colspan="2" style="text-align: center;">Teslim Edilen</th>
            <th rowspan="1" colspan="2" style="text-align: center;">İade</th>
            <th rowspan="1" colspan="2" style="text-align: center;">Onaylanan</th>
            <th rowspan="1" colspan="2" style="text-align: center;">İptal</th>
            <th rowspan="1" colspan="2" style="text-align: center;">Geçersiz</th>
            <th rowspan="1" colspan="2" style="text-align: center;">Ulaşılamayan</th>
        </tr>
        <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">Api Adı</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
            <th style="text-align: center;">Adet</th>
            <th style="text-align: center;">Ciro</th>
        </tr>
        </thead>
        <tbody>

        <?php

        if ($_SESSION["yetki"] == 0) {
            $api_applications = $db->get_results("SELECT * FROM `api_applications` WHERE `active`=1");
        } else {
            $api_applications = $db->get_results("SELECT * FROM `api_applications` WHERE `active`=1");
        }

        $T_Toplam = 0;
        $T_ToplamCiro = 0;
        $T_Teslim = 0;
        $T_TeslimCiro = 0;
        $T_Iade = 0;
        $T_IadeCiro = 0;
        $T_Onaylanan = 0;
        $T_OnaylananCiro = 0;
        $T_Iptal = 0;
        $T_IptalCiro = 0;
        $T_Gecersiz = 0;
        $T_GecersizCiro = 0;
        $T_Ulasilamayan = 0;
        $T_UlasilamayanCiro = 0;

        $x = 1;

        foreach ($api_applications as $api_application) {

            $p = array();

            $Toplam = 0;
            $ToplamCiro = 0;
            $Teslim = 0;
            $TeslimCiro = 0;
            $Iade = 0;
            $IadeCiro = 0;
            $Onaylanan = 0;
            $OnaylananCiro = 0;
            $Iptal = 0;
            $IptalCiro = 0;
            $Gecersiz = 0;
            $GecersizCiro = 0;
            $Ulasilamayan = 0;
            $UlasilamayanCiro = 0;


            $sor = $db->get_results("SELECT * FROM `siparisler` WHERE  `private_api`='" . $api_application->id . "' AND `kayit_tarihi` BETWEEN '" . $start_data . " 00:00:00' AND '" . $stop_data . " 23:59:59'  ");

            $Toplam = count($sor);

            foreach ($sor as $value) {

                $ToplamCiro += $value->fiyat;

                switch($value->siparis_durumu){
                    case 2:
                        $Ulasilamayan++;
                        $UlasilamayanCiro += (float)$value->fiyat;
                        break;
                    case 4:
                        $Gecersiz++;
                        $GecersizCiro += (float)$value->fiyat;
                        break;
                    case 5:
                        $Iptal++;
                        $IptalCiro += (float)$value->fiyat;
                        break;
                    case 6:
                        $Iade++;
                        $IadeCiro += (float)$value->fiyat;
                        break;
                    case 7:
                        $Onaylanan++;
                        $OnaylananCiro += (float)$value->fiyat;
                        break;
                    case 8:
                        $Teslim++;
                        $TeslimCiro += (float)$value->fiyat;
                        break;
                }

            }

            $T_Toplam += $Toplam;
            $T_ToplamCiro += $ToplamCiro;
            $T_Teslim += $Teslim;
            $T_TeslimCiro += $TeslimCiro;
            $T_Iade += $Iade;
            $T_IadeCiro += $IadeCiro;
            $T_Onaylanan += $Onaylanan;
            $T_OnaylananCiro += $OnaylananCiro;
            $T_Iptal += $Iptal;
            $T_IptalCiro += $IptalCiro;
            $T_Gecersiz += $Gecersiz;
            $T_GecersizCiro += $GecersizCiro;
            $T_Ulasilamayan += $Ulasilamayan;
            $T_UlasilamayanCiro += $UlasilamayanCiro;


            echo '<tr>
                    <th scope="row">' . $x . '</th>
                    <td>' . $api_application->name . '</td>
                    <td style="color:blue;">' . $Toplam . '</td>
                    <td style="color:#00CC99;">' . number_format($ToplamCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:green;">' . $Teslim . '</td>
                    <td style="color:#00CC99;">' . number_format($TeslimCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:red;">' . $Iade . '</td>
                    <td style="color:#00CC99;">' . number_format($IadeCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:red;">' . $Onaylanan . '</td>
                    <td style="color:#00CC99;">' . number_format($OnaylananCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:red;">' . $Iptal . '</td>
                    <td style="color:#00CC99;">' . number_format($IptalCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:red;">' . $Gecersiz . '</td>
                    <td style="color:#00CC99;">' . number_format($GecersizCiro, 2, ".", ",") . ' ₺</td>
                    <td style="color:red;">' . $Ulasilamayan . '</td>
                    <td style="color:#00CC99;">' . number_format($UlasilamayanCiro, 2, ".", ",") . ' ₺</td>
                  </tr>';
            $x++;


        }

        echo '<tr style="background-color: #0385ea !important;color: #f1f1f1;text-shadow: 1px 1px 1px #002a80;">
                <th scope="row">' . $x . '</th>
                <td>Genel Toplam</td>
                <td style="color:blue;">' . $T_Toplam . '</td>
                <td style="color:#00CC99;">' . number_format($T_ToplamCiro, 2, ",", ".") . ' ₺</td>
                <td style="color:green;">' . $T_Teslim . '</td>
                <td style="color:#00CC99;">' . number_format($T_TeslimCiro, 2, ".", ",") . ' ₺</td>
                <td  style="color:red;"><b>' . $T_Iade . '</b></td>
                <td style="color:#00CC99;">' . number_format($T_IadeCiro, 2, ".", ",") . ' ₺</td>
                <td  style="color:red;"><b>' . $T_Onaylanan . '</b></td>
                <td style="color:#00CC99;">' . number_format($T_OnaylananCiro, 2, ".", ",") . ' ₺</td>
                <td  style="color:red;"><b>' . $T_Iptal . '</b></td>
                <td style="color:#00CC99;">' . number_format($T_IptalCiro, 2, ".", ",") . ' ₺</td>
                <td  style="color:red;"><b>' . $T_Gecersiz . '</b></td>
                <td style="color:#00CC99;">' . number_format($T_GecersizCiro, 2, ".", ",") . ' ₺</td>
                <td  style="color:red;"><b>' . $T_Ulasilamayan . '</b></td>
                <td style="color:#00CC99;">' . number_format($T_UlasilamayanCiro, 2, ".", ",") . ' ₺</td>
             <tr>';


        ?>


        </tbody>
    </table>
</div>

<script src="js/moment.js"></script>
<script src="js/moment-tr.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
    $('.date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
</script>