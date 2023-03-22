<?php
$url = "http://gstockob-api.sodenci.com/api/dash"; // URL de l'API GET

while (true) { // Boucle infinie pour continuer à recevoir les mises à jour en temps réel
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Traitez la réponse ici
    echo $response . "\n";

    // Attendez un certain temps avant de récupérer les données suivantes
    sleep(10);
}
