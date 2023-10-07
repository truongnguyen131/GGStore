<?php
function print_data($id, $product_name, $discount_amount, $price_old, $start_date, $end_date)
{
    $price_new = $price_old - ($price_old * ($discount_amount / 100));
    echo "<tr>
    <td>
        $id
    </td>
    <td>
        $product_name
    </td>
    <td>
        $discount_amount
    </td>
    <td>
        $price_old
    </td>
    <td>
        $price_new
    </td>
    <td>
        $start_date
    </td>
    <td>
        $end_date
    </td>
    <td><a href='update_discount.php?id=$id'>Update</a></td>
    <td><a href='javascript:deleteItem($id)'>Delete</a></td>
</tr>";
}
?>

<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $status = isset($_POST["status"]) ? $_POST["status"] : "all";
    $id_manufacturer = isset($_POST["id_manufacturer"]) ? $_POST["id_manufacturer"] : "";
    $date_start = isset($_POST["date_start"]) ? $_POST["date_start"] : "";
    $date_end = isset($_POST["date_end"]) ? $_POST["date_end"] : "";
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

    $query = "SELECT d.id,product_id,id_manufacturer,product_name, discount_amount,price,start_date,end_date 
        FROM discounts d, products p 
        WHERE p.id = d.product_id AND p.product_name LIKE ? ";

    $params = array("%" . $search . "%");

    if ($id_manufacturer != "all" && $id_manufacturer != "") {
        $query .= "AND id_manufacturer = ? ";
        $params[] = $id_manufacturer;
    }
    if ($date_start != "") {
        $query .= "AND start_date >= ? ";
        $params[] = $date_start;
    }
    if ($date_end != "") {
        $query .= "AND end_date <= ? ";
        $params[] = $date_end;
    }

    if ($status == "on_sale") {
        $query .= "AND CURRENT_DATE() >= start_date AND  CURRENT_DATE() <= end_date ";
    }
    if ($status == "expired_discount") {
        $query .= "AND CURRENT_DATE() > end_date ";
    }
    if ($status == "coming_discount") {
        $query .= "AND CURRENT_DATE() < start_date ";
    }

    if (isset($_GET["asc_percent"])) {
        $query .= "ORDER BY (discount_amount) DESC ";
    }
    if (isset($_GET["desc_percent"])) {
        $query .= "ORDER BY (discount_amount) ASC ";
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

    <style>
        .icon {
            color: #dddfeb;
            font-size: 10px;
        }

        .icon:hover {
            color: #4e73df;
        }

        .icon1 {
            color: #4e73df;
            font-size: 10px;
        }
    </style>

    <div class="container-fluid">
        <form method="post" name="frmDataDiscount">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable Discounts</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Show entries and Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-2">
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

                                <div class="col-sm-12 col-md-2">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Date start
                                            <input type="date" id="date_start" name="date_start" onchange="submit()"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $date_start; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Date end
                                            <input type="date" id="date_end" onchange="submit()" name="date_end"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $date_end; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Manufacturer
                                            <select onchange="submit()" name="id_manufacturer" id="id_manufacturer"
                                                aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="all">All</option>
                                                <?php
                                                $sql1 = "SELECT * FROM users WHERE role = 'manufacturer'";
                                                $result_sql = $conn->query($sql1);

                                                if ($result_sql->num_rows > 0) {
                                                    while ($row1 = $result_sql->fetch_assoc()) {
                                                        if ($row1["id"] == $id_manufacturer) { ?>
                                                            <option selected value="<?php echo $row1["id"]; ?>">
                                                                <?php echo $row1["full_name"]; ?>
                                                            </option>
                                                        <?php } else {
                                                            ?>
                                                            <option value="<?php echo $row1["id"]; ?>">
                                                                <?php echo $row1["full_name"]; ?>
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

                                <div class="col-sm-12 col-md-2">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Status
                                            <select onchange="submit()" name="status" id="status" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="all">All</option>
                                                <option value="on_sale" <?php echo (($status) == "on_sale") ? 'selected' : ''; ?>>On sale</option>
                                                <option value="expired_discount" <?php echo (($status) == "expired_discount") ? 'selected' : ''; ?>>Expired discount</option>
                                                <option value="coming_discount" <?php echo (($status) == "coming_discount") ? 'selected' : ''; ?>>Coming discount</option>
                                                <option value="no_discount" <?php echo (($status) == "no_discount") ? 'selected' : ''; ?>>Not yet discounted</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2" style=" text-align: right;">
                                    <div id="dataTable_filter" class="dataTables_filter">
                                        <label>Search
                                            <input type="search" id="search" name="search" onkeyup="submit()"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $search; ?>">
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
                                                        style="width: 70px;">
                                                        ID
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Product name: activate to sort column ascending"
                                                        style="width: 147px;">
                                                        Product name</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Discount amount: activate to sort column ascending"
                                                        style="width: 150px;">
                                                        Percentage
                                                        <a
                                                            href="javascript:a('discounts_Management.php?page=<?php echo $page; ?><?= isset($_GET['desc_percent']) ? '' : '&desc_percent' ?>')">
                                                            <i
                                                                class="fas fa-arrow-down <?= isset($_GET['desc_percent']) ? 'icon1' : 'icon' ?>"></i>
                                                        </a>
                                                        <a
                                                            href="javascript:a('discounts_Management.php?page=<?php echo $page; ?><?= isset($_GET['asc_percent']) ? '' : '&asc_percent' ?>')">
                                                            <i
                                                                class="fas fa-arrow-up <?= isset($_GET['asc_percent']) ? 'icon1' : 'icon' ?>"></i>
                                                        </a>
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Price old: activate to sort column ascending"
                                                        style="width: 139px;">
                                                        Price old

                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Price new: activate to sort column ascending"
                                                        style="width: 139px;">
                                                        Price new</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Date start: activate to sort column ascending"
                                                        style="width: 147px;">
                                                        Date start</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" aria-label="Date end: activate to sort column ascending"
                                                        style="width: 147px;">
                                                        Date end</th>
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
                                                    <th rowspan="1" colspan="1">Product name</th>
                                                    <th rowspan="1" colspan="1">Percentage</th>
                                                    <th rowspan="1" colspan="1">Price old</th>
                                                    <th rowspan="1" colspan="1">Price new</th>
                                                    <th rowspan="1" colspan="1">Date start</th>
                                                    <th rowspan="1" colspan="1">Date end</th>
                                                    <th rowspan="1" colspan="1">Update</th>
                                                    <th rowspan="1" colspan="1">Delete</th>
                                                </tr>
                                            </tfoot>

                                            <tbody>
                                                <?php
                                                if ($date_start != "" && $date_end != "" && $date_start > $date_end) {
                                                    echo '<td></td><td style="border: none;">Error: Start date must be greater than end date</td>';
                                                } else {
                                                    if ($result->num_rows > 0 && $status != "no_discount") {
                                                        while ($row = $result->fetch_assoc()) {
                                                            print_data($row['id'], $row['product_name'], $row['discount_amount'], $row['price'], $row['start_date'], $row['end_date']);
                                                        }
                                                    }
                                                    if ($result->num_rows == 0 && $status != "no_discount") {
                                                        echo '<td></td><td style="border: none;">No discount data</td>';
                                                    }
                                                    if (
                                                        $status == "no_discount" || $status == "all" && $page == $total_page && $date_start == "" && $date_end == ""
                                                    ) {
                                                        $query_sl_products = "SELECT * FROM products
                                                        WHERE id NOT IN (SELECT product_id FROM discounts)
                                                        AND product_name LIKE '%$search%'";
                                                        $result_sl_products = $conn->query($query_sl_products);
                                                        if ($result_sl_products->num_rows > 0) {
                                                            while ($row1 = $result_sl_products->fetch_assoc()) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $row1['id']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $row1['product_name']; ?>
                                                                    </td>
                                                                    <td>
                                                                        0%
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $row1['price']; ?>
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        if ($result_sl_products->num_rows == 0 && $status == "no_discount") {
                                                            echo '<td></td><td style="border: none;">No data returned</td>';
                                                        }
                                                    }
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
                                                    <a href="javascript:a('discounts_Management.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('discounts_Management.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('discounts_Management.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                            <a href="./add_discount.php">Add new discount</a>
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
            var url = "delete_discount.php?id=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>