<?php
session_start();
include_once('./mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./mod/head.php"; ?>
<link rel="stylesheet" href="./assets/css/grand_custom.css">
<link rel="stylesheet" href="./assets/css/new.css">

    <body>

        <?php include "./mod/nav.php"; ?>

        <div class="nk-main">

            <!-- START: Breadcrumbs -->
            <div class="nk-gap-1"></div>
            <div class="container">
                <ul class="nk-breadcrumbs">

                    <li><a href="index.html">Home</a></li>

                    <li><span class="fa fa-angle-right"></span></li>

                    <li><span>Recharge</span></li>

                </ul>
            </div>
            <div class="nk-gap-1"></div>
            <!-- END: Breadcrumbs -->

            <div class="container">
                <div class="row vertical-gap">
                    <div class="col-lg-12">
                        <div class="btn_tabs">
                            <button class="btn_tab" id="phone"><img src="../assets/images/card_phone.png" alt>
                                Phone Card
                            </button>
                            <button class="btn_tab" id="momo"><img src="../assets/images/momo.png" alt>Momo</button>
                        </div>
                        <!-- tabs content -->
                        <div class="tabs_content" id="tab_phone" style="display: none;">
                            <select name id>
                                <option value>Viettel</option>
                                <option value>VinaPhone</option>
                                <option value>Mobifone</option>
                            </select>
                            <div class="content">
                                <div class="exchange_rate_table">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="titles">VND</h3>
                                        </div>
                                        <div class="col">
                                            <h3 class="titles">G-Coin</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            10.000 VND
                                        </div>
                                        <div class="col">20</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            20.000 VND
                                        </div>
                                        <div class="col">40</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            50.000 VND
                                        </div>
                                        <div class="col">100</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            100.000 VND
                                        </div>
                                        <div class="col">200</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            200.000 VND
                                        </div>
                                        <div class="col">400</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            500.000 VND
                                        </div>
                                        <div class="col">1000</div>
                                    </div>
                                </div>
                                <div class="enter_information">
                                    <h3 class="titles">Fill in card information</h3>
                                    <div class="input">
                                        <span>Card recharge code:</span>
                                        <input type="text" placeholder="Code">
                                    </div>
                                    <div class="nk-gap"></div>
                                    <a href="#">Agree to exchange</a>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_content" id="tab_momo" style="display: none;">
                            <div class="content">
                                <div class="exchange_rate_table">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="titles">VND</h3>
                                        </div>
                                        <div class="col">
                                            <h3 class="titles">G-Coin</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="10" class="col">
                                            <input type="checkbox" name="price" id="10" value="10000">
                                            10.000 VND
                                        </label>
                                        <label for="10" class="col">20</label>
                                    </div>
                                    <div class="row">
                                        <label for="20" class="col">
                                            <input type="checkbox" name="price" id="20" value="20000">
                                            20.000 VND
                                        </label>
                                        <label for="20" class="col">40</label>
                                    </div>
                                    <div class="row">
                                        <label for="50" class="col">
                                            <input type="checkbox" name="price" id="50" value="50000">
                                            50.000 VND
                                        </label>
                                        <label for="50" class="col">100</label>
                                    </div>
                                    <div class="row">
                                        <label for="100" class="col">
                                            <input type="checkbox" name="price" id="100" value="100000">
                                            100.000 VND
                                        </label>
                                        <label for="100" class="col">200</label>
                                    </div>
                                    <div class="row">
                                        <label for="200" class="col">
                                            <input type="checkbox" name="price" id="200" value="200000">
                                            200.000 VND
                                        </label>
                                        <label for="200" class="col">400</label>
                                    </div>
                                    <div class="row">
                                        <label for="500" class="col">
                                            <input type="checkbox" name="price" id="500" value="500000">
                                            500.000 VND
                                        </label>
                                        <label for="500" class="col">1000</label>
                                    </div>
                                </div>
                                <div class="transaction_details">
                                    <h3 class="titles">Transaction details</h3>
                                    <div class="details_item">
                                        <span>Selected Product</span>
                                        <div class="value">
                                            <div id="value_gcoin" class="value_item">0</div>
                                            <div class="value_item"><i class="fas fa-gem"></i></div>
                                        </div>

                                    </div>
                                    <div class="details_item">
                                        <span>Price</span>
                                        <div class="value">
                                            <div id="value_vnd" class="value_item">0</div>
                                            <div class="value_item">VND</div>
                                        </div>

                                    </div>
                                    <div class="details_item">
                                        <span>Payment Methods</span>
                                        <span>Momo</span>
                                    </div>
                                    <div class="nk-gap"></div>
                                    <a href="#" class="btn_payment">Payment Processing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-gap-2"></div>

            <!-- START: Footer -->
            <footer class="nk-footer">

                <div class="container">
                    <div class="nk-gap-3"></div>
                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <div class="nk-widget">
                                <h4 class="nk-widget-title"><span class="text-main-1">Contact</span> With Us</h4>
                                <div class="nk-widget-content">
                                    <form action="php/ajax-contact-form.php" class="nk-form nk-form-ajax">
                                        <div class="row vertical-gap sm-gap">
                                            <div class="col-md-6">
                                                <input type="email" class="form-control required" name="email"
                                                    placeholder="Email *">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control required" name="name"
                                                    placeholder="Name *">
                                            </div>
                                        </div>
                                        <div class="nk-gap"></div>
                                        <textarea class="form-control required" name="message" rows="5"
                                            placeholder="Message *"></textarea>
                                        <div class="nk-gap-1"></div>
                                        <button class="nk-btn nk-btn-rounded nk-btn-color-white">
                                            <span>Send</span>
                                            <span class="icon"><i class="ion-paper-airplane"></i></span>
                                        </button>
                                        <div class="nk-form-response-success"></div>
                                        <div class="nk-form-response-error"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="nk-widget">
                                <h4 class="nk-widget-title"><span class="text-main-1">Latest</span> Posts</h4>
                                <div class="nk-widget-content">
                                    <div class="row vertical-gap sm-gap">

                                        <div class="col-lg-6">
                                            <div class="nk-widget-post-2">
                                                <a href="blog-article.html" class="nk-post-image">
                                                    <img src="../assets/images/post-1-sm.jpg" alt>
                                                </a>
                                                <div class="nk-post-title"><a href="blog-article.html">Smell magic in
                                                        the air. Or maybe barbecue</a></div>
                                                <div class="nk-post-date">
                                                    <span class="fa fa-calendar"></span> Sep 18, 2018
                                                    <span class="fa fa-comments"></span> <a href="#">4</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="nk-widget-post-2">
                                                <a href="blog-article.html" class="nk-post-image">
                                                    <img src="../assets/images/post-2-sm.jpg" alt>
                                                </a>
                                                <div class="nk-post-title"><a href="blog-article.html">Grab your sword
                                                        and fight the Horde</a></div>
                                                <div class="nk-post-date">
                                                    <span class="fa fa-calendar"></span> Sep 5, 2018
                                                    <span class="fa fa-comments"></span> <a href="#">7</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="nk-widget">
                                <h4 class="nk-widget-title"><span class="text-main-1">In</span> Twitter</h4>
                                <div class="nk-widget-content">
                                    <div class="nk-twitter-list" data-twitter-count="1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-gap-3"></div>
                </div>

                <div class="nk-copyright">
                    <div class="container">
                        <div class="nk-copyright-left">
                            <a target="_blank" href="https://www.templateshub.net">Templates Hub</a>
                        </div>
                        <div class="nk-copyright-right">
                            <ul class="nk-social-links-2">
                                <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                                <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a></li>
                                <li><a class="nk-social-steam" href="#"><span class="fab fa-steam"></span></a></li>
                                <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a></li>
                                <li><a class="nk-social-google-plus" href="#"><span class="fab fa-google-plus"></span></a>
                                </li>
                                <li><a class="nk-social-twitter" href="#" target="_blank"><span
                                            class="fab fa-twitter"></span></a></li>
                                <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a>
                                </li>

                                <!-- Additional Social Buttons
                        <li><a class="nk-social-behance" href="#"><span class="fab fa-behance"></span></a></li>
                        <li><a class="nk-social-bitbucket" href="#"><span class="fab fa-bitbucket"></span></a></li>
                        <li><a class="nk-social-dropbox" href="#"><span class="fab fa-dropbox"></span></a></li>
                        <li><a class="nk-social-dribbble" href="#"><span class="fab fa-dribbble"></span></a></li>
                        <li><a class="nk-social-deviantart" href="#"><span class="fab fa-deviantart"></span></a></li>
                        <li><a class="nk-social-flickr" href="#"><span class="fab fa-flickr"></span></a></li>
                        <li><a class="nk-social-foursquare" href="#"><span class="fab fa-foursquare"></span></a></li>
                        <li><a class="nk-social-github" href="#"><span class="fab fa-github"></span></a></li>
                        <li><a class="nk-social-instagram" href="#"><span class="fab fa-instagram"></span></a></li>
                        <li><a class="nk-social-linkedin" href="#"><span class="fab fa-linkedin"></span></a></li>
                        <li><a class="nk-social-medium" href="#"><span class="fab fa-medium"></span></a></li>
                        <li><a class="nk-social-odnoklassniki" href="#"><span class="fab fa-odnoklassniki"></span></a></li>
                        <li><a class="nk-social-paypal" href="#"><span class="fab fa-paypal"></span></a></li>
                        <li><a class="nk-social-reddit" href="#"><span class="fab fa-reddit"></span></a></li>
                        <li><a class="nk-social-skype" href="#"><span class="fab fa-skype"></span></a></li>
                        <li><a class="nk-social-soundcloud" href="#"><span class="fab fa-soundcloud"></span></a></li>
                        <li><a class="nk-social-slack" href="#"><span class="fab fa-slack"></span></a></li>
                        <li><a class="nk-social-tumblr" href="#"><span class="fab fa-tumblr"></span></a></li>
                        <li><a class="nk-social-vimeo" href="#"><span class="fab fa-vimeo"></span></a></li>
                        <li><a class="nk-social-vk" href="#"><span class="fab fa-vk"></span></a></li>
                        <li><a class="nk-social-wordpress" href="#"><span class="fab fa-wordpress"></span></a></li>
                        <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a></li>
                    -->
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- END: Footer -->

        </div>

        <!-- START: Scripts -->
        <?php include "./mod/add_script.php"; ?>
        <!-- END: Scripts -->

        <script>
            const phone = document.getElementById('phone');
            const momo = document.getElementById('momo');
            const tab_phone = document.getElementById('tab_phone');
            const tab_momo = document.getElementById('tab_momo');
            tab_phone.style.display = 'block';
            phone.style.border = '1px solid #dd163b';
            phone.onclick = () => {
                phone.style.border = '1px solid #dd163b';
                momo.style.border = '1px solid gray';
                tab_phone.style.display = 'block';
                tab_momo.style.display = 'none';
            }
            momo.onclick = () => {
                phone.style.border = '1px solid gray';
                momo.style.border = '1px solid #dd163b';
                tab_phone.style.display = 'none';
                tab_momo.style.display = 'block';
            }

        </script>

        <script>
        const checkboxes = document.querySelectorAll('input[name="price"]');
        const value_gcoin = document.getElementById('value_gcoin');
        const value_vnd = document.getElementById('value_vnd');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                // Uncheck other checkboxes
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
                function formatCurrency(number) {
                    const parts = number.toString().split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return parts.join(",");
                }
                // Get the value of the checked checkbox
                const checkedCheckbox = document.querySelector('input[name="price"]:checked');
       
                if (checkedCheckbox) {
                    const value = checkedCheckbox.value;
                    value_vnd.innerHTML =  formatCurrency(value);
                    value_gcoin.innerHTML = (value / 1000) * 2;
                }else{
                    value_vnd.innerHTML =  0;
                    value_gcoin.innerHTML = 0;
                }
            });
        });
        </script>



    </body>

</html>