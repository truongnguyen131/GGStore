window.onload = function() {
    let loading = document.getElementById('loading');
    let content = document.getElementById('content');
    content.style.transition = 'all .5s linear';
    loading.style.display = "block";
    setTimeout(function() {
        loading.style.display = "none";
    }, 2000);
    setTimeout(function() {
        content.style.opacity = "1";
    }, 1000);

}