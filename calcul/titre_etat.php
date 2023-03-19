<?php
$total_titres_etat = 0;
$total_titres_etat_creation = 0;
$titres_etat_mois_1 =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois_1")->fetchColumn();
$titres_etat_mois =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois")->fetchColumn();
$titres_etat_mars =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = 3")->fetchColumn();
$titres_etat_mensuelle = DivisionPar0($titres_etat_mois , $titres_etat_mois_1) - 1;
$titres_etat_annuelle = DivisionPar0($titres_etat_mois , $titres_etat_mars) - 1;


$revenus_date_titres_etat = $bdd->query("select tr.capital,tr.taux from trade tr join achat a on tr.id_achat = a.id_achat where a.id_type_titre = 3 and MONTH(tr.date_trade) = $mois ")->fetchAll();
foreach ($revenus_date_titres_etat as $item){
    $information_titres_etat = ($item[0] * $item[1])/100;
    $valeur_titres_etat = ($information_titres_etat * $calcul_365);
    $total_titres_etat = $total_titres_etat + $valeur_titres_etat;
}

$revenus_creation_titres_etat = $bdd->query("select tr.capital,tr.taux,MONTH(tr.date_trade),YEAR(tr.date_trade) from trade tr join achat a on tr.id_achat = a.id_achat where a.id_type_titre = 3")->fetchAll();
foreach ($revenus_creation_titres_etat as $item){
    $mois_creation_titres_etat = $item[2];
    $year_creation_titres_etat = $item[3];
    $jour_restant_titres_etat = cal_days_in_month(CAL_GREGORIAN,$mois_creation_titres_etat,$year_creation_titres_etat);
    $calcul_365_titres_etat =(float) ($jour_restant_titres_etat / 365);
    $information_titres_etat_creation = ($item[0] * $item[1])/100;
    $valeur_titres_etat_creation =  ($information_titres_etat_creation * $calcul_365_titres_etat);
    $total_titres_etat_creation = $total_titres_etat_creation + $valeur_titres_etat_creation;
}

//calculpvalue
$valorasiation_titres_etat = (int) $bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois")->fetchColumn();



$sum_montant_achat_titres_etat = (int) $bdd->query("select sum(trade.montant) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$sum_quantite_achat_titres_etat = (int) $bdd->query("select sum(trade.quantite) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$countNombre_achat_titres_etat = (int) $bdd->query("select count(trade.id_achat) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 3 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$valorasiation_cmp_titres_etat = DivisionPar0($sum_montant_achat_titres_etat,$countNombre_achat_titres_etat);

$valorasiation_p_value_titres_etat = $valorasiation_titres_etat - ($valorasiation_cmp_titres_etat * $sum_quantite_achat_titres_etat);

$valorasiation_p_value_pourcentage_titres_etat = 0;

//mensuel

$titres_etat[0]=$titres_etat_mois_1;
$titres_etat[1]=$titres_etat_mois;
$titres_etat[2]=$titres_etat_mensuelle;
$titres_etat[3]=$titres_etat_annuelle;
$titres_etat[4]=$valorasiation_p_value_titres_etat;
$titres_etat[5]=$valorasiation_p_value_pourcentage_titres_etat;
$titres_etat[6]=$valorasiation_titres_etat;
$titres_etat[7]=$total_titres_etat;
$titres_etat[8]=$total_titres_etat_creation;



