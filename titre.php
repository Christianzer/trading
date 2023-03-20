<?php
if (isset($_POST['valider'])){
    extract($_POST);
    $bdd->query("insert into achat(code_achat, isin, libelle_achat, id_code, id_garant, id_notation, id_type_achat, id_type_titre) values ('{$code_achat}','{$isin}','{$titre}',$code,$garant,$notation,$code,$type_titre)");

    echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Operation effectuée avec succès!</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
        ';

}

$listes_achat = $bdd->query("select * from achat left join code c on achat.id_code = c.id_code left join garant g on g.id_garant = achat.id_garant
    left join type_achat ta on achat.id_type_achat = ta.id_type_achat left join notation n on achat.id_notation = n.id_notation
left join type_titre tt on achat.id_type_titre = tt.id_type_titre order by achat.libelle_achat asc ")->fetchAll();

$type_titres = $bdd->query("select * from type_titre")->fetchAll();
$garants = $bdd->query("select * from garant")->fetchAll();
$notations = $bdd->query("select * from notation")->fetchAll();

?>
<form action="" method="post">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-uppercase text-primary">Informations générales titres</h3>
        </div>
        <div class="container-fluid">
            <div class="row ">
                <!-- ue -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">TYPE ACHAT</label>
                        <select name="code" class="form-control text-uppercase font-weight-bold form-control-lg" >
                            <option value="1">Action</option>
                            <option value="2">Obligation</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">TYPE TITRE</label>
                        <select name="type_titre" class="form-control text-uppercase font-weight-bold form-control-lg" >
                            <option value=""></option>
                            <?php foreach ($type_titres as $p): ?>
                                <option value="<?php echo $p['id_type_titre'] ?>">
                                    <?php echo $p['libelle_type_titre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">GARANT</label>
                        <select name="garant" class="form-control text-uppercase font-weight-bold form-control-lg" >
                            <option value=""></option>
                            <?php foreach ($garants as $p): ?>
                                <option value="<?php echo $p['id_garant'] ?>">
                                    <?php echo $p['libelle_garant'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="etablissementId" class="col-form-label-lg font-weight-bold text-uppercase">NOTATION</label>
                        <select name="notation" class="form-control text-uppercase font-weight-bold form-control-lg" >
                            <option value=""></option>
                            <?php foreach ($notations as $p): ?>
                                <option value="<?php echo $p['id_notation'] ?>">
                                    <?php echo $p['libelle_notation'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>





            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Code</label>
                        <input class="form-control form-control-lg" type="text" name="code_achat"  >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">ISIN</label>
                        <input class="form-control form-control-lg" type="text" name="isin" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="content_date">
                        <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Nom Titre</label>
                        <input class="form-control form-control-lg" type="text" name="titre" >
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
            <table class="table table-striped table-bordered w-100" style="font-size: 15px" id="dataTable">
                <thead>
                <tr>
                    <th width="3%">Id</th>
                    <th>Titres</th>
                    <th width="3%"></th>
                    <th width="3"></th>
                    <th></th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                <?php foreach ($listes_achat as $value) : ?>
                <tr>
                    <td><?= $value['id_achat'] ?></td>
                    <td><?= $value['libelle_achat'] ?></td>
                    <td><?= $value['libelle_code'] ?></td>
                    <td><?= $value['libelle_notation'] ?></td>
                    <td><?= $value['libelle_garant'] ?></td>

                    <td><?= $value['libelle_type_titre'] ?></td>

                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </form>
    </div>
</div>
