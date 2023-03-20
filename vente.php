<?php

$_SESSION['date_jour_vente'] = date('Y-m-d');





//les contraintes
if(isset($_POST['valider'])){
    if (isset($_POST['capital'])){

        $actions_elemenet = explode(";",$_POST['actions']);
        $actions = $actions_elemenet[0];
        $trade = $actions_elemenet[1];
        $capital = $_POST['capital'];
        $date_vente = $_POST['date_vente'];
        $quantite = $_POST['quantite'];
        $montant = $_POST['montant'];
        $bdd->query("insert into vente(id_achat,quantite,montant, ventes,date_vente,id_trade) VALUES ('$actions',$quantite,$montant,'$capital','$date_vente','$trade')");

        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Operation effectuée avec succès!</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
        ';
    }
}

$reqEtb = $bdd->query("select * from trade join achat on trade.id_achat = achat.id_achat group by achat.id_achat order by achat.libelle_achat ASC ");

$actionsEL = $reqEtb->fetchAll();


$reqEtbDim = $bdd->query("select * from trade join achat on trade.id_achat = achat.id_achat group by achat.id_achat order by achat.libelle_achat ASC ");
$actionsDim = $reqEtbDim->fetchAll();
$array = array();
foreach ($actionsDim as $actions){
    $capital = $bdd->query("select sum(capital),sum(quantite) from trade where id_achat = {$actions['id_achat']}")->fetch();
    $ventes = $bdd->query("select sum(ventes),sum(quantite) from vente where id_achat = {$actions['id_achat']}")->fetch();
    $libelle_achat = $bdd->query("select libelle_achat from achat where  id_achat = {$actions['id_achat']}")->fetchColumn();
    $e = array(
        "libelle_achat"=>$libelle_achat,
        "capital_trade"=>$capital,
        "vente_trade"=>$ventes,

    );
    array_push($array,$e);
}



?>


<form action="" method="post">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-uppercase text-primary">Informations générales ventes</h3>
        </div>
        <div class="container-fluid">
            <div class="row ">
                <!-- ue -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">Titres</label>
                        <select name="actions" id="etablissementId" class="form-control form-control-lg" required>
                            <?php foreach ($actionsEL as $p): ?>
                                <option value="<?php echo $p['id_achat'].";".$p['id_trade'] ?>">
                                    <?php echo $p['libelle_achat'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- ecue -->


            </div>

            <div class="row" id="information">

            </div>

            <div align="right">

                <button type="submit" name="valider" class="btn btn-success btn-lg">Valider
                </button>

                <button type="reset" class="btn btn-danger btn-lg">Annuler
                </button>

            </div>
            <br>
        </div>
    </div>


</form>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-uppercase text-primary">Liste des titres achétés</h3>
    </div>
    <div class="card-body">
        <form method="post">
            <table class="table table-striped table-bordered w-100">
                <thead>

                <tr style="font-size: 19px" class="text-uppercase font-weight-bold">
                    <th width="60%" style="vertical-align: middle" rowspan="2">
                        Titres
                    </th>
                    <th width="20%" class="text-center" colspan="2">
                        achats
                    </th>
                    <th width="20%" class="text-center" colspan="2">
                       ventes
                    </th>
                </tr>


                <tr style="font-size: 19px" class="text-uppercase font-weight-bold">
                    <th width="10%">
                        quantite
                    </th>
                    <th width="10%">
                        montant
                    </th>
                    <th width="10%">
                        quantite
                    </th>
                    <th width="10%">
                        montant
                    </th>
                </tr>



                </thead>

                <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 20px">
                <?php foreach ($array as $value) : ?>
                    <tr>
                        <td width="60%"><?= $value['libelle_achat'] ?></td>
                        <td width="10%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['capital_trade'][1],'0','.',' ') ?></td>
                        <td width="10%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['capital_trade'][0],'0','.',' ') ?></td>
                        <td width="10%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['vente_trade'][1],'0','.',' ') ?></td>
                        <td width="10%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['vente_trade'][0],'0','.',' ') ?></td>
                    </tr>

                <?php endforeach;?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {




        $("#etablissementId").on("change", function () {
            var etablissementId = $(this).val();
            $.ajax({
                url: "ajax_vente.php",
                method: 'GET',
                data: {etablissementId: etablissementId},
                success: function (data) {
                    $("#information").html(data);
                }
            });
        }).trigger("change");










    });

</script>