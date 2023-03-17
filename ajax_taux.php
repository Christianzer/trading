<?php
session_start();
setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('Africa/Abidjan');
class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance == null) {
            try {
                self::$instance = new PDO('mysql:host=127.0.0.1; dbname=trading; charset=utf8', 'root', '1234');
            } catch (PDOException $e) {
                // Handle this properly
                throw $e;
            }
        }
        return self::$instance;
    }
}
$bdd = DB::get();
$id_titre = $_GET['id_titre'];
$code = $bdd->query("select id_code from achat where id_achat = $id_titre")->fetchColumn();
?>
<?php
if ($code == 2):
    ?>
    <label for="departementId" class="col-form-label-lg font-weight-bold text-uppercase font-weight-bold text-danger">Taux %</label>
    <input class="form-control form-control-lg text-danger font-weight-bold" type="number" step="any" name="taux" max="100" value="<?php echo $_SESSION['select_taux'] ?>">
<?php
endif;
?>

