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
//recuperation du montantReeel dans import
$reel_import_brvm = (int) $bdd->query("select import.montant from import join achat a on import.id_achat = a.id_achat 
                      where a.id_type_titre = 1 and MONTH(import.date_import) = $mois order by import.date_import desc limit 1")->fetchColumn();

//recuperation de la quantite trader
$quantiteTrader_brvm = (int) $bdd->query("select sum(trade.quantite) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();


//on recupère tous les montants saisies
$montant_saisie_brvm = (int) $bdd->query("select sum(trade.montant) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();


//on recupère le nombre de saisie
$nombres_saisie_brvm = (int) $bdd->query("select count(trade.id_achat) from trade join achat a on trade.id_achat = a.id_achat 
                          where  a.id_type_titre = 1 and MONTH(trade.date_trade) = $mois")->fetchColumn();


$cmp_brvm = DivisionPar0($montant_saisie_brvm,$nombres_saisie_brvm);


$montant_total_cmp_brvm = $cmp_brvm * $quantiteTrader_brvm;

//valeurReeel
$valeurreel_brvm= $reel_import_brvm * $quantiteTrader_brvm;


$montant_pvalue_brvm = $valeurreel_brvm - $montant_total_cmp_brvm;

$pourcentage_pvalue_brvm = cal_percentage($montant_pvalue_brvm,$valeurreel_brvm);

//mensuel

$actions_brvm[0]=$actions_brvm_mois_1;
$actions_brvm[1]=$actions_brvm_mois;
$actions_brvm[2]=$actions_brvm_mensuelle;
$actions_brvm[3]=$actions_brvm_annuelle;
$actions_brvm[4]=$montant_pvalue_brvm;
$actions_brvm[5]=$pourcentage_pvalue_brvm;
$actions_brvm[6]=$valeurreel_brvm;
$actions_brvm[7]=$total_actions_brvm;
$actions_brvm[8]=$total_actions_brvm_creation;

