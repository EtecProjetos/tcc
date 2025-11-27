<?php
// contato.php
session_start(); // Para gerenciamento de sessão, se necessário
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" type="imagex/png" href="fotos/logo.png">

  <style>
    .content { display: flex; gap: 20px; justify-content: space-between; flex-wrap: wrap; }
    .map { flex: 1 1 50%; min-width: 300px; display: flex; justify-content: center; }
    .map iframe { width: 100%; max-width: 100%; height: 350px; border: 0; }
    .form { flex: 1 1 40%; min-width: 280px; display: flex; flex-direction: column; gap: 10px; align-items: center; }
    input, textarea { padding: 10px; border: none; background-color: #333; color: #fff; border-radius: 5px; width: 100%; }
    button { padding: 10px 20px; border: none; background-color: purple; color: #fff; font-weight: bold; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease; width: 100%; }
    button:hover { background-color: #5c007a; }
    h2, h3 { margin-bottom: 10px; text-align: center; }
    .social-icons { display: flex; justify-content: center; gap: 30px; margin-top: 20px; font-size: 36px; }
    .icon-link { text-decoration: none; transition: transform 0.3s ease, color 0.3s ease; }
    .icon-link.instagram { color: #c13584; }
    .icon-link.whatsapp { color: #25d366; }
    .icon-link:hover { transform: scale(1.2); }
    @media (max-width: 768px) { .content { flex-direction: column; align-items: center; } .form, .map { width: 100%; } button { width: 100%; } }
    .icon-link::before { content: none !important; }
  </style>
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
        <div class="col-lg-9 mx-auto text-center">
          <h1 class="text-white" style="font-size: 70px; text-shadow: 2px 2px 5px purple;">CONTATO</h1>
          <p>Entre em contato conosco!</p>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top: 90px;" class="container">
    <div class="content">
      <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3670.082822748068!2d-47.70771802490598!3d-23.09406377912376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c6126d5796017d%3A0x4cadfa18841f1b8a!2sR.%20Santa%20Cruz%2C%20955%2C%20Tiet%C3%AA%20-%20SP%2C%2018530-000!5e0!3m2!1spt-PT!2sbr!4v1736340064152!5m2!1spt-PT!2sbr" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="form">
        <h3 style="margin-top: 30px;">FAZER MATRÍCULA AGORA!!!</h3>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfy2oWCKkH75VGBrcBEptNYzXtDP2-1tWvou_ePPtoQ0cmm6w/viewform" target="_blank">
          <button type="button">CLIQUE AQUI PARA REALIZAR SUA MATRÍCULA</button>
        </a>
        <div class="social-icons">
          <a href="https://www.instagram.com/newfootball_aiqfome" target="_blank" class="icon-link instagram" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="https://wa.me/5511999999999" target="_blank" class="icon-link whatsapp" aria-label="WhatsApp">
            <i class="bi bi-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <br>

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
<!-- .site-wrap -->

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

</body>
</html>
