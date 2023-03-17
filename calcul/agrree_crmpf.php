<?php
$total_agrre_crpmf = 0;
$total_agrre_crpmf_creation = 0;
$agrre_crpmf_mois_1 = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_garant = 1 and MONTH(trade.date_trade) = $mois_1")->fetchColumn();
$agrre_crpmf_mois = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_garant = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();
$agrre_crpmf_mars = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_garant = 1 and MONTH(trade.date_trade) = 3")->fetchColumn();
$agrre_crpmf_mensuelle = DivisionPar0($agrre_crpmf_mois , $agrre_crpmf_mois_1) - 1;
$agrre_crpmf_annuelle = DivisionPar0($agrre_crpmf_mois , $agrre_crpmf_mars) - 1;


$revenus_date_agrre_crpmf = $bdd->query("select tr.capital,tr.taux from trade tr join achat a on tr.id_achat = a.id_achat where  a.id_garant = 1 and MONTH(tr.date_trade) = $mois ")->fetchAll();
foreach ($revenus_date_agrre_crpmf as $item) {
    $information_agrre_crpmf = ($item[0] * $item[1]) / 100;
    $valeur_agrre_crpmf = ($information_agrre_crpmf * $calcul_365);
    $total_agrre_crpmf = $total_agrre_crpmf + $valeur_agrre_crpmf;
}

$revenus_creation_agrre_crpmf = $bdd->query("select tr.capital,tr.taux,MONTH(tr.date_trade),YEAR(tr.date_trade) from trade tr join achat a on tr.id_achat = a.id_achat where  a.id_garant = 1")->fetchAll();
foreach ($revenus_creation_agrre_crpmf as $item) {
    $mois_creation_agrre_crpmf = $item[2];
    $year_creation_agrre_crpmf = $item[3];
    $jour_restant_agrre_crpmf = cal_days_in_month(CAL_GREGORIAN, $mois_creation_agrre_crpmf, $year_creation_agrre_crpmf);
    $calcul_365_agrre_crpmf = (float)($jour_restant_agrre_crpmf / 365);
    $information_agrre_crpmf_creation = ($item[0] * $item[1]) / 100;
    $valeur_agrre_crpmf_creation = ($information_agrre_crpmf_creation * $calcul_365_agrre_crpmf);
    $total_agrre_crpmf_creation = $total_agrre_crpmf_creation + $valeur_agrre_crpmf_creation;
}


//mensuel
$agrre_crpmf[0] = $agrre_crpmf_mois_1;
$agrre_crpmf[1] = $agrre_crpmf_mois;
$agrre_crpmf[2] = $agrre_crpmf_mensuelle;
$agrre_crpmf[3] = $agrre_crpmf_annuelle;
$agrre_crpmf[4] = $total_agrre_crpmf;
$agrre_crpmf[5] = $total_agrre_crpmf_creation;