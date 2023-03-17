<?php
if (isset($_POST['valider'])){
    $type_caisse = $_POST['type_caisse'];
    $montant = $_POST['montant'];
    $date_achat = $_POST['date_achat'];
    $bdd->query("insert into caisse(type_caisse, montant,date_achat) VALUES ('$type_caisse','$montant','$date_achat')");
}

$encaisse = $bdd->query("SELECT sum(montant) from caisse where type_caisse = 1")->fetchColumn();
$depot_bancaire = $bdd->query("SELECT sum(montant) from caisse where type_caisse = 2")->fetchColumn();
$fond_propre = $bdd->query("SELECT montant from caisse where type_caisse = 3 order by date_achat desc")->fetchColumn();
?>
<form action="" method="post">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-uppercase text-primary">Informations générales caisse</h3>
        </div>
        <div class="container-fluid">
            <div class="row ">
                <!-- ue -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase"></label>

                        <select name="type_caisse" class="form-control text-uppercase font-weight-bold form-control-lg" required>
                            <option value="1">Encaisse</option>
                            <option value="2">DEPOT BANCAIRES</option>
                            <option value="3">FOND PROPRE</option>
                        </select>

                    </div>

                </div>


                <!-- ecue -->


            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Montant</label>
                        <input class="form-control form-control-lg" type="number" name="montant" required value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Date</label>
                        <input class="form-control form-control-lg" type="date" name="date_achat" value="<?php echo date('Y-m-d') ?>">
                    </div>
                </div>
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
        <h3 class="m-0 font-weight-bold text-uppercase text-primary"></h3>
    </div>
    <div class="card-body">
        <form method="post">
            <table class="table table-striped table-bordered w-100">


                <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 20px">

                <tr>
                    <td style="font-size: large">FOND PROPRE</td>
                    <td style="font-size: large" class="text-right text-danger"><?= number_format((float)$depot_bancaire,'0','.',' ') ?></td>
                </tr>
                <tr>
                    <td style="font-size: large">ENCAISSE</td>
                    <td style="font-size: large" class="text-right text-danger"><?= number_format((float)$encaisse,'0','.',' ') ?></td>
                </tr>
                <tr>
                    <td style="font-size: large">DEPOT BANCAIRES</td>
                    <td style="font-size: large" class="text-right text-danger"><?= number_format((float)$fond_propre,'0','.',' ') ?></td>
                </tr>



                </tbody>
            </table>
        </form>
    </div>
</div>
