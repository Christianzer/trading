<?php

session_start();

ini_set('display_errors', 'off');
setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('Africa/Abidjan');


class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance == null) {
            try {
                self::$instance = new PDO('mysql:host=127.0.0.1; dbname=trading; charset=utf8', 'root', '');
            } catch (PDOException $e) {
                // Handle this properly
                throw $e;
            }
        }
        return self::$instance;
    }
}

$bdd = DB::get();

$plafond_sommaire = 1000000000;
$mois = date('m');
$year = date('Y');
$mois_1 = date('m',strtotime('-1 month'));
$jour_restant = cal_days_in_month(CAL_GREGORIAN,$mois,$year);
$calcul_365 =(float) ($jour_restant / 365);
//
$encaisse = array(0,0,0,0,0,0);
$titres_etat = array(0,0,0,0,0,0);
$titres_garantie_etat = array(0,0,0,0,0,0);
$depot_bancaire = array(0,0,0,0,0,0);
$titre_365 = array(0,0,0,0,0,0);
$titre_note_aaa_neg = array(0,0,0,0,0,0);
$agrre_crpmf = array(0,0,0,0,0,0);
$dettes_bbbb_neg = array(0,0,0,0,0,0);
$dettes_brvm = array(0,0,0,0,0,0);
$actions_brvm = array(0,0,0,0,0,0);
$parts_opcvm = array(0,0,0,0,0,0);
$total_variables = array(0,0,0,0,0,0);
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




function roundElementPvalue($data){
    if (is_null($data) or is_nan($data) or is_infinite($data)){
        $valeur = 0;
    }else{
        $valeur = round($data,3);

    }

    if (is_float($data))
        return number_format($valeur, 3, ',', ' ');
    else
        return number_format($valeur, 0, ',', ' ');


}



$array_data = array($encaisse,$titres_etat,$titres_garantie_etat,$dettes_bbbb_neg,$depot_bancaire,$agrre_crpmf,$titre_365,$dettes_brvm,$actions_brvm,$parts_opcvm,$titre_note_aaa_neg);


foreach ($array_data as $item):
    for($x = 0; $x <= 5; $x++):
        $total_variables[$x] = $total_variables[$x] + $item[$x];
    endfor;
endforeach;

foreach ($array_data as $item):

    $revenu_final = $revenu_final + $item[5];

endforeach;

function calculerPourcentage($valeur, $total) {
    $pourcentage = ($valeur / $total) * 100;
    return $pourcentage;
}

$revenu_final_pourcenatge = calculerPourcentage($revenu_final,$montant_atteindre);

function roundElement($data){

    if (is_null($data) or is_nan($data) or is_infinite($data) or $data<0){
        $valeur = 0;
    }else{
        $valeur = $data;

    }



    if (is_float($valeur))
        return number_format($valeur, 3, ',', ' ');
    else
        return number_format($valeur, 0, ',', ' ');

}

$roundElement = 'roundElement';

$output = <<<EOD
<table cellspacing="0" border="1" style="width: 100%; text-align: center">
    <thead>
    <tr style="font-size: 15px;vertical-align: middle" class="text-uppercase text-center font-weight-bold">
        <th style="border-bottom: 2px solid #3a3b45;" class="text-white" colspan="2">

        </th>
        <th class="text-white bg-primary text-white" colspan="4">
            En cours
        </th>
        <th class="bg-danger text-white text-center" colspan="4">
            Performances
        </th>
        <th class="bg-success text-white text-center" colspan="5">
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
           $mois_1-$year
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
           $mois-$year
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Mensuel $mois-$year
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Annuel $year
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Revenu à date $mois-$year
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
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($encaisse[$x])}</td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="5">
                {$roundElement($encaisse[$x] + $titres_etat[$x] + $titres_garantie_etat[$x] + $depot_bancaire[$x] + $titre_365[$x])}
            </td>
EOD;
        endfor;
        $output .= <<<EOD
        <td  class="text-center bg-success font-weight-bold text-white" style="font-size: 25px;vertical-align: middle" rowspan="11">75 000 000</td>
    </tr>
    <tr>
        <td>Titres des états</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($titres_etat[$x])}</td>
EOD;
        endfor;
        $output .= <<<EOD
    </tr>
    <tr>
        <td>Titres garantis par les états et institutions financières de premier rang</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($titres_garantie_etat[$x])}</td>
EOD;
        endfor;
        $output .= <<<EOD
    </tr>
    <tr>
        <td>Depot bancaires</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($depot_bancaire[$x])}</td>
EOD;
        endfor;
        $output .= <<<EOD
    </tr>
    <tr>
        <td>Titres de dette de - de 365 jours</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($titre_365[$x])}</td>
EOD;
        endfor;
        $output .= <<<EOD
    </tr>
    <tr>
        <td rowspan="2" class="text-center" STYLE="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2A</td>
        <td>Titres de dettes notés au moins AA- à long terme</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($titre_note_aaa_neg[$x])}</td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="2">
                {$roundElement($titre_note_aaa_neg[$x] + $agrre_crpmf[$x])}
            </td>
EOD;
        endfor;
        $output .= <<<EOD

    </tr>
    <tr>

        <td>Titres de dettes cotés et garantis par des garants agréés CREPMF</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($agrre_crpmf[$x])}</td>

EOD;
        endfor;
        $output .= <<<EOD




    </tr>

    <tr>
        <td rowspan="4" class="text-center" style="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2B</td>
        <td>Titres de dettes notés entre BBB- et A+ à long terme</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($dettes_bbbb_neg[$x])}</td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="4">
                {$roundElement($dettes_bbbb_neg[$x] + $dettes_brvm[$x] + $actions_brvm[$x] + $parts_opcvm[$x])}
            </td>
EOD;
        endfor;
        $output .= <<<EOD
    </tr>
    <tr>
        <td>Titres de dettes d'entreprises cotées à la BRVM</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($dettes_brvm[$x])}</td>

EOD;
        endfor;
        $output .= <<<EOD


    </tr>
    <tr>
        <td>Actions cotées à la BRVM</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($actions_brvm[$x])}</td>

EOD;
        endfor;
        $output .= <<<EOD


    </tr>
    <tr>
        <td>Parts d'OPCVM</td>
EOD;
        for($x = 0; $x <= 5; $x++):
            $output .= <<<EOD
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle">{$roundElement($parts_opcvm[$x])}</td>

EOD;
        endfor;
        $output .= <<<EOD
    </tr>


    </tbody>
    
    
    <tfoot>
    <tr style="font-size: 15px" class="text-uppercase bg-danger text-white text-right font-weight-bold">
        <td colspan="2" >Total</td>
EOD;
for($x = 0; $x <= 5; $x++):
$output .= <<<EOD
            <td>
                {$roundElement($total_variables[$x])}
            </td>
            <td>
                {$roundElement($total_variables[$x])}
            </td>
EOD;
endfor;
$output .= <<<EOD
        <td bgcolor="black"></td>

    </tr>
    </tfoot>
    
  
</table>
EOD;
$variable = "reporting_du_".date('d-m-Y').".xls";
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename='.$variable);
echo $output;
?>

