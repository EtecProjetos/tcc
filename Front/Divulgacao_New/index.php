<?php
// index.php
session_start(); // Para futuras funcionalidades, caso queira usar sessão
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

  <style>
    html, body {
      overflow-x: hidden !important;
    }
    .hero {
      padding: 50px 15px;
      text-align: center;
    }
    .hero h1 {
      font-size: 70px;
      text-shadow: 2px 2px 5px purple;
      color: white;
      margin: 0 auto;
      max-width: 90%;
    }
    .hero p {
      color: white;
      margin-top: 10px;
    }

    /* Botão Login no menu */
    .site-menu .btn-login {
        display: inline-block;
        padding: 10px 20px;
        background-color: purple;
        color: #fff !important;
        border-radius: 5px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
    }
    .site-menu .btn-login:hover {
        background-color: #5a0b8f;
        color: #fff !important;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 40px;
        line-height: 1.2;
      }
      .hero p {
        font-size: 16px;
      }
      .container {
        padding: 0 10px;
      }
    }
  </style>
</head>

<body class="body">

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
      <div class="row align-items-center justify-content-center text-center">
        <div class="col-lg-5 col-md-8 col-sm-10">
          <h1 class="text-white">NEW FOOTBALL AIQFOME</h1>
          <p>Formando craques para o futebol e para a vida.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="latest-news">
    <div class="container">
      <div class="row">
        <div class="col-12 title-section">
          <h2 class="heading">Conquistas</h2>
        </div>
      </div>
      <div class="row no-gutters">
        <div class="col-md-4">
          <div class="post-entry">
            <a href="#">
              <img src="fotos/fotos conquistas/2.png" alt="Image" class="img-fluid">
            </a>
            <div class="caption">
              <div class="caption-inner">
                <h3 class="mb-3">SUB 15 VICE CAMPEÃO COPA BAND</h3>
                <div class="author d-flex align-items-center">
                  <div class="text">
                    <span>junho, 2024</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="post-entry">
            <a href="#">
              <img src="fotos/fotos conquistas/3.png" alt="Image" class="img-fluid">
            </a>
            <div class="caption">
              <div class="caption-inner">
                <h3 class="mb-3">SUB 13 CAMPEÃO COPA JOGA BONITO</h3>
                <div class="author d-flex align-items-center">
                  <div class="text">
                    <span>Setembro, 2022</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="post-entry">
            <a href="#">
              <img src="fotos/fotos conquistas/1.png" alt="Image" class="img-fluid">
            </a>
            <div class="caption">
              <div class="caption-inner">
                <h3 class="mb-3">SUB 16 CAMPEÃO DA COPA RIO DAS PEDRAS</h3>
                <div class="author d-flex align-items-center">
                  <div class="text">
                    <span>Fevereiro, 2024</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-6 title-section">
          <h2 class="heading">Videos de treino</h2>
        </div>
        <div class="col-6 text-right">
          <div class="custom-nav">
            <a href="#" class="js-custom-prev-v2"><span class="icon-keyboard_arrow_left"></span></a>
            <span></span>
            <a href="#" class="js-custom-next-v2"><span class="icon-keyboard_arrow_right"></span></a>
          </div>
        </div>
      </div>

      <div class="owl-4-slider owl-carousel">
        <div class="item">
          <div class="video-media">
            <img src="fotos/fotos2/tr 1.jpeg" alt="Image" class="img-fluid">
            <a href="fotos/fotos2/vd 1.mp4" class="d-flex play-button align-items-center" data-fancybox>
              <span class="icon mr-3"><span class="icon-play"></span></span>
              <div class="caption"><h3 class="m-0">Passe</h3></div>
            </a>
          </div>
        </div>
        <div class="item">
          <div class="video-media">
            <img src="fotos/fotos2/tr 2.jpeg" alt="Image" class="img-fluid">
            <a href="fotos/fotos2/vd 2.mp4" class="d-flex play-button align-items-center" data-fancybox>
              <span class="icon mr-3"><span class="icon-play"></span></span>
              <div class="caption"><h3 class="m-0">Chute ao gol</h3></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer style="margin-top: 20px;" class="footer-section">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-12">
          <div class="pt-5">
            <p> Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. Copyright &copy; 
              <script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with 
              <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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
