<?php
/**
 * Endpoint AJAX per caricare i prodotti senza refresh della pagina
 * Restituisce JSON con i dati dei prodotti/specie/varietà
 */

header('Content-Type: application/json');

// Include la configurazione del database
require_once 'config.php';

// Gestione parametri GET
$filtro_specie_id = isset($_GET['specie']) ? intval($_GET['specie']) : 0;
$filtro_varieta_id = isset($_GET['varieta']) ? intval($_GET['varieta']) : 0;
$show_all = isset($_GET['show_all']) ? true : false;

// Determina quale tipo di contenuto mostrare
$mostra_specie = ($filtro_specie_id == 0 && $filtro_varieta_id == 0 && !$show_all);
$mostra_varieta = ($filtro_specie_id > 0 && $filtro_varieta_id == 0);
$mostra_prodotti = ($filtro_varieta_id > 0 || $show_all);

// Inizializza la risposta
$response = [
    'success' => true,
    'type' => '', // 'specie', 'varieta', 'prodotti'
    'title' => '',
    'subtitle' => '',
    'data' => [],
    'filters' => [
        'specie' => $filtro_specie_id,
        'varieta' => $filtro_varieta_id,
        'show_all' => $show_all
    ]
];

try {
    if ($mostra_specie) {
        // MOSTRA LE SPECIE
        $response['type'] = 'specie';
        $response['title'] = 'Tutte le Categorie';
        $response['subtitle'] = 'Seleziona una categoria per vedere le varietà disponibili';

        // Query per ottenere le specie con immagine principale
        $query = "
            SELECT s.id, s.nome, COUNT(DISTINCT v.id) as num_varieta, COUNT(DISTINCT fp.id) as num_prodotti,
                   (SELECT fp2.nome_file
                    FROM foto_piante fp2
                    JOIN varieta v2 ON fp2.varieta_id = v2.id
                    WHERE v2.specie_id = s.id AND fp2.is_principale = 1
                    LIMIT 1) as immagine
            FROM specie s
            LEFT JOIN varieta v ON v.specie_id = s.id
            LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
            GROUP BY s.id
            HAVING num_prodotti > 0
            ORDER BY s.nome";

        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'num_prodotti' => $row['num_prodotti'],
                    'num_varieta' => $row['num_varieta'],
                    'immagine' => $row['immagine']
                ];
            }
        }

    } elseif ($mostra_varieta) {
        // MOSTRA LE VARIETÀ PER UNA SPECIE
        $response['type'] = 'varieta';

        // Ottieni il nome della specie
        $specie_query = "SELECT nome FROM specie WHERE id = $filtro_specie_id";
        $specie_result = $conn->query($specie_query);
        if ($specie_result && $specie_result->num_rows > 0) {
            $specie_row = $specie_result->fetch_assoc();
            $response['title'] = $specie_row['nome'];
            $response['subtitle'] = 'Seleziona una varietà per vedere i prodotti disponibili';
        }

        // Query per ottenere le varietà
        $query = "
            SELECT v.id, v.nome, COUNT(DISTINCT fp.id) as num_prodotti,
                   (SELECT fp2.nome_file
                    FROM foto_piante fp2
                    WHERE fp2.varieta_id = v.id AND fp2.is_principale = 1
                    LIMIT 1) as immagine
            FROM varieta v
            LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
            WHERE v.specie_id = $filtro_specie_id
            GROUP BY v.id
            HAVING num_prodotti > 0
            ORDER BY v.nome";

        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'num_prodotti' => $row['num_prodotti'],
                    'immagine' => $row['immagine']
                ];
            }
        }

    } elseif ($mostra_prodotti) {
        // MOSTRA I PRODOTTI
        $response['type'] = 'prodotti';

        if ($show_all) {
            $response['title'] = 'Catalogo Prodotti';
            $response['subtitle'] = 'La nostra selezione di piante di alta qualità';

            // Mostra tutte le foto principali (limitato)
            $query = "
                SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
                FROM foto_piante fp
                JOIN varieta v ON fp.varieta_id = v.id
                JOIN specie s ON v.specie_id = s.id
                WHERE fp.is_principale = 1
                ORDER BY fp.data_upload ASC
                LIMIT 50";
        } else {
            // Ottieni nome varietà e specie
            $nome_query = "SELECT v.nome, s.nome as specie_nome
                          FROM varieta v
                          JOIN specie s ON v.specie_id = s.id
                          WHERE v.id = $filtro_varieta_id";
            $nome_result = $conn->query($nome_query);
            if ($nome_result && $nome_result->num_rows > 0) {
                $nome_row = $nome_result->fetch_assoc();
                $response['title'] = $nome_row['specie_nome'] . " - " . $nome_row['nome'];
                $response['subtitle'] = 'La nostra selezione di piante di alta qualità';
            }

            // Query per i prodotti di una varietà specifica
            $query = "
                SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
                FROM foto_piante fp
                JOIN varieta v ON fp.varieta_id = v.id
                JOIN specie s ON v.specie_id = s.id
                WHERE fp.varieta_id = $filtro_varieta_id
                ORDER BY fp.data_upload ASC";
        }

        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = [
                    'id' => $row['id'],
                    'titolo' => $row['titolo'],
                    'descrizione' => $row['descrizione'],
                    'nome_file' => $row['nome_file'],
                    'specie_nome' => $row['specie_nome'],
                    'varieta_nome' => $row['varieta_nome'],
                    'varieta_id' => $row['varieta_id'],
                    'is_principale' => $row['is_principale']
                ];
            }
        }
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = 'Errore nel caricamento dei dati: ' . $e->getMessage();
}

// Chiudi la connessione
$conn->close();

// Invia la risposta JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
