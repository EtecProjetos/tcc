<?php
// nav_div.php
?>
<header class="site-navbar py-4" role="banner">
  <div class="container">
    <div class="d-flex align-items-center">
      <div class="site-logo">
        <a href="index.php">
          <img style="width: 80px; height: 80px;" src="fotos/logo.png" alt="Logo">
        </a>
      </div>
      <div class="ml-auto">
        <nav class="site-navigation position-relative text-right" role="navigation">
          <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-flex align-items-center">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="galeria.php" class="nav-link">Galeria</a></li>
            <li><a href="escola.php" class="nav-link">A Escola</a></li>
            <li><a href="contato.php" class="nav-link">Contato</a></li>
            <!-- Botão Login Aluno -->
            <li class="ml-3">
              <a href="../pages/Aluno/loginAluno.php" class="btn-login">Login</a>
            </li>
          </ul>
        </nav>
        <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white">
          <span class="icon-menu h3 text-white"></span>
        </a>
      </div>
    </div>
  </div>
</header>

<style>
/* Botão estilizado no menu */
.site-menu .btn-login {
    display: inline-block;
    padding: 10px 25px;
    background: linear-gradient(135deg, #8000ff, #b366ff);
    color: #fff !important;
    border-radius: 30px;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.site-menu .btn-login:hover {
    background: linear-gradient(135deg, #5a00b3, #9933cc);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}
</style>
