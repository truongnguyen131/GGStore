const user_icon = document.getElementById('user_icon');
const user_box = document.getElementById('user_box');
const search_icon = document.getElementById('search_icon');
const search_box = document.getElementById('search_box');

user_icon.addEventListener('mouseenter', function() {
    user_box.classList.add('active');
});

user_icon.addEventListener('mouseleave', function() {
    user_box.classList.remove('active');
});

search_icon.onclick = function(event) {
    event.stopPropagation();
    search_box.style.display = 'flex';
};

document.addEventListener('click', function(event) {
    const isClickInside = search_box.contains(event.target);
    if (search_box.style.display === 'flex' && !isClickInside) {
        search_box.style.display = 'none';
    }
});