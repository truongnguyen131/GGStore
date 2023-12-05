<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $query = "UPDATE `orders` SET `status`= 'Waiting for delivery' , `order_date`= NOW() WHERE `id`= $id";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        createNotification("Approve Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='approval_order.php';</script>";
    }
    if (isset($_GET['revoke'])) {
        $order_id = $_GET['revoke'];

        $sql_update_order = "UPDATE `orders` SET `order_date`=NOW(),`status`= 'Cancelled' WHERE `id`= ?";
        $stmt_update_order = $conn->prepare($sql_update_order);
        if ($stmt_update_order === false) {
            die("Error preparing statement");
        }
        $stmt_update_order->bind_param("i", $order_id);
        if ($stmt_update_order->execute()) {
            $sql_order = "SELECT * FROM `orders` WHERE id = $order_id";
            $result_order = $conn->query($sql_order);
            $row_order = $result_order->fetch_assoc();
            $user_id = $row_order['customer_id'];
            $total_amount = $row_order['total_amount'];

            $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin+? WHERE `id`= ?";
            $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
            if ($stmt_update_gcoin === false) {
                die("Error preparing statement");
            }
            $stmt_update_gcoin->bind_param("ii", $total_amount, $user_id);
            if ($stmt_update_gcoin->execute()) {

                $sql_voucher_usage = "SELECT * FROM `voucher_usage` WHERE order_id = $order_id";
                $result_voucher_usage = $conn->query($sql_voucher_usage);

                if ($result_voucher_usage->num_rows > 0) {
                    while ($row_voucher_usage = $result_voucher_usage->fetch_assoc()) {
                        $voucher_id = $row_voucher_usage['voucher_id'];

                        $sql_delete_voucher_usage = "DELETE FROM `voucher_usage` WHERE `voucher_id`= $voucher_id AND `order_id`= $order_id";
                        $conn->query($sql_delete_voucher_usage);

                        $sql_update_user_voucher = "UPDATE `user_voucher` SET `amount`= amount + 1 
                                                    WHERE `user_id`= $user_id AND `voucher_id`= $voucher_id";
                        $conn->query($sql_update_user_voucher);
                    }
                }

            }
        }
        $stmt_update_order->close();
        createNotification("Revoke Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='approval_order.php';</script>";
    }

    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : 10;
    $from_date = isset($_POST["from_date"]) ? $_POST["from_date"] : "";
    $to_date = isset($_POST["to_date"]) ? $_POST["to_date"] : "";
    $status = isset($_POST["status"]) ? $_POST["status"] : "All";

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    if ($page == 1) {
        $begin = 0;
    } else {
        $begin = ($page * $show_entries) - $show_entries;
    }

    $query = "SELECT o.id,o.order_date,u.full_name,o.total_amount,o.status FROM orders o, users u 
    WHERE u.id = o.customer_id AND u.full_name LIKE ? AND status = 'Waiting for confirmation' ";

    $params = array("%" . $search . "%");

    if ($from_date != "") {
        $query .= "AND o.order_date >= ? ";
        $params[] = $from_date;
    }

    $to_date_obj = new DateTime($to_date);
    $to_date_obj->modify('+1 day');
    $next_day = $to_date_obj->format('Y-m-d H:i:s');

    if ($to_date != "") {
        $query .= "AND o.order_date <= ? ";
        $params[] = $next_day;
    }

    //select $total_page
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: Reload page now");
    }

    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $total_num_rows = $result->num_rows;
    $total_page = ceil($total_num_rows / $show_entries);
    $stmt->close();

    //select data
    $query .= "LIMIT ?, ?";

    $params[] = $begin;
    $params[] = $show_entries;

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: Reload page now");
    }

    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    ?>

    <div class="container-fluid">
        <form method="post" name="frmDataDiscount">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Show entries and Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_length" id="dataTable_length">
                                        <label>Show entries
                                            <select name="show_entries" onchange="submit()" id="show_entries"
                                                aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10" <?php echo (($show_entries) == 10) ? 'selected' : ''; ?>>10
                                                </option>
                                                <option value="25" <?php echo (($show_entries) == 25) ? 'selected' : ''; ?>>25
                                                </option>
                                                <option value="50" <?php echo (($show_entries) == 50) ? 'selected' : ''; ?>>50
                                                </option>
                                                <option value="100" <?php echo (($show_entries) == 100) ? 'selected' : ''; ?>>
                                                    100</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_filter">
                                        <label>From date
                                            <input type="date" id="from_date" name="from_date" onchange="submit()"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $from_date; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_filter">
                                        <label>To date
                                            <input type="date" id="to_date" onchange="submit()" name="to_date"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $to_date; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3" style=" text-align: right;">
                                    <div class="dataTables_filter">
                                        <label>Search
                                            <div class="input-group">
                                                <input type="search" id="search" name="search"
                                                    class="form-control form-control-sm" placeholder=""
                                                    aria-controls="dataTable" value="<?php echo $search; ?>">
                                                <input type="button" onclick="submit()" value="Find">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Data table -->
                            <div id="table_data_return">
                                <!-- Data -->
                                <div class="row">
                                    <div class="col-sm-12">

                                        <table class="table table-bordered dataTable" id="dataTable" width="100%"
                                            cellspacing="0" role="grid" aria-describedby="dataTable_info"
                                            style="width: 100%;">

                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable"
                                                        rowspan="1" colspan="1" aria-sort="ascending"
                                                        aria-label="ID: activate to sort column descending"
                                                        style="width: 50px;">
                                                        ID
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Order date: activate to sort column ascending"
                                                        style="width: 210px;">
                                                        Order date</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" style="width: 130px;"
                                                        aria-label="Customer: activate to sort column ascending"
                                                        style="width: 150px;">
                                                        Customer
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Total amount: activate to sort column ascending"
                                                        style="width: 110px;">
                                                        Total amount

                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Age: activate to sort column ascending"
                                                        style="width: 70px;">Approval
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Age: activate to sort column ascending"
                                                        style="width: 70px;">Revoke
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th rowspan="1" colspan="1">ID</th>
                                                    <th rowspan="1" colspan="1">Order date</th>
                                                    <th rowspan="1" colspan="1">Customer</th>
                                                    <th rowspan="1" colspan="1">Total amount</th>
                                                    <th rowspan="1" colspan="1">Approval</th>
                                                    <th rowspan="1" colspan="1">Revoke</th>
                                                </tr>
                                            </tfoot>

                                            <tbody>
                                                <?php
                                                if ($from_date != "" && $to_date != "" && $from_date > $to_date) {
                                                    echo '<td></td><td style="border: none;">Error: Start date must be greater than end date</td>';
                                                } else {
                                                    if ($result->num_rows > 0) {

                                                        while ($row = $result->fetch_assoc()) { ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $row['id']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $row['order_date']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $row['full_name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $row['total_amount']; ?>
                                                                </td>
                                                                <td><a href="approval_order.php?approve=<?= $row['id'] ?>">Approval</a>
                                                                </td>
                                                                <td><a href="approval_order.php?revoke=<?= $row['id'] ?>">Revoke</a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<td style="border: none;">No results found.</td>';
                                                    }
                                                }
                                                ?>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous" id="dataTable_previous">
                                                    <a href="javascript:a('approval_order.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('approval_order.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('approval_order.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                            <a href="./orders_management.php">Orders Table</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>


    <script>
        function a(url) {
            document.frmDataDiscount.action = url;
            document.frmDataDiscount.submit();
        }
    </script>



<?php }
?>