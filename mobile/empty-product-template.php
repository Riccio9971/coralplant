<?php
/**
 * Template migliorato per il messaggio "nessun prodotto trovato"
 * Da usare in mobile-prodotti.php
 */

// Determina il tipo di messaggio da mostrare in base ai parametri
$icon_class = !empty($search_term) ? 'fa-search' : 'fa-seedling';
$heading = !empty($search_term) ? 'Nessun risultato trovato' : 'Nessun prodotto disponibile';

// Determina il messaggio e l'azione in base ai parametri
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
</div><?php
/**
 * Template migliorato per il messaggio "nessun prodotto trovato"
 * Da usare in mobile-prodotti.php
 */

// Determina il tipo di messaggio da mostrare in base ai parametri
$icon_class = !empty($search_term) ? 'fa-search' : 'fa-seedling';
$heading = !empty($search_term) ? 'Nessun risultato trovato' : 'Nessun prodotto disponibile';

// Determina il messaggio e l'azione in base ai parametri
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
</div><?php
/**
 * Template migliorato per il messaggio "nessun prodotto trovato"
 * Da usare in mobile-prodotti.php
 */

// Determina il tipo di messaggio da mostrare in base ai parametri
$icon_class = !empty($search_term) ? 'fa-search' : 'fa-seedling';
$heading = !empty($search_term) ? 'Nessun risultato trovato' : 'Nessun prodotto disponibile';

// Determina il messaggio e l'azione in base ai parametri
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
