<?php if (!defined("idokey")) {
    exit();
}?>

<div class="col-sm-6 col-md-3">
    <div class="panel panel-primary panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">E-Ticaret</small>
                        <h1><?=$dizim[4];?> adet</h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <small class="stat-label">Dün</small>
                <h4><?=(int)$dizim_dun[4];?> adet</h4>

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3" <?=$div_statu?> >
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">Bugün Sipariş</small>
                        <h1><?=(int)$dizim["1"]?> adet</h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <div class="row">
                    <div class="col-xs-6">
                        <small class="stat-label">Dün</small>
                        <h4><?=(int)$dizim_dun["1"]?> adet</h4>
                    </div>


                </div>
                <!-- row -->
            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->

<style>
    .refreshing-kuyruk{
        position: absolute;
        z-index: 1;
        right: 25px;
    }
</style>
<div class="col-sm-6 col-md-3">
    <div class="panel panel-danger panel-stat">
        <div class="panel-heading">
            <button type="button" class="close refreshing-kuyruk" aria-label="Refresh">
                <span aria-hidden="true">
                    <img src="images/loaders/loader3.gif">
                </span>
            </button>
            <div class="stat">
                <div id="t_kuyruk">
                    <center><img src="images/19-1.gif"></center>
                </div>
                <div id="t_kuyruk_yaz"></div>


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3" style="display:none;">
    <div class="panel panel-danger panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">Arama Talebi</small>
                        <h1><?=$dizim["2"]?> adet</h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <small class="stat-label">Dün</small>
                <h4><?=(int)$dizim_dun["2"]?> adet</h4>

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3">
    <div class="panel panel-dark panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">Bugün Kasa</small>
                        <h1><?=number_format($esqlx->ciro, 2)?> </h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <div class="row">
                    <div class="col-xs-4">
                        <small class="stat-label">Satış Adeti</small>
                        <h4><?=$esqlx->adet?></h4>
                    </div>
                    <div class="col-xs-4">
                        <small class="stat-label">Fatura Orta.</small>
                        <h4><?=number_format($esqlx->ciro / $esqlx->adet, 2)?></h4>
                    </div>
                    <div class="col-xs-4">
                        <h4><a href="pages.php?ido=rpr&status=7" class="btn btn-lightblue mr5">Listele</a></h4>
                    </div>
                </div>
                <!-- row -->

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div>

<?php

foreach($_APIS as $_API){

    $link_types = null;

    $apis = array();
    $apis_type = array();
    $ApiApplications = $db->get_results("SELECT * FROM `api_applications` WHERE `user_id` = '".$_API->id."'");
    foreach($ApiApplications AS $ApiApplication){
        $apis[] = $ApiApplication->id;
        $apis_type[] = $ApiApplication->siparis_tipi;
        $link_types .= "&type[]=".$ApiApplication->siparis_tipi;
    }
    $apis = implode(",", $apis);
    $apis_type = implode(",", $apis_type);
    $_API_CALC = $db->get_row("SELECT SUM(fiyat) AS ciro, COUNT(*) AS adet FROM  siparisler WHERE  (islem_tarihi BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:59:59')  AND  siparis_durumu IN (7,8) AND e_satis = 0 AND private_api IN(".$apis.") AND siparis_tipi IN(".$apis_type.")");

?>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-warning panel-stat">
            <div class="panel-heading">

                <div class="stat">
                    <div class="row">
                        <div class="col-xs-4">
                            <img src="images/is-money.png" alt=""/>
                        </div>
                        <div class="col-xs-8">
                            <small class="stat-label"><?=$_API->name;?></small>
                            <h1><?=number_format($_API_CALC->ciro, 2);?> </h1>
                        </div>
                    </div>
                    <!-- row -->

                    <div class="mb15"></div>

                    <div class="row">
                        <div class="col-xs-4">
                            <small class="stat-label">Satış Adeti</small>
                            <h4><?=$_API_CALC->adet?></h4>
                        </div>

                        <div class="col-xs-4">
                            <small class="stat-label">Fatura Orta.</small>
                            <h4><?=number_format($_API_CALC->ciro / $_API_CALC->adet, 2)?></h4>
                        </div>
                        <div class="col-xs-4">
                            <h4>
                                <a href="pages.php?ido=api_siparis_listele&status[]=7&status[]=8<?=$link_types;?>&api_name=<?=$_API->name?>" class="btn btn-lightblue mr5">Listele</a>
                            </h4>
                            <h4>
                                <a target="backservice" href="inc/api_kargo_export.php?api_name=<?=$_API->name?>" class="btn btn-lightblue mr5">Kargo Aktar</a>
                            </h4>
                        </div>
                    </div>
                    <!-- row -->

                </div>
                <!-- stat -->

            </div>
            <!-- panel-heading -->
        </div>
        <!-- panel -->
    </div>
<?php
}
?>

<div class="col-sm-6 col-md-3">
    <div class="panel panel-dark panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">Ekstra Satışlar</small>
                        <h1><?=number_format($esql_EX->ciro, 2)?> </h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <div class="row">
                    <div class="col-xs-4">
                        <small class="stat-label">Satış Adeti</small>
                        <h4><?=$esql_EX->adet?></h4>
                    </div>
                    <div class="col-xs-4">
                        <small class="stat-label">Fatura Orta.</small>
                        <h4><?=number_format($esql_EX->ciro / $esql_EX->adet, 2)?></h4>
                    </div>
                    <div class="col-xs-4">
                        <h4><a href="pages.php?ido=rpr&status=7&type=7" class="btn btn-lightblue mr5">Listele</a></h4>
                    </div>
                </div>
                <!-- row -->

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div>

<div class="col-sm-6 col-md-3">
    <div class="panel panel-dark panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-money.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small class="stat-label">Tele Satışlar</small>
                        <h1><?=number_format($esql_TELE->ciro, 2)?> </h1>
                    </div>
                </div>
                <!-- row -->

                <div class="mb15"></div>

                <div class="row">
                    <div class="col-xs-6">
                        <small class="stat-label">Satış Adeti</small>
                        <h4><?=$esql_TELE->adet?></h4>
                    </div>

                    <div class="col-xs-6">
                        <small class="stat-label">Fatura Orta.</small>
                        <h4><?=number_format($esql_TELE->ciro / $esql_TELE->adet, 2)?></h4>
                    </div>
                </div>
                <!-- row -->

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div>

<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/screen.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Müşteri Kartları</small>
                        <button class="btn btn-primary mr5 btn-lg" data-toggle="modal"
                                data-target=".bs-example-modal-lg">Müşteri Ara
                        </button>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-document.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Kargo</small>
                        <button class="btn btn-primary mr5 btn-lg" data-toggle="modal" data-target=".kargoara">
                            Kargo Listesi Al
                        </button>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-document.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Kargo Listesi Çek</small>
                        <a href="inc/this_excel_kargocek.php" class="btn btn-primary mr5 btn-lg">Kargo Listesi Çek</a>
                    </div>


                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3" <?=$div_statu?>>
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/screen.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Data işlemleri</small>
                        <button class="btn btn-primary mr5 btn-lg" data-toggle="modal" data-target=".dataView">Data
                            Aktar
                        </button>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3 hidden" <?=$div_statu?>>
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/cargoPost.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Karo İşlemleri</small>
                        <a href="pages.php?ido=KargoPost" class="btn btn-primary mr5 btn-lg">Kargo Gönder</a>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-document.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Kilit Sipariş</small>
                        <br>
                        <button class="btn btn-primary mr5 btn-lg" onclick="SipReset();">Sıfırla</button>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->

<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-document.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>Günlük Satış</small>
                        <br>
                        <a href="pages.php?ido=satisrapor" class="btn btn-primary mr5 btn-lg">Satış rapor</a>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->


<div class="col-sm-6 col-md-3">
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="images/is-document.png" alt=""/>
                    </div>
                    <div class="col-xs-8">
                        <small>İleri Tarihte Aranacak Listesi</small>
                        <br>
                        <a href="pages.php?ido=ileriTarihTeAra" class="btn btn-primary mr5 btn-lg">Listeyi Aç</a>
                    </div>
                </div>
                <!-- row -->

            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->
<?php
'
<div class="col-sm-6 col-md-3" <?=$div_statu?>>
    <div class="panel panel-success panel-stat">
        <div class="panel-heading">

            <div class="stat">
                <div class="row">
                    <div class="col-xs-4">
                        <!--<img src="images/cargoPost.png" alt=""/>-->
                        <span style="font-size: 70px;color: #1D2939;" class="glyphicon glyphicon-qrcode"></span>
                    </div>
                    <div class="col-xs-8">
                        <small>Fatura İşlemleri</small>
                        <a href="pages.php?ido=FaturaPrint" class="btn btn-primary mr5 btn-lg">Fatura Yazdır</a>
                    </div>
                </div>
                <!-- row -->


            </div>
            <!-- stat -->

        </div>
        <!-- panel-heading -->
    </div>
    <!-- panel -->
</div><!-- col-sm-6 -->';
?>