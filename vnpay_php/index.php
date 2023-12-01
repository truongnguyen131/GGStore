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
    <div class="container">
        <div class="header clearfix">

            <h3 class="text-muted">VNPAY TRANSACTION</h3>
        </div>
        <div class="form-group">
            <button onclick="pay()">Payment transactions</button><br>
        </div>
        <div class="form-group">
            <button onclick="querydr()">API that queries payment results</button><br>
        </div>
        <div class="form-group">
            <button onclick="refund()">Transaction Rebate API</button><br>
        </div>
        <div class="form-group">
            <button onclick="back()">Back</button><br>
        </div>

    </div>
    <script>
        function pay() {
            window.location.href = "./vnpay_pay.php";
        }
        function querydr() {
            window.location.href = "./vnpay_querydr.php";
        }
        function refund() {
            window.location.href = "./vnpay_refund.php";
        }
        function back() {
            window.location.href = "../recharge";
        }
    </script>
</body>

</html>