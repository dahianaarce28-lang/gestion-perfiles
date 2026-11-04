// ============================================================
// SCRIPT.JS · Gestión de Perfiles
// Este archivo debe estar en la MISMA carpeta que index.php
// ============================================================

document.addEventListener("DOMContentLoaded", function () {

  // ---------- Estrellas de fondo ----------
  var stars = document.getElementById("stars");
  if (stars) {
    for (var i = 0; i < 60; i++) {
      var s = document.createElement("div");
      var size = 1 + Math.random() * 3;
      s.className = "star";
      s.style.width = size + "px";
      s.style.height = size + "px";
      s.style.left = Math.random() * 100 + "%";
      s.style.top = Math.random() * 100 + "%";
      s.style.animationDelay = (Math.random() * 3).toFixed(2) + "s";
      s.style.animationDuration = (2 + Math.random() * 3).toFixed(2) + "s";
      stars.appendChild(s);
    }
  }

  // ---------- Nieve (se omite si la persona prefiere menos movimiento) ----------
  var snow = document.getElementById("snow");
  var menosMovimiento = window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (snow && !menosMovimiento) {
    for (var j = 0; j < 35; j++) {
      var f = document.createElement("div");
      f.className = "snowflake";
      f.textContent = "❄";
      f.style.left = Math.random() * 100 + "%";
      f.style.fontSize = (10 + Math.random() * 14).toFixed(0) + "px";
      f.style.animationDuration = (8 + Math.random() * 10).toFixed(1) + "s";
      f.style.animationDelay = (Math.random() * -12).toFixed(1) + "s";
      snow.appendChild(f);
    }
  }

  // ---------- Aviso de éxito: se oculta solo ----------
  var alerta = document.getElementById("alerta");
  if (alerta && !alerta.classList.contains("alert-error")) {
    setTimeout(function () {
      alerta.style.display = "none";
    }, 4500);
  }

  // Quita "?ok=1" de la dirección para que, al recargar, no repita el aviso
  if (window.history && window.history.replaceState && window.location.search.indexOf("ok=1") !== -1) {
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  // ---------- Validación antes de enviar ----------
  var form = document.getElementById("formPerfil");
  if (form) {
    form.addEventListener("submit", function (e) {
      var nombre = document.getElementById("nombre").value.trim();
      var correo = document.getElementById("correo").value.trim();
      var correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);

      if (nombre === "" || !correoValido) {
        e.preventDefault();
        mostrarError("Revisá los datos: escribí tu nombre y un correo válido.");
      }
    });
  }

  function mostrarError(texto) {
    var caja = document.getElementById("alerta");
    if (!caja) {
      caja = document.createElement("div");
      caja.id = "alerta";
      var tarjeta = document.querySelector(".card-form");
      tarjeta.parentNode.insertBefore(caja, tarjeta);
    }
    caja.className = "alert-box alert-error";
    caja.style.display = "block";
    caja.textContent = "⚠️ " + texto;
  }
});
