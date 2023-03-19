<?php
$encaisse_mois_1 =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 1 and MONTH(date_achat) = $mois_1")->fetchColumn();
$encaisse_mois =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 1 and MONTH(date_achat) = $mois")->fetchColumn();
$encaisse_mars =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 1 and MONTH(date_achat) = 3")->fetchColumn();
$encaisse_mensuelle = DivisionPar0($encaisse_mois , $encaisse_mois_1) - 1;
$encaisse_annuelle = DivisionPar0($encaisse_mois , $encaisse_mars) - 1;
$encaisse_revenu = 0;
$encaisse_creation = 0;
$encaisse_valeur = 0;
$encaisse_valeur_val = 0;
$encaisse_valeur_pourcentage = 0;


//mensuel
$encaisse[0]= $encaisse_mois_1;
$encaisse[1]=$encaisse_mois;
$encaisse[2]=$encaisse_mensuelle;
$encaisse[3]=$encaisse_annuelle;
$encaisse[4]=$encaisse_valeur_val;
$encaisse[5]=$encaisse_valeur_pourcentage;
$encaisse[6]=$encaisse_valeur;
$encaisse[7]=$encaisse_revenu;
$encaisse[8]=$encaisse_creation;