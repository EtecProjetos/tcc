fetch("nav_professor.php")
  .then(response => response.text())
  .then(data => {
    document.getElementById("nav-placeholder").innerHTML = data;

    const btnToggle = document.getElementById("btn-toggle-popup");
    const btnClose = document.getElementById("btn-close-popup");
    const popupMenu = document.getElementById("popup-menu");

    if (btnToggle && btnClose && popupMenu) {
      btnToggle.addEventListener("click", () => {
        popupMenu.classList.toggle("open");
        popupMenu.setAttribute("aria-hidden", popupMenu.classList.contains("open") ? "false" : "true");
      });

      btnClose.addEventListener("click", () => {
        popupMenu.classList.remove("open");
        popupMenu.setAttribute("aria-hidden", "true");
      });

      document.addEventListener("click", function (event) {
        const isClickInside = popupMenu.contains(event.target) || btnToggle.contains(event.target);
        if (!isClickInside) {
          popupMenu.classList.remove("open");
        }
      });
    }
  });
