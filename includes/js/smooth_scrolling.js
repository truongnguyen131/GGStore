$(document).ready(function() {
    $(".catagory-slider").slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        draggable: false,
        prevArrow: `<img src="../../images/arrow.png" alt="" class="slick-arrow arrow_left">`,
        nextArrow: `<img src="../../images/arrow.png" alt="" class="slick-next slick-arrow">`,
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