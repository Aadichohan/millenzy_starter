document.addEventListener("DOMContentLoaded", function () {
  const searchToggle = document.getElementById("searchToggle");
  const searchOverlay = document.getElementById("searchOverlay");
  const closeSearch = document.getElementById("closeSearch");

  searchToggle.addEventListener("click", (e) => {
    e.preventDefault();
    searchOverlay.classList.add("active");
    document.body.classList.add("no-scroll"); // 🧠 disable scrol
  });

  closeSearch.addEventListener("click", () => {
    searchOverlay.classList.remove("active");
    document.body.classList.remove("no-scroll"); // ✅ re-enable scroll
  });

  // Optional: close on pressing Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      searchOverlay.classList.remove("active");
      document.body.classList.remove("no-scroll"); // ✅ re-enable scroll
    }
  });
});
