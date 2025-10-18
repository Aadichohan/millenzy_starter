document.addEventListener("DOMContentLoaded", function () {
  const header = document.querySelector(".main-header");
  const placeholder = document.createElement("div");
  placeholder.classList.add("header-placeholder");
  header.parentNode.insertBefore(placeholder, header);

  const getAdminBarHeight = () => {
    const adminBar = document.getElementById("wpadminbar");
    return adminBar ? adminBar.offsetHeight : 0;
  };

  const setHeaderTop = () => {
    const adminBarHeight = getAdminBarHeight();
    header.style.top = adminBarHeight > 0 ? adminBarHeight + "px" : "0";
  };

  const updatePlaceholder = (active) => {
    placeholder.style.height = active ? header.offsetHeight + "px" : "0";
  };

  let lastScroll = 0;

  window.addEventListener("scroll", function () {
    const currentScroll = window.scrollY;

    if (currentScroll > 100 && currentScroll > lastScroll) {
      // Scrolling down → fix header
      if (!header.classList.contains("is-fixed")) {
        header.classList.add("is-fixed");
        setHeaderTop();
        updatePlaceholder(true);
      }
    } else if (currentScroll < lastScroll) {
      // Scrolling up → remove fixed when back to top
      if (currentScroll <= 0) {
        header.classList.remove("is-fixed");
        header.style.top = ""; // reset inline top
        updatePlaceholder(false);
      }
    }

    lastScroll = currentScroll;
  });

  // On resize, re-check admin bar and placeholder height
  window.addEventListener("resize", function () {
    // setHeaderTop();
    if (header.classList.contains("is-fixed")) {
      updatePlaceholder(true);
    }
  });
});
