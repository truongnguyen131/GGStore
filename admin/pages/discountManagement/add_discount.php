<?php
include("../add_template.php");

function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $genre_id = isset($_POST["genre_id"]) ? $_POST["genre_id"] : "";
    $discount_amount = isset($_POST["discount_amount"]) ? $_POST["discount_amount"] : "";
    $checkboxes = isset($_POST["cb_select_product"]) ? $_POST["cb_select_product"] : "";
    $date_start = isset($_POST["date_start"]) ? $_POST["date_start"] : "";
    $date_end = isset($_POST["date_end"]) ? $_POST["date_end"] : "";

    if ($task == 'save') {
        $check_duplicate = false;
        foreach ($checkboxes as $value) {
            $sql_check_duplicate = "SELECT * FROM `discounts` WHERE `product_id` = ? AND `start_date` <= ? AND `end_date` >= ?";
            $stmt_check_duplicate = $conn->prepare($sql_check_duplicate);
            if (!$stmt_check_duplicate) {
                die("Prepare failed");
            }
            $stmt_check_duplicate->bind_param("iss", $value, $date_end, $date_start);
            $stmt_check_duplicate->execute();
            $result = $stmt_check_duplicate->get_result();

            if ($result->num_rows > 0) {
                $check_duplicate = true;
                break;
            }
            $stmt_check_duplicate->close();
        }
        if ($check_duplicate == false) {
            foreach ($checkboxes as $value) {
                $sql_insert_discount = "INSERT INTO `discounts`(`product_id`, `discount_amount`, `start_date`, `end_date`) 
                VALUES (?,?,?,?)";
                $stmt_insert_discount = $conn->prepare($sql_insert_discount);
                if (!$stmt_insert_discount) {
                    die("Prepare failed");
                }
                $stmt_insert_discount->bind_param("iiss", $value, $discount_amount, $date_start, $date_end);
                if (!$stmt_insert_discount->execute()) {
                    die("Execute failed: " . $stmt_insert_discount->error);
                }
                $stmt_insert_discount->close();
            }
            createNotification("Add Discount Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_discount.php';</script>";
        } else {
            createNotification("Duplicated added data!! Add Discount Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_discount.php';</script>";
        }

    }

    ?>
    <style>
        .table-responsive {
            max-height: 300px;
        }
    </style>
    <div id="data"></div>
    <div class="container-fluid">
        <form name="frmAddUser" method="post">
            <h4 class="ico_mug">
                ADD NEW DISCOUNTS
            </h4>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <label>Genres
                                        <select name="genre_id" onchange="submit()" id="genre_id"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="all">All</option>
                                            <?php
                                            $sql = "SELECT * FROM genres";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    if ($genre_id == $row["id"]) { ?>
                                                        <option value="<?php echo $row["id"]; ?>" selected>
                                                            <?php echo $row["genre_name"]; ?>
                                                        </option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row["id"]; ?>">
                                                            <?php echo $row["genre_name"]; ?>
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
                                                colspan="1" aria-label="Price old: activate to sort column ascending"
                                                style="width: 100px;">
                                                Price old
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Price new: activate to sort column ascending"
                                                style="width: 100px;">
                                                Price new</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Age: activate to sort column ascending"
                                                style="width: 90px;">
                                                <a href="javascript:Checkedboxes()" id="Checkedboxes"
                                                    style="color: #858796">Select All Pro</a>
                                                <a href="javascript:Deselect()" id="Deselect"
                                                    style="color: #858796; display: none;">Deselect Pro</a>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM products WHERE product_name LIKE '%$search%'";

                                        if ($genre_id != "" && $genre_id != "all") {
                                            $query .= " AND id in (SELECT id FROM products p, genre_product gp WHERE p.id = gp.product_id AND gp.genre_id = $genre_id)";
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
                                                        <?php echo $row['price']; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (
                                                            $checkboxes != "" && $discount_amount != ""
                                                            && $discount_amount > 0 && $discount_amount < 100
                                                        ) {
                                                            if (in_array($row['id'], $checkboxes)) {
                                                                $new_price = $row['price'] - ($row['price'] * $discount_amount / 100);
                                                                echo $new_price;
                                                            } else {
                                                                echo "0";
                                                            }
                                                        } else {
                                                            echo "0";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" onclick="submit()" 
                                                        <?php if ($checkboxes != "" && in_array($row['id'], $checkboxes)) echo "checked"; ?>
                                                            value="<?= $row['id']; ?>" name="cb_select_product[]"
                                                            style="transform: scale(1.5);">
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

                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        Discount amount
                        <div class="input-group">
                            <input type="number" value="<?php echo $discount_amount; ?>" name="discount_amount" min="1"
                                max="100" step="5" class="form-control">
                            <a href="javascript:submit()" style="text-decoration: none;"><span
                                    class="input-group-text">%</span></a>
                        </div>


                    </div>
                    <div class="col-sm-12 col-md-4">
                        Date start
                        <input type="date" name="date_start" class="form-control form-control-sm" placeholder=""
                            aria-controls="dataTable" min="<?php echo date("Y-m-d"); ?>">

                    </div>
                    <div class="col-sm-12 col-md-4">
                        Date end <input type="date" name="date_end" class="form-control form-control-sm" placeholder=""
                            aria-controls="dataTable" min="<?php echo date("Y-m-d"); ?>">

                    </div>
                </div>

                <div id="error" style="color: red;"></div>
                <input type="button" class="btn btn-info input-left mt-2" name="btnSave" value="Save" onClick="save();">
                <input type="button" class="btn btn-secondary mt-2" name="btnBack" value="Back" onClick="goback()">
            </div>

        </form>
    </div>

    <script>
        function submit() {
            document.frmAddUser.submit();
        }
    </script>


    <!-- save -->
    <script>
        function save() {
            var discount_amount = document.frmAddUser.discount_amount.value;
            var date_start = document.frmAddUser.date_start.value;
            var date_end = document.frmAddUser.date_end.value;
            var startDate = new Date(date_start);
            var endDate = new Date(date_end);
            var checkboxes = document.getElementsByName("cb_select_product[]");
            var checkedCount = 0;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    checkedCount++;
                }
            }
            if (checkedCount === 0) {
                document.getElementById("error").innerText = "Select Product!!!";
                return;
            }

            if (isNaN(discount_amount) || discount_amount === "" || discount_amount < 1 || discount_amount > 100) {
                document.getElementById("error").innerText = "Discount amount must be a number(1-100)";
                return;
            }
            if (date_start == "") {
                document.getElementById("error").innerText = "The start-date cannot empty";
                return;
            }
            if (date_end == "") {
                document.getElementById("error").innerText = "The end-date cannot empty";
                return;
            }
            if (endDate < startDate) {
                document.getElementById("error").innerText = "The start-date can't be greater than the end-date";
                return;
            }

            document.frmAddUser.action = "add_discount.php?task=save";
            document.frmAddUser.submit();
        }
    </script>

    <!-- select all and deselect -->
    <script>
        function Checkedboxes() {
            var checkboxes = document.getElementsByName("cb_select_product[]");
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
            document.getElementById("Checkedboxes").style.display = "none";
            document.getElementById("Deselect").style.display = "inline-block";
        }
        function Deselect() {
            var checkboxes = document.getElementsByName("cb_select_product[]");
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = false;
            }
            document.getElementById("Deselect").style.display = "none";
            document.getElementById("Checkedboxes").style.display = "inline-block";
        }

        function goback() {
            location.href = "discounts_management.php";
        }
    </script>

    <?php
}

?>