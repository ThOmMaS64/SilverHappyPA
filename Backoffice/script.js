function loadWelcome() {
  if (window.location.href === "http://localhost/ProjetAnnuel/Backoffice") {
    window.location.replace(
      "http://localhost/ProjetAnnuel/Backoffice/#pagewelcome",
    );
  } else if (
    window.location.href ===
    "http://localhost/ProjetAnnuel/Backoffice/#pagewelcome"
  ) {
    showWelcome();
  } else {
    hideWelcome();
  }
}

function showWelcome() {
  document.getElementById("pagewelcome").style.display = "block";
}

function hideWelcome() {
  if (window.location.href !== "http://localhost/ProjetAnnuel/Backoffice") {
    document.getElementById("pagewelcome").style.display = "none";
  }
}
