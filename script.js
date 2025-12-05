// Smooth scrolling for internal anchors (#about, #projects, etc.)
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const targetId = this.getAttribute("href");

    // Ignore if just "#"
    if (!targetId || targetId === "#") return;

    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      e.preventDefault();
      targetElement.scrollIntoView({
        behavior: "smooth",
      });
    }
  });
});

// Scroll reveal animation for elements with .hidden
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  },
  {
    threshold: 0.15,
  }
);

document.querySelectorAll(".hidden").forEach((el) => observer.observe(el));
