<?php
/**
 * Configurazione del database locale XAMPP e altre impostazioni globali
 *
 * Questo file può essere utilizzato al posto di config.php per connettersi al database locale XAMPP
 * Rinominare questo file in config.php o modificare i file che includono config.php per utilizzare questo file
 */

// Configurazione del database locale XAMPP
$db_host = 'db5017800562.hosting-data.io';
$db_user = 'dbu1153779';
$db_pass = 'Calabrone27!';
$db_name = 'dbs14204460'; // Il nome del database che hai creato in XAMPP

// Crea connessione
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Imposta charset a utf8
$conn->set_charset("utf8");

// Impostazioni generali del sito
define('SITE_NAME', 'Coral Plant');
define('SITE_URL', 'http://coralplant.it/'); // URL locale

// Funzione per la validazione degli input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}