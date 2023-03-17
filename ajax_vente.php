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
    $vente_anterieur = $bdd->query("select sum(ventes) from vente where id_achat = $id_titre")->fetchColumn();
    $total_titre = calculNumeric($placement_total,$vente_anterieur);
    ?>

    <div class="col-md-4">
        <div class="form-group" id="content_departement">
            <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Actif disponible</label>
            <input class="form-control form-control-lg text-danger font-weight-bold" readonly type="text" id="placement" value="<?php echo number_format((int)$total_titre,'0','.',' ') ?>" >
        </div>
    </div>

    <?php
    if ($total_titre > 0) :
        ?>
        <div class="col-md-4">
            <div class="form-group" id="content_departement">
                <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase">Ventes</label>
                <input class="form-control form-control-lg" type="number"id="capital" required name="capital" value="" >
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
