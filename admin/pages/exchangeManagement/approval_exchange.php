<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = isset($_GET["id"]) ? $_GET["id"] : "";

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
        echo "<script>location.href='approval_exchange.php';</script>";
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

    $query = "SELECT pd.*,u.full_name,p.product_name FROM `purchased_products` pd 
    INNER JOIN users u ON u.id = pd.customer_id
    INNER JOIN products p ON p.id = pd.product_id
    WHERE u.full_name LIKE '%$search%' AND status = 'review' ";

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
                    <h6 class="m-0 font-weight-bold text-primary">DataTable Approval Exchanges</h6>
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
                                                        Approval</th>
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th rowspan="1" colspan="1">ID</th>
                                                    <th rowspan="1" colspan="1">Customer Name</th>
                                                    <th rowspan="1" colspan="1">Product Name</th>
                                                    <th rowspan="1" colspan="1">Quantity</th>
                                                    <th rowspan="1" colspan="1">Price</th>
                                                    <th rowspan="1" colspan="1">Approval</th>
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
                                                                <a
                                                                    href="approval_exchange.php?approve=<?php echo $row['id']; ?>">Approval</a>
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
                                                    <a href="javascript:a('approval_exchange.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('approval_exchange.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('approval_exchange.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                            <a href="./exchanges_management.php">Exchanges Table</a>
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