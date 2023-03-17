<?php
var_dump($_POST);
?>
<form action="" enctype="multipart/form-data" method="post">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Autres</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Procès Verbal</label>
                    <input type="file" name="pv"  accept="image/*,.pdf" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Fiche des différentes inscriptions</label>
                    <input type="file"  name="ficheinscription[]" accept="image/*,.pdf" multiple class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Doc Master</label>
                    <input type="file" name="dip"  accept="image/*,.pdf" class="form-control">
                </div>
            </div>

        </div>
    </div>
    <div align="right">
        <button type="submit" name="importer" class="btn btn-success btn-lg">IMPORTER
        </button>
    </div>
    <br>
</form>