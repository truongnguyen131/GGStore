//  open close you like
const open_container_like = document.querySelector('.btn_open_you-will-like .open');
const close_container_like = document.querySelector('.close');
const container = document.querySelector('.you_like_container');
close_container_like.addEventListener('click', function() {
    container.style.left = "-100%";
    open_container_like.style.display = 'flex';
    close_container_like.style.display = 'none';
});
open_container_like.addEventListener('click', function() {
    container.style.left = '0';
    open_container_like.style.display = 'none';
    close_container_like.style.display = 'block';
});

//  slider recommende
$(document).ready(function() {
    $(".image-slider").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        draggable: false,
        prevArrow: '<img src="../../images/tb_jiantou_fb.png" alt="" class="slick-arrow arrow_left">',
        nextArrow: '<img src="../../images/tb_jiantou_fb.png" alt="" class="slick-next slick-arrow">',
        responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    infinite: false,
                },
            },
        ],
        // autoplay: true,
        // autoplaySpeed: 1000,
    });
});
//  slider recommende
$(document).ready(function() {
    $(".catagory-slider").slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        draggable: false,
        prevArrow: '<img src="../../images/tb_jiantou_fb.png" alt="" class="slick-arrow arrow_left">',
        nextArrow: '<img src="../../images/tb_jiantou_fb.png" alt="" class="slick-next slick-arrow">',
        responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    infinite: false,
                },
            },
        ],
        // autoplay: true,
        // autoplaySpeed: 1000,
    });
});