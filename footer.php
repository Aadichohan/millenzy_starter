<footer class="millenzy-footer">
  <div class="footer-inner container">
    <div class="footer-column">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.png" alt="Millenzy Logo">
      <p>Luxury, passion, and elegance — bottled for you.</p>
    </div>
    <div class="footer-column">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#">Shop</a></li>
        <li><a href="#">Collections</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">About Us</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
      </div>
    </div>
  </div>
  <div class="copyright">
    © <?php echo date('Y'); ?> Millenzy. All Rights Reserved.
  </div>
</footer>

<?php wp_footer(); ?>
</body>
<script>

  const navToggle = document.getElementById("navToggle");
const mobileNav = document.getElementById("mobileNav");
const menuOverlay = document.getElementById("menuOverlay");
const mainHeader = document.querySelector(".main-header");

function updateMenuPosition() {
  if (window.innerWidth > 999) {
    mobileNav.style = "";
    menuOverlay.style = "";
    return;
  }

  const wpAdminBar = document.getElementById("wpadminbar");
  const adminBarHeight = wpAdminBar ? wpAdminBar.offsetHeight : 0;

  const headerRect = mainHeader.getBoundingClientRect();
  const headerHeight = mainHeader.offsetHeight;


  // Base offset = header bottom position + scroll proportion (to make smooth)
  const baseTop = headerRect.bottom + window.scrollY;
  const headerBottom = headerRect.bottom + window.scrollY;
  // const dynamicOffset = baseTop + scrollPercent * 10; // adjust "10" for sensitivity
  // const dynamicOffset = (window.scrollY == 0)? 175 :  baseTop - window.scrollY;
  const dynamicOffset =   baseTop - window.scrollY;

  mobileNav.style.top = `${ dynamicOffset}px`;
  mobileNav.style.height = `calc(100vh - ${adminBarHeight + dynamicOffset}px)`;
  // menuOverlay.style.top = `${adminBarHeight + dynamicOffset}px`;
  // menuOverlay.style.height = `calc(100vh - ${adminBarHeight + dynamicOffset}px)`;
}

window.addEventListener("scroll", updateMenuPosition);
window.addEventListener("scroll", function(){
  mobileNav.classList.remove("active");
  // menuOverlay.classList.remove("active");
});
window.addEventListener("resize", updateMenuPosition);
window.addEventListener("load", updateMenuPosition);

navToggle.addEventListener("click", () => {
  mobileNav.classList.toggle("active");
  // menuOverlay.classList.toggle("active");
  updateMenuPosition();
});

// menuOverlay.addEventListener("click", () => {
//   mobileNav.classList.remove("active");
//   // menuOverlay.classList.remove("active");
// });
</script>



</html>
