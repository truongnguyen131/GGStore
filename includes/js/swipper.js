// recommended game
var TrandingSlider = new Swiper('.tranding-slider', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    slidesPerView: 'auto',
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 2.5,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});

// Hover play video
// // Optional: Pause the video when the mouse leaves the image
const imageContainer = document.querySelector('.image_recommender');
imageContainer.addEventListener('DOMContentLoaded', function() {
    var video = document.querySelector('.hover_video');

    // Phát video khi rê chuột vào
    video.addEventListener('mouseenter', function() {
        video.play();
    });

    // Dừng video khi rời chuột ra khỏi vùng chứa video
    video.addEventListener('mouseleave', function() {
        video.pause();
        video.currentTime = 0; // Đặt thời gian video về 0 để khi hover vào lại chạy từ đầu
    });
});