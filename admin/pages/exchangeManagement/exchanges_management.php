<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = isset($_GET["id"]) ? $_GET["id"] : "";

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM `purchased_products` WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        createNotification("Delete Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='exchanges_management.php';</script>";
    }
    if (isset($_GET['revoke'])) {
        $id = $_GET['revoke'];
        $query = "UPDATE `purchased_products` SET `status`='review' WHERE `id`= $id";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        createNotification("Revoke Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='exchanges_management.php';</script>";
    }
    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $query = "UPDATE `purchased_products` SET `status`='trading' WHERE `id`= $id";
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
        echo "<script>location.href='exchanges_management.php';</script>";
    }

    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : 10;
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

    $query = "SELECT pd.*,u.full_name,p.product_name FROM `purchased_products` pd 
    INNER JOIN users u ON u.id = pd.customer_id
    INNER JOIN products p ON p.id = pd.product_id
    WHERE u.full_name LIKE '%$search%' ";

    if ($id != "") {
        $query .= "AND pd.id = $id ";
    }

    if ($status != "All") {
        $query .= "AND pd.status = '$status' ";
    }
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

    ?>

    <div class="container-fluid">
        <form method="post" name="frmDataDiscount">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable Exchanges</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Show entries and Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
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

                                <div class="col-sm-12 col-md-4">
                                    <div class="dataTables_filter">
                                        <label>Status
                                            <select onchange="submit()" name="status" id="status" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="All">All</option>
                                                <option value="trading" <?php echo (($status) == "trading") ? 'selected' : '' ?>>Trading</option>
                                                <option value="not trading" <?php echo (($status) == "not trading") ? 'selected' : '' ?>>Not Trading</option>
                                                <option value="review" <?php echo (($status) == "review") ? 'selected' : '' ?>>Review</option>
                                                <option value="traded" <?php echo (($status) == "traded") ? 'selected' : '' ?>>Traded</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4" style=" text-align: right;">
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
                                                        Customer Name</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Customer: activate to sort column ascending"
                                                        style="width: 250px;">
                                                        Product Name
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Total amount: activate to sort column ascending"
                                                        style="width: 100px;">
                                                        Quantity
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                                        style="width: 100px;">
                                                        Price</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                                        style="width: 100px;">
                                                        Status</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Age: activate to sort column ascending"
                                                        style="width: 70px;">Update
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Age: activate to sort column ascending"
                                                        style="width: 70px;">Delete
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th rowspan="1" colspan="1">ID</th>
                                                    <th rowspan="1" colspan="1">Customer Name</th>
                                                    <th rowspan="1" colspan="1">Product Name</th>
                                                    <th rowspan="1" colspan="1">Quantity</th>
                                                    <th rowspan="1" colspan="1">Price</th>
                                                    <th rowspan="1" colspan="1">Status</th>
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
                                                                <?php echo $row['product_name']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['quantity']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['price']; ?>
                                                            </td>
                                                            <td>

                                                                <?php
                                                                if ($row['status'] == "trading" || $row['status'] == "review") { ?>
                                                                    <a href="javascript:<?= ($row['status'] == "trading")? "revoke" : "approve" ?>(<?php echo $row['id']; ?>)">
                                                                        <?php echo $row['status']; ?>
                                                                    </a>
                                                                <?php } else {
                                                                    echo $row['status'];
                                                                } ?>
                                                            </td>
                                                            <td><a
                                                                    href="update_exchange.php?id=<?php echo $row['id']; ?>">Update</a>
                                                            </td>
                                                            <td><a
                                                                    href="javascript:deleteItem(<?php echo $row['id']; ?>)">Delete</a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo '<td style="border: none;">No results found.</td>';
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
                                                    <a href="javascript:a('exchanges_management.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('exchanges_management.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('exchanges_management.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                            <a href="./add_exchange.php">Add new exchange</a>
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

    <script>
        function a(url) {
            document.frmDataDiscount.action = url;
            document.frmDataDiscount.submit();
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
            var url = "exchanges_management.php?delete=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

    <script>
        function revoke(id) {
            var url = "exchanges_management.php?revoke=" + id;
            window.location.href = url;
        }
        function approve(id) {
            var url = "exchanges_management.php?approve=" + id;
            window.location.href = url;
        }
    </script>

<?php }
?>