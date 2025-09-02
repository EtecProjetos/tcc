    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
<div class="icon-navbar">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <a href="home_professor.php"><div class="nav-icon"><i class="fas fa-home"></i></div></a>
  <a href="Treinosprofessor.php"><div class="nav-icon"><i class="bi bi-cone-striped"></i></div></a>
  <a href="Jogosprofessor.php"><div class="nav-icon"><i class="bi bi-dribbble"></i></div></a>
  <a href="javascript:void(0)" id="btn-toggle-popup"><div class="nav-icon"><i class="fas fa-bars"></i></div></a>
</div>

<!-- Menu lateral popup -->
<div id="popup-menu" class="popup-menu" aria-hidden="true">
  <button class="popup-close-btn" id="btn-close-popup" aria-label="Fechar menu">×</button>
  <div class="popup-btn-container">
    <button class="popup-btn" onclick="location.href='perfil_professor.php'">
      <i class="bi bi-person-fill"></i> Perfil
    </button>
    <button class="popup-btn" onclick="location.href='campeonatos.php'">
      <i class="bi bi-trophy-fill"></i> Campeonatos
    </button>
    <button class="popup-btn" onclick="location.href='chamada_iniciar.php'">
      <i class="bi bi-card-checklist"></i> Chamada
    </button>
        <button class="popup-btn" onclick="location.href='gerenciar_alunos.php'">
      <i class="bi bi-person-arms-up"></i> Alunos
    </button>
    <button class="popup-btn" onclick="location.href='index.php'">
      <i class="bi bi-door-closed-fill"></i> Sair
    </button>
  
  </div>
</div>

<style>
    .popup-btn i {
  margin-right: 10px;
  font-size: 20px;
  vertical-align: middle;
}
  .footer-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #2c003c;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 0;
    border-top: 2px solid #fff;
    z-index: 999;
}

.nav-icon {
    color: yellow;
    font-size: 26px;
}

@media (max-width: 600px) {
    .logo-time,
    .logo {
        width: 60px;
        height: 60px;
    }

    .bi-x-lg {
        font-size: 24px;
        height: 60px; /* Ajustar também no mobile */
    }

    .versus {
        font-size: 1em;
    }

    .info {
        font-size: 0.95em;
        padding: 10px 12px;
    }

    .nav-icon {
        font-size: 22px;
    }
}

.icon-navbar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #390062;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 0;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff900;
    font-size: 2rem;
    width: 50px;
    height: 50px;
    line-height: 1;
    vertical-align: middle;
    margin: 0;
    transition: transform 0.2s;
    text-decoration: none;
}

.nav-icon i {
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
}

.nav-icon.active {
    transform: translateY(-10px);
}

.nav-icon:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

@media (max-width: 480px) {
    .nav-icon {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
    }

    .nav-icon.active {
        transform: translateY(-8px);
    }
}

.popup-menu {
    position: fixed;
    top: 0;
    right: -300px;
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #5e2ca5, #7e57c2);
    border-radius: 16px 0 0 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    transition: right 0.4s ease;
    z-index: 1000;
    overflow: hidden;
    box-shadow: -5px 0 12px rgba(0, 0, 0, 0.3);
}

.popup-menu.open {
    right: 0;
}

.popup-close-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
    align-self: flex-end;
    margin-bottom: 25px;
}

.popup-close-btn:hover {
    color: #ffd700;
}

.popup-btn-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
}

.popup-btn {
    background: #FFD700;
    border: none;
    border-radius: 20px;
    padding: 16px;
    color: #4b0082;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin: 10px 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease, background-color 0.3s;
}

.popup-btn:hover {
    background-color: #ffe34d;
    transform: translateY(-3px);
}

.time-bloco {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    max-width: 100px;
    text-align: center;
}

.time-nome {
    font-size: 0.9rem;
    font-weight: bold;
    color: #fff;
    word-wrap: break-word;
    margin-top: 10px;
}
.popup-btn {
  position: relative;
  background: #FFD700;
  border: none;
  border-radius: 20px;
  padding: 16px;
  color: #4b0082;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  margin: 10px 0;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease, background-color 0.3s;
  display: flex;
  justify-content: center; /* centra o texto */
  align-items: center;
}

.popup-btn i {
  position: absolute;
  left: 20px; /* distância do ícone da borda esquerda */
  font-size: 20px;
  vertical-align: middle;
}

.popup-btn:hover {
  background-color: #ffe34d;
  transform: translateY(-3px);
}


#btn-toggle-popup,
#btn-toggle-popup:focus,
#btn-toggle-popup:active,
#btn-toggle-popup:visited {
    outline: none !important;
    box-shadow: none !important;
    border: none !important;
    text-decoration: none !important;
    background: transparent !important;
}
</style>
<script>
   
  document.getElementById("btn-toggle-popup").addEventListener("click", function () {
    document.getElementById("popup-menu").classList.add("open");
  });

  document.getElementById("btn-close-popup").addEventListener("click", function () {
    document.getElementById("popup-menu").classList.remove("open");
  });


// Abre e fecha o menu lateral
const popupMenu = document.getElementById('popup-menu');
const btnToggle = document.getElementById('btn-toggle-popup');
const btnClose = document.getElementById('btn-close-popup');

btnToggle.addEventListener('click', () => {
  popupMenu.classList.toggle('open');
});

btnClose.addEventListener('click', () => {
  popupMenu.classList.remove('open');
});

// Fecha o popup se clicar fora dele
document.addEventListener('click', function (event) {
  const isClickInside = popupMenu.contains(event.target) || btnToggle.contains(event.target);
  if (!isClickInside) {
    popupMenu.classList.remove('open');
  }
});
</script>

