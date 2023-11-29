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

<body>
    <?php require_once("./config.php"); ?>

    <div class="container">
        <h3>Create a new order</h3>
        <div class="table-responsive">
            <form action="./vnpay_create_payment.php" id="frmCreateOrder" method="post">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input class="form-control" data-val="true" data-val-number="The field Amount must be a number."
                        data-val-required="The Amount field is required." id="amount" max="100000000" min="1"
                        name="amount" id="amount" type="number" readonly />
                </div>
                <script>
                    let selectedPrice = sessionStorage.getItem('price');
                    document.getElementById("amount").value = selectedPrice;
                    sessionStorage.removeItem('price');
                </script>
                <h4>Choose a payment method</h4>
                <div class="form-group">
                    <h5>Option 1: Redirect to VNPAY Gateway to select payment method</h5>
                    <input type="radio" Checked="True" id="bankCode" name="bankCode" value="">
                    <label for="bankCode">VNPAYQR payment gateway</label><br>

                    <h5>Method 2: Split the method at the connection unit's site</h5>
                    <input type="radio" id="bankCode" name="bankCode" value="VNPAYQR">
                    <label for="bankCode">Payment by VNPAYQR support application</label><br>

                    <input type="radio" id="bankCode" name="bankCode" value="VNBANK">
                    <label for="bankCode">Payment via ATM/Local Account</label><br>

                    <input type="radio" id="bankCode" name="bankCode" value="INTCARD">
                    <label for="bankCode">Payment via international cards</label><br>

                </div>
                <div class="form-group">
                    <h5>Select payment interface language:</h5>
                    <input type="radio" id="language" Checked="True" name="language" value="vn">
                    <label for="language">Vietnamese</label><br>
                    <input type="radio" id="language" name="language" value="en">
                    <label for="language">English</label><br>

                </div>
                <button type="submit" class="btn btn-default">Abate</button>
                <button type="button" onclick="back()" class="btn btn-default">Back</button>
            </form>

            <script>
                function back() {
                    window.location.href = "../recharge";
                }
            </script>
        </div>

    </div>
</body>

</html>