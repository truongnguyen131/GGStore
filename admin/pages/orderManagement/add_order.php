<?php
include("../add_template.php");

function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";
    $user_id = isset($_POST["slUser"]) ? $_POST["slUser"] : "";
    $order_date = isset($_POST["order_date"]) ? $_POST["order_date"] : "";
    $address = isset($_POST["txtAddress"]) ? $_POST["txtAddress"] : "";
    $status = isset($_POST["status"]) ? $_POST["status"] : "";
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $manufacturer_id = isset($_POST["sl_manufacturer"]) ? $_POST["sl_manufacturer"] : "";
    $checkboxes = isset($_POST["cb_select_product"]) ? $_POST["cb_select_product"] : "";
    $quantity_product = isset($_POST["quantity_product"]) ? $_POST["quantity_product"] : "";
    ?>

    <style>
        .table-responsive {
            max-height: 250px;
        }
    </style>


    <div class="container-fluid">
        <form name="frmAddUser" method="post">
            <h4 class="ico_mug">
                ADD NEW ORDERS
            </h4>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <label>Manufacturer
                                        <select name="sl_manufacturer" onchange="submit()" id="sl_manufacturer"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="all">All</option>
                                            <?php
                                            $sql = "SELECT * FROM users WHERE role = 'manufacturer'";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    if (isset($_POST['sl_manufacturer']) && $_POST['sl_manufacturer'] != "all") { ?>
                                                        <option value="<?php echo $row["id"]; ?>" selected>
                                                            <?php echo $row["full_name"]; ?>
                                                        </option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row["id"]; ?>">
                                                            <?php echo $row["full_name"]; ?>
                                                        </option>
                                                    <?php }
                                                }
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6" style=" text-align: right;">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <label>Search
                                        <div class="input-group">
                                            <input type="search" name="search" value="<?php echo $search; ?>" id="search"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable">
                                            <input type="button" onclick="submit()" value="Find">
                                        </div>

                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0"
                                    role="grid" aria-describedby="dataTable_info" style="width: 100%;">

                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="ID: activate to sort column descending" style="width: 70px;">
                                                ID
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Product name: activate to sort column ascending"
                                                style="width: 147px;">
                                                Product name</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Product name: activate to sort column ascending"
                                                style="width: 147px;">
                                                Manufacturer</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Price old: activate to sort column ascending"
                                                style="width: 100px;">
                                                Price
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Quantity: activate to sort column ascending"
                                                style="width: 100px;">
                                                Quantity</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Age: activate to sort column ascending"
                                                style="width: 90px;">
                                                Select product</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $query = "SELECT p.*,u.full_name FROM products p,users u
                                        WHERE p.id_manufacturer = u.id AND p.product_name LIKE '%$search%'";

                                        if ($manufacturer_id != "" && $manufacturer_id != "all") {
                                            $query .= " AND p.id_manufacturer = $manufacturer_id";
                                        }

                                        $result = $conn->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['product_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['full_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $id_p = $row['id'];
                                                        $sql = "SELECT discount_amount FROM discounts WHERE product_id = $id_p AND start_date <= CURDATE() AND end_date >= CURDATE()";
                                                        $result1 = $conn->query($sql);

                                                        if ($result1->num_rows > 0) {
                                                            $discountAmount = $result1->fetch_assoc()['discount_amount'];
                                                            $price_new = $row['price'] - ($row['price'] * $discountAmount) / 100;
                                                            echo $price_new;
                                                        } else {
                                                            echo $row['price'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td id="quantity_product_<?= $row['id']; ?>">
                                                        <input style="border: #dddfeb solid 1px;width: 40px;color: #858796;"
                                                            type="number" name="quantity_product[<?= $row['id']; ?>]" readonly
                                                            value="<?php echo (isset($quantity_product[$row['id']]) && $quantity_product[$row['id']] != "") ? $quantity_product[$row['id']] : 0; ?>">
                                                    </td>

                                                    <td style="text-align: center;">
                                                        <input type="checkbox" onclick="handleCheckboxChange(<?= $row['id']; ?>)"
                                                            value="<?= $row['id']; ?>" name="cb_select_product[]"
                                                            style="transform: scale(1.5);" <?php if (isset($quantity_product[$row['id']]) && $quantity_product[$row['id']] != 0)
                                                                echo "checked"; ?>>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            echo '<td style="border: none;">No results found.</td>';
                                        }
                                        ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <br>

                <!-- Total amount,Customer,Order date, Status -->
                <div class="row">
                    <!-- Total amount -->
                    <div class="col-sm-12 col-md-3">
                        <tr>
                            <td>
                                Total amount
                            </td>
                            <td>
                                <input class="form-control input-left" name="total_amount" type="text" readonly value="<?php

                                if (!empty($quantity_product)) {
                                    $check = false;
                                    foreach ($quantity_product as $key => $quantity) {
                                        if ($quantity >= 1) {
                                            $check = true;
                                            break;
                                        }

                                    }
                                    if ($check) {
                                        $sql_total_amount = "SELECT SUM(total_price) AS total_price FROM ( ";
                                        for ($i = 0; $i < count($checkboxes); $i++) {
                                            $product = $checkboxes[$i];
                                            $quantity = $quantity_product[$product];

                                            $sql_discount_product = "SELECT d.discount_amount, p.price FROM discounts d, products p WHERE d.product_id = p.id AND product_id = $product AND start_date <= CURDATE() AND end_date >= CURDATE()";
                                            $result_discount_product = $conn->query($sql_discount_product);

                                            if ($result_discount_product->num_rows > 0) {
                                                $row = $result_discount_product->fetch_assoc();
                                                $discountAmount = $row['discount_amount'];
                                                $price = $row['price'];
                                                $price_new = $price - ($price * $discountAmount) / 100;
                                                if ($i != 0) {
                                                    $sql_total_amount .= "UNION ALL ";
                                                }
                                                $sql_total_amount .= "SELECT SUM($price_new * $quantity) AS total_price ";
                                            } else {
                                                $sql_product = "SELECT price FROM products WHERE id = $product";
                                                $result_product = $conn->query($sql_product);
                                                $row = $result_product->fetch_assoc();
                                                $price = $row['price'];
                                                if ($i != 0) {
                                                    $sql_total_amount .= "UNION ALL ";
                                                }
                                                $sql_total_amount .= "SELECT SUM($price * $quantity) AS total_price ";
                                            }
                                        }
                                        $sql_total_amount .= ") AS subquery;";
                                        $result_total_amount = $conn->query($sql_total_amount);
                                        echo $result_total_amount->fetch_assoc()['total_price'];
                                    } else {
                                        echo "0";
                                    }
                                } else {
                                    echo "0";
                                }
                                ?>">
                            </td>
                        </tr>
                    </div>
                    <!-- Customer -->
                    <div class="col-sm-12 col-md-3">
                        <tr>
                            <td>
                                Customer
                            </td>
                            <td>
                                <select class="form-control input-left" name="slUser">
                                    <?php
                                    $sql = "SELECT * FROM users WHERE role = 'user'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <option <?php echo ($row["id"] == $user_id) ? 'selected' : ''; ?>
                                                value="<?php echo $row["id"]; ?>">
                                                <?php echo $row["full_name"]; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </div>
                    <!-- Order date -->
                    <div class="col-sm-12 col-md-3">
                        <tr>
                            <td>
                                Order date
                            </td>
                            <td>
                                <input type="datetime-local" class="form-control input-left" name="order_date"
                                    value="<?= $order_date; ?>">
                            </td>
                        </tr>
                    </div>
                    <!-- Status -->
                    <div class="col-sm-12 col-md-3">
                        <tr>
                            <td>
                                Status
                            </td>
                            <td>
                                <select class="form-control input-left" name="status">
                                    <option value="Waiting for confirmation" <?php echo (($status) == "Waiting for confirmation") ? 'selected' : ''; ?>>Waiting for confirmation</option>
                                    <option value="Waiting for delivery" <?php echo (($status) == "Waiting for delivery") ? 'selected' : ''; ?>>Waiting for delivery</option>
                                    <option value="Paid" <?php echo (($status) == "Paid") ? 'selected' : ''; ?>>
                                        Paid</option>
                                    <option value="Cancelled" <?php echo (($status) == "Cancelled") ? 'selected' : ''; ?>>
                                        Cancelled</option>
                                </select>
                            </td>
                        </tr>
                    </div>
                </div>
                <!-- Address -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        Address

                        <input type="text" class="form-control input-left" placeholder="" name="txtAddress"
                            value="<?= $address ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control input-left" name="province" id="province">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="district" id="district">
                                    <option value="">--District--</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="ward" id="ward">
                                    <option value="">--Ward--</option>
                                </select>
                            </div>
                        </div>

                        <p id="errorAddress" class="error"></p>
                    </div>


                </div>

                <div id="error" style="color: red;"></div>
                <input type="button" class="btn btn-info input-left mt-2" name="btnSave" value="Save" onClick="save();">
                <input type="button" class="btn btn-secondary mt-2" name="btnBack" value="Back" onClick="goback()">
            </div>

        </form>
    </div>

    <div id="getProcessAdd"></div>

    <!-- save -->
    <script>
        function save() {
            document.getElementById("error").innerText = "";
            var checkboxes = document.getElementsByName("cb_select_product[]");
            var quantities = [];
            var products = [];
            var user_id = document.frmAddUser.slUser.value;
            var total_amount = document.frmAddUser.total_amount.value;
            var order_date = document.frmAddUser.order_date.value;
            var status = document.frmAddUser.status.value;
            var txtAddress = document.frmAddUser.txtAddress.value;
            var province = document.frmAddUser.province.value;
            var district = document.frmAddUser.district.value;
            var ward = document.frmAddUser.ward.value;
            var checkedCount = 0;

            checkboxes.forEach(element => {
                var td = document.getElementById("quantity_product_" + element.value + "");
                var hasButton = td.querySelector("button") == null;
                if (hasButton) {
                    if (element.checked) {
                        var quantity = document.getElementsByName("quantity_product[" + element.value + "]");
                        products.push(element.value);
                        quantities.push(quantity[0].value);
                        checkedCount = 1;
                    }
                }
            });

            if (checkedCount === 0) {
                document.getElementById("error").innerText = "Select Product!!!";
                return;
            }

            if (order_date === "") {
                document.getElementById("error").innerText = "Select Order Date!!!";
                return;
            }

            if (txtAddress.trim() == "" || province == "" || district == "" || ward == "") {
                document.getElementById("error").innerText = "Let's enter address!!!";
                return;
            } else {

                var provinceSelect = document.getElementById("province");
                for (var i = 0; i < provinceSelect.options.length; i++) {
                    var option = provinceSelect.options[i];

                    if (option.selected) {
                        var province = option.innerHTML;
                        break;
                    }
                }

                var districtSelect = document.getElementById("district");
                for (var i = 0; i < districtSelect.options.length; i++) {
                    var option = districtSelect.options[i];

                    if (option.selected) {
                        var district = option.innerHTML;
                        break;
                    }
                }

                var wardSelect = document.getElementById("ward");
                for (var i = 0; i < wardSelect.options.length; i++) {
                    var option = wardSelect.options[i];

                    if (option.selected) {
                        var ward = option.innerHTML;
                        break;
                    }
                }

                var address = txtAddress.trim() + " " + ward + " " + district + " " + province;
            }

            $.post('add_order_process.php', {
                customer_id: user_id,
                order_date: order_date,
                total_amount: total_amount,
                address: address,
                status: status,
                products: products,
                quantities: quantities
            }, function (data) {
                $('#getProcessAdd').html(data);
            })

        }
    </script>

    <!-- select product -->
    <script>
        const previousCheckboxStates = {};

        function handleCheckboxChange(id) {
            const checkbox = event.target;
            const currentState = checkbox.checked;

            if (currentState && !previousCheckboxStates[id]) {
                update(id);
            } else {
                var genreName = "quantity_product_" + id;
                var genreName_id = document.getElementById(genreName);
                genreName_id.innerHTML = "";
                const updatedInput = document.createElement("input");
                updatedInput.style.border = "#dddfeb solid 1px";
                updatedInput.style.width = "40px";
                updatedInput.type = "number";
                updatedInput.style.color = "#858796";
                updatedInput.name = "quantity_product[" + id + "]";
                updatedInput.readOnly = true;
                updatedInput.value = 0;
                genreName_id.appendChild(updatedInput);
                document.frmAddUser.submit();
            }

            previousCheckboxStates[id] = currentState;
        }

        function update(id) {
            var genreName = "quantity_product_" + id;
            var genreName_id = document.getElementById(genreName);
            var currentGenre = genreName_id.innerText;

            var input = document.createElement("input");
            input.type = "number";
            input.min = 1;
            input.value = 1;
            input.style.border = "#dddfeb solid 1px";
            input.style.color = "#858796";
            input.style.width = "40px";
            input.name = "quantity_product[" + id + "]";
            genreName_id.innerHTML = "";
            genreName_id.appendChild(input);

            var saveButton = document.createElement("button");
            saveButton.innerHTML = "Add";
            saveButton.style.backgroundColor = "white";
            saveButton.style.color = "#4e73df";
            saveButton.style.border = "#dddfeb solid 1px";
            saveButton.style.borderRadius = "10%";

            saveButton.onclick = function () {
                genreName_id.innerHTML = "";
                const updatedInput = document.createElement("input");
                updatedInput.style.border = "#dddfeb solid 1px";
                updatedInput.style.width = "40px";
                updatedInput.type = "number";
                updatedInput.style.color = "#858796";
                updatedInput.name = "quantity_product[" + id + "]";
                updatedInput.readOnly = true;

                if (input.value < 100 && input.value > 0 && Number.isInteger(Number(input.value))) {
                    updatedInput.value = input.value;
                } else {
                    updatedInput.value = 0;
                }

                genreName_id.appendChild(updatedInput);
                document.frmAddUser.submit();
            };

            genreName_id.appendChild(saveButton);
        }
    </script>

    <!-- go back -->
    <script>
        function goback() {
            location.href = "orders_management.php";
        }
    </script>
    <?php
}

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
    integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    const host = "https://provinces.open-api.vn/api/";
    var callAPI = (api) => {
        return axios.get(api)
            .then((response) => {
                renderData(response.data, "province");
            });
    }
    callAPI('https://provinces.open-api.vn/api/?depth=1');
    var callApiDistrict = (api) => {
        return axios.get(api)
            .then((response) => {
                renderData(response.data.districts, "district");
            });
    }
    var callApiWard = (api) => {
        return axios.get(api)
            .then((response) => {
                renderData(response.data.wards, "ward");
            });
    }

    var renderData = (array, select) => {
        let row = ' <option disable value="">--Province--</option>';
        array.forEach(element => {
            row += `<option value="${element.code}">${element.name}</option>`
        });
        document.querySelector("#" + select).innerHTML = row
    }

    $("#province").change(() => {
        callApiDistrict(host + "p/" + $("#province").val() + "?depth=2");
    });
    $("#district").change(() => {
        callApiWard(host + "d/" + $("#district").val() + "?depth=2");
    });
</script>