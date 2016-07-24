<?php if (!defined("idokey")) {
    exit();
} ?>



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

                <div class="input-group col-md-4" style="float:left; margin-left:20px; width:200px;">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input type="text" name="stop" value="<?=$p2;?>" placeholder="Bitiş" id="date"
                           class="form-control date"/>
                </div>

                <div class="input-group col-md-4" style="float:left; margin-left:20px; width:200px;">
                    <input type="submit" class="btn btn-info"></div>
            </form>

        </div>
    </div>
    <!-- panel -->
</div><!-- col-md-6 -->


<div class="table-responsive">
    <h3>Primler Tablosu</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <!--<th>#</th>-->
            <th>Personel Adı</th>
            <th>Toplam Satış</th>
            <th>Teslim Edilen</th>
            <th>Sepet Arttırımı</th>
            <th>İndirim</th>
            <th>Bekleyen</th>
            <th>Bekleyen Ciro</th>
            <th>Toplam Ciro</th>
            <th>Teslim Ciro</th>
            <th>Prim</th>
            <th>Toplam İade</th>
            <th>İade Ciro</th>
            <th>İade Prim</th>
            <th>Net Prim</th>


        </tr>
        </thead>
        <tbody>

        <?php
        $x = 1;


        if ($_SESSION["yetki"] == 0) {
            $personeller = $db->get_results("SELECT * FROM admin WHERE login_case=0  " . $sql_statu3 . " ");
        } else {
            $personeller = $db->get_results("SELECT * FROM admin WHERE user_type=0 AND login_case=0  " . $sql_statu3 . " ");
        }

        $BekleyenToplam = 0;
        $BekleyenCiroToplam = 0;
        $TeslimCiroToplam = 0;
        $IadeCiroToplam = 0;
        $PrimToplam = 0;
        $IadePrimToplam = 0;
        $NetPrimToplam = 0;
        $sepet_sayisi1 = 0;

        foreach ($personeller as $perss) {

            $p = array();

            unset($dizim);
            unset($cirox);
            unset($indirim);
            unset($iade_cirox);
            unset($sepet);
            unset($prim);


            $sor = $db->get_results("SELECT * FROM siparisler where    personel='" . $perss->admin_id . "' AND satis_tarihi between '" . $start_data . " 00:00:00' AND '" . $stop_data . " 23:59:59'  ");

            $BekleyenCiro = 0;
            $TeslimCiro = 0;
            $IadeCiro = 0;
            $Prim = 0;
            $IadePrim = 0;
            $NetPrim = 0;
            $cirox          = array();
            $net_ciro       = array();
            $iade_cirox     = array();
            $prim           = array();
            $indirim        = array();
            $bekleyen       = 0;
            $bekleyen_ciro  = array();
            $iadeprim       = array();
            $sepet          = array();
            $dizim          = array();
            $sepet_sayisi = 0;

            foreach ($sor as $value) {
                $dizim[$value->siparis_durumu][] = 1;
                $p["fiyat"][$value->siparis_durumu][] = ((float)$value->fiyat - (float)$value->indirim);
                $gdd = $value->urun_adeti - $value->ilk_urun_adeti;

                if ($value->siparis_durumu == 6 OR $value->siparis_durumu == 7 OR $value->siparis_durumu == 8) {
                    $cirox[] = (float)$value->fiyat;
                    $indirim[] = (float)$value->indirim;
                    $net_ciro[] = (float)((float)$value->fiyat - (float)$value->indirim);


                }

                if ($value->siparis_durumu == 6) {
                    $iade_cirox[] = (float)$value->fiyat;
                }
                if ($value->siparis_durumu == 7) {
                    $bekleyen++;
                    $bekleyen_ciro[] = (float)$value->fiyat - (float)$value->indirim;
                }

                if ($value->ilk_urun_adeti > 0 AND $gdd > 0 AND ($value->siparis_durumu == 6 OR $value->siparis_durumu == 7 OR $value->siparis_durumu == 8) AND $value->siparis_tipi = 1) {
                    if ($value->siparis_durumu == 8) {
                        $prim[] = $gdd;
                    }
                    if ($value->siparis_durumu == 6) {
                        $iadeprim[] = $gdd;
                    }
                    $sepet[] = $gdd;

                }

                /**
                 * @PrimHesaplama:
                 * @Formula; (Total_Net_Endorsement * 0,02 - Total_Net_Extradited_Price * 0,015)
                 */

                /*if ($value->siparis_durumu == 8) {
                    $Prim += ($value->fiyat*(0.02));
                }
                if ($value->siparis_durumu == 6) {
                    $IadePrim += ($value->fiyat*(0.015));
                }
                $NetPrim += ($Prim-$IadePrim);*/

            }

            $TeslimCiro = (float) array_sum($p["fiyat"][8]);
            $IadeCiro = (float) array_sum($p["fiyat"][6]);

            $BekleyenCiro = array_sum($bekleyen_ciro);

            $Prim = ($TeslimCiro*(0.02));
            $IadePrim = ($IadeCiro*(0.015));
            $NetPrim = ($Prim-$IadePrim);

            $TeslimCiroToplam += $TeslimCiro;
            $IadeCiroToplam += $IadeCiro;

            $BekleyenToplam += $bekleyen;
            $BekleyenCiroToplam += $BekleyenCiro;
            $PrimToplam += $Prim;
            $IadePrimToplam += $IadePrim;
            $NetPrimToplam = ($PrimToplam-$IadePrimToplam);


            $tum_satislar = array_sum($dizim[6]) + array_sum($dizim[7]) + array_sum($dizim[8]);
            $kesinlesen = (int)array_sum($dizim[8]);
            $sepet_sayisi = (int)array_sum($sepet);
            #$ciro = number_format(array_sum($cirox), 2);
            $ciro = number_format((float)array_sum($net_ciro), 2);
            $iade_ciro = number_format(array_sum($iade_cirox), 2);
            $iade = (int)(array_sum($dizim[6]));
            $indirim = array_sum($indirim);
            $prim = array_sum($prim);
            $iadeprim = array_sum($iadeprim);


            $tum_satislar1 = $tum_satislar1 + $tum_satislar;
            $kesinlesen1 = $kesinlesen1 + $kesinlesen1;
            $sepet_sayisi1 = $sepet_sayisi1 + $sepet_sayisi;
            $ciro1 = ($ciro1 + array_sum($cirox));
            $iade_ciro1 = $iade_ciro1 + array_sum($iade_cirox);
            $indirim1 = $indirim1 + $indirim;
            $prim1 = $prim1 + $prim;

            $iade1 = $iade1 + $iade;


            if ($tum_satislar > 0) {

                echo '<tr>
            <!--<th scope="row">' . $x . '</th>-->
            <td>' . $perss->name_surname . '</td>
            <td>' . $tum_satislar . ' adet</td>
            <td>' . $kesinlesen . ' adet</td>
            <td style="color:green"><b>' . number_format($sepet_sayisi, 0, ',', '.') . ' adet </b></td>
            <td><b>' . number_format($indirim, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . $bekleyen . '</b></td>
            <td><b>' . number_format($BekleyenCiro, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . $ciro . ' ₺</b></td>
            <td><b>' . number_format($TeslimCiro, 2, ',', '.') . ' ₺</b></td>
            <td><b style="color:red">' . number_format($Prim, 2, ',', '.') . ' ₺</b></td>
            <td>' . $iade . ' adet</td>
            <td><b>' . number_format($IadeCiro, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($IadePrim, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($NetPrim, 2, ',', '.') . ' ₺</b></td>
           
          </tr>';
                $x++;
            }


        }

        echo '<tr style="background-color: #0385ea !important;color: #f1f1f1;text-shadow: 1px 1px 1px #002a80;">
            <!--<th scope="row">' . $x . '</th>-->
            <td>Genel Toplam</td>
            <td>' . $tum_satislar1 . ' adet</td>
            <td>' . $kesinlesen1 . ' adet</td>
            <td style="color:green"><b>' . number_format($sepet_sayisi1, 0, ',', '.') . ' adet</b></td>
             <td><b>' . number_format($indirim1, 2, ',', '.') . ' ₺</b></td>
             <td><b>' . $BekleyenToplam . '</b></td>
             <td><b>' . number_format($BekleyenCiroToplam, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($ciro1, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($TeslimCiroToplam, 2, ',', '.') . ' ₺</b></td>
            <td><b style="color:red">' . number_format($PrimToplam, 2, ',', '.') . ' ₺</b></td>
            <td>' . $iade1 . ' adet</td>
            <td><b>' . number_format($IadeCiroToplam, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($IadePrimToplam, 2, ',', '.') . ' ₺</b></td>
            <td><b>' . number_format($NetPrimToplam, 2, ',', '.') . ' ₺</b></td>
           
            <tr>';


        ?>


        </tbody>
    </table>
</div>










