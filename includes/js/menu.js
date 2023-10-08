let menu = document.querySelector(".menu_icon");
let navbar = document.querySelector(".menu");

menu.onclick = () => {
    menu.classList.toggle("move");
    navbar.classList.toggle("active");
}

// menu usser

let menu_user = document.querySelector(".menu_user");
let menu_search = document.querySelector(".menu_search");
let menu_nofication = document.querySelector(".menu_nofication");

// Hàm ẩn tất cả các menu
function hideAllMenus() {
    menu_user.classList.remove("active");
    menu_search.classList.remove("active");
    menu_nofication.classList.remove("active");
}

document.querySelector("#user").onclick = () => {
    menu_user.classList.toggle("active");
    // Ẩn các menu khác
    menu_search.classList.remove("active");
    menu_nofication.classList.remove("active");
}

document.querySelector("#search").onclick = () => {
    menu_search.classList.toggle("active");
    // Ẩn các menu khác
    menu_user.classList.remove("active");
    menu_nofication.classList.remove("active");
}

document.querySelector("#bell").onclick = () => {
    menu_nofication.classList.toggle("active");
    // Ẩn các menu khác
    menu_user.classList.remove("active");
    menu_search.classList.remove("active");
}

// Ẩn tất cả các menu khi nhấn ra ngoài
document.addEventListener("click", function(event) {
    const targetElement = event.target;
    const isClickInsideMenu = targetElement.closest(".menu_user, .menu_search, .menu_nofication");
    const isClickOnMenuButton = targetElement.matches("#user, #search, #bell");

    if (!isClickInsideMenu && !isClickOnMenuButton) {
        hideAllMenus();
    }
});

function showDropdown() {
    var submenu = document.getElementById("submenu");
    submenu.style.display = "block";
}

function hideDropdown() {
    var submenu = document.getElementById("submenu");
    submenu.style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    var trigger = document.querySelector('.dropdown-trigger');
    trigger.addEventListener('mouseleave', hideDropdown);
});