<?php
/**
 * Pagina prodotti mobile ottimizzata per Coral Plant
 */

// Impostazioni della pagina
$page_title = 'Prodotti - Coral Plant Mobile';
$is_mobile = true;

// Include la configurazione del database
require_once '../config.php';

// Gestione filtri
$filtro_specie = isset($_GET['specie']) ? intval($_GET['specie']) : 0;
$filtro_varieta = isset($_GET['varieta']) ? intval($_GET['varieta']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Immagini placeholder per categorie
$placeholder_images = [
    'fiori' => '../images/rosa.jpg',
    'arbusti' => '../images/lavanda.jpg',
    'alberi' => '../images/girasole.jpeg',
    'ortaggi' => '../images/pomodori.jpg',
    'frutta' => '../images/frutti.jpg',
    'erbe' => '../images/erbe.jpg',
    'piante_grasse' => '../images/piante_grasse.jpg',
    'acquatiche' => '../images/acquatiche.jpg',
];

// Immagini default da usare se le altre non sono disponibili
$default_images = [
    '../images/rosa.jpg',
    '../images/lavanda.jpg',
    '../images/girasole.jpeg',
    '../images/pomodori.jpg'
];

// Ottieni tutte le specie per i filtri con conteggio dei prodotti
// Verifica se esiste la tabella specie_immagini
$check_table = $conn->query("SHOW TABLES LIKE 'specie_immagini'");
$table_exists = ($check_table !== false && $check_table->num_rows > 0);

if ($table_exists) {
    // Usa la tabella specie_immagini se esiste
    $query_specie = "
        SELECT s.id, s.nome, COUNT(DISTINCT fp.id) as num_prodotti, si.nome_file as immagine_custom
        FROM specie s
        LEFT JOIN varieta v ON v.specie_id = s.id
        LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
        LEFT JOIN specie_immagini si ON s.id = si.specie_id
        GROUP BY s.id
        ORDER BY s.nome";
} else {
    // Versione originale senza specie_immagini
    $query_specie = "
        SELECT s.id, s.nome, COUNT(DISTINCT fp.id) as num_prodotti
        FROM specie s
        LEFT JOIN varieta v ON v.specie_id = s.id
        LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
        GROUP BY s.id
        ORDER BY s.nome";
}

$result_specie = $conn->query($query_specie);
$specie_list = [];
if ($result_specie && $result_specie->num_rows > 0) {
    while ($row = $result_specie->fetch_assoc()) {
        $specie_list[] = $row;
    }
}

// Associa un'immagine a ciascuna specie
foreach ($specie_list as &$specie) {
    if (isset($specie['immagine_custom']) && !empty($specie['immagine_custom'])) {
        // Se esiste un'immagine custom, usala
        $upload_path = "../admin/uploads/specie/";
        // Verifica se la directory esiste, altrimenti usa un percorso alternativo
        if (file_exists($upload_path . $specie['immagine_custom'])) {
            $specie['image'] = $upload_path . $specie['immagine_custom'];
        } else {
            // Fallback se il file non esiste
            $nome_normalizzato = strtolower(trim($specie['nome']));
            $found = false;
            foreach ($placeholder_images as $key => $image) {
                if (strpos($nome_normalizzato, $key) !== false) {
                    $specie['image'] = $image;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $index = array_search($specie['id'], array_column($specie_list, 'id')) % count($default_images);
                $specie['image'] = $default_images[$index];
            }
        }
    } else {
        // Usa il metodo esistente per le immagini placeholder
        $nome_normalizzato = strtolower(trim($specie['nome']));
        $image_found = false;

        // Cerca corrispondenza diretta o parziale
        foreach ($placeholder_images as $key => $image) {
            if (strpos($nome_normalizzato, $key) !== false || strpos($key, $nome_normalizzato) !== false) {
                $specie['image'] = $image;
                $image_found = true;
                break;
            }
        }

        // Se non trova corrispondenza, usa un'immagine predefinita
        if (!$image_found) {
            // Assegna un'immagine default a rotazione
            $index = array_search($specie['id'], array_column($specie_list, 'id')) % count($default_images);
            $specie['image'] = $default_images[$index];
        }
    }
}
unset($specie);

$varieta_list = [];
if ($filtro_specie > 0) {
    // Utilizziamo una query che include le immagini personalizzate delle varietà
    $query_varieta = "
        SELECT v.id, v.nome, COUNT(DISTINCT fp.id) as num_prodotti,
               vi.nome_file as immagine_custom
        FROM varieta v
        LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
        LEFT JOIN varieta_immagini vi ON v.id = vi.varieta_id
        WHERE v.specie_id = $filtro_specie
        GROUP BY v.id
        ORDER BY v.nome";
    $result_varieta = $conn->query($query_varieta);
    if ($result_varieta && $result_varieta->num_rows > 0) {
        while ($row = $result_varieta->fetch_assoc()) {
            $varieta_list[] = $row;
        }

        // Applica le immagini alle varietà
        foreach ($varieta_list as &$varieta) {
            if (!empty($varieta['immagine_custom'])) {
                // Usa l'immagine personalizzata se disponibile
                $varieta['image'] = "../admin/uploads/varieta/" . $varieta['immagine_custom'];
            } else {
                // Altrimenti usa l'immagine della specie
                $specie_image = '';
                foreach ($specie_list as $specie) {
                    if ($specie['id'] == $filtro_specie) {
                        $specie_image = $specie['image'];
                        break;
                    }
                }

                // Se non è stata trovata un'immagine per la specie, usa la prima immagine di default
                if (empty($specie_image)) {
                    $specie_image = $default_images[0];
                }

                $varieta['image'] = $specie_image;
            }
        }
        unset($varieta);
    }
}
// Costruisci la query per i prodotti in base ai filtri
if ($filtro_varieta > 0) {
    // Se è selezionata una varietà specifica, mostra tutte le immagini
    $base_query = "
        SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
        FROM foto_piante fp
        JOIN varieta v ON fp.varieta_id = v.id
        JOIN specie s ON v.specie_id = s.id
        WHERE v.id = $filtro_varieta";

    if (!empty($search_term)) {
        $search_term = $conn->real_escape_string($search_term);
        $base_query .= " AND (fp.titolo LIKE '%$search_term%' OR
                             fp.descrizione LIKE '%$search_term%')";
    }

    // Ordina per data di caricamento (più recenti prima)
    $base_query .= " ORDER BY fp.data_upload DESC";
} elseif (!empty($search_term)) {
    // Se c'è una ricerca
    $search_term = $conn->real_escape_string($search_term);
    $base_query = "
        SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
        FROM foto_piante fp
        JOIN varieta v ON fp.varieta_id = v.id
        JOIN specie s ON v.specie_id = s.id
        WHERE (fp.titolo LIKE '%$search_term%' OR
              fp.descrizione LIKE '%$search_term%' OR
              v.nome LIKE '%$search_term%' OR
              s.nome LIKE '%$search_term%')";

    if ($filtro_specie > 0) {
        $base_query .= " AND s.id = $filtro_specie";
    }

    $base_query .= " ORDER BY fp.data_upload DESC";
} else {
    // Visualizzazione generale o filtrata per specie
    if ($filtro_specie > 0) {
        // Mostra solo le immagini principali per ogni varietà della specie
        $base_query = "
            SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
            FROM foto_piante fp
            JOIN varieta v ON fp.varieta_id = v.id
            JOIN specie s ON v.specie_id = s.id
            WHERE v.specie_id = $filtro_specie AND fp.is_principale = 1
            ORDER BY fp.data_upload DESC";

        // Se non ci sono immagini principali, prendi una qualsiasi immagine per varietà
        $check_result = $conn->query($base_query);
        if ($check_result && $check_result->num_rows == 0) {
            $base_query = "
                SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
                FROM foto_piante fp
                JOIN varieta v ON fp.varieta_id = v.id
                JOIN specie s ON v.specie_id = s.id
                WHERE v.specie_id = $filtro_specie
                GROUP BY v.id
                ORDER BY fp.data_upload DESC";
        }
    } else {
        // Per la visualizzazione di tutte le categorie, mostra solo immagini principali
        $base_query = "
            SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
            FROM foto_piante fp
            JOIN varieta v ON fp.varieta_id = v.id
            JOIN specie s ON v.specie_id = s.id
            WHERE fp.is_principale = 1
            ORDER BY fp.data_upload DESC";
    }
}

// Esegui la query
$result_prodotti = $conn->query($base_query);
$prodotti = [];

if ($result_prodotti && $result_prodotti->num_rows > 0) {
    while ($row = $result_prodotti->fetch_assoc()) {
        $prodotti[] = $row;
    }
}

// Ottieni titolo della pagina in base ai filtri
$titolo_pagina = "Tutti i Prodotti";
$descrizione_pagina = "La nostra selezione di piante di alta qualità";

if ($filtro_varieta > 0) {
    $query_varieta = "SELECT v.nome, s.nome as specie_nome FROM varieta v JOIN specie s ON v.specie_id = s.id WHERE v.id = $filtro_varieta";
    $result_varieta = $conn->query($query_varieta);
    if ($result_varieta && $result_varieta->num_rows > 0) {
        $row = $result_varieta->fetch_assoc();
        $titolo_pagina = $row['nome'];
        $descrizione_pagina = "Varietà di " . $row['specie_nome'];
    }
} elseif ($filtro_specie > 0) {
    $query_specie = "SELECT nome FROM specie WHERE id = $filtro_specie";
    $result_specie_nome = $conn->query($query_specie);
    if ($result_specie_nome && $result_specie_nome->num_rows > 0) {
        $row = $result_specie_nome->fetch_assoc();
        $titolo_pagina = $row['nome'];
        $descrizione_pagina = "Seleziona una varietà per vedere i prodotti";
    }
} elseif (!empty($search_term)) {
    $titolo_pagina = "Risultati per \"" . htmlspecialchars($search_term) . "\"";
    $descrizione_pagina = "Prodotti trovati per la tua ricerca";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $page_title; ?></title>

    <!-- Importazione del font Varela Round da Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="mobile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Stili per impedire lo scorrimento orizzontale */
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            padding-bottom: 95px; /* Spazio per il footer fisso + footer legale */
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        button, a, input, textarea {
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header e barra di ricerca migliorata - solo al click */
        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--white);
            box-shadow: var(--shadow);
            z-index: 1000;
            height: var(--header-height);
            transition: height 0.3s ease;
        }

        .search-bar {
            position: absolute;
            top: var(--header-height);
            left: 0;
            width: 100%;
            padding: 15px var(--gutter);
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 999;
            pointer-events: none;
        }

        .mobile-header.expanded .search-bar {
            max-height: 70px;
            opacity: 1;
            pointer-events: auto;
        }

        .search-bar form {
            display: flex;
            width: 100%;
            align-items: center;
        }

        .search-input-container {
            position: relative;
            flex: 1;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 18px;
            border: 1px solid var(--beige);
            border-radius: 24px;
            background: white;
            font-size: 1rem;
            box-shadow: var(--shadow-sm);
            color: var(--text-dark);
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(126, 162, 56, 0.2);
        }

        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }

        .search-btn:active {
            transform: scale(0.95);
            background: var(--primary-dark);
        }

        /* Modifica gli stili per la griglia di categorie */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
            width: 100%;
        }

        /* Aggiungi chiarezza sulla categoria sezione */
        .variety-section,
        .category-section {
            width: 100%;
        }

        /* Aggiungi questo per garantire che il grid funzioni correttamente */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            width: 100%;
        }

        /* Assicurati che l'immagine abbia una altezza fissa */
        .product-image {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .category-card {
            height: 140px;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
            border: 1px solid var(--beige);
        }

        .category-card:active {
            transform: scale(0.98);
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: white;
        }

        .category-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);
        }

        .category-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            color: white;
        }

        .category-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .category-count {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Footer fisso in fondo alla pagina */
        .footer-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: var(--footer-height);
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 900;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid var(--beige);
        }

        /* Footer legale discreto - posizionato sopra il footer di navigazione */
        .legal-footer-mobile {
            position: fixed;
            bottom: var(--footer-height);
            left: 0;
            width: 100%;
            background: rgba(85, 126, 52, 0.95);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 0.65rem;
            line-height: 1.4;
            z-index: 850;
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
        }

        .legal-footer-mobile a {
            color: white;
            text-decoration: none;
            opacity: 0.95;
            padding: 0 6px;
            font-weight: 500;
        }

        .legal-footer-mobile a:active {
            opacity: 0.7;
        }

        .legal-footer-mobile .separator {
            opacity: 0.7;
            padding: 0 3px;
        }

        .footer-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-light);
            padding: 8px 0;
            flex: 1;
            font-size: 0.8rem;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .footer-link i {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .footer-link.active {
            color: var(--primary-color);
        }

        /* Miglioramenti alla sezione titoli e filtri */
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .filter-title {
            font-size: 1.2rem;
            color: var(--accent-color);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .view-all-categories {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .empty-products-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px 20px;
            margin: 20px auto;
            max-width: 400px;
            width: calc(100% - 32px);
        }

        .empty-products-message i {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 15px;
        }

        .empty-products-message h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
            font-size: 1.2rem;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .empty-products-message p {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .reset-search {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: var(--radius);
            font-weight: 500;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .products-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .products-section .filter-header {
            width: 100%;
        }

        /* Card migliorata con bordi come in prodotti.php */
        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--beige);
            transition: all 0.3s ease;
        }

        .product-card:active {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* Dialog più moderna */
        .product-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1500;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-modal.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            width: 90%;
            max-width: 400px;
            max-height: 90vh;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transform: translateY(30px);
            transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.1);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .product-modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 15px 20px;
            background: var(--accent-color);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .close-modal {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .close-modal:active {
            background: rgba(255, 255, 255, 0.4);
        }

        .modal-body {
            overflow-y: auto;
            max-height: calc(90vh - 60px);
        }

        .product-image-slider {
            position: relative;
        }

        .slider-container {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: var(--beige-light);
        }

        .slider-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        .slider-controls {
            position: absolute;
            bottom: 15px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 8px;
            z-index: 10;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }

        .slider-dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
            border-color: white;
        }

        /* Indicatore di principale per le foto nei prodotti */
        .product-card {
            position: relative;
        }

        .principal-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Animazione di transizione per le foto */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        .product-details {
            padding: 20px;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-light);
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .product-description {
            margin-top: 15px;
            padding: 15px;
            background: var(--beige-light);
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
            line-height: 1.6;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .products-section {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            box-sizing: border-box;
        }

        .products-section .filter-header {
            width: 100%;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            width: 100%;
            box-sizing: border-box;
            padding-top: 10px;
        }

        /* Migliora il main container */
        .main-container {
            padding: 0 var(--gutter) 30px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Definisci variabili CSS personalizzate se mancanti */
        :root {
            --header-height: 60px;
            --footer-height: 60px;
            --gutter: 16px;
            --white: #ffffff;
            --primary-color: #7EA238;
            --primary-dark: #4d6222;
            --primary-light: #b6d37d;
            --beige: #D2DAC5;
            --beige-light: #f6f7f3;
            --accent-color: #557E34;
            --text-dark: #2c3e50;
            --text-light: #546e7a;
            --shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 6px 12px rgba(0, 0, 0, 0.15);
            --radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Header fisso -->
    <header class="mobile-header">
        <div class="header-content">
            <a href="mobile-index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <img src="../images/logo.svg" alt="Coral Plant Logo" class="logo">
            <button class="search-toggle">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="search-bar">
            <form action="mobile-prodotti.php" method="GET">
                <div class="search-input-container">
                    <input type="text" name="search" placeholder="Cerca prodotti..." id="search-input" value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Intro Banner -->
    <div class="intro-banner">
        <h1><?php echo htmlspecialchars($titolo_pagina); ?></h1>
        <p><?php echo htmlspecialchars($descrizione_pagina); ?></p>
    </div>

    <!-- Main Container -->
    <main class="main-container">
        <?php if (!empty($search_term)): ?>
            <!-- Breadcrumbs per ricerca -->
            <div class="breadcrumbs">
                <div class="breadcrumb-item">
                    <a href="mobile-prodotti.php" class="breadcrumb-link">Prodotti</a>
                </div>
                <div class="breadcrumb-item">
                    <span class="breadcrumb-current">Ricerca: "<?php echo htmlspecialchars($search_term); ?>"</span>
                </div>
            </div>
        <?php elseif ($filtro_varieta > 0): ?>
            <!-- Breadcrumbs per varietà specifica -->
            <div class="breadcrumbs">
                <div class="breadcrumb-item">
                    <a href="mobile-prodotti.php" class="breadcrumb-link">Prodotti</a>
                </div>
                <?php
                $specie_corrente = null;
                foreach ($specie_list as $specie):
                    if ($specie['id'] == $filtro_specie):
                        $specie_corrente = $specie;
                ?>
                        <div class="breadcrumb-item">
                            <a href="mobile-prodotti.php?specie=<?php echo $specie['id']; ?>" class="breadcrumb-link"><?php echo htmlspecialchars($specie['nome']); ?></a>
                        </div>
                    <?php endif;
                endforeach; ?>
                <div class="breadcrumb-item">
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($titolo_pagina); ?></span>
                </div>
            </div>
        <?php elseif ($filtro_specie > 0): ?>
            <!-- Breadcrumbs per specie -->
            <div class="breadcrumbs">
                <div class="breadcrumb-item">
                    <a href="mobile-prodotti.php" class="breadcrumb-link">Prodotti</a>
                </div>
                <div class="breadcrumb-item">
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($titolo_pagina); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($filtro_specie == 0 && $filtro_varieta == 0 && empty($search_term)): ?>
            <!-- Vista categorie principali -->
            <section class="category-section">
                <div class="filter-header">
                    <h2 class="filter-title"><i class="fas fa-leaf"></i> Categorie</h2>
                </div>

                <div class="categories-grid">
                    <?php foreach ($specie_list as $specie): ?>
                        <a href="mobile-prodotti.php?specie=<?php echo $specie['id']; ?>" class="category-card">
                            <img src="<?php echo $specie['image']; ?>" alt="<?php echo htmlspecialchars($specie['nome']); ?>" class="category-image" onerror="this.src='../images/placeholder-product.jpg'">
                            <div class="category-bg"></div>
                            <div class="category-content">
                                <h3 class="category-title"><?php echo htmlspecialchars($specie['nome']); ?></h3>
                                <span class="category-count"><?php echo $specie['num_prodotti']; ?> prodotti</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($filtro_specie > 0 && $filtro_varieta == 0 && empty($search_term)): ?>
            <!-- Vista varietà per specie selezionata -->
            <section class="variety-section">
                <div class="filter-header">
                    <h2 class="filter-title"><i class="fas fa-seedling"></i> Varietà</h2>
                    <a href="mobile-prodotti.php" class="view-all-categories">
                        Tutte le categorie <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

                <div class="categories-grid">
                    <?php if (empty($varieta_list)): ?>
                        <div class="empty-products-message" style="grid-column: 1 / -1;">
                            <i class="fas fa-seedling"></i>
                            <h3>Nessuna varietà disponibile</h3>
                            <p>Questa categoria non ha ancora varietà specifiche</p>
                            <a href="mobile-prodotti.php" class="reset-search">
                                <i class="fas fa-undo"></i> Visualizza tutte le categorie
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($varieta_list as $varieta): ?>
                            <a href="mobile-prodotti.php?varieta=<?php echo $varieta['id']; ?>" class="category-card">
                                <img src="<?php echo $varieta['image']; ?>" alt="<?php echo htmlspecialchars($varieta['nome']); ?>" class="category-image" onerror="this.src='../images/placeholder-product.jpg'">
                                <div class="category-bg"></div>
                                <div class="category-content">
                                    <h3 class="category-title"><?php echo htmlspecialchars($varieta['nome']); ?></h3>
                                    <span class="category-count"><?php echo $varieta['num_prodotti']; ?> prodotti</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Prodotti in layout a griglia - Solo se è selezionata una varietà o c'è una ricerca -->
        <?php if ($filtro_varieta > 0 || !empty($search_term)): ?>
            <section class="products-section">
                <div class="filter-header">
                    <h2 class="filter-title">
                        <i class="fas fa-th-large"></i>
                        <?php if ($filtro_varieta > 0): ?>
                            Prodotti
                        <?php elseif (!empty($search_term)): ?>
                            Risultati ricerca
                        <?php else: ?>
                            Tutti i prodotti
                        <?php endif; ?>
                    </h2>

                    <?php if ($filtro_varieta > 0): ?>
                        <a href="mobile-prodotti.php?specie=<?php echo $filtro_specie; ?>" class="view-all-categories">
                            Torna alle varietà <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php elseif ($filtro_specie > 0): ?>
                        <a href="mobile-prodotti.php" class="view-all-categories">
                            Tutte le categorie <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="products-grid" id="products-grid">
                    <?php if (empty($prodotti)): ?>
                        <?php
                        // Variabili per empty-product-template.php
                        $icon_class = !empty($search_term) ? 'fa-search' : 'fa-seedling';
                        $heading = !empty($search_term) ? 'Nessun risultato trovato' : 'Nessun prodotto disponibile';

                        if (!empty($search_term)) {
                            $message = 'La tua ricerca "' . htmlspecialchars($search_term) . '" non ha prodotto risultati.';
                            $action_url = 'mobile-prodotti.php';
                            $action_text = 'Mostra tutti i prodotti';
                            $action_icon = 'fa-th-large';
                        } elseif ($filtro_varieta > 0) {
                            $message = 'Questa varietà non ha ancora prodotti disponibili.';
                            $action_url = 'mobile-prodotti.php?specie=' . $filtro_specie;
                            $action_text = 'Torna alle varietà';
                            $action_icon = 'fa-arrow-left';
                        } elseif ($filtro_specie > 0) {
                            $message = 'Questa categoria non ha ancora varietà specifiche.';
                            $action_url = 'mobile-prodotti.php';
                            $action_text = 'Tutte le categorie';
                            $action_icon = 'fa-th';
                        } else {
                            $message = 'Non ci sono ancora prodotti nel catalogo.';
                            $action_url = 'mobile-index.php';
                            $action_text = 'Torna alla home';
                            $action_icon = 'fa-home';
                        }
                        ?>
                        <div class="empty-products-message">
                            <i class="fas <?php echo $icon_class; ?>"></i>
                            <h3><?php echo $heading; ?></h3>
                            <p><?php echo $message; ?></p>
                            <a href="<?php echo $action_url; ?>" class="reset-search">
                                <i class="fas <?php echo $action_icon; ?>"></i> <?php echo $action_text; ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($prodotti as $index => $prodotto): ?>
                            <div class="product-card" data-id="<?php echo $prodotto['id']; ?>">
                                <img src="../admin/uploads/piante/<?php echo htmlspecialchars($prodotto['nome_file']); ?>"
                                     alt="<?php echo htmlspecialchars($prodotto['titolo']); ?>"
                                     class="product-image"
                                     onerror="this.src='../images/placeholder-product.jpg'">
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($prodotto['titolo']); ?></h3>
                                    <p class="product-category">
                                        <i class="fas fa-seedling"></i>
                                        <?php echo htmlspecialchars($prodotto['specie_nome']); ?> &gt;
                                        <?php echo htmlspecialchars($prodotto['varieta_nome']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Modal Dettaglio Prodotto -->
<div class="product-modal" id="product-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Nome Prodotto</h2>
            <button class="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="product-image-slider" id="product-image-slider">
                <div class="slider-container">
                    <img src="" alt="Prodotto" id="modal-image" onerror="this.src='../images/placeholder-product.jpg'">
                </div>
                <div class="slider-controls">
                    <button class="slider-dot active"></button>
                    <button class="slider-dot"></button>
                    <button class="slider-dot"></button>
                </div>
            </div>
            <div class="product-details">
                <div class="product-info-row">
                    <div class="info-label">Categoria:</div>
                    <div class="info-value" id="modal-specie-value">Fiori</div>
                </div>
                <div class="product-info-row">
                    <div class="info-label">Varietà:</div>
                    <div class="info-value" id="modal-varieta-value">Rosa Rossa</div>
                </div>
                <div class="product-info-row" id="modal-badge-principale">
                </div>
                <div class="product-description" id="modal-description">
                    Descrizione del prodotto che verrà caricata dinamicamente.
                </div>
                
                <!-- Aggiunta dei pulsanti di azione come nella versione desktop -->
                <div class="modal-actions">
                    <a href="mobile-index.php#contatti" class="modal-action-btn">
                        <i class="fas fa-envelope"></i> Richiedi informazioni
                    </a>
                    <button class="modal-close-btn">
                        <i class="fas fa-arrow-left"></i> Torna ai prodotti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Tornare Su -->
    <button class="back-to-top" id="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Footer Mobile -->
    <div class="footer-nav">
        <a href="mobile-index.php" class="footer-link">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="mobile-prodotti.php" class="footer-link active">
            <i class="fas fa-leaf"></i>
            <span>Prodotti</span>
        </a>
        <a href="mobile-index.php#chi-siamo" class="footer-link">
            <i class="fas fa-users"></i>
            <span>Chi Siamo</span>
        </a>
        <a href="mobile-index.php#contatti" class="footer-link">
            <i class="fas fa-envelope"></i>
            <span>Contatti</span>
        </a>
    </div>

    <!-- Footer legale discreto -->
    <div class="legal-footer-mobile">
        Coral Plant Srl | P.IVA: 09087111218<br>
        <a href="mobile-privacy.php">Privacy Policy</a>
        <span class="separator">•</span>
        <a href="mobile-termini.php">Termini e Condizioni</a>
    </div>

    <!-- Passa i dati dei prodotti a JavaScript -->
    <script>
    var prodottiData = <?php echo json_encode($prodotti); ?>;
    </script>

    <!-- Script principale -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gestione header di ricerca
        const searchToggle = document.querySelector('.search-toggle');
        const header = document.querySelector('.mobile-header');

        if (searchToggle && header) {
            searchToggle.addEventListener('click', function() {
                header.classList.toggle('expanded');
                if (header.classList.contains('expanded')) {
                    document.getElementById('search-input').focus();
                }
            });

            // Chiudi la barra di ricerca quando si clicca fuori
            document.addEventListener('click', function(event) {
                const isClickInside = header.contains(event.target);

                if (!isClickInside && header.classList.contains('expanded')) {
                    header.classList.remove('expanded');
                }
            });

            // Previeni chiusura quando si clicca nella barra di ricerca
            const searchBar = document.querySelector('.search-bar');
            searchBar.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }

        // Gestione pulsante torna su
        const backToTopBtn = document.getElementById('back-to-top');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate([15, 10, 15]);
            }
        });

        // Gestione click sulle schede prodotti
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.dataset.id;

                // Trova il prodotto corrispondente nell'array
                const product = prodottiData.find(p => p.id == productId);

                if (product) {
                    // Popoliamo la modale con i dati del prodotto
                    document.getElementById('modal-title').textContent = product.titolo;
                    document.getElementById('modal-image').src = '../admin/uploads/piante/' + product.nome_file;
                    document.getElementById('modal-specie-value').textContent = product.specie_nome;
                    document.getElementById('modal-varieta-value').textContent = product.varieta_nome;
                    document.getElementById('modal-description').textContent = product.descrizione;

                    // Nascondi il badge principale (non ci interessa nella modale)
                    document.getElementById('modal-badge-principale').style.display = 'none';

                    // Carica le foto aggiuntive
                    loadAdditionalPhotos(productId, product.nome_file);

                    // Mostra la modale
                    document.getElementById('product-modal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        // Funzione per caricare le foto aggiuntive
        function loadAdditionalPhotos(productId, mainPhotoFilename) {
            // Reset dei controlli slider
            const sliderControls = document.querySelector('.slider-controls');
            sliderControls.innerHTML = '';

            // Aggiungi un puntino per l'immagine principale
            const mainDot = document.createElement('button');
            mainDot.className = 'slider-dot active';
            mainDot.dataset.index = 0;
            sliderControls.appendChild(mainDot);

            // Nascondi i controlli fino a quando non sappiamo se ci sono foto aggiuntive
            sliderControls.style.display = 'none';

            // Crea array delle immagini che conterrà principale + aggiuntive
            const images = [{
                src: '../admin/uploads/piante/' + mainPhotoFilename
            }];

            // Effettua la chiamata AJAX per ottenere le foto aggiuntive
            fetch('get-additional-photos.php?product_id=' + productId)
                .then(response => response.json())
                .then(data => {
                    if (data.photos && data.photos.length > 0) {
                        // Aggiungi le foto aggiuntive all'array immagini
                        data.photos.forEach((photo, index) => {
                            images.push({
                                src: '../admin/uploads/piante/' + photo.nome_file
                            });

                            // Crea un puntino per questa foto
                            const dot = document.createElement('button');
                            dot.className = 'slider-dot';
                            dot.dataset.index = index + 1; // +1 perché l'indice 0 è l'immagine principale
                            sliderControls.appendChild(dot);
                        });

                        // Mostra i controlli solo se abbiamo più di un'immagine
                        if (images.length > 1) {
                            sliderControls.style.display = 'flex';

                            // Aggiungi evento click a tutti i puntini
                            document.querySelectorAll('.slider-dot').forEach(dot => {
                                dot.addEventListener('click', function() {
                                    const imageIndex = parseInt(this.dataset.index);
                                    showSlide(imageIndex);
                                });
                            });

                            // Aggiungi swipe per mobile
                            const sliderContainer = document.querySelector('.slider-container');
                            let touchStartX = 0;
                            let touchEndX = 0;

                            sliderContainer.addEventListener('touchstart', function(e) {
                                touchStartX = e.changedTouches[0].screenX;
                            }, { passive: true });

                            sliderContainer.addEventListener('touchend', function(e) {
                                touchEndX = e.changedTouches[0].screenX;
                                handleSwipe();
                            }, { passive: true });

                            function handleSwipe() {
                                const threshold = 50; // Distanza minima per considerarlo uno swipe
                                const currentIndex = parseInt(document.querySelector('.slider-dot.active').dataset.index);

                                if (touchStartX - touchEndX > threshold) {
                                    // Swipe a sinistra (avanti)
                                    const nextIndex = Math.min(currentIndex + 1, images.length - 1);
                                    showSlide(nextIndex);
                                } else if (touchEndX - touchStartX > threshold) {
                                    // Swipe a destra (indietro)
                                    const prevIndex = Math.max(currentIndex - 1, 0);
                                    showSlide(prevIndex);
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Errore nel caricamento delle foto aggiuntive:', error);
                });

            // Funzione per mostrare una specifica foto
            function showSlide(index) {
                if (index >= 0 && index < images.length) {
                    // Aggiorna l'immagine visualizzata
                    const modalImage = document.getElementById('modal-image');
                    modalImage.src = images[index].src;
                    modalImage.classList.add('fade-in');

                    // Rimuovi la classe di animazione dopo che è terminata
                    setTimeout(() => {
                        modalImage.classList.remove('fade-in');
                    }, 300);

                    // Aggiorna i puntini attivi
                    document.querySelectorAll('.slider-dot').forEach((dot, i) => {
                        if (i === index) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });

                    // Feedback tattile
                    if ('vibrate' in navigator) {
                        navigator.vibrate(20);
                    }
                }
            }
        }
        // Gestione chiusura modale
        document.querySelector('.close-modal').addEventListener('click', function() {
            document.getElementById('product-modal').classList.remove('active');
            document.body.style.overflow = '';
        });

        // Aggiungi event listener per il pulsante "Torna ai prodotti"
const closeBtn = document.querySelector('.modal-close-btn');
if (closeBtn) {
    closeBtn.addEventListener('click', function() {
        // Chiudi la modale
        document.getElementById('product-modal').classList.remove('active');
        document.body.style.overflow = '';
        
        // Feedback tattile
        if ('vibrate' in navigator) {
            navigator.vibrate(20);
        }
    });
}

        // Chiudi modale quando si clicca fuori dal contenuto
        document.getElementById('product-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                document.getElementById('product-modal').classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Effetto hover per le categorie su dispositivi touch
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            }, {passive: true});

            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }, {passive: true});
        });

        // Gestisce l'errore delle immagini
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                if (this.src !== '../images/placeholder-product.jpg') {
                    this.src = '../images/placeholder-product.jpg';
                }
            });
        });
    });
    </script>
</body>
</html>
<?php
// Chiudi la connessione al database
$conn->close();
?>