<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Create a new order</title>
    <!-- Bootstrap core CSS -->
    <link href="./assets/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="./assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="./assets/jquery-1.11.3.min.js"></script>

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
    }

    .formbold-main-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .formbold-form-wrapper {
        margin: 0 auto;
        max-width: 570px;
        width: 100%;
        background: white;
    }

    .formbold-img {
        margin-bottom: 40px;
    }

    .formbold-input-flex {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .formbold-input-flex>div {
        width: 50%;
    }

    .formbold-form-input {
        width: 100%;
        padding: 13px 22px;
        border-radius: 5px;
        border: 1px solid #dde3ec;
        background: #ffffff;
        font-weight: 500;
        font-size: 16px;
        color: #536387;
        outline: none;
        resize: none;
    }

    .formbold-form-input:focus {
        border-color: #6a64f1;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
    }

    .formbold-form-label {
        color: #536387;
        font-weight: 500;
        font-size: 14px;
        line-height: 24px;
        display: block;
        margin-bottom: 10px;
    }

    .formbold-mb-5 {
        margin-bottom: 20px;
    }

    .formbold-radio-flex {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .formbold-radio-label {
        font-size: 14px;
        line-height: 24px;
        color: #07074d;
        position: relative;
        padding-left: 25px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .formbold-input-radio {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .formbold-radio-checkmark {
        position: absolute;
        top: -1px;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #ffffff;
        border: 1px solid #dde3ec;
        border-radius: 50%;
    }

    .formbold-radio-label .formbold-input-radio:checked~.formbold-radio-checkmark {
        background-color: #6a64f1;
    }

    .formbold-radio-checkmark:after {
        content: '';
        position: absolute;
        display: none;
    }

    .formbold-radio-label .formbold-input-radio:checked~.formbold-radio-checkmark:after {
        display: block;
    }

    .formbold-radio-label .formbold-radio-checkmark:after {
        top: 50%;
        left: 50%;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ffffff;
        transform: translate(-50%, -50%);
    }

    .formbold-btn {
        text-align: center;
        width: 100%;
        font-size: 16px;
        border-radius: 5px;
        padding: 14px 25px;
        border: none;
        font-weight: 500;
        background-color: #6a64f1;
        color: white;
        cursor: pointer;
        margin-top: 25px;
    }

    .formbold-btn:hover {
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
    }
</style>

<body>
    <?php require_once("./config.php"); ?>


    <div class="formbold-main-wrapper">
        <!-- Author: FormBold Team -->
        <!-- Learn More: https://formbold.com -->
        <div class="formbold-form-wrapper">
            <h1 style="text-align: center;">Create a new order</h1><br>
            <form action="./vnpay_create_payment.php" id="frmCreateOrder" method="post">

                <div class="form-group">
                    <label for="amount" class="formbold-form-label">Amount</label>
                    <input class="form-control" style="width: 100%;" data-val="true"
                        data-val-number="The field Amount must be a number."
                        data-val-required="The Amount field is required." id="amount" max="100000000" min="1"
                        name="amount" id="amount" type="number" readonly />
                </div>
                <script>
                    let selectedPrice = sessionStorage.getItem('price');
                    document.getElementById("amount").value = selectedPrice;
                    sessionStorage.removeItem('price');
                </script>

                <h3 style="margin-top: 30px; margin-bottom: 20px;">Choose a payment method</h3>

                <div class="formbold-mb-5">
                    <label for="qusOne" class="formbold-form-label">
                        Option 1: Redirect to VNPAY Gateway to select payment method
                    </label>

                    <div class="formbold-radio-flex">
                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input type="radio" class="formbold-input-radio" Checked="True" id="bankCode"
                                    name="bankCode" value="">
                                VNPAYQR payment gateway
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="formbold-mb-5">
                    <label for="qusTwo" class="formbold-form-label">
                        Option 2: Split the method at the connection unit's site
                    </label>

                    <div class="formbold-radio-flex">
                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input class="formbold-input-radio" type="radio" id="bankCode" name="bankCode"
                                    value="VNPAYQR">
                                Payment by VNPAYQR support application
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>

                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input class="formbold-input-radio" type="radio" id="bankCode" name="bankCode"
                                    value="VNBANK">
                                Payment via ATM/Local Account
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>

                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input class="formbold-input-radio" type="radio" id="bankCode" name="bankCode"
                                    value="INTCARD">
                                Payment via international cards
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="qusThree" class="formbold-form-label">
                        Select payment interface language:
                    </label>

                    <div class="formbold-radio-flex">
                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input class="formbold-input-radio" type="radio" id="language" Checked="True"
                                    name="language" value="vn">
                                Vietnamese
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>

                        <div class="formbold-radio-group">
                            <label class="formbold-radio-label">
                                <input class="formbold-input-radio" type="radio" id="language" name="language"
                                    value="en">
                                English
                                <span class="formbold-radio-checkmark"></span>
                            </label>
                        </div>



                    </div>
                </div>
                <style>
                    .submit-btn,
                    .back-btn {
                        padding: 10px 20px;
                        border: none;
                        border-radius: 4px;
                    }

                    .submit-btn {
                        margin-top: 20px;
                        background-color: #6a64f1;
                        color: #fff;
                    }

                    .back-btn {
                        background-color: #dde3ec;
                        color: #6a64f1;
                    }

                    .submit-btn:hover,
                    .back-btn:hover {
                        opacity: 0.8;
                    }

                    .back-btn {
                        margin-left: 10px;
                    }
                </style>
                <button type="submit" class="submit-btn">Abate</button>
                <button type="button" class="back-btn" onclick="back()">Back</button>
            </form>
        </div>
    </div>


    <script>
        function back() {
            window.location.href = "../recharge";
        }
    </script>


</body>

</html>