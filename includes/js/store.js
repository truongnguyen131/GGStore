window.onscroll = function() { // Khi cuộn trang
    var button = document.getElementById("filter");
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop; // Lấy vị trí cuộn của trang

    if (scrollTop >= 500) { // Nếu vị trí cuộn lớn hơn hoặc bằng 100px
        button.style.display = "flex"; // Hiển thị button
    } else {
        button.style.display = "none"; // Ẩn button
    }
};