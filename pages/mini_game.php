<?php
include_once('./mod/database_connection.php');
?>
<?php
$username = isset($_SESSION["userName"]) ? $_SESSION["userName"] : "";
$currentDate = date('Y-m-d');
$sql_user = "SELECT id,Gcoin,number_spins,lastest_login FROM `users` WHERE username = '" . $username . "'";
$result_users = $conn->query($sql_user);
while ($row_data_users = mysqli_fetch_assoc($result_users)) {
    $data_user_id = isset($row_data_users['id']) ? $row_data_users['id'] : "";
    $gcoin = isset($row_data_users['Gcoin']) ? $row_data_users['Gcoin'] : 0;
    $lastest_login = isset($row_data_users['lastest_login']) ? $row_data_users['lastest_login'] : "";
    $number_spins = isset($row_data_users['number_spins']) ? $row_data_users['number_spins'] : 0;
}
$sql_product = "SELECT p.id,p.product_name as name,p.image_avt_url as img,p.price FROM `products` p WHERE (p.release_date <= NOW()) AND (p.price BETWEEN 1 AND 50) AND (p.classify = 'game')";
$sql_voucher = "SELECT v.id, v.value,v.type ,v.minimum_condition
FROM `vouchers` v 
WHERE (v.date_expiry >= NOW() OR v.date_expiry IS NULL) AND 
(v.quantity > 0 OR v.quantity IS NULL) AND 
(v.value BETWEEN 1 AND 50)";

$result_products = $conn->query($sql_product);
$result_vouchers = $conn->query($sql_voucher);

while ($row_data_products = mysqli_fetch_assoc($result_products)) {
    $data_products[] = $row_data_products;
}

$jsonData_products = json_encode($data_products);

while ($row_data_vouchers = mysqli_fetch_assoc($result_vouchers)) {
    $row_data_vouchers['img'] = 'voucher_gcoin_img.jpg';
    if ($row_data_vouchers['type'] == 'percent') {
        if ($row_data_vouchers['minimum_condition'] <= 0) {
            $name_voucher = $row_data_vouchers['value'] . "% discount";
        } else {
            $name_voucher = "Apply a " . $row_data_vouchers['value'] . "% discount to the total bill value of " . $row_data_vouchers['minimum_condition'] . " or more.";
        }
    } else {
        if ($row_data_vouchers['minimum_condition'] <= 0) {
            $name_voucher = $row_data_vouchers['value'] . " G-Coin discount";
        } else {
            $name_voucher = "Apply a " . $row_data_vouchers['value'] . " G-Coin discount to the total bill value of " . $row_data_vouchers['minimum_condition'] . " G-Coin or more.";
        }
    }
    $row_data_vouchers['name'] = $name_voucher;
    $data_vouchers[] = $row_data_vouchers;
}

$jsonData_vouchers = json_encode($data_vouchers);

echo "<script> var productsData = " . $jsonData_products . ";</script>";
echo "<script> var vouchersData = " . $jsonData_vouchers . ";</script>";

$sql = "SELECT u.username,ob.bag_id,b.bag_name, ob.opened_at FROM `opened_bags` ob,`users` u, `bags` b WHERE u.id = ob.user_id GROUP BY(u.username)";
?>
<style>
    /* START : MINI GAME */
.mini_game_contain{
    position: relative;
}
#loading{
    display: none;
    position: absolute;
    z-index: 1001;
    top: 50%;
    left: 50%;
    translate: -50% -50%;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.733);
}
.loader {
    position: fixed;
    z-index: 1002;
    top: 50%;
    left: 50%;
    translate: -50% -50%;
    border: 4px solid rgb(255, 174, 0);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border-right-color: transparent;
    animation: rot 1s linear infinite;
    box-shadow: 0px 0px 20px rgb(255, 174, 0) inset;
  }
  
  @keyframes rot {
    100% {
      transform: rotate(360deg);
    }
  }

.list_item_mini_game {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    column-gap: 5px;
    --items: 3;
    --pace: 10px;
    width: 100%;
}

.item_game {
    position: relative;
    cursor: pointer;
    width: calc(calc(100% / var(--items)) - var(--pace));
}

.item_game_img{
    width: 90px;
    height: 90px;
    background-image: url(../Galaxy_Game_Store/assets/images/item1.png);
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   /* animation: .5s shine_brightly linear infinite alternate; */
  
}
.animation_gift{
    filter: drop-shadow(0px 0px 8px yellow);
    transform: scale(1.1);
}
.item_game_img img{
    width: 100%;height: 100%;
    object-fit: contain;
}
.animation_chose_gift{
      animation: .3s shine_brightly linear infinite alternate; 
}
@keyframes shine_brightly {
    0% {
        filter: drop-shadow(0px 0px 0px yellow);
        transform: rotate(-3deg);
    }

    100% {
        filter: drop-shadow(0px 0px 8px yellow);
        transform: rotate(3deg);
    }
}
.animation_open{
 animation: 1.5s open linear;
 animation-fill-mode: forwards; 
}
@keyframes open {
    0% {
        background-image: url(../Galaxy_Game_Store/assets/images/item2.png);
    }
    100% {
        background-image: url(../Galaxy_Game_Store/assets/images/item3.png);
    }
}
.display_gift{
    position: absolute;
    z-index: 100;
    top: 48%;
    left: 50%;
    translate: -50% -50%;
    width: 100%;
    height: auto;
    padding: 10px 0;
    border-radius: 3px;
    background: rgba(0, 0, 0, 0.9);
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 5px;
}
.button-wrapper{
    position: relative;
}
.button-wrapper canvas {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
  }
.display_gift .img_gift img{
    width: 100%;
    height: 100px;
    object-fit: contain;
}
.display_gift__content{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 5px;
}
.display_gift__content h4{
    margin: 0;
    font-size: 15px;
    text-align: center;
}
.display_gift__content span{
    text-align: center;
    font-size: 15px;
    font-weight: 600;
    color: #ff0;
}
.btn_game button{
    background: #dd163b;
    width: 100px;
    height: 30px;
    border: none;
    outline: none;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 600;
    color: white;
    transition: .3s linear;
}
.btn_game button:hover{
    background: #ec6880;
}
.btn_game button:active{
    transform: scale(0.9);
}


.introduction {
    display: flex;
    align-items: center;
    position: relative;

}

.icon_control{
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.icon_help {
    display: block;
    cursor: pointer;
    width: 30px;
    height: 30px;
    color: white;
    transition: .3s linear;
}

.icon_gift{
    display: block;
    cursor: pointer;
    width: 25px;
    height: 25px;
    color: white;
    transition: .3s linear;
}

.icon_help:hover , .icon_gift:hover{
    color: #dd163b;
}
.introduction_box ,.introduction__list_gift{
    display: none;
    position: absolute;
    z-index: 100;
    top: 100%;
    left: 0;
    width: 100%;
    height: auto;
    background: rgba(0, 0, 0, 0.763);
    padding: 5px;
}

.introduction_box .introduction_box__content {
    display: block;
    font-family: "Montserrat", sans-serif;
    color: white;
    font-size: 12px;
}

.spin_free {
    display: block;
    color: green;
    font-size: 12px;
    font-family: "Montserrat", sans-serif;
    font-weight: 550;
}

.introduction__list_gift-content{
    padding: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    --col:3;
    --spac:5px;
    width: 100%;
    margin-left: calc(-1 * var(--pace));
}
.introduction__list_gift-content .gift__item{
    width: calc(calc(100% / var(--col)) - var(--spac));
    height: 60px;
    margin: 1px 1px;
    border: 1px solid rgb(255, 196, 0);
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}
.gift__item img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.percent{
    position: absolute;
    bottom: 0;right: 0;
    background: #dd163bbd;
    padding: 0 2px;
    font-size: 12px;
    font-weight: 600;
    color: black;
}
.list_gift__title{
    font-size: 15px;
    font-weight: 600;
    color: yellow;
    margin-left: 10px;
}

/*START: Button Play */
.btn_control_minigame{
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 10px;
}
button {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 0 10px;
    height: 35px;
    color: white;
    text-shadow: 2px 2px rgb(116, 116, 116);
    text-transform: uppercase;
    border: solid 2px white;
    letter-spacing: 1px;
    font-weight: 600;
    font-size: 17px;
    background-color: hsl(49deg 98% 60%);
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    transition: all .5s ease;
  }
  
  button:active {
    transform: scale(.9);
    transition: all 100ms ease;
  }
  
  button svg {
    transition: all .5s ease;
    z-index: 2;
  }
  
  .play {
    transition: all .5s ease;
    transition-delay: 300ms;
  }
  
  button:hover svg {
    transform: scale(3) translate(50%);
  }
  
  .now {
    position: absolute;
    left: 0;
    transform: translateX(-100%);
    transition: all .5s ease;
    z-index: 2;
  }
  .now .fa-gem {
    font-size: 5px;
  }
  button:hover .now {
    transform: translateX(10px);
    transition-delay: 300ms;
  }
  
  button:hover .play {
    transform: translateX(200%);
    transition-delay: 300ms;
  }
  .disabled{
    pointer-events: none;
   filter: grayscale(100%);
  }
/*END: Button Play */
/* END : MINI GAME */
</style>
<div class="mini_game_contain">
    <div id="loading">
        <div class="loader"></div>
    </div>
    <div class="introduction">
        <div class="icon_control">
            <ion-icon name="help-circle-outline" class="icon_help"></ion-icon>
            <ion-icon name="gift-outline" class="icon_gift"></ion-icon>
        </div>

        <div class="introduction_box">
            <span class="introduction_box__content">- This is a mini game where you can open attractive gifts such as games and vouchers.</span>
            <span class="introduction_box__content">- Each opening will cost 10 <i class="fas fa-gem"></i></span>
            <span class="introduction_box__content">- The price will increase after each opening, and the winning rate will also increase.</span>
            <span class="introduction_box__content">- Every day you will receive one free opening.</span>
            <span class="spin_free">Free opening: <span id="free_opens"></span></span>
        </div>
        <div class="introduction__list_gift">
            <span class="list_gift__title">List Gift</span>
            <div class="introduction__list_gift-content" id="gift-container">
            </div>

        </div>
    </div>
    <div class="mini_game">
        <div class="list_item_mini_game" id="list_item_mini_game">
        </div>
        <div class="btn_control_minigame">
            <button class="<?= empty($username) ? 'disabled' : ''; ?>" onclick="refresh_list_gift()">
                <svg height="36px" width="36px" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <rect fill="#fdd835" y="0" x="0" height="36" width="36"></rect>
                    <path d="M38.67,42H11.52C11.27,40.62,11,38.57,11,36c0-5,0-11,0-11s1.44-7.39,3.22-9.59 c1.67-2.06,2.76-3.48,6.78-4.41c3-0.7,7.13-0.23,9,1c2.15,1.42,3.37,6.67,3.81,11.29c1.49-0.3,5.21,0.2,5.5,1.28 C40.89,30.29,39.48,38.31,38.67,42z" fill="#e53935"></path>
                    <path d="M39.02,42H11.99c-0.22-2.67-0.48-7.05-0.49-12.72c0.83,4.18,1.63,9.59,6.98,9.79 c3.48,0.12,8.27,0.55,9.83-2.45c1.57-3,3.72-8.95,3.51-15.62c-0.19-5.84-1.75-8.2-2.13-8.7c0.59,0.66,3.74,4.49,4.01,11.7 c0.03,0.83,0.06,1.72,0.08,2.66c4.21-0.15,5.93,1.5,6.07,2.35C40.68,33.85,39.8,38.9,39.02,42z" fill="#b71c1c"></path>
                    <path d="M35,27.17c0,3.67-0.28,11.2-0.42,14.83h-2C32.72,38.42,33,30.83,33,27.17 c0-5.54-1.46-12.65-3.55-14.02c-1.65-1.08-5.49-1.48-8.23-0.85c-3.62,0.83-4.57,1.99-6.14,3.92L15,16.32 c-1.31,1.6-2.59,6.92-3,8.96v10.8c0,2.58,0.28,4.61,0.54,5.92H10.5c-0.25-1.41-0.5-3.42-0.5-5.92l0.02-11.09 c0.15-0.77,1.55-7.63,3.43-9.94l0.08-0.09c1.65-2.03,2.96-3.63,7.25-4.61c3.28-0.76,7.67-0.25,9.77,1.13 C33.79,13.6,35,22.23,35,27.17z" fill="#212121"></path>
                    <path d="M17.165,17.283c5.217-0.055,9.391,0.283,9,6.011c-0.391,5.728-8.478,5.533-9.391,5.337 c-0.913-0.196-7.826-0.043-7.696-5.337C9.209,18,13.645,17.32,17.165,17.283z" fill="#01579b"></path>
                    <path d="M40.739,37.38c-0.28,1.99-0.69,3.53-1.22,4.62h-2.43c0.25-0.19,1.13-1.11,1.67-4.9 c0.57-4-0.23-11.79-0.93-12.78c-0.4-0.4-2.63-0.8-4.37-0.89l0.1-1.99c1.04,0.05,4.53,0.31,5.71,1.49 C40.689,24.36,41.289,33.53,40.739,37.38z" fill="#212121"></path>
                    <path d="M10.154,20.201c0.261,2.059-0.196,3.351,2.543,3.546s8.076,1.022,9.402-0.554 c1.326-1.576,1.75-4.365-0.891-5.267C19.336,17.287,12.959,16.251,10.154,20.201z" fill="#81d4fa"></path>
                    <path d="M17.615,29.677c-0.502,0-0.873-0.03-1.052-0.069c-0.086-0.019-0.236-0.035-0.434-0.06 c-5.344-0.679-8.053-2.784-8.052-6.255c0.001-2.698,1.17-7.238,8.986-7.32l0.181-0.002c3.444-0.038,6.414-0.068,8.272,1.818 c1.173,1.191,1.712,3,1.647,5.53c-0.044,1.688-0.785,3.147-2.144,4.217C22.785,29.296,19.388,29.677,17.615,29.677z M17.086,17.973 c-7.006,0.074-7.008,4.023-7.008,5.321c-0.001,3.109,3.598,3.926,6.305,4.27c0.273,0.035,0.48,0.063,0.601,0.089 c0.563,0.101,4.68,0.035,6.855-1.732c0.865-0.702,1.299-1.57,1.326-2.653c0.051-1.958-0.301-3.291-1.073-4.075 c-1.262-1.281-3.834-1.255-6.825-1.222L17.086,17.973z" fill="#212121"></path>
                    <path d="M15.078,19.043c1.957-0.326,5.122-0.529,4.435,1.304c-0.489,1.304-7.185,2.185-7.185,0.652 C12.328,19.467,15.078,19.043,15.078,19.043z" fill="#e1f5fe"></path>
                </svg>
                <span class="now">5<i class="fas fa-gem"></i></span>
                <span class="play">Refresh</span>
            </button>
            <button class="<?= empty($username) ? 'disabled' : ''; ?>" onclick="play()">
                <svg height="36px" width="36px" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <rect fill="#fdd835" y="0" x="0" height="36" width="36"></rect>
                    <path d="M38.67,42H11.52C11.27,40.62,11,38.57,11,36c0-5,0-11,0-11s1.44-7.39,3.22-9.59 c1.67-2.06,2.76-3.48,6.78-4.41c3-0.7,7.13-0.23,9,1c2.15,1.42,3.37,6.67,3.81,11.29c1.49-0.3,5.21,0.2,5.5,1.28 C40.89,30.29,39.48,38.31,38.67,42z" fill="#e53935"></path>
                    <path d="M39.02,42H11.99c-0.22-2.67-0.48-7.05-0.49-12.72c0.83,4.18,1.63,9.59,6.98,9.79 c3.48,0.12,8.27,0.55,9.83-2.45c1.57-3,3.72-8.95,3.51-15.62c-0.19-5.84-1.75-8.2-2.13-8.7c0.59,0.66,3.74,4.49,4.01,11.7 c0.03,0.83,0.06,1.72,0.08,2.66c4.21-0.15,5.93,1.5,6.07,2.35C40.68,33.85,39.8,38.9,39.02,42z" fill="#b71c1c"></path>
                    <path d="M35,27.17c0,3.67-0.28,11.2-0.42,14.83h-2C32.72,38.42,33,30.83,33,27.17 c0-5.54-1.46-12.65-3.55-14.02c-1.65-1.08-5.49-1.48-8.23-0.85c-3.62,0.83-4.57,1.99-6.14,3.92L15,16.32 c-1.31,1.6-2.59,6.92-3,8.96v10.8c0,2.58,0.28,4.61,0.54,5.92H10.5c-0.25-1.41-0.5-3.42-0.5-5.92l0.02-11.09 c0.15-0.77,1.55-7.63,3.43-9.94l0.08-0.09c1.65-2.03,2.96-3.63,7.25-4.61c3.28-0.76,7.67-0.25,9.77,1.13 C33.79,13.6,35,22.23,35,27.17z" fill="#212121"></path>
                    <path d="M17.165,17.283c5.217-0.055,9.391,0.283,9,6.011c-0.391,5.728-8.478,5.533-9.391,5.337 c-0.913-0.196-7.826-0.043-7.696-5.337C9.209,18,13.645,17.32,17.165,17.283z" fill="#01579b"></path>
                    <path d="M40.739,37.38c-0.28,1.99-0.69,3.53-1.22,4.62h-2.43c0.25-0.19,1.13-1.11,1.67-4.9 c0.57-4-0.23-11.79-0.93-12.78c-0.4-0.4-2.63-0.8-4.37-0.89l0.1-1.99c1.04,0.05,4.53,0.31,5.71,1.49 C40.689,24.36,41.289,33.53,40.739,37.38z" fill="#212121"></path>
                    <path d="M10.154,20.201c0.261,2.059-0.196,3.351,2.543,3.546s8.076,1.022,9.402-0.554 c1.326-1.576,1.75-4.365-0.891-5.267C19.336,17.287,12.959,16.251,10.154,20.201z" fill="#81d4fa"></path>
                    <path d="M17.615,29.677c-0.502,0-0.873-0.03-1.052-0.069c-0.086-0.019-0.236-0.035-0.434-0.06 c-5.344-0.679-8.053-2.784-8.052-6.255c0.001-2.698,1.17-7.238,8.986-7.32l0.181-0.002c3.444-0.038,6.414-0.068,8.272,1.818 c1.173,1.191,1.712,3,1.647,5.53c-0.044,1.688-0.785,3.147-2.144,4.217C22.785,29.296,19.388,29.677,17.615,29.677z M17.086,17.973 c-7.006,0.074-7.008,4.023-7.008,5.321c-0.001,3.109,3.598,3.926,6.305,4.27c0.273,0.035,0.48,0.063,0.601,0.089 c0.563,0.101,4.68,0.035,6.855-1.732c0.865-0.702,1.299-1.57,1.326-2.653c0.051-1.958-0.301-3.291-1.073-4.075 c-1.262-1.281-3.834-1.255-6.825-1.222L17.086,17.973z" fill="#212121"></path>
                    <path d="M15.078,19.043c1.957-0.326,5.122-0.529,4.435,1.304c-0.489,1.304-7.185,2.185-7.185,0.652 C12.328,19.467,15.078,19.043,15.078,19.043z" fill="#e1f5fe"></path>
                </svg>
                <span class="now"><span id="money_payment"></span><i class="fas fa-gem"></i></span>
                <span class="play">play</span>
            </button>
        </div>

        <input type="text" id="value_gift_item" style="display: none;">
        <div class="display_gift animate__animated animate__tada" style="display: none;" id="display_gift">
            <div class="button-wrapper">
                <button id="fireworks" class="confetti-button" style="display: none;"></button>
            </div>
            <div class="img_gift">
                <img src="" alt="" id="display_img_gift">
            </div>
            <div class="display_gift__content">
                <h4 id="status_notification"></h4>
                <span id="name_gift"></span>
            </div>
            <div class="btn_game">
                <button id="btn_ok">Ok</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- START JS : Process MINI GAME -->
<script>
    var loading = document.getElementById("loading");
    const value_gift_item_temp = document.getElementById('value_gift_item');
    const display_gift = document.getElementById('display_gift');
    const btn_ok = document.getElementById('btn_ok');
    const display_img_gift = document.getElementById('display_img_gift');
    const name_gift = document.getElementById('name_gift');
    const status_notification = document.getElementById('status_notification');
    const fireworks = document.getElementById('fireworks');
    const free_opens = document.getElementById('free_opens');
    var giftContainer = document.getElementById('gift-container');
    var list_item_mini_game = document.getElementById('list_item_mini_game');
    var money_payment = document.getElementById("money_payment");
    var money_payment_value = 10;
    money_payment.innerHTML = money_payment_value;
    var lastest_login = "<?= $lastest_login ?>";
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    var currentDay = currentDate.getDate().toString().padStart(2, '0');
    var formattedDate = currentYear + '-' + currentMonth + '-' + currentDay;
    var user_id = <?= $data_user_id ?>;
    var count = 0;
    var gifts = [];
    var user_money = <?= $gcoin ?>;
    var number_spins = <?= $number_spins ?>;
    var number_spins_temp = number_spins;
    var free_open = 0;
    if (lastest_login == formattedDate && number_spins_temp == 0) {
        free_open = 1;
    }
    free_opens.innerHTML = free_open;
    var percent_goodluck = 0.5;
    var percent_goodluck_temp = percent_goodluck;
    var percent_goodluck_gift;
    var goodluck_temp_decrease;
    var percent_voucher;
    var percent_product;
    var isRequestPending = false;
    document.addEventListener('DOMContentLoaded', function() {
        percent(number_spins_temp);
        push_lucky_gift();
        random_product();
        random_voucher();
        updateGiftContainer();
        update_list_gift_minigame();
    });


    function percent(number_spins_temp) {
        percent_goodluck_temp = percent_goodluck_temp - (number_spins_temp / 30);
        goodluck_temp_decrease = (number_spins_temp / 30);
        percent_voucher = (1 - percent_goodluck_temp) * 0.6;
        percent_product = (1 - percent_goodluck_temp) * 0.4;
        percent_goodluck_gift = percent_goodluck_temp / 2;
    }

    function random_product() {
        var randomProducts = [];
        while (randomProducts.length < 2 && randomProducts.length < productsData.length) {
            var randomIndex = Math.floor(Math.random() * productsData.length);
            var randomProduct = productsData[randomIndex];
            if (!randomProducts.includes(randomProduct)) {
                randomProducts.push(randomProduct);
            }
        }

        var ratio1, ratio2;
        if (randomProducts.length === 2) {
            var product1 = randomProducts[0];
            var product2 = randomProducts[1];
            if (product1.price > product2.price) {
                ratio1 = percent_product * 0.4;
                ratio2 = percent_product * 0.6;
            } else {
                ratio2 = percent_product * 0.4;
                ratio1 = percent_product * 0.6;
            }
        } else if (randomProducts.length === 1) {
            var product = randomProducts[0];
            ratio1 = percent_product;
        }


        for (var i = 0; i < randomProducts.length; i++) {
            var product = randomProducts[i];
            var gift_product = {
                id: product.id,
                type: 'game',
                name: product.name,
                img: product.img,
                status: "Congratulation",
                ratio: i === 0 ? ratio1 : ratio2
            };
            gifts.push(gift_product);
        }
    }

    function random_voucher() {
        var randomVouchers = [];
        while (randomVouchers.length < 2 && randomVouchers.length < vouchersData.length) {
            var randomIndex = Math.floor(Math.random() * vouchersData.length);
            var randomVoucher = vouchersData[randomIndex];
            if (!randomVouchers.includes(randomVoucher)) {
                randomVouchers.push(randomVoucher);
            }
        }

        var ratio1, ratio2;
        if (randomVouchers.length === 2) {
            var voucher1 = randomVouchers[0];
            var voucher2 = randomVouchers[1];
            if (voucher1.value > voucher2.value) {
                ratio1 = percent_voucher * 0.4;
                ratio2 = percent_voucher * 0.6;
            } else {
                ratio2 = percent_voucher * 0.4;
                ratio1 = percent_voucher * 0.6;
            }
        } else if (randomVouchers.length === 1) {
            var voucher = randomVouchers[0];
            ratio1 = percent_voucher;
        }

        for (var i = 0; i < randomVouchers.length; i++) {
            var voucher = randomVouchers[i];
            var gift_voucher = {
                id: voucher.id,
                type: 'voucher',
                name: voucher.name,
                img: voucher.img,
                status: "Congratulation",
                ratio: i === 0 ? ratio1 : ratio2
            };
            gifts.push(gift_voucher);
        }
    }

    function push_lucky_gift() {
        var lucky_gift1 = {
            id: "1",
            name: "",
            img: "sad_icon.png",
            status: "Better luck next time",
            ratio: percent_goodluck_gift
        };

        var lucky_gift2 = {
            id: "2",
            name: "",
            img: "sad_icon.png",
            status: "Better luck next time",
            ratio: percent_goodluck_gift
        };

        gifts.push(lucky_gift1);
        gifts.push(lucky_gift2);
    }

    function refresh_list_gift() {
        if (user_money >= 5) {
            user_money -= 5;
            $.post('./pages/process_mini_game.php', {
                user_id: user_id,
                user_money: user_money
            });
            loading.style.display = 'block';
            setTimeout(function() {
                loading.style.display = 'none';
            }, 1000);
            gifts.splice(0, gifts.length);
            push_lucky_gift();
            random_product();
            random_voucher();
            gifts.sort(randomGifts);
            giftContainer.innerHTML = '';
            list_item_mini_game.innerHTML = '';
            updateGiftContainer();
            update_list_gift_minigame();
        } else {
            display_img_gift.src = "../Galaxy_Game_Store/uploads/sad_icon.png";
            status_notification.innerHTML = "You don't have enough G-Coins. Please recharge.";
            display_gift.style.display = 'flex';
        }
    }

    function chooseGift() {
        var randomNumber = Math.random();
        var cumulativeRatio = 0;
        for (var i = 0; i < gifts.length; i++) {
            cumulativeRatio += gifts[i].ratio;
            if (randomNumber <= cumulativeRatio) {
                return gifts[i];
            }
        }
    }

    function startAnimation(position, count, number_loop) {
        var numbers = document.getElementsByClassName("item_game_img");
        var delay = 0;
        if (count < numbers.length * number_loop) {
            var loopCount = Math.floor(count / numbers.length);
            var index = count % numbers.length;
            var currentNumber = numbers[index];
            var found = false;

            if (loopCount === number_loop - 1 && index === position) {
                numbers[index].classList.add("animation_gift");
                setTimeout(function() {
                    numbers[index].classList.remove("animation_gift");
                    numbers[index].classList.add("animation_chose_gift");
                }, 110);
                found = true;
            } else {
                numbers[index].classList.add("animation_gift");
                setTimeout(function() {
                    numbers[index].classList.remove("animation_gift");
                }, 50);
            }
            count++;
            if (!found) {
                setTimeout(function() {
                    startAnimation(position, count, number_loop);
                }, 50);
            }
        }
    }

    function open_gift(position, id_gift, type, img, name, status, user_money, number_spins_temp) {
        var id_img = 'item_game_img_' + position;
        const item_game_img = document.getElementById(id_img);
        item_game_img.classList.add('animation_open');
        setTimeout(function() {
            item_game_img.classList.remove('animation_open');
        }, 1550);
        display_img_gift.src = "../Galaxy_Game_Store/uploads/" + img;
        name_gift.innerHTML = name;
        status_notification.innerHTML = status;
        setTimeout(function() {
            if (status == 'Congratulation') {
                fireworks.click();
                $.post('./pages/process_mini_game.php', {
                    user_id: user_id,
                    product_id: id_gift,
                    type: type,
                    user_money: user_money,
                    number_spins: number_spins_temp
                });
                if (free_open == 0) {
                    money_payment_value += 2;
                    money_payment.innerHTML = money_payment_value;
                }

            } else {
                $.post('./pages/process_mini_game.php', {
                    user_id: user_id,
                    user_money: user_money,
                    number_spins: number_spins_temp
                }, function(data) {
                    $('#asd').html(data);
                });
                if (free_open == 0) {
                    money_payment_value += 1;
                    money_payment.innerHTML = money_payment_value;
                }
            }
            display_gift.style.display = 'flex';
        }, 1550);
    }

    function randomGifts() {
        return Math.random() - 0.5;
    }

    function updateAllGiftRatios(percent_goodluck_temp) {
        for (var i = 0; i < gifts.length; i++) {
            if (gifts[i].status === 'Better luck next time') {
                gifts[i].ratio = percent_goodluck_temp / 2;

            } else if (gifts[i].type == 'game') {
                var decreaseRatio = (goodluck_temp_decrease * 0.4) / 2;
                gifts[i].ratio += decreaseRatio;
            } else if (gifts[i].type == 'voucher') {
                var decreaseRatio = (goodluck_temp_decrease * 0.6) / 2;
                gifts[i].ratio += decreaseRatio;
            }
        }
    }

    function play() {
        if (free_open == 1 || user_money >= money_payment_value) {
            if (free_open == 0) {
                user_money -= money_payment_value;
            }
            var selected_gift = chooseGift();
            var index_gift = gifts.findIndex(function(element) {
                return element.id === selected_gift.id;
            });
            startAnimation(index_gift, 0, 4);
            setTimeout(function() {
                open_gift(index_gift, selected_gift.id, selected_gift.type, selected_gift.img, selected_gift.name, selected_gift.status, user_money, number_spins_temp);
            }, 2800);
            number_spins_temp++;
            if(percent_goodluck_temp > 0.4){
            percent(number_spins_temp);
            }

        } else {
            display_img_gift.src = "../Galaxy_Game_Store/uploads/sad_icon.png";
            status_notification.innerHTML = "You don't have enough G-Coins. Please recharge.";
            display_gift.style.display = 'flex';
        }
    }

    btn_ok.onclick = () => {
        display_gift.style.display = 'none';
        display_img_gift.src = "";
        name_gift.innerHTML = "";
        status_notification.innerHTML = "";
        updateAllGiftRatios(percent_goodluck_temp);
        gifts.sort(randomGifts);
        giftContainer.innerHTML = '';
        list_item_mini_game.innerHTML = '';
        updateGiftContainer();
        update_list_gift_minigame();
        free_open = 0;
        free_opens.innerHTML = free_open;
    }

</script>
<!-- END JS : Process MINI GAME -->
<!-- ========================= -->
<!-- START JS : Add data List Gift -->
<script>
    function updateGiftContainer() {
        gifts.forEach(function(item) {
            var giftItem = document.createElement('div');
            giftItem.classList.add('gift__item');
            var roundedNumber = item.ratio.toPrecision(2);
            var roundedNumber = parseFloat(roundedNumber);
            var img = document.createElement('img');
            img.src = '../Galaxy_Game_Store/uploads/' + item.img;

            var percent = document.createElement('div');
            percent.classList.add('percent');
            percent.textContent = (roundedNumber * 100) + '%';

            giftItem.appendChild(img);
            giftItem.appendChild(percent);
            giftContainer.appendChild(giftItem);
        });
    }
</script>
<!-- END JS : Add data List Gift -->
<!-- START JS : Add data List Gift MINI GAME -->
<script>
    function update_list_gift_minigame() {
        var item_game_img_number = 0;
        gifts.forEach(function(item) {
            var item_game = document.createElement('div');
            item_game.classList.add('item_game');
            item_game.setAttribute("id", item.ratio);

            var item_game_img = document.createElement('div');
            item_game_img.classList.add('item_game_img');
            item_game_img.setAttribute("id", "item_game_img_" + item_game_img_number);

            item_game.appendChild(item_game_img);
            list_item_mini_game.appendChild(item_game);
            item_game_img_number++;
        });
    }
</script>
<!-- END JS : Add data List Gift MINI GAME-->
<!-- ========================= -->
<!--START JS: Firework -->
<script>
    document.getElementsByClassName("confetti-button")[0].addEventListener("click", () => {
        let canvas = document.createElement("canvas");
        canvas.width = 600;
        canvas.height = 600;
        let container = document.getElementsByClassName("button-wrapper")[0];
        container.appendChild(canvas);

        let confetti_button = confetti.create(canvas);
        confetti_button({
            particleCount: 200,
            spread: 200,
            startVelocity: 15,
            scalar: 0.9,
            ticks: 200
        }).then(() => container.removeChild(canvas));
    });
</script>
<!--END JS: Firework -->
<!-- ========================= -->
<!-- START JS: Open Help MINIGAME -->
<script>
    // Lấy phần tử icon_help và introduction_box
    const iconHelp = document.querySelector('.icon_help');
    const introductionBox = document.querySelector('.introduction_box');
    const icon_gift = document.querySelector('.icon_gift');
    const introduction__list_gift = document.querySelector('.introduction__list_gift');

    // Gắn sự kiện hover vào icon_help
    iconHelp.addEventListener('mouseover', () => {
        introductionBox.style.display = 'block';
    });

    // Gắn sự kiện hover ra khỏi icon_help
    iconHelp.addEventListener('mouseout', () => {
        introductionBox.style.display = 'none';
    });
    // Gắn sự kiện hover vào icon_help
    icon_gift.addEventListener('mouseover', () => {
        introduction__list_gift.style.display = 'block';
    });

    // Gắn sự kiện hover ra khỏi icon_help
    icon_gift.addEventListener('mouseout', () => {
        introduction__list_gift.style.display = 'none';
    });
</script>
<!-- END JS: Open Help MINIGAME -->