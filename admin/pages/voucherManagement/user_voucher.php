<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
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

    $query = "SELECT u.id,u.full_name,v.id id_voucher, v.value, uv.amount
    FROM user_voucher uv
    JOIN users u ON uv.user_id = u.id
    JOIN vouchers v ON uv.voucher_id = v.id WHERE u.full_name LIKE '%$search%'
    GROUP BY u.id, v.id ";

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
        $id_user = $_POST['id_user'];
        $id_voucher = $_POST['percent'];
        $amount = $_POST['amount'];

        $checkSql = "SELECT * FROM user_voucher WHERE user_id = '$id_user' AND voucher_id = '$id_voucher'";
        $result = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($result) > 0) {
            $updateSql = "UPDATE user_voucher SET amount = amount + $amount WHERE user_id = '$id_user' AND voucher_id = '$id_voucher'";
            if (mysqli_query($conn, $updateSql)) {
                createNotification("Update User's Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='user_voucher.php';</script>";
            } else {
                echo "Error: " . $updateSql . "<br>" . mysqli_error($conn);
            }
        } else {
            $insertSql = "INSERT INTO `user_voucher`(`user_id`, `voucher_id`, `amount`) VALUES ('$id_user','$id_voucher','$amount')";
            if (mysqli_query($conn, $insertSql)) {
                createNotification("Add User's Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='user_voucher.php';</script>";
            } else {
                echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
            }
        }
    }


    if (isset($_GET['task']) && $_GET['task'] == "delete") {
        $id_user = $_GET['id_user'];
        $id_voucher = $_GET['id_voucher'];

        $sql = "DELETE FROM user_voucher WHERE user_id = $id_user AND voucher_id = $id_voucher";

        if (mysqli_query($conn, $sql)) {
            createNotification("Delete User's Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='user_voucher.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if (isset($_GET['task']) && $_GET['task'] == "updated") {
        $id_user_old = $_GET['id_user'];
        $id_voucher_old = $_GET['id_voucher'];
        $amount_old = $_GET['amount'];

        $id_user_new = $_POST['id_user'];
        $id_voucher_new = $_POST['percent'];
        $amount_new = $_POST['amount'];

        if ($id_user_old == $id_user_new && $id_voucher_old == $id_voucher_new) {
            $sql = "UPDATE user_voucher SET amount = $amount_new WHERE user_id = $id_user_old AND voucher_id = $id_voucher_old";
            if (mysqli_query($conn, $sql)) {
                createNotification("Update User's Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='user_voucher.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            $checkDuplicate = "SELECT * FROM user_voucher WHERE user_id = '$id_user_new' AND voucher_id = '$id_voucher_new'";
            $result_checkDuplicate = mysqli_query($conn, $checkDuplicate);
            if (mysqli_num_rows($result_checkDuplicate) > 0) {
                createNotification("The user and voucher are duplicated!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='user_voucher.php';</script>";
            } else {
                $sql = "UPDATE user_voucher SET user_id = $id_user_new, voucher_id= $id_voucher_new, amount = $amount_new WHERE user_id = $id_user_old AND voucher_id = $id_voucher_old";
                if (mysqli_query($conn, $sql)) {
                    createNotification("Update User's Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                    echo "<script>location.href='user_voucher.php';</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
    }

    ?>

    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTable User's Vouchers</h6>
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

                                <div class="col-sm-12 col-md-6" style=" text-align: right;">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Search
                                            <input type="search" name="search" onkeyup="submit()"
                                                class="form-control form-control-sm" aria-controls="dataTable"
                                                value="<?= $search; ?>">
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
                                                ID User
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Genre name: activate to sort column ascending"
                                                style="width: 283px;">
                                                Full Name
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Genre name: activate to sort column ascending"
                                                style="width: 203px;">
                                                Voucher
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Genre name: activate to sort column ascending"
                                                style="width: 120px;">
                                                Amount
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
                                            <th rowspan="1" colspan="1">ID User</th>
                                            <th rowspan="1" colspan="1">Full Name</th>
                                            <th rowspan="1" colspan="1">Voucher</th>
                                            <th rowspan="1" colspan="1">Amount</th>
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
                                                        <?php echo $row['full_name']; ?>
                                                    </td>
                                                    <td>
                                                        <a href="vouchers_management.php?id=<?= $row['id_voucher'] ?>"><?= $row['id_voucher'] ?></a>
                                                        
                                                    </td>
                                                    <td>
                                                        <?php echo $row['amount']; ?>
                                                    </td>
                                                    <td><a
                                                            href="user_voucher.php?task=update&id_user=<?= $row['id'] ?>&id_voucher=<?= $row['id_voucher'] ?>&amount=<?= $row['amount'] ?>">Update</a>
                                                    </td>
                                                    <td><a
                                                            href="javascript:deleteItem(<?= $row['id'] . ',' . $row['id_voucher']; ?>)">Delete</a>
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
                                            <div class="col-md-4">
                                                <select id="id_user" name="id_user" aria-controls="dataTable"
                                                    class="custom-select custom-select-sm form-control form-control-sm">
                                                    <option value="none">---Select username---</option>
                                                    <?php
                                                    $sql_sl_username = "SELECT * FROM users WHERE role = 'user'";
                                                    $result_sl_username = $conn->query($sql_sl_username);

                                                    while ($row = $result_sl_username->fetch_assoc()) {
                                                        if (isset($_GET['id_user']) && $_GET['id_user'] == $row["id"]) { ?>
                                                            <option value="<?php echo $row["id"]; ?>" selected>
                                                                <?php echo $row["username"]; ?>
                                                            </option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $row["id"]; ?>">
                                                                <?php echo $row["username"]; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="percent" name="percent" aria-controls="dataTable"
                                                    class="custom-select custom-select-sm form-control form-control-sm">
                                                    <option value="none">Percent</option>
                                                    <?php
                                                    $sql_sl_vouchers = "SELECT * FROM vouchers ";
                                                    $result_sl_vouchers = $conn->query($sql_sl_vouchers);

                                                    while ($row = $result_sl_vouchers->fetch_assoc()) {
                                                        if (isset($_GET['id_voucher']) && $_GET['id_voucher'] == $row["id"]) { ?>
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
                                            <div class="col-md-2">
                                                <input type="number" placeholder="qty" id="amount" name="amount" min="1"
                                                    max="99" value="<?= (isset($_GET['amount'])) ? $_GET['amount'] : "" ?>"
                                                    step="1" class="custom-select-sm form-control form-control-sm">
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
            var amount = document.getElementById("amount").value;
            if (document.getElementById("id_user").value == "none") {
                document.getElementById("error").innerHTML = "Select username!";
                return;
            }
            if (document.getElementById("percent").value == "none") {
                document.getElementById("error").innerHTML = "Select percent!";
                return;
            }
            if (isNaN(amount) || amount < 1 || amount > 99) {
                document.getElementById("error").innerHTML = "The quantity must be numeric (1-99)";
                document.getElementById("amount").focus();
                return;
            }
            <?php
            if (!isset($_GET['task'])) { ?>
                document.getElementById("FrmAdd_Update").action = "user_voucher.php?task=add";
            <?php } else { ?>
                document.getElementById("FrmAdd_Update").action = "user_voucher.php?task=updated&id_user=<?= $_GET['id_user'] ?>&id_voucher=<?= $_GET['id_voucher'] ?>&amount=<?= $_GET['amount'] ?>";

            <?php } ?>

            document.getElementById("FrmAdd_Update").submit();
        }
    </script>

    <!-- update -->
    <script>
        function cancel_Update() {
            window.location.href = "user_voucher.php";
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
        function deleteItem(id_user, id_voucher) {
            $('#deleteConfirmationModal').modal('show');

            var confirmDeleteButton = document.querySelector('#deleteConfirmationModal #confirmDeleteButton');
            confirmDeleteButton.setAttribute('data-id-user', id_user);
            confirmDeleteButton.setAttribute('data-id-voucher', id_voucher);

            confirmDeleteButton.addEventListener('click', performDelete);

            var cancelButton = document.querySelector('#deleteConfirmationModal .btn-secondary');
            cancelButton.addEventListener('click', cancelDelete);
        }

        function performDelete(event) {
            var id_user = event.target.getAttribute('data-id-user');
            var id_voucher = event.target.getAttribute('data-id-voucher');
            var url = "user_voucher.php?task=delete&id_user=" + encodeURIComponent(id_user) + "&id_voucher=" + encodeURIComponent(id_voucher);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>