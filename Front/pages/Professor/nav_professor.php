<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<div class="icon-navbar">
  <a href="home_professor.php"><div class="nav-icon"><i class="fas fa-home"></i></div></a>
  <a href="treinosprofessor.php"><div class="nav-icon"><i class="bi bi-cone-striped"></i></div></a>
  <a href="jogosprofessor.php"><div class="nav-icon"><i class="bi bi-dribbble"></i></div></a>
  <a href="javascript:void(0)" id="btn-toggle-popup"><div class="nav-icon"><i class="fas fa-bars"></i></div></a>
</div>

<!-- Menu lateral popup -->
<div id="popup-menu" class="popup-menu" aria-hidden="true">
  <button class="popup-close-btn" id="btn-close-popup" aria-label="Fechar menu">×</button>
  <div class="popup-btn-container">
    <button class="popup-btn" onclick="location.href='perfil_professor.php'">
      <i class="bi bi-person-fill"></i> Perfil
    </button>
    <button class="popup-btn" onclick="location.href='campeonatos_professor.php'">
      <i class="bi bi-trophy-fill"></i> Campeonatos
    </button>
    <button class="popup-btn" onclick="location.href='chamada_iniciar.php'">
      <i class="bi bi-card-checklist"></i> Chamada
    </button>
    <button class="popup-btn" onclick="location.href='gerenciar_alunos.php'">
      <i class="bi bi-person-arms-up"></i> Alunos
    </button>
<button class="popup-btn" onclick="location.href='logout_professor.php'">
  <i class="bi bi-door-closed-fill"></i> Sair
</button>

  </div>
</div>

<style>/* ================= Navbar Inferior ================= */
.icon-navbar {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: #000;
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 10px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
  z-index: 999;
  border-top-left-radius: 16px;
  border-top-right-radius: 16px;
  font-size: 0;
}

.icon-navbar a { display: flex; align-items: center; justify-content: center; text-decoration: none; }

.nav-icon {
  display: flex; align-items: center; justify-content: center;
  color: #fff900; font-size: 2rem;
  width: 50px; height: 50px; margin: 0;
  transition: transform 0.2s ease;
  text-decoration: none; border: none; background: transparent;
}

.nav-icon i { display: block; line-height: 1; margin: 0; padding: 0; vertical-align: middle; }

.nav-icon:hover { background-color: rgba(255,255,255,0.1); transform: translateY(-5px); }

/* ================= Popup Lateral ================= */
.popup-menu {
  position: fixed;
  top: 0;
  right: 0;
  width: 250px;
  height: 100vh;
  background: black;
  border-radius: 16px 0 0 16px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  transition: transform 0.4s ease, opacity 0.4s ease;
  transform: translateX(100%);
  opacity: 0;
  z-index: 1000;
  box-shadow: -5px 0 12px rgba(0,0,0,0.5);
}

/* Botão fechar permanece no topo */
.popup-close-btn {
  background: none; border: none; color: #fff;
  font-size: 28px; cursor: pointer; align-self: flex-end;
  margin-bottom: 15px; transition: color 0.2s ease;
}
.popup-close-btn:hover { color: #ffd700; }

/* Container dos botões centralizado verticalmente */
.popup-btn-container {
  flex: 1; /* ocupa todo espaço restante */
  display: flex;
  flex-direction: column;
  justify-content: center; /* centraliza verticalmente */
  align-items: stretch; /* ocupa largura total */
  gap: 15px;
}

/* Botões do popup */
.popup-btn {
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 10px;
  padding: 0 16px;
  background: #FFD700;
  border: none;
  border-radius: 12px;
  color: #4b0082;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, background-color 0.3s;
}
.popup-btn i { font-size: 20px; }
.popup-btn:hover { background-color: #ffe34d; transform: translateX(5px); }

/* ================= Responsividade ================= */
@media (max-width: 480px) {
  .nav-icon { font-size: 1.5rem; width: 40px; height: 40px; }
  .popup-btn { font-size: 16px; padding: 12px 14px; }
  .popup-btn i { font-size: 18px; }
}

/* Remove foco do botão toggle */
#btn-toggle-popup,
#btn-toggle-popup:focus,
#btn-toggle-popup:active,
#btn-toggle-popup:visited {
  outline: none !important; box-shadow: none !important; border: none !important;
  text-decoration: none !important; background: transparent !important;
}

/* Mostrar popup quando aberto */
.popup-menu.open {
  transform: translateX(0);
  opacity: 1;
}
</style>

<script>
// Abre e fecha popup
const popupMenu = document.getElementById('popup-menu');
const btnToggle = document.getElementById('btn-toggle-popup');
const btnClose = document.getElementById('btn-close-popup');

btnToggle.addEventListener('click', () => popupMenu.classList.toggle('open'));
btnClose.addEventListener('click', () => popupMenu.classList.remove('open'));

// Fecha popup se clicar fora dele
document.addEventListener('click', function(event) {
    const isClickInside = popupMenu.contains(event.target) || btnToggle.contains(event.target);
    if (!isClickInside) {
        popupMenu.classList.remove('open');
    }
});
</script>
