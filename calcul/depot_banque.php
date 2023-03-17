<?php
$depot_bancaire_mois_1 =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 2 and MONTH(date_achat) = $mois_1")->fetchColumn();
$depot_bancaire_mois =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 2 and MONTH(date_achat) = $mois")->fetchColumn();
$depot_bancaire_mars =  (int)$bdd->query("select sum(montant) from caisse where type_caisse = 2 and MONTH(date_achat) = 3")->fetchColumn();
$depot_bancaire_mensuelle = DivisionPar0($depot_bancaire_mois , $depot_bancaire_mois_1) - 1;
$depot_bancaire_annuelle = DivisionPar0($depot_bancaire_mois , $depot_bancaire_mars) - 1;
$depot_bancaire_revenu = 0;
$depot_bancaire_creation = 0;
//mensuel
$depot_bancaire[0]= $depot_bancaire_mois_1;
$depot_bancaire[1]=$depot_bancaire_mois;
$depot_bancaire[2]=$depot_bancaire_mensuelle;
$depot_bancaire[3]=$depot_bancaire_annuelle;
$depot_bancaire[4]=$depot_bancaire_revenu;
$depot_bancaire[5]=$depot_bancaire_creation;