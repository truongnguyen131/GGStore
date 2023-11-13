<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Galaxy Game Store | Lucky Wheel</title>

    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
    <meta name="author" content="_nK">

    <link rel="icon" type="image/png" href="../assets/images/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- START: Styles -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700" rel="stylesheet" type="text/css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script defer src="../assets/vendor/fontawesome-free/js/all.js"></script>
    <script defer src="../assets/vendor/fontawesome-free/js/v4-shims.js"></script>

    <!-- IonIcons -->
    <link rel="stylesheet" href="../assets/vendor/ionicons/css/ionicons.min.css">

    <!-- Flickity -->
    <link rel="stylesheet" href="../assets/vendor/flickity/dist/flickity.min.css">

    <!-- Photoswipe -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" type="text/css" href="../assets/vendor/photoswipe/dist/default-skin/default-skin.css">

    <!-- Seiyria Bootstrap Slider -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap-slider/dist/css/bootstrap-slider.min.css">

    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/summernote/dist/summernote-bs4.css">

    <!-- GoodGames -->
    <link rel="stylesheet" href="../assets/css/goodgames.css">
    <link rel="stylesheet" href="../assets/css/lucky-wheel.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/custom.css">

    <!-- END: Styles -->

    <!-- jQuery -->
    <script src="../assets/vendor/jquery/dist/jquery.min.js"></script>

</head>


<body>
<?php include'../mod/navbar.php'?>

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">

                <li><a href="index.html">Home</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="#">Game</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><span>Lucky Wheel</span></li>

            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->




        <div class="container">
            <div class="row vertical-gap">
                <div class="col-md-6">
                    <div class="wrapper typo" id="wrapper">
                        <section id="luckywheel" class="hc-luckywheel">
                            <div class="hc-luckywheel-container">
                                <canvas class="hc-luckywheel-canvas" width="500px" height="500px">Vòng Xoay May Mắn</canvas>
                            </div>
                            <a class="hc-luckywheel-btn" href="javascript:;">Spins</a>
                        </section>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                        <div class="tab">
                            <button class="tablinks" onclick="openCity(event, 'participation_guidelines')">Participation
                                guidelines</button>
                            <button class="tablinks" onclick="openCity(event, 'reward_history')">Reward history</button>
                        </div>
                        <!-- Tab content -->
                        <div id="participation_guidelines" class="tabcontent">
                            <h3>Participation guidelines</h3>
                            <p>This is a mini-game with many attractive prizes: games, gear, vouchers,...</p>
                            <p>You need to pay 10 G-coins for each spin.</p>
                            <p>Every day you will receive one free spin.</p>
                            <h4 class="spins">The number of available free spins : 1</h3>
                                <p>Spend 5 G-coins to refresh the rewards: <button class="refresh">Refresh</button></p>

                        </div>

                        <div id="reward_history" class="tabcontent">
                            <h3>Reward history</h3>
                            <div class="your__reward_history">
                                <div class="reward">
                                    <span class="date_time">2023/11/9</span>
                                    <span>Trailmakers</span>
                                </div>
                                <div class="reward">
                                    <span class="date_time">2023/11/9</span>
                                    <span>Trailmakers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- notification -->
        <div class="notification" id="notification" style="display: none;">
            <div class="notification_box">
                <span>You don't have enough GCoins, please top up!!</span>
                <div class="btn_notification">
                    <a href="">Yes</a>
                    <button id="btn_cancel">Cancel</button>
                </div>
            </div>
        </div>

        <!-- START: Footer -->
        <?php include'../mod/footer.php'?>
        <!-- END: Footer -->

    </div>

    <!-- START: Page Background -->

    <img class="nk-page-background-top" src="../assets/images/bg-top-3.png" alt="">
    <img class="nk-page-background-bottom" src="../assets/images/bg-bottom.png" alt="">

    <!-- END: Page Background -->

    <!-- START: Scripts -->

    <!-- Object Fit Polyfill -->
    <script src="../assets/vendor/object-fit-images/dist/ofi.min.js"></script>

    <!-- GSAP -->
    <script src="../assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
    <script src="../assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>

    <!-- Popper -->
    <script src="../assets/vendor/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Sticky Kit -->
    <script src="../assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>

    <!-- Jarallax -->
    <script src="../assets/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="../assets/vendor/jarallax/dist/jarallax-video.min.js"></script>

    <!-- imagesLoaded -->
    <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>

    <!-- Flickity -->
    <script src="../assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>

    <!-- Photoswipe -->
    <script src="../assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
    <script src="../assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>

    <!-- Jquery Validation -->
    <script src="../assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

    <!-- Jquery Countdown + Moment -->
    <script src="../assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="../assets/vendor/moment/min/moment.min.js"></script>
    <script src="../assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>

    <!-- Hammer.js -->
    <script src="../assets/vendor/hammerjs/hammer.min.js"></script>

    <!-- NanoSroller -->
    <script src="../assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>

    <!-- SoundManager2 -->
    <script src="../assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>

    <!-- Seiyria Bootstrap Slider -->
    <script src="../assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>

    <!-- Summernote -->
    <script src="../assets/vendor/summernote/dist/summernote-bs4.min.js"></script>

    <!-- nK Share -->
    <script src="../assets/plugins/nk-share/nk-share.js"></script>

    <!-- GoodGames -->
    <script src="../assets/js/goodgames.min.js"></script>
    <script src="../assets/js/goodgames-init.js"></script>
    <!-- END: Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="../assets/js/navbar.js"></script>
    <script src="../assets/js/lucky-wheel.js"></script>

    <script>
        var isPercentage = true;
        var prizes = [{
            text: "J2Team Security",
            percentpage: 0.24 // 24%
        }, {
            text: "Áo thun J2Team",
            percentpage: 0.01 // 1%
        }, {
            text: "Áo thun J2Team",
            percentpage: 0.01 // 1%
        }, {
            text: "Vòng Tay J2Team",
            percentpage: 0.1 // 10%
        }, {
            text: "J2Team Security",
            percentpage: 0.24 // 24%
        }, {
            text: "Áo thun J2Team",
            percentpage: 0.01 // 1%
        }, {
            text: "J2Team Security",
            percentpage: 0.24 // 24%
        }, {
            text: "Good luck next time",
            percentpage: 0.1 // 60%
        }];
        document.addEventListener(
            "DOMContentLoaded",
            function() {
                hcLuckywheel.init({
                    id: "luckywheel",
                    config: function(callback) {
                        callback &&
                            callback(prizes);
                    },
                    mode: "both",
                    getPrize: function(callback) {
                        var rand = randomIndex(prizes);
                        var chances = rand;
                        callback && callback([rand, chances]);
                    },
                    gotBack: function(data) {
                        if (data == null) {
                            Swal.fire(
                                'Chương trình kết thúc',
                                'Đã hết phần thưởng',
                                'error'
                            )
                        } else if (data == 'Good luck next time') {
                            Swal.fire(
                                "You didn't win the lottery",
                                data,
                                'error'
                            )
                        } else {
                            Swal.fire(
                                'Won the prize',
                                data,
                                'success'
                            )
                            confetti();
                        }
                    }
                });
            },
            false
        );

        function randomIndex(prizes) {
            if (isPercentage) {
                var counter = 1;
                for (let i = 0; i < prizes.length; i++) {
                    if (prizes[i].number == 0) {
                        counter++
                    }
                }
                if (counter == prizes.length) {
                    return null
                }
                let rand = Math.random();
                let prizeIndex = null;
                console.log(rand);

                //START: create switch case 
                let result = 'switch (true) {\n';

                for (let i = 7; i >= 0; i--) {
                    result += `  case rand < ${calculateCumulativePercent(i)}:\n`;
                    result += `    prizeIndex = ${i};\n`;
                    result += `    break;\n`;
                }

                result += `}`;
                eval(result);

                function calculateCumulativePercent(index) {
                    let cumulativePercent = '';

                    for (let j = 7; j >= index; j--) {
                        cumulativePercent += `prizes[${j}].percentpage + `;
                    }

                    return cumulativePercent.slice(0, -3);
                }

                //END: create switch case 

                return prizeIndex;
            } else {
                var counter = 0;
                for (let i = 0; i < prizes.length; i++) {
                    if (prizes[i].number == 0) {
                        counter++
                    }
                }
                if (counter == prizes.length) {
                    return null
                }
                var rand = (Math.random() * (prizes.length)) >>> 0;
                return rand;
            }
        }
    </script>

    <script>
        const participation_guidelines = document.getElementById("participation_guidelines");
        participation_guidelines.style.display = 'block';

        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <script>
        const notification = document.getElementById('notification');
        const btn_cancel = document.getElementById('btn_cancel');
        btn_cancel.onclick = () =>{
            notification.style.display = 'none';
        }
    </script>

</body>

</html>