<?php

$page = $_GET['page'];

$dictionary = array( // on va utiliser ce dictionaire pour récupérer l'activité de l'utilisateur
    "date" => "",
    "user_id" => "", // le user ID est une manière de déterminer si l'utilisateur ayant fait la requète est bien unique et différenciable, on fait un hash de l'user agent et de l'ip de l'utilisateur.
    "timestamp" => "",
    "user_agent" => "",
    "ip" => "",
    "request" => "",
    "response" => ""
);

function logger($stringToAdd) {
    $filePath = 'logs/log_'. date("Y-m-d") .'.json';
    // cette fonction m'aide à formater et enregistrer l'activité des recherches dans les logs
    if (!file_exists($filePath)) {
        file_put_contents($filePath, "[\n", FILE_APPEND);
        file_put_contents($filePath, "]\n", FILE_APPEND);
    }

    // Lire le contenu actuel du fichier
    $lines = file($filePath);

    // Si le fichier a plus d'une ligne, et que la ligne précédente n'est pas la première ligne
    if (count($lines) > 1 && trim($lines[count($lines) - 2]) !== "[") {
        $beforeLastLineIndex = count($lines) - 2;
        $lines[$beforeLastLineIndex] = rtrim($lines[$beforeLastLineIndex], "\n") . ",\n";
    }

    // Insérer la nouvelle chaîne avant la dernière ligne
    array_splice($lines, -1, 0, $stringToAdd . PHP_EOL);

    // Réécrire le fichier avec le contenu modifié
    file_put_contents($filePath, implode('', $lines));
}



// $jsonData = json_encode($dictionary);
// php -S 127.0.0.1:80 -t . 
// Fonction pour afficher le contenu d'un fichier
// cette fonction est faillible RFI
function afficher_contenu_fichier($page) {
    
    // Vérifier si le fichier existe
    if (file_exists("pages/" . $page)) { // toute cette partie de la fonction va utiliser un docker pour aller chercher les fichiers dedans, l'idée est d'avoir une structure semblable à un OS linux normal sans pour autant compromettre les données dans le vrai serveur.
        // Charger le contenu du fichier
        $contenu = file_get_contents("pages/" . $page);
		if ($contenu == "") {
			echo file_get_contents("pages/index.html");
		}
        echo $contenu;
    } else {
        $contenu = file_get_contents("pages/index.html");
        // Afficher le contenu
        echo $contenu;
    }

    global $dictionary;
    // Récupérer l'adresse IP du visiteur
    $ip = $_SERVER['REMOTE_ADDR'];
    // Récupérer l'agent utilisateur du visiteur
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Récupérer la requête HTTP
    $request = $_SERVER['REQUEST_URI'];
    // Récupérer la date et l'heure actuelles
    $date = date("Y-m-d H:i:s");
    // Récupérer le timestamp actuel
    $timestamp = time();
    $dictionary["date"] = $date;
    $dictionary["user_id"] = hash("sha256", $user_agent . $ip);
    $dictionary["timestamp"] = $timestamp;
    $dictionary["user_agent"] = $user_agent;
    $dictionary["ip"] = $ip;
    $dictionary["request"] = $request;
    $dictionary["response"] = $contenu;

    logger(json_encode($dictionary));
}

// Appeler la fonction pour afficher le contenu du fichier spécifié dans ?page=
afficher_contenu_fichier($page);

?>
