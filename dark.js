function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", document.body.classList.contains("dark-mode"));
  }
  
  document.getElementById("darkModeToggle").addEventListener("click", toggleDarkMode);

  window.addEventListener("load", function() {
    if (localStorage.getItem("darkMode") === "true") {
      document.body.classList.add("dark-mode");
    }
  });