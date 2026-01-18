<?php
/**
 * Header template per il sito pubblico
 */
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Coral Plant - Azienda Agricola Biologica'; ?></title>
  <!-- Importazione diretta del font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital@1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="web/styles.css">
  <?php if (isset($additional_css) && !empty($additional_css)): ?>
    <?php foreach ($additional_css as $css_file): ?>
      <link rel="stylesheet" href="<?php echo $css_file; ?>">
    <?php endforeach; ?>
  <?php endif; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <!-- Navigation con Hamburger Menu -->
  <nav class="nav-container">
    <a href="index.php" class="logo">
      <img src="images/logo.svg" alt="Coral Plant Logo" style="height: 80px; width: auto; margin-top: -10px; margin-bottom: -40px;">
    </a>
    <div class="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <ul class="nav-links">
      <li><a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
      <li><a href="prodotti.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'prodotti.php') ? 'class="active"' : ''; ?>>Prodotti</a></li>
      <li><a href="index.php#chi-siamo">Chi Siamo</a></li>
      <li><a href="index.php#contatti">Contatti</a></li>
    </ul>
  </nav>