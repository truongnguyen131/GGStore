let nav = document.getElementById('navbar');
let lanterns_left = document.getElementById('lanterns_left');
let lanterns_right = document.getElementById('lanterns_right');
window.addEventListener('scroll', () => {
    let value = window.scrollY;
    nav.style.height = "80px";
    lanterns_left.style.top = "63px";
    lanterns_right.style.top = "63px";
    if (value <= 0) {
        nav.style.height = "100px";
        lanterns_left.style.top = "83px";
        lanterns_right.style.top = "83px";
    }
});