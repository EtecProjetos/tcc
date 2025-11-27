<?php
// galeria.php
session_start(); // Se for necessário gerenciar sessões

$ano_atual = date("Y"); // Exemplo de variável dinâmica
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <title>NEW FOOTBALL</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" type="imagex/png" href="fotos/logo.png">

  <script>
    function showGallery(groupIndex) {
      const groups = document.querySelectorAll('.gallery-group');
      const paginationLinks = document.querySelectorAll('.pagination-link');

      groups.forEach((group, index) => {
        group.classList.toggle('active', index >= groupIndex * 6 && index < (groupIndex + 1) * 6);
      });

      paginationLinks.forEach((link, index) => {
        link.classList.toggle('active', index === groupIndex);
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      showGallery(0);
    });
  </script>

</head>

<body>

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

<?php include 'nav_div.php'; ?> 

    <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 mx-auto text-center">
            <h1 class="text-white" style="font-size: 70px; text-shadow: 2px 2px 5px purple;">GALERIA</h1>
            <p>Os nossos craques</p>
          </div>
        </div>
      </div>
    </div>

    <div class="container site-section">
      <div class="row">
        <div class="col-6 title-section">
          <h2 class="heading">GALERIA</h2>
        </div>
      </div>

      <div class="row">
        <?php
        // Exemplo: criar array de fotos para gerar galeria dinamicamente
        $fotos = [
          "fotos/fotos galeria/f1.jpeg",
          "fotos/fotos galeria/f2.jpeg",
          "fotos/fotos galeria/f5.jpeg",
          "fotos/fotos galeria/f6.jpeg",
          "fotos/fotos galeria/f7.jpeg",
          "fotos/fotos galeria/f8.jpeg",
          "fotos/fotos2/1.png",
          "fotos/fotos2/2.png",
          "fotos/fotos2/3.png",
          "fotos/fotos2/4.png",
          "fotos/fotos2/5.png",
          "fotos/fotos2/6.png"
        ];

        foreach ($fotos as $index => $foto) {
          $groupClass = ($index < 6) ? "gallery-group active" : "gallery-group";
          echo "<div class='col-lg-4 mb-4 $groupClass'>
                  <div class='custom-media d-block'>
                    <div class='img mb-4'>
                      <img src='$foto' alt='Imagem $index' class='img-fluid'>
                    </div>
                  </div>
                </div>";
        }
        ?>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-7 text-center">
          <div class="custom-pagination">
            <span class="pagination-link active" onclick="showGallery(0)">1</span>
            <span class="pagination-link" onclick="showGallery(1)">2</span>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer-section">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-12">
            <div class="pt-5">
              <p>
                Copyright 2025 Direitos Reservados | NEW FOOTBALL
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/jquery.countdown.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.fancybox.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/jquery.mb.YTPlayer.min.js"></script>
<script src="js/main.js"></script>