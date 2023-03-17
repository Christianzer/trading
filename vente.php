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
        $bdd->query("insert into vente(id_achat, ventes,date_vente,id_trade) VALUES ('$actions','$capital','$date_vente','$trade')");
    }
}

$reqEtb = $bdd->query("select * from trade join achat on trade.id_achat = achat.id_achat group by achat.id_achat order by achat.libelle_achat ASC ");

$actionsEL = $reqEtb->fetchAll();


$reqEtbDim = $bdd->query("select * from trade join achat on trade.id_achat = achat.id_achat group by achat.id_achat order by achat.libelle_achat ASC ");
$actionsDim = $reqEtbDim->fetchAll();
$array = array();
foreach ($actionsDim as $actions){
    $capital = $bdd->query("select sum(capital) from trade where id_achat = {$actions['id_achat']}")->fetchColumn();
    $ventes = $bdd->query("select sum(ventes) from vente where id_achat = {$actions['id_achat']}")->fetchColumn();
    $libelle_achat = $bdd->query("select libelle_achat from achat where  id_achat = {$actions['id_achat']}")->fetchColumn();
    $e = array(
        "libelle_achat"=>$libelle_achat,
        "capital_trade"=>(float)$capital,
        "vente_trade"=>(float)$ventes,

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
                    <th width="70%" >
                        Titres
                    </th>
                    <th width="15%">
                        Achat
                    </th>
                    <th width="15%">
                        Vente
                    </th>
                </tr>

                </thead>

                <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 20px">
                <?php foreach ($array as $value) : ?>
                    <tr>
                        <td width="70%"><?= $value['libelle_achat'] ?></td>
                        <td width="15%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['capital_trade'],'0','.',' ') ?></td>
                        <td width="15%" style="font-size: large" class="text-right text-danger"><?= number_format((float)$value['vente_trade'],'0','.',' ') ?></td>
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