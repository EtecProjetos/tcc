<?php
// escola.php
session_start(); // Se quiser gerenciar sessões

$ano_atual = date("Y"); // Ano atual dinâmico
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
            <h1 class="text-white" style="font-size: 70px; text-shadow: 2px 2px 5px purple;">A ESCOLA</h1>
            <p>Um pouco de onde tudo começou.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-6 title-section">
            <h2 class="heading">O QUE O NEW FOOTBALL NOS TROUXE</h2>
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
          <?php
          $fotos_slider = [
            "fotos/alto rendimento/1.png",
            "fotos/alto rendimento/2.png",
            "fotos/alto rendimento/3.png",
            "fotos/alto rendimento/4.png",
            "fotos/alto rendimento/1.png",
            "fotos/alto rendimento/2.png"
          ];

          foreach ($fotos_slider as $foto) {
            echo "<div class='item'>
                    <div class='photo-media position-relative'>
                      <img src='$foto' alt='Imagem' class='img-fluid'>
                    </div>
                  </div>";
          }
          ?>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-12 title-section">
            <h2 class="heading">UM POUCO SOBRE A NOSSA HISTÓRIA</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="custom-media">
              <p>
                Nossa história começou em 2021, no Clube Regatas de Tietê, localizado na Avenida Beira Rio. Dois apaixonados pelo futebol, que desde jovens sonhavam em se tornar profissionais, mas infelizmente não conseguiram realizar esse sonho, decidiram seguir o que sempre amaram: o futebol. Mesmo sem alcançar seus próprios objetivos, o amor pelo esporte nunca os abandonou. Foi então que surgiu a ideia de abrir uma escolinha, para que as crianças pudessem se divertir e, quem sabe, alcançar o alto rendimento.
              </p>
              <p>
                No ano seguinte, em 2022, a escolinha se mudou para o bairro Santa Cruz, buscando melhorar as condições de treino e oferecer um ambiente mais confortável para os alunos. E desde então, estamos no Santa Cruz, seguindo com o mesmo propósito: ensinar futebol, promover diversão e, acima de tudo, formar novos sonhos no coração de cada criança. A jornada continua até hoje, com muita paixão e dedicação ao esporte.
              </p>
              <p>
                Estatísticas: 
                30 competições disputadas; 15 competições ganhas (destaques: campeão sub-16 em 2024 e vice-campeão da Taça Band 2024 sub-15). Atualmente temos 310 alunos, sendo 30 em altos rendimentos.
              </p>
            </div>
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
