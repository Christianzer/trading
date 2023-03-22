<?php
$total_dettes_brvm = 0;
$total_dettes_brvm_creation = 0;
$dettes_brvm_mois_1 = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = $mois_1")->fetchColumn();
$dettes_brvm_mois = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = $mois")->fetchColumn();
$dettes_brvm_mars = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = 3")->fetchColumn();
$dettes_brvm_mensuelle = DivisionPar0($dettes_brvm_mois , $dettes_brvm_mois_1) - 1;
$dettes_brvm_annuelle = DivisionPar0($dettes_brvm_mois , $dettes_brvm_mars) - 1;


$revenus_date_dettes_brvm = $bdd->query("select tr.capital,tr.taux from trade tr join achat a on tr.id_achat = a.id_achat where  a.id_type_titre = 2 and MONTH(tr.date_trade) = $mois ")->fetchAll();
foreach ($revenus_date_dettes_brvm as $item) {
    $information_dettes_brvm = ($item[0] * $item[1]) / 100;
    $valeur_dettes_brvm = ($information_dettes_brvm * $calcul_365);
    $total_dettes_brvm = $total_dettes_brvm + $valeur_dettes_brvm;
}

$revenus_creation_dettes_brvm = $bdd->query("select tr.capital,tr.taux,MONTH(tr.date_trade),YEAR(tr.date_trade) from trade tr join achat a on tr.id_achat = a.id_achat where  a.id_type_titre = 2")->fetchAll();
foreach ($revenus_creation_dettes_brvm as $item) {
    $mois_creation_dettes_brvm = $item[2];
    $year_creation_dettes_brvm = $item[3];
    $jour_restant_dettes_brvm = cal_days_in_month(CAL_GREGORIAN, $mois_creation_dettes_brvm, $year_creation_dettes_brvm);
    $calcul_365_dettes_brvm = (float)($jour_restant_dettes_brvm / 365);
    $information_dettes_brvm_creation = ($item[0] * $item[1]) / 100;
    $valeur_dettes_brvm_creation = ($information_dettes_brvm_creation * $calcul_365_dettes_brvm);
    $total_dettes_brvm_creation = $total_dettes_brvm_creation + $valeur_dettes_brvm_creation;
}

//calculpvalue
//recuperation du montantReeel dans import
$reel_import_dettes_brvm = (int) $bdd->query("select import.montant from import join achat a on import.id_achat = a.id_achat 
                      where a.id_type_titre = 2 and MONTH(import.date_import) = $mois order by import.date_import desc limit 1")->fetchColumn();

//recuperation de la quantite trader
$quantiteTrader_dettes_brvm = (int) $bdd->query("select sum(trade.quantite) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = $mois")->fetchColumn();


//on recupère tous les montants saisies
$montant_saisie_dettes_brvm = (int) $bdd->query("select sum(trade.montant) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = $mois")->fetchColumn();


//on recupère le nombre de saisie
$nombres_saisie_dettes_brvm = (int) $bdd->query("select count(trade.id_achat) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 2 and MONTH(trade.date_trade) = $mois")->fetchColumn();


$cmp_dettes_brvm = DivisionPar0($montant_saisie_dettes_brvm,$nombres_saisie_dettes_brvm);

$montant_total_cmp_dettes_brvm = $cmp_dettes_brvm * $quantiteTrader_dettes_brvm;

//valeurReeel
$valeurreel_dettes_brvm= $reel_import_dettes_brvm * $quantiteTrader_dettes_brvm;


$montant_pvalue_dettes_brvm = $valeurreel_dettes_brvm - $montant_total_cmp_dettes_brvm;

$pourcentage_pvalue_dettes_brvm = cal_percentage($montant_pvalue_dettes_brvm,$valeurreel_dettes_brvm);


//mensuel

$dettes_brvm[0]=$dettes_brvm_mois_1;
$dettes_brvm[1]=$dettes_brvm_mois;
$dettes_brvm[2]=$dettes_brvm_mensuelle;
$dettes_brvm[3]=$dettes_brvm_annuelle;
$dettes_brvm[4]=$montant_pvalue_dettes_brvm;
$dettes_brvm[5]=$pourcentage_pvalue_dettes_brvm;
$dettes_brvm[6]=$valeurreel_dettes_brvm;
$dettes_brvm[7]=$total_dettes_brvm;
$dettes_brvm[8]=$total_dettes_brvm_creation;

