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

search_icon.addEventListener('mouseenter', function() {
    search_box.classList.add('active');
});

search_icon.addEventListener('mouseleave', function(){
    search_box.classList.remove('active');
});
