<?php
session_start();

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

$bdd->query("TRUNCATE TABLE trade ");
$bdd->query("TRUNCATE TABLE vente ");
$bdd->query("TRUNCATE TABLE caisse ");


?>
