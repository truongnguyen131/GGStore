<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    if (isset($_GET['id_update'])) {
        $id = $_GET['id_update'];
        $sql_dID = "SELECT * FROM voucher_usage WHERE id = $id";
        $result_dID = $conn->query($sql_dID);
        $row_dID = $result_dID->fetch_assoc();
        $voucher_id = $row_dID['voucher_id'];
        $order_id = $row_dID['order_id'];
    }

    $show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : 10;

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

    $query = "SELECT * FROM `voucher_usage` ";

    //select $total_page
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: Reload page now");
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $total_num_rows = $result->num_rows;
    $total_page = ceil($total_num_rows / $show_entries);
    $stmt->close();

    //select data
    $query .= "LIMIT $begin, $show_entries";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: Reload page now");
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if (isset($_GET['task']) && $_GET['task'] == "add") {
        $voucher_id = $_POST['voucher_id'];
        $order_id = $_POST['order_id'];

        $checkSql = "SELECT * FROM voucher_usage WHERE voucher_id = '$voucher_id' AND order_id = '$order_id'";
        $result = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($result) > 0) {
            createNotification("Duplicate Data!!! Add New Failed!!!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='voucher_usage.php';</script>";
        } else {
            $insertSql = "INSERT INTO `voucher_usage`(`voucher_id`, `order_id`) VALUES ('$voucher_id','$order_id')";
            if (mysqli_query($conn, $insertSql)) {
                createNotification("Add New Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='voucher_usage.php';</script>";
            } else {
                echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
            }
        }
    }

    if (isset($_GET['task']) && $_GET['task'] == "delete") {
        $id = $_GET['id'];

        $sql = "DELETE FROM voucher_usage WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            createNotification("Delete Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='voucher_usage.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if (isset($_GET['task']) && $_GET['task'] == "updated") {
        $id = $_GET['id'];

        $voucher_id = $_POST['voucher_id'];
        $order_id = $_POST['order_id'];

        $checkSql = "SELECT * FROM voucher_usage WHERE voucher_id = '$voucher_id' AND order_id = '$order_id'";
        $result = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($result) > 0) {
            createNotification("Duplicate Data!!! Update Failed!!!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='voucher_usage.php?task=update&id_update=$id';</script>";
        } else {
            $insertSql = "UPDATE `voucher_usage` SET `voucher_id`='$voucher_id',`order_id`='$order_id' WHERE id = $id";
            if (mysqli_query($conn, $insertSql)) {
                createNotification("Update Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='voucher_usage.php';</script>";
            } else {
                echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
            }
        }
    }

    ?>

    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTable Voucher Usage</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <!-- Show entries and Search -->
                        <form method="post" id="frmSearch">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTable_length">
                                        <label>Show entries
                                            <select onchange="submit()" name="show_entries" aria-controls="dataTable"
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


                            </div>
                        </form>

                        <!-- Data -->
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0"
                                    role="grid" aria-describedby="dataTable_info" style="width: 100%;">

                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="ID: activate to sort column descending" style="width: 120px;">
                                                ID
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Genre name: activate to sort column ascending"
                                                style="width: 120px;">
                                                Voucher ID
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Genre name: activate to sort column ascending"
                                                style="width: 120px;">
                                                Order ID
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Age: activate to sort column ascending"
                                                style="width: 120px;">Update
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Age: activate to sort column ascending"
                                                style="width: 120px;">Delete
                                            </th>
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th rowspan="1" colspan="1">ID</th>
                                            <th rowspan="1" colspan="1">Voucher ID</th>
                                            <th rowspan="1" colspan="1">Order ID</th>
                                            <th rowspan="1" colspan="1">Update</th>
                                            <th rowspan="1" colspan="1">Delete</th>
                                        </tr>
                                    </tfoot>

                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {

                                            while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['id']; ?>
                                                    </td>
                                                    <td>
                                                        <a href="vouchers_management.php?id=<?= $row['voucher_id'] ?>">
                                                            <?= $row['voucher_id'] ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="../orderManagement/orders_management.php?id=<?= $row['order_id'] ?>">
                                                            <?= $row['order_id'] ?>
                                                        </a>
                                                    </td>
                                                    <td><a
                                                            href="voucher_usage.php?task=update&id_update=<?= $row['id'] ?>">Update</a>
                                                    </td>
                                                    <td><a href="javascript:deleteItem(<?= $row['id'] ?>)">Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo '<td style="border: none;">No results found.</td>';
                                        }
                                        $stmt->close();

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
                                            <a href="javascript:a('user_voucher.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                class="page-link">Previous</a>
                                        </li>
                                        <?php
                                        for ($i = 1; $i <= $total_page; $i++) { ?>
                                            <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                <a href="javascript:a('user_voucher.php?page=<?= $i; ?>')"
                                                    aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                    class="page-link">
                                                    <?= $i; ?>
                                                </a>
                                            </li>
                                        <?php }
                                        ?>
                                        <li class="paginate_button page-item next" id="dataTable_next">
                                            <a href="javascript:a('user_voucher.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>" tabindex="0"
                                                class="page-link">Next</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Add and Update function -->
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-sm-12 col-md-6 text-md-right">
                                <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                    <form method="post" id="FrmAdd_Update">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <select id="voucher_id" name="voucher_id" aria-controls="dataTable"
                                                    class="custom-select custom-select-sm form-control form-control-sm">
                                                    <option value="none">---Select voucher---</option>
                                                    <?php
                                                    $sql_sl_voucher = "SELECT * FROM vouchers";
                                                    $result_sl_voucher = $conn->query($sql_sl_voucher);

                                                    while ($row = $result_sl_voucher->fetch_assoc()) {
                                                        if (isset($voucher_id) && $voucher_id == $row["id"]) { ?>
                                                            <option value="<?php echo $row["id"]; ?>" selected>
                                                                <?php echo $row["id"]; ?>
                                                            </option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $row["id"]; ?>">
                                                                <?php echo $row["id"]; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <select id="order_id" name="order_id" aria-controls="dataTable"
                                                    class="custom-select custom-select-sm form-control form-control-sm">
                                                    <option value="none">---Select order---</option>
                                                    <?php
                                                    $sql_sl_orders = "SELECT * FROM orders ";
                                                    $result_sl_orders = $conn->query($sql_sl_orders);

                                                    while ($row = $result_sl_orders->fetch_assoc()) {
                                                        if (isset($order_id) && $order_id == $row["id"]) { ?>
                                                            <option value="<?php echo $row["id"]; ?>" selected>
                                                                <?php echo $row["id"]; ?>
                                                            </option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $row["id"]; ?>">
                                                                <?php echo $row["id"]; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <?php
                                                if (!isset($_GET['task'])) { ?>
                                                    <button onclick="submit_FrmAdd()"
                                                        style="background-color: white; color: #4e73df;border: #dddfeb solid 1px; border-radius: 10%;"
                                                        type="button">Add
                                                    </button>
                                                <?php } else { ?>
                                                    <button onclick="submit_FrmAdd()"
                                                        style="background-color: white; color: #4e73df;border: #dddfeb solid 1px; border-radius: 10%;"
                                                        type="button">Save
                                                    </button>
                                                <?php }
                                                ?>
                                            </div>
                                            <?php
                                            if (isset($_GET['task'])) { ?>
                                                <div class="col-md-1">
                                                    <button onclick="cancel_Update()"
                                                        style="background-color: white; color: red;border: #dddfeb solid 1px; border-radius: 10%;"
                                                        type="button">X
                                                    </button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row" id="error" style="color: red; margin-left: 5px;"></div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Confirm Delete Dialog -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm deletion?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to delete?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- submit FrmAdd_Update -->
    <script>
        function submit_FrmAdd() {
            document.getElementById("error").innerHTML = "";

            if (document.getElementById("voucher_id").value == "none") {
                document.getElementById("error").innerHTML = "Select voucher!";
                return;
            }
            if (document.getElementById("order_id").value == "none") {
                document.getElementById("error").innerHTML = "Select order!";
                return;
            }

            <?php
            if (!isset($_GET['task'])) { ?>
                document.getElementById("FrmAdd_Update").action = "voucher_usage.php?task=add";
            <?php } else { ?>
                document.getElementById("FrmAdd_Update").action = "voucher_usage.php?task=updated&id=<?= $id; ?>";

            <?php } ?>

            document.getElementById("FrmAdd_Update").submit();
        }
    </script>

    <!-- update -->
    <script>
        function cancel_Update() {
            window.location.href = "voucher_usage.php";
        }
    </script>

    <script>
        function a(url) {
            document.getElementById("frmSearch").action = url;
            document.getElementById("frmSearch").submit();
        }
    </script>

    <!-- delete -->
    <script>
        function deleteItem(id) {

            $('#deleteConfirmationModal').modal('show');

            var confirmDeleteButton = document.querySelector('#deleteConfirmationModal #confirmDeleteButton');
            confirmDeleteButton.setAttribute('data-id', id);

            confirmDeleteButton.addEventListener('click', performDelete);

            var cancelButton = document.querySelector('#deleteConfirmationModal .btn-secondary');
            cancelButton.addEventListener('click', cancelDelete);
        }


        function performDelete(event) {
            var id = event.target.getAttribute('data-id');
            var url = "voucher_usage.php?task=delete&id=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>