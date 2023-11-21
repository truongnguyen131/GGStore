const search_textbox = document.getElementById('search_textbox');
search_textbox.addEventListener('input', () => {
    const searchValue = search_textbox.value;
    $.post('../mod/navbar.php', {
        page: 'search',
        search_textbox: searchValue,
    }, function(data) {
        $('header').html(data);
    });
});