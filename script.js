// Smooth scrolling for navigation links
document.querySelectorAll(".nav-links a").forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});

// Interactive project hover effect
document.querySelectorAll(".project-item").forEach((item) => {
  item.addEventListener("mouseenter", () => {
    item.style.boxShadow = "0 8px 15px rgba(0, 0, 0, 0.2)";
    item.style.transform = "scale(1.05)";
  });
  item.addEventListener("mouseleave", () => {
    item.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.1)";
    item.style.transform = "scale(1)";
  });
});

// Contact form submission alert
document
  .querySelector(".contact-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    alert("Thank you for reaching out! I will get back to you soon.");
    this.reset();
  });
