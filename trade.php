<?php
$_SESSION['date_jour_trade'] = date('Y-m-d');

$placement_nette = 0;
$erreur1 = true;
$erreur2 = true;
$erreur3 = true;
$erreur4 = true;
$erreur5 = true;
$erreur6 = true;




function choix($erreur){
    $valeur = '';
    if ($erreur){
        $valeur = '<i class="fa fa-500px fa-check text-white text-center"></i>';
    }else{
        $valeur = '<i class="fa fa-500px fa-times text-white text-center"></i>';
    }

    return $valeur;
}


function colorLigne($erreur){
    $valeur = '';
    if ($erreur){
        $valeur = 'bg-success font-weight-bold text-white';
    }else{
        $valeur = 'bg-danger font-weight-bold text-white';
    }

    return $valeur;
}

function calculNumericSomme($x,$y,$z){
    if ($z > $x){
        $total = $y;
    }else{

        $total = ($x + $y) - $z;
    }

    return (int)$total;
}

function calculNumeric($x,$y){
    $total = (int)$x - (int)$y;
    if ($total < 0 ){
        return 0;
    }else{
        return $total;
    }
}



//les contraintes
if (isset($_POST['controler'])){
    $actions = $_POST['actions'];
    $capital = $_POST['capital'];
    $date_trade = $_POST['date_trade'];
    $taux = $_POST['taux'];
    $_SESSION['select_id_achat'] = $actions;
    $_SESSION['select_capital'] = $capital;
    $_SESSION['date_jour_trade'] = $date_trade;
    $_SESSION['date_jour_trade'] = $date_trade;
    $_SESSION['select_taux'] = $taux;

    //controle 1
    $placement_nette = (($capital * 100) / $placement_propre);
    $information_etat = $bdd->query("select * from achat where id_achat = $actions")->fetch();


    $code = $information_etat['id_code'];
    $garant = $information_etat['id_garant'];
    $notation = $information_etat['id_notation'];
    $type_achat = $information_etat['id_type_achat'];
    $type_titre = $information_etat['id_type_titre'];

    //calcul des éléments dans la bd
    $somme_placement = $bdd->query("select sum(capital) from trade where id_achat = $actions")->fetchColumn();
    $somme_placement_ventes = $bdd->query("select sum(ventes) from vente where id_achat = $actions")->fetchColumn();
    $somme_placement_capital = ((int)$somme_placement+(int)$capital);
    $somme_placement_total = ((int)$somme_placement+(int)$capital)-(int)$somme_placement_ventes;
    $somme_placement_total = calculNumericSomme($somme_placement,$capital,$somme_placement_ventes);
    $somme_placement_pourcentage = round((( $somme_placement_total * 100) / $placement_propre),5);


    //somme_garant_titre
    if ($garant == 1 or $notation == 1){
        $somme_garant_titre = $bdd->query("select sum(tr.capital) from trade tr join achat a on tr.id_achat = a.id_achat where a.id_garant = 1 or a.id_notation = 1")->fetchColumn();
        $somme_garant_titre_ventes = $bdd->query("select sum(tr.ventes) from vente tr join achat a on tr.id_achat = a.id_achat where a.id_garant = 1 or a.id_notation = 1")->fetchColumn();
        $somme_garant_titre_capital = ((int)$somme_garant_titre+(int)$capital);
        $somme_garant_titre_total = ((int)$somme_garant_titre+(int)$capital)-(int)$somme_garant_titre_ventes;
        $somme_garant_titre_total = calculNumericSomme($somme_garant_titre,$capital,$somme_garant_titre_ventes);
        $somme_garant_titre_pourcentage = round((( $somme_garant_titre_total * 100) / $placement_propre),5);
    }

    //somme_actions_dettes

    if ($type_titre == 1 or $type_titre == 2){
        $somme_actions_dettes = $bdd->query("select sum(tr.capital) from trade tr join achat a on tr.id_achat = a.id_achat where a.id_type_titre in (1,2)")->fetchColumn();
        $somme_actions_dettes_ventes = $bdd->query("select sum(tr.ventes) from vente tr join achat a on tr.id_achat = a.id_achat where a.id_type_titre in (1,2)")->fetchColumn();
        $somme_actions_dettes_capital = ((int)$somme_actions_dettes+(int)$capital);
        $somme_actions_dettes_total = ((int)$somme_actions_dettes+(int)$capital)-(int)$somme_actions_dettes_ventes;
        $somme_actions_dettes_total = calculNumericSomme($somme_actions_dettes,$capital,$somme_actions_dettes_ventes);
        $somme_actions_dettes_pourcentage = round((( $somme_actions_dettes_total * 100) / $placement_propre),5);

    }





    //ne doit pas depasser 25% sur les titres d'etats
    if ($type_titre == 3){
        if ($somme_placement_pourcentage > 25){
            $erreur1 = false;
        }
    }

    //ne doit pas depasser 15% sur les dettes entreprises
    if ($type_titre == 2){
        if ($somme_placement_pourcentage > 15){
            $erreur2 = false;
        }
    }

    //levier de fond
    $somme_fond_trade = $bdd->query("select sum(capital) from trade")->fetchColumn();
    $somme_fond_ventes = $bdd->query("select sum(ventes) from vente")->fetchColumn();
    $somme_fond = calculNumeric((int)$somme_fond_trade,(int)$somme_fond_ventes);
    if ($somme_fond > $placement_propre ){
        $erreur3 = false;
    }


    //actif de niveau 1
    if ($type_titre != 3){
        if ($somme_placement_total > $placement_propre){
            $erreur4 = false;
        }
    }


    //actif de niveau 2
    if ($garant == 1 or $notation == 1){
        if ($somme_placement_pourcentage > 40){
            $erreur5 = false;
        }

        if ($somme_garant_titre_pourcentage> 40){
            $erreur5 = false;
        }
    }



    //actif de niveau 3
    if ($type_titre == 1 or $type_titre == 2){
        if ($somme_placement_pourcentage > 15){
            $erreur6 = false;
        }

        if ($somme_actions_dettes_pourcentage > 15){
            $erreur6 = false;
        }
    }

}elseif (isset($_POST['valider'])){
    $actions = $_POST['actions'];
    $capital = $_POST['capital'];
    $date_trade = $_POST['date_trade'];
    if (isset($_POST['taux'])) :$taux = $_POST['taux'];else: $taux = 1 ;endif;
    $bdd->query("insert into trade(id_achat, capital,date_trade,taux) VALUES ('$actions','$capital','$date_trade','$taux')");
    unset($_SESSION['select_id_achat']);
    unset($_SESSION['select_capital']) ;
    $_SESSION['select_taux'] = 1;
}else{

    unset($_SESSION['select_id_achat']);
    unset($_SESSION['select_capital']) ;
    $_SESSION['select_taux'] = 1 ;

}

$reqEtb = $bdd->query("select * from achat order by libelle_achat ASC ");

$actions = $reqEtb->fetchAll();

?>


<form action="" method="post">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-uppercase text-primary">Informations générales achats</h3>
        </div>
        <div class="container-fluid">
            <div class="row ">
                <!-- ue -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">Titres</label>
                        <select name="actions" id="etablissementId" class="form-control form-control-lg" required>
                            <?php foreach ($actions as $p): ?>
                                <option value="<?php echo $p['id_achat'] ?>"<?php if (isset($_POST['controler']) && $_SESSION['select_id_achat'] === $p['id_achat']) echo 'selected'; ?>>
                                    <?php echo $p['libelle_achat'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mr-5 ml-5">
                    <div class="form-group" id="content_taux">

                    </div>
                </div>
                <!-- ecue -->



                <div class="col-md-4">
                    <div class="form-group" id="content_departement">
                        <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Placement</label>
                        <input class="form-control form-control-lg" type="number" name="capital" max="<?php echo $placement_propre ?>" value="<?php echo $_SESSION['select_capital'] ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Date</label>
                        <input class="form-control form-control-lg" type="date" name="date_trade" value="<?php echo $_SESSION['date_jour_trade'] ?>">
                    </div>
                </div>








                <!-- notation garant -->







            </div>

            <div class="row">
                <?php if (isset($_POST['controler'])): ?>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement initial sur le titre</label>
                            <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_placement,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement total sur le titre</label>
                            <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_placement_capital,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Vente total sur le titre</label>
                            <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_placement_ventes,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Actif total sur le titre</label>
                            <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo number_format($somme_placement_total,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">nette total sur un titre</label>
                            <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo $somme_placement_pourcentage.'%' ?>">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php
                if (isset($_POST['controler'])):
                    if ($garant == 1 or $notation == 1) :
                        ?>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement initial sur AN2A</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_garant_titre,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement total sur AN2A</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_garant_titre_capital,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Vente total sur AN2A</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_garant_titre_ventes,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Actif total sur AN2A</label>
                                <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo number_format($somme_garant_titre_total,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">nette total sur AN2A</label>
                                <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo $somme_garant_titre_pourcentage.'%' ?>">
                            </div>
                        </div>
                    <?php
                    endif;
                endif; ?>
            </div>

            <div class="row">
                <?php

                if (isset($_POST['controler'])):
                    if($type_titre == 1 or $type_titre == 2):
                        ?>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement initial sur AN2B</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_actions_dettes,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Placement total sur AN2B</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_actions_dettes_capital,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Vente total sur AN2B</label>
                                <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_actions_dettes_ventes,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Actif total sur AN2B</label>
                                <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo number_format($somme_actions_dettes_total,'0','.',' ') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="placement">
                                <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">nette total sur AN2B</label>
                                <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo $somme_actions_dettes_pourcentage.'%' ?>">
                            </div>
                        </div>
                    <?php
                    endif;
                endif; ?>
            </div>
            <!-- dettes et actions -->
            <div class="row">


                <?php if (isset($_POST['controler'])): ?>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Total achat</label>
                            <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_fond_trade,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase">Total vente</label>
                            <input class="form-control form-control-lg" readonly type="text" name="placement"  value="<?php echo number_format($somme_fond_ventes,'0','.',' ') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="placement">
                            <label for="placement" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">portefeuille</label>
                            <input class="form-control form-control-lg font-weight-bold text-danger" readonly type="text" name="placement"  value="<?php echo number_format($somme_fond,'0','.',' ') ?>">
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <div align="right">
                <button type="submit" name="controler" class="btn btn-danger btn-lg">Faire le contrôle des exigences
                </button>
                <?php if (isset($_POST['controler'])): ?>
                    <?php if ($erreur1 && $erreur2 && $erreur3 && $erreur4 && $erreur5 && $erreur6): ?>
                        <button type="submit" name="valider" class="btn btn-success btn-lg">Valider opération
                        </button>

                    <?php endif; ?>
                    <button type="submit" name="retour" class="btn btn-warning btn-lg">Retour
                    </button>
                <?php endif; ?>
            </div>
            <br>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-uppercase text-primary">Recapitulatif des exigences</h3>
        </div>
        <div class="card-body">
            <form method="post">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                    <tr class="">
                        <th width="90%" style="font-size: 25px" class="text-uppercase font-weight-bold">
                            Exigences
                        </th>
                        <th width="10%">

                        </th>
                    </tr>

                    </thead>

                    <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 20px">
                    <?php if (isset($_POST['controler'])): ?>
                        <tr class="<?= colorLigne($erreur1) ?>">
                            <td width="90%">Position nette sur un Etat<=25% des placements totaux</td>
                            <td width="10%" style="font-size: medium" class="text-center"><?= choix($erreur1) ?></td>
                        </tr>
                        <tr class="<?= colorLigne($erreur2) ?>">
                            <td width="90%">Position nette des émetteurs d'un même groupe<=15% des placements totaux </td>
                            <td width="10%" style="font-size: medium" class="text-center"><?= choix($erreur2) ?></td>
                        </tr>
                        <tr class="<?= colorLigne($erreur3) ?>">
                            <td width="90%">Placement/fond propre <1</td>
                            <td width="10%" style="font-size: medium" class="text-center "><?= choix($erreur3) ?></td>
                        </tr>
                        <tr class="<?= colorLigne($erreur4) ?>">
                            <td width="90%">Actif liquide de niveau 1</td>
                            <td width="10%" style="font-size: medium" class="text-center"><?= choix($erreur4) ?></td>
                        </tr>
                        <tr class="<?= colorLigne($erreur5) ?>">
                            <td width="90%">Actif liquide de de niveau 2 A</td>
                            <td width="10%" style="font-size: medium" class="text-center"><?= choix($erreur5) ?></td>
                        </tr>
                        <tr class="<?= colorLigne($erreur6) ?>">
                            <td width="90%">Actif liquide de niveau 2B</td>
                            <td width="10%" style="font-size: medium" class="text-center"><?= choix($erreur6) ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {




        $("#etablissementId").on("change", function () {
            var etablissementId = $(this).val();
            $.ajax({
                url: "ajax_taux.php",
                method: 'GET',
                data: {id_titre: etablissementId},
                success: function (data) {
                    $("#content_taux").html(data);
                }
            });
        }).trigger("change");







    });

</script>