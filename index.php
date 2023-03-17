<?php
session_start();

ini_set('display_errors', 'off');
setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('Africa/Abidjan');


function active($currect_page){
    $url_array =  explode('?page=', $_SERVER['REQUEST_URI']) ;
    $url = end($url_array);
    if($currect_page == $url){
        echo 'active'; //class name in css
    }
}


class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance == null) {
            try {
                self::$instance = new PDO('mysql:host=127.0.0.1; dbname=trading; charset=utf8', 'root', '1234');
            } catch (PDOException $e) {
                // Handle this properly
                throw $e;
            }
        }
        return self::$instance;
    }
}

$bdd = DB::get();

$fond_propre = $bdd->query("SELECT montant from caisse where type_caisse = 3 order by date_achat desc")->fetchColumn();
if ((int)$fond_propre == 0){
    $placement_propre = 1000000000;
}else{
    $placement_propre = $fond_propre;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit =no">
    <title>TRADING PLATEFORME</title>
    <link href="assets/css/sb-admin-2.css?version=1" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <script src="vendor/jquery/jquery.js"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.checkboxes.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/toaster/bootstrap-toaster.css" rel="stylesheet" type="text/css">
    <!-- select 2 -->
    <link href="vendor/select2/select2.min.css" rel="stylesheet"/>

    <style>
        /*Code to change color of active link*/
        .navbar-expand > .navbar-nav > .active > a {
            color: red;
        }
    </style>
</head>


<body id="page-top">
<!-- Page Wrapper -->

<div id="wrapper">



    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand topbar mb-4 static-top" style="background-color: white !important">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>


                <!-- Topbar Navbar -->
                <ul class="navbar-nav d-flex align-items-center">



                    <li class="nav-item <?php active('reglementation');?>">
                        <a class="nav-link text-uppercase font-weight-bold" href="index.php?page=reglementation">
                            réglémentations
                        </a>
                    </li>

                    <li class="nav-item  <?php active('ventes');?>">
                        <a class="nav-link text-uppercase font-weight-bold" href="index.php?page=ventes">
                            ventes
                        </a>
                    </li>

                    <li class="nav-item  <?php active('reporting');?>">
                        <a class="nav-link text-uppercase font-weight-bold" href="index.php?page=reporting">
                            reporting
                        </a>
                    </li>

                    <li class="nav-item  <?php active('caisse');?>">
                        <a class="nav-link text-uppercase font-weight-bold" href="index.php?page=caisse">
                            caisse
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item  no-arrow">
                        <a class="nav-link" onclick="renitialiser()">
                            <span class="mr-2  font-weight-bold  btn btn-danger rounded-0 text-uppercase"> <span
                                        class="d-none d-lg-inline">■</span> Renitialiser LA BD</span>
                        </a>
                    </li>
                </ul>


            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">



                <?php
                if (isset($_GET['page'])) {
                    switch ($_GET['page'])
                    {
                        case 'reglementation': include("trade.php");
                            break;

                        case 'ventes': include("vente.php");
                            break;

                        case 'reporting': include ("reporting.php");
                            break;

                        case 'caisse': include ("caisse.php");
                            break;

                        default : include ("trade.php");
                    }
                }else{
                    header("Location: index.php?page=reglementation");
                }

                ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->


        <form action="" method="post">
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white font-weight-bold">
                            <h5 class="modal-title " id="exampleModalLongTitle">CAISSE</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="content_departement">
                                        <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Placement</label>
                                        <select name="type_caisse" class="form-control" required>
                                            <option value="1">Encaisse</option>
                                            <option value="2">DEPOT BANCAIRES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="content_date">
                                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Montant</label>
                                        <input class="form-control" type="number" name="date_trade" required value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
    <!-- End of Content Wrapper -->

</div>


<!-- Bootstrap core JavaScript-->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="vendor/datatable_french.js"></script>
<script src="vendor/toaster/bootstrap-toaster.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $("ul.navbar-nav > li").click(function (e) {
            $("ul.navbar-nav > li").removeClass("active");
            $(this).addClass("active");
        });


    });

    function renitialiser() {
        $.ajax({
            url: "renitialiser.php",
            method: 'GET',
            data: {renitialiser: 1},
            success: function (data) {
               var rep =  confirm("Base de données rénitialisé avec success")
                if (rep){
                    window.location.reload();
                }

            }
        });
    }

    function lancerModal(){
        $('#exampleModalCenter').modal('show')
    }
</script>
</body>
</html>

