// Tab Switching
const tabButtons = document.querySelectorAll(".tab-btn");
const tabPanels = document.querySelectorAll(".tab-panel");

tabButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Remove active class
    tabButtons.forEach((b) => b.classList.remove("active"));
    tabPanels.forEach((p) => p.classList.remove("active"));

    // Add to clicked tab
    btn.classList.add("active");
    const target = btn.getAttribute("data-tab");
    document.getElementById(target).classList.add("active");
  });
});

// Add form submission (template only)
const addForm = document.querySelector(".add-form");
if (addForm) {
  addForm.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("✅ Announcement saved (template only, no backend).");
    addForm.reset();
  });
}
