<?php
$total_actions_brvm = 0;
$total_actions_brvm_creation = 0;
$actions_brvm_mois_1 =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois_1")->fetchColumn();
$actions_brvm_mois =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();
$actions_brvm_mars =  (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 1 and MONTH(trade.date_trade) = 3")->fetchColumn();
$actions_brvm_mensuelle = DivisionPar0($actions_brvm_mois , $actions_brvm_mois_1) - 1;
$actions_brvm_annuelle = DivisionPar0($actions_brvm_mois , $actions_brvm_mars) - 1;

$achats_brvm_mensuelle = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();
$ventes_brvm_mensuelle = (int)$bdd->query("select sum(vt.ventes) from vente vt join achat a on vt.id_achat = a.id_achat 
                          where a.id_type_titre = 1 and MONTH(vt.date_vente) = $mois")->fetchColumn();

$total_actions_brvm = $ventes_brvm_mensuelle - $achats_brvm_mensuelle;


$achats_brvm_continuation = (int)$bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where a.id_type_titre = 1 ")->fetchColumn();
$ventes_brvm_continuation = (int)$bdd->query("select sum(vt.ventes) from vente vt join achat a on vt.id_achat = a.id_achat 
                          where a.id_type_titre = 1")->fetchColumn();

$total_actions_brvm_creation = $ventes_brvm_continuation - $achats_brvm_continuation;

//calculpvalue
$valorasiation_actions_brvm = (int) $bdd->query("select sum(trade.capital) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();



$sum_montant_achat_actions_brvm = (int) $bdd->query("select sum(trade.montant) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$sum_quantite_achat_actions_brvm = (int) $bdd->query("select sum(trade.quantite) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$countNombre_achat_actions_brvm = (int) $bdd->query("select count(trade.id_achat) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();

$valorasiation_cmp_actions_brvm = DivisionPar0($sum_montant_achat_actions_brvm,$countNombre_achat_actions_brvm);

$valorasiation_p_value_actions_brvm = $valorasiation_actions_brvm - ($valorasiation_cmp_actions_brvm * $sum_quantite_achat_actions_brvm);

$valorasiation_p_value_pourcentage_actions_brvm = 0;

//mensuel

$actions_brvm[0]=$actions_brvm_mois_1;
$actions_brvm[1]=$actions_brvm_mois;
$actions_brvm[2]=$actions_brvm_mensuelle;
$actions_brvm[3]=$actions_brvm_annuelle;
$actions_brvm[4]=$valorasiation_p_value_actions_brvm;
$actions_brvm[5]=$valorasiation_p_value_pourcentage_actions_brvm;
$actions_brvm[6]=$valorasiation_actions_brvm;
$actions_brvm[7]=$total_actions_brvm;
$actions_brvm[8]=$total_actions_brvm_creation;

