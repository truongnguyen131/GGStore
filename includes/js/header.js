document.addEventListener("DOMContentLoaded", function() {
    // Tại đây, bạn có thể thực hiện các hành động cần thiết sau khi trang web đã tải xong.
    console.log("Trang web đã tải xong!");
});

document.addEventListener("DOMContentLoaded", function() {
    let cloud1 = document.getElementById('cl1');
    let cloud2 = document.getElementById('cl2');
    let cloud3 = document.getElementById('cl3');
    let cloud4 = document.getElementById('cl4');

    // Thiết lập giá trị left và right là -100% khi tải lại trang
    cloud1.style.left = "-100%";
    cloud2.style.left = "-100%";
    cloud3.style.right = "-100%";
    cloud4.style.right = "-100%";

    window.addEventListener('scroll', () => {
        let value = window.scrollY;
        cloud1.style.left = '0';
        cloud2.style.left = '0';
        cloud3.style.right = '0';
        cloud4.style.right = '0';

        // Đặt lại vị trí ban đầu (-100%) khi cuộn lên
        if (value <= 0) {
            cloud1.style.left = "-100%";
            cloud2.style.left = "-100%";
            cloud3.style.right = "-100%";
            cloud4.style.right = "-100%";
        }
    });
});