<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM `tradings_history` WHERE id = ?";
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
        echo "<script>location.href='tradings_history.php';</script>";
    }

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

    $query = "SELECT us.full_name as Seller,th.*,ub.full_name as Buyer FROM `tradings_history` th 
    INNER JOIN users ub ON ub.id = th.buyer_id
    INNER JOIN purchased_products pd ON pd.id = th.product_id
    INNER JOIN users us ON us.id = pd.customer_id
    WHERE ub.full_name LIKE '%$search%' OR us.full_name LIKE '%$search%'";


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
                    <h6 class="m-0 font-weight-bold text-primary">DataTable Trading History</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Show entries and Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
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

                                <div class="col-sm-12 col-md-6" style=" text-align: right;">
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
                                                        Seller</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Customer: activate to sort column ascending"
                                                        style="width: 210px;">
                                                        Buyer
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Total amount: activate to sort column ascending"
                                                        style="width: 130px;">
                                                        Product ID
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                                        style="width: 100px;">
                                                        Price</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                                        style="width: 180px;">
                                                        Transaction Date</th>
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
                                                    <th rowspan="1" colspan="1">Seller</th>
                                                    <th rowspan="1" colspan="1">Buyer</th>
                                                    <th rowspan="1" colspan="1">Product ID</th>
                                                    <th rowspan="1" colspan="1">Price</th>
                                                    <th rowspan="1" colspan="1">Transaction Date</th>
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
                                                                <?php echo $row['Seller']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['Buyer']; ?>
                                                            </td>
                                                            <td>
                                                                <a href="./exchanges_management.php?id=<?= $row['product_id']; ?>">
                                                                    <?= $row['product_id']; ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['price']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['transaction_date']; ?>
                                                            </td>
                                                            <td><a
                                                                    href="update_trading.php?id=<?php echo $row['id']; ?>">Update</a>
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
                                                    <a href="javascript:a('tradings_history.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('tradings_history.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('tradings_history.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                            <a href="./add_trading.php">Add new trading</a>
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
            var url = "tradings_history.php?delete=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>