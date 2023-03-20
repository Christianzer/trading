<?php
session_start();
$_SESSION['date_jour_vente'] = date('Y-m-d');
//ini_set('display_errors', 'off');
setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('Africa/Abidjan');
class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance == null) {
            try {
                self::$instance = new PDO('mysql:host=127.0.0.1; dbname=trading; charset=utf8', 'root', '');
            } catch (PDOException $e) {
                // Handle this properly
                throw $e;
            }
        }
        return self::$instance;
    }
}
$bdd = DB::get();

function calculNumeric($x,$y){
    $total = (int)$x - (int)$y;
    if ($total < 0 ){
        return 0;
    }else{
        return $total;
    }
}

if (isset($_GET['etablissementId']) && !empty($_GET['etablissementId'])):
    $id_titre = $_GET['etablissementId'];
    $placement_total = $bdd->query("select sum(capital) from trade where id_achat = $id_titre")->fetchColumn();
    $placement_quantite = $bdd->query("select sum(quantite) from trade where id_achat = $id_titre")->fetchColumn();
    $vente_anterieur = $bdd->query("select sum(ventes) from vente where id_achat = $id_titre")->fetchColumn();
    $vente_quantite = $bdd->query("select sum(quantite) from vente where id_achat = $id_titre")->fetchColumn();
    $total_titre = calculNumeric($placement_total,$vente_anterieur);
    $quantite_dispo = $placement_quantite - $vente_quantite;
    ?>

    <div class="col-md-4">
        <div class="form-group" id="content_departement">
            <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Quantite disponible</label>
            <input class="form-control form-control-lg text-danger font-weight-bold" readonly type="text" id="quantite" value="<?php echo number_format((int)$quantite_dispo,'0','.',' ') ?>" >
        </div>
    </div>

    <div class="col-md-8"></div>

    <?php
    if ($total_titre > 0) :
        ?>
        <div class="col-md-3">
            <div class="form-group" id="content_departement">
                <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Quantité à vendre</label>
                <input class="form-control form-control-lg" type="number" id="quantite_vente" max="<?= $quantite_dispo ?>" required name="quantite" value="" >
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group" id="content_departement">
                <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Montant</label>
                <input class="form-control form-control-lg" type="number" id="montant_vente" required name="montant"  value="" >
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group" id="content_departement">
                <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Total Montant</label>
                <input class="form-control form-control-lg" type="number" readonly id="capital_vente" required name="capital" value="" >
            </div>
        </div>
    <?php
    endif;
    ?>

    <div class="col-md-4">
        <div class="form-group" id="content_date">
            <label for="content_date" class="col-form-label-lg font-weight-bold text-uppercase">Date</label>
            <input class="form-control form-control-lg" type="date" name="date_vente" value="<?php echo $_SESSION['date_jour_vente'] ?>">
        </div>
    </div>


<?php
endif;
?>

<script>
    $(document).ready(function () {






        $("#quantite_vente").on("change", function () {
            var quantite = $(this).val();
            var montant = $("#montant").val()
            var placement = quantite * montant
            $("#capital").val(placement)
        }).trigger("change");



        $("#montant_vente").on("change", function () {
            var montant = $(this).val();
            var quantite = $("#quantite").val()
            var placement = quantite * montant
            $("#capital_vente").val(placement)
        }).trigger("change");








    });

</script>