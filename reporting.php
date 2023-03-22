<?php

require "excelzipp/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$readerxlsx = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$readerxls = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

$base_dir  = $_SERVER['DOCUMENT_ROOT']; // Absolute path to your installation, ex: /var/www/mywebsite
$longeur = $base_dir."/excel_export/element.xlsx";
//$spreadsheet = $reader->load($longeur);
//$sheetData = $spreadsheet->getActiveSheet()->toArray();

if (isset($_POST['importer'])){
    //fichier excel


    if(isset($_FILES['document'])) {

        $filename = $_FILES['document']['name'];
        $filetmpname = $_FILES['document']['tmp_name'];
        $ext = pathinfo($filename,PATHINFO_EXTENSION);
        $folder = 'excel_export/';
        move_uploaded_file($filetmpname, $folder.$filename);
        $excelFile = $base_dir."/excel_export/".$filename;
        if ($ext == 'xlsx'){
            $spreadsheet = $readerxlsx->load($excelFile);
        }else{
            $spreadsheet = $readerxls->load($excelFile);
        }

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);

        foreach ($sheetData as $t) {
            $id_action = (int)$t[0];
            $quantite = (int)$t[1];
            $prix_cours = (int)$t[3];
            $date_jour = date('Y-m-d');
            $bdd->query("insert into import(id_achat, montant, date_import) VALUES ('$id_action',$prix_cours,'$date_jour')");
        }

        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Importation effectué avec succès!</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
        ';

    }


}



function DivisionPar0($x,$y){
    if ($y == 0) return 0 ; else return $x / $y;
}

function cal_percentage($num_amount, $num_total) {
    $count1 = DivisionPar0($num_amount,$num_total);
    $count2 = $count1 * 100;
    return $count2;
}


function roundElement($type,$data){


    if (is_null($data) or is_nan($data) or is_infinite($data) or $data < 0 ){
        if ($type ==4 or $type ==5){
            $valeur = $data;
        }else{
            $valeur = 0;
        }

    }else{
        $valeur = $data;

    }


    if ($valeur == 0):
        return " ";
    elseif (is_float($valeur)):
        return number_format($valeur, 3, ',', ' ');
    else:
        return number_format($valeur, 0, ',', ' ');
    endif;

}

function roundElementPvalue($data){
    if (is_null($data) or is_nan($data) or is_infinite($data) or $data < 0 ){
        $valeur = 0;
    }else{
        $valeur = round($data,3);

    }

    if (is_float($data))
        return number_format($valeur, 3, ',', ' ');
    else
        return number_format($valeur, 0, ',', ' ');


}

$plafond_sommaire = 1000000000;
$mois = date('m');
$year = date('Y');
$mois_1 = date('m',strtotime('-1 month'));
$jour_restant = cal_days_in_month(CAL_GREGORIAN,$mois,$year);
$calcul_365 = ($jour_restant / 365);
//
$encaisse = array(0,0,0,0,0,0,0,0,0);
$titres_etat = array(0,0,0,0,0,0,0,0,0);
$titres_garantie_etat = array(0,0,0,0,0,0,0,0,0);
$depot_bancaire = array(0,0,0,0,0,0,0,0,0);
$titre_365 = array(0,0,0,0,0,0,0,0,0);
$titre_note_aaa_neg = array(0,0,0,0,0,0,0,0,0);
$agrre_crpmf = array(0,0,0,0,0,0,0,0,0);
$dettes_bbbb_neg = array(0,0,0,0,0,0,0,0,0);
$dettes_brvm = array(0,0,0,0,0,0,0,0,0);
$actions_brvm = array(0,0,0,0,0,0,0,0,0);
$parts_opcvm = array(0,0,0,0,0,0,0,0,0);
$total_variables = array(0,0,0,0,0,0,0,0,0);
$revenu_final = 0;
$revenu_final_pourcenatge = 0;
$montant_atteindre = 75000000;


//encaisse
include "calcul/encaisse.php";


//titres_etat
include "calcul/titre_etat.php";


//depot_bancaire
include "calcul/depot_banque.php";


//agrre crmpf
include "calcul/agrree_crmpf.php";

//dette_brvm
include "calcul/dette_brmv.php";

//actions_brvm
include "calcul/action_vrm.php";







$array_data = array($encaisse,$titres_etat,$titres_garantie_etat,$dettes_bbbb_neg,$depot_bancaire,$agrre_crpmf,$titre_365,$dettes_brvm,$actions_brvm,$parts_opcvm,$titre_note_aaa_neg);

$bon_valeur = array();

foreach ($array_data as $item):
    for($x = 0; $x <= 8; $x++):

        $total_variables[$x] = $total_variables[$x] + $item[$x];
    endfor;
endforeach;

foreach ($array_data as $item):

    $revenu_final = $revenu_final + $item[5];

endforeach;



$revenu_final_pourcenatge = cal_percentage($revenu_final,$montant_atteindre);

?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-uppercase text-primary">Importer fichier Excel</h3>
    </div>
    <div class="container-fluid">
        <form action="" enctype="multipart/form-data" method="post">
            <div class="row ">
                <!-- ue -->
                <div class="col-md-8 mt-3 mb-3">
                    <div class="form-group" >
                        <label class="col-form-label-lg font-weight-bold text-uppercase">Choisir Fichier Excel</label>
                        <input name="document" class="form-control form-control-lg"
                               accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                               type="file" >
                    </div>
                </div>
                <!-- ecue -->


            </div>
            <div align="right">
                <button type="submit" name="importer" class="btn btn-success btn-lg">IMPORTER
                </button>
            </div>
            <br>
        </form>
    </div>
</div>




<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-uppercase text-primary">PORTFOLIO TRADING POUR COMPTE PROPRE</h3>
    </div>
    <div class="card-body">
        <table border="1" class="table table-bordered w-100">
            <thead>
            <tr style="font-size: 15px;vertical-align: middle" class="text-uppercase text-center font-weight-bold">
                <th style="border-bottom: 2px solid #3a3b45;" class="text-white" colspan="2">

                </th>
                <th class="text-white bg-primary text-white" style="vertical-align: middle;border-bottom: 2px solid #3a3b45" colspan="4">
                    Historiques
                </th>
                <th class="bg-danger text-white text-center" style="vertical-align: middle;border-bottom: 2px solid #3a3b45" colspan="4">
                    Performances
                </th>

                <th class="bg-secondary text-white text-center" style="vertical-align: middle;border-bottom: 2px solid #3a3b45" colspan="6">
                    Valorisation
                </th>



                <th class="bg-success text-white text-center" style="vertical-align: middle;border-bottom: 2px solid #3a3b45" colspan="5">
                    Revenu
                </th>



            </tr>

            <tr style="font-size: 15px;vertical-align: middle" class="text-uppercase text-center font-weight-bold">
                <th style="border-bottom: 2px solid #3a3b45;">
                    Typologie d'actifs
                </th>
                <th style="border-bottom: 2px solid #3a3b45;">
                    Désignation
                </th>
                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    <?php echo $mois_1.'/'.$year ?>
                </th>
                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    <?php echo $mois.'/'.$year ?>
                </th>
                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    Mensuel (<?php echo $mois.'/'.$year ?>)
                </th>
                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    Annuel (<?php echo $year ?>)
                </th>



                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    (+)/- value
                </th>

                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    (+)/-value (%)
                </th>

                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    valeur
                </th>


                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    Revenu à date (<?php echo $mois.'/'.$year ?>)
                </th>
                <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
                    Revenu depuis création
                </th>
                <th style="border-bottom: 2px solid #3a3b45;">
                    Revenu attendu
                </th>

            </tr>

            </thead>

            <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 12.5px">

            <tr>
                <td rowspan="5" class="text-center" STYLE="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 1</td>
                <td>Encaisse</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$encaisse[$x]) ?></td>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="5">
                        <?php echo roundElement($x,$encaisse[$x] + $titres_etat[$x] + $titres_garantie_etat[$x] + $depot_bancaire[$x] + $titre_365[$x]) ?>
                    </td>
                <?php
                endfor;
                ?>
                <td  class="text-center bg-success font-weight-bold text-white" style="font-size: 25px;vertical-align: middle" rowspan="11">75 000 000</td>
            </tr>
            <tr>
                <td>Titres des états</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$titres_etat[$x]) ?></td>
                <?php
                endfor;
                ?>
            </tr>
            <tr>
                <td>Titres garantis par les états et institutions financières de premier rang</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$titres_garantie_etat[$x]) ?></td>
                <?php
                endfor;
                ?>
            </tr>
            <tr>
                <td>Depot bancaires</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$depot_bancaire[$x]) ?></td>
                <?php
                endfor;
                ?>
            </tr>
            <tr>
                <td>Titres de dette de - de 365 jours</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$titre_365[$x]) ?></td>
                <?php
                endfor;
                ?>
            </tr>
            <tr>
                <td rowspan="2" class="text-center" STYLE="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2A</td>
                <td>Titres de dettes notés au moins AA- à long terme</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$titre_note_aaa_neg[$x]) ?></td>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="2">
                        <?php echo roundElement($x,$titre_note_aaa_neg[$x] + $agrre_crpmf[$x]) ?>
                    </td>
                <?php
                endfor;
                ?>

            </tr>
            <tr>

                <td>Titres de dettes cotés et garantis par des garants agréés CREPMF</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$agrre_crpmf[$x]) ?></td>

                <?php
                endfor;
                ?>




            </tr>

            <tr>
                <td rowspan="4" class="text-center" style="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2B</td>
                <td>Titres de dettes notés entre BBB- et A+ à long terme</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$dettes_bbbb_neg[$x]) ?></td>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="4">
                        <?php echo roundElement($x,$dettes_bbbb_neg[$x] + $dettes_brvm[$x] + $actions_brvm[$x] + $parts_opcvm[$x]) ?>
                    </td>
                <?php
                endfor;
                ?>
            </tr>
            <tr>
                <td>Titres de dettes d'entreprises cotées à la BRVM</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$dettes_brvm[$x]) ?></td>

                <?php
                endfor;
                ?>


            </tr>
            <tr>
                <td>Actions cotées à la BRVM</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$actions_brvm[$x]) ?></td>

                <?php
                endfor;
                ?>


            </tr>
            <tr>
                <td>Parts d'OPCVM</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($x,$parts_opcvm[$x]) ?></td>

                <?php
                endfor;
                ?>
            </tr>


            </tbody>
            <tfoot>
            <?php

            ?>
            <tr style="font-size: 15px" class="text-uppercase bg-danger text-white text-right font-weight-bold">
                <td colspan="2" >Total</td>
                <?php
                for($x = 0; $x <= 8; $x++):
                    ?>
                    <td>
                        <?php echo roundElement($x,$total_variables[$x]) ?>
                    </td>
                    <td>
                        <?php echo roundElement($x,$total_variables[$x]) ?>
                    </td>
                <?php
                endfor;
                ?>
                <td bgcolor="black"></td>

            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div align="right" class="mt-3 mb-3">
    <button class="btn btn-outline-primary btn-lg text-uppercase" onclick="exportDataEdition()" type="button">Exporter vers excel</button>
</div>

<script>


    function exportDataEdition() {

        window.open("export_reporting.php");

    }

</script>