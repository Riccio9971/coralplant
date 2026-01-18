<?php
/**
 * API per ottenere le foto aggiuntive di un prodotto dalla tabella apposita
 */

// Headers per JSON
header('Content-Type: application/json');

// Include la configurazione del database
require_once '../config.php';

// Verifica che l'ID del prodotto sia stato fornito
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo json_encode(['error' => 'ID prodotto mancante']);
    exit;
}

$product_id = intval($_GET['product_id']);

// Verifica se la tabella foto_prodotto_aggiuntive esiste
$check_table = $conn->query("SHOW TABLES LIKE 'foto_prodotto_aggiuntive'");
$table_exists = ($check_table !== false && $check_table->num_rows > 0);

if (!$table_exists) {
    // Se la tabella non esiste, restituisci un array vuoto
    echo json_encode(['photos' => []]);
    exit;
}

// Recupera le foto aggiuntive per il prodotto dalla tabella corretta
$query = "SELECT id, nome_file, ordine
          FROM foto_prodotto_aggiuntive
          WHERE prodotto_id = ?
          ORDER BY ordine ASC";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode([
        'error' => 'Errore nella preparazione della query: ' . $conn->error,
        'query' => $query
    ]);
    exit;
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$photos = [];
while ($row = $result->fetch_assoc()) {
    $photos[] = $row;
}

// Restituisci i risultati in formato JSON
echo json_encode([
    'photos' => $photos,
    'debug' => [
        'product_id' => $product_id,
        'table_exists' => $table_exists,
        'query' => $query
    ]
]);

// Chiudi le connessioni
$stmt->close();
$conn->close();
?>