<!-- Footer -->
<footer class="footer" id="contatti">
    <div class="footer-custom-grid">
      <div class="footer-section-left">
        <h3>Contattaci</h3>
        <p>Email: info@coralplant.it</p>
        <p>Tel: 081 883 17 02</p>
        <div class="social-icons">
          <a href="https://www.facebook.com/p/Azienda-Agricola-Coral-Plant-100063724861260/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/coral.plant/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
	
      <!-- Sezione logo centrale con path dinamico -->
      <div class="footer-logo-section">
        <?php 
        // Determina il percorso base del sito
        $basePath = '';
        if (strpos($_SERVER['PHP_SELF'], 'prodotti.php') !== false) {
            $basePath = './'; // Se siamo in prodotti.php
        } else {
            $basePath = './'; // Se siamo in index.php o altre pagine nella root
        }
        ?>
        <img src="<?php echo $basePath; ?>images/logo.svg" alt="Coral Plant Logo" class="footer-logo">
      </div>
	
      <div class="footer-section-right">
        <h3>Orari</h3>
        <p>Lun-Ven: 07:30 – 13:00 | 15:00-17:00</p>
        <p>Sab: 07:30 – 13:30</p>
        <p>Dom: Chiuso</p>
      </div>
    </div>
    
    <!-- Sezione copyright e partita IVA -->
    <div class="footer-bottom">
      <div class="footer-bottom-content">
        <p>Coral Plant Srl di Angelo D'apuzzo | P.IVA: 09087111218</p>
        <p>&copy; <?php echo date('Y'); ?> Coral Plant. Tutti i diritti riservati.</p>
        <p class="footer-legal-links">
          <a href="privacy.php">Privacy Policy</a> |
          <a href="termini.php">Termini e Condizioni</a>
        </p>
      </div>
    </div>
  </footer>
  
  <!-- JavaScript aggiuntivi specifici per ogni pagina -->
  <?php if (isset($additional_js) && !empty($additional_js)): ?>
    <?php foreach ($additional_js as $js_file): ?>
      <script src="<?php echo $js_file; ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <!-- Stili aggiuntivi per il logo nel footer -->
  <style>
    .footer-custom-grid {
      display: flex;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
      align-items: center;
      padding: 40px 20px 20px 20px;
    }
    
    .footer-section-left, 
    .footer-section-right {
      flex: 1;
      box-sizing: border-box;
      text-align: center;
    }
    
    .footer-section-left {
      padding-right: 50px;
    }
    
    .footer-section-right {
      padding-left: 50px;
    }
    
    .footer-logo-section {
      width: 240px;
      flex-shrink: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 50px;
    }
    
    .footer-logo {
      width: 100%;
      height: auto;
      object-fit: contain;
      filter: brightness(0) invert(1); /* Rende il logo bianco */
    }
    
    .footer-section-left .social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }
    
    .footer h3 {
      margin-bottom: 15px;
    }
    
    .footer p {
      margin-bottom: 8px;
    }
    
    /* Nuovi stili per la sezione copyright */
    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      margin-top: 20px;
      padding-top: 20px;
    }
    
    .footer-bottom-content {
      max-width: 1200px;
      margin: 0 auto;
      text-align: center;
      padding: 0 20px;
    }
    
    .footer-bottom p {
      margin: 5px 0;
      font-size: 0.9em;
      opacity: 0.8;
    }

    .footer-legal-links {
      margin-top: 10px !important;
    }

    .footer-legal-links a {
      color: white;
      text-decoration: none;
      opacity: 0.9;
      transition: opacity 0.3s ease;
      padding: 0 8px;
    }

    .footer-legal-links a:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .footer-custom-grid {
        flex-direction: column;
        gap: 30px;
        padding: 30px 20px 20px 20px;
      }
      
      .footer-section-left, 
      .footer-section-right {
        padding: 0;
      }
      
      .footer-logo-section {
        width: 180px;
        margin: 0;
      }
      
      .footer-bottom-content p {
        font-size: 0.8em;
      }
    }
  </style>
</body>
</html>
