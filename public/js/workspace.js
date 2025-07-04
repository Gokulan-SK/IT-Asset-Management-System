document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const toggleButton = document.querySelector(".menu-symbol");
  const parentMenus = document.querySelectorAll(".has-submenu");
  const userInfo = document.getElementById("user-info");
  const dropdown = document.getElementById("user-dropdown");

  // Toggle menu visibility
  toggleButton.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
  });

  // Toggle user dropdown menu
  userInfo.addEventListener("click", (e) => {
    e.stopPropagation(); // Prevent click event from bubbling up
    console.log("User info clicked", e);
    dropdown.classList.toggle("hidden");
  });

  // Toggle sidebar dropdown menu
  parentMenus.forEach((parentMenu) => {
    parentMenu.addEventListener("click", (e) => {
      if (
        e.target.tagName === "A" &&
        e.target.parentNode.classList.contains("submenu")
      ) {
        // if the clicked element is an <a> tag and it's a child of the submenu, do nothing
        return;
      }
      const submenu = parentMenu.querySelector(".submenu");
      if (
        e.target === parentMenu.querySelector("a") ||
        e.target.parentNode === parentMenu.querySelector("a")
      ) {
        submenu.classList.toggle("hidden");
      }
    });
  });
});

const closebtn = document.getElementsByClassName("closebtn");
//alert close button functionality
for (let i = 0; i < closebtn.length; i++) {
  closebtn[i].addEventListener("click", function (e) {
    const div = e.target.parentNode; // Get the parent element of the close button
    div.style.opacity = 0;
    setTimeout(function () {
      div.style.display = "none";
    }, 600); // Wait for the fade-out transition to complete
  });
}
