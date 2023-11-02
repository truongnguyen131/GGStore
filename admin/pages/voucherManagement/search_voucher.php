<?php
include("../../../mod/database_connection.php");
$arrangement = isset($_POST["arrangement"]) ? $_POST["arrangement"] : "";
$show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : 10;
$voucher_type = isset($_POST["voucher_type"]) ? $_POST["voucher_type"] : "";
$status = isset($_POST["status"]) ? $_POST["status"] : "";

if (isset($_POST['index_page'])) {
    $page = $_POST['index_page'];
} else {
    $page = "";
}
if ($page == "" || $page == 1) {
    $begin = 0;
} else {
    $begin = ($page * $show_entries) - $show_entries;
}

$query = "SELECT * FROM vouchers WHERE id LIKE ? ";
$params = array("%%");

if ($voucher_type != "all") {
    $query .= "AND type = ? ";
    $params[] = $voucher_type;
}

if ($status == "expiration") {
    $query .= "AND date_expiry >= CURDATE() ";
}

if ($status == "expired") {
    $query .= "AND date_expiry < CURDATE() ";
}

if ($arrangement == "ascending") {
    $query .= "ORDER BY(value) ASC ";
}
if ($arrangement == "descending") {
    $query .= "ORDER BY(value) DESC ";
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

<!-- Data -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid"
            aria-describedby="dataTable_info" style="width: 100%;">

            <thead>
                <tr role="row">
                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-sort="ascending" aria-label="ID: activate to sort column descending" style="width: 203px;">
                        ID
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Value: activate to sort column ascending" style="width: 203px;">
                        Value
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Type: activate to sort column ascending" style="width: 307px;">
                        Type
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Quantity: activate to sort column ascending" style="width: 203px;">
                        Condition
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Quantity: activate to sort column ascending" style="width: 203px;">
                        Quantity
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Date expiry: activate to sort column ascending" style="width: 307px;">
                        Date expiry
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Age: activate to sort column ascending" style="width: 70px;">Update
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Age: activate to sort column ascending" style="width: 70px;">Delete
                    </th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">ID</th>
                    <th rowspan="1" colspan="1">Value</th>
                    <th rowspan="1" colspan="1">Type</th>
                    <th rowspan="1" colspan="1">Condition</th>
                    <th rowspan="1" colspan="1">Quantity</th>
                    <th rowspan="1" colspan="1">Date expiry</th>
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
                                <?php echo $row['value']; ?><?= ($row['type'] == 'percent') ? "%" : "" ?>
                            </td>
                            <td>
                                <?php echo $row['type']; ?>
                            </td>
                            <td>
                                <?php echo $row['minimum_condition']; ?>
                            </td>
                            <td>
                                <?= $row['quantity']; ?>
                            </td>
                            <td>
                                <?php echo $row['date_expiry']; ?>
                            </td>
                            <td><a href="update_voucher.php?id=<?php echo $row['id']; ?>">Update</a></td>
                            <td><a href="javascript:deleteItem(<?php echo $row['id']; ?>)">Delete</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<td style="border: none;">No results found.</td>';
                }
                $stmt->close();
                $conn->close();
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
                    <a href="javascript:pre_next(0)" aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                        class="page-link">Previous</a>
                </li>
                <?php
                for ($i = 1; $i <= $total_page; $i++) { ?>
                    <li class="paginate_button page-item <?php if ($i == $page || $i == 1 && $page == "") {
                        echo 'active';
                    } ?>">
                        <a href="javascript:search(<?php echo $i; ?>)" aria-controls="dataTable"
                            data-dt-idx="<?php echo $i; ?>" tabindex="0" class="page-link">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php }
                ?>
                <li class="paginate_button page-item next" id="dataTable_next">
                    <a href="javascript:pre_next(<?php echo $total_page; ?>)" aria-controls="dataTable"
                        data-dt-idx="<?php echo $total_page + 1; ?>" tabindex="0" class="page-link">Next</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 text-md-right mt-3">
        <div id="dataTable_filter" class="dataTables_filter">
            <a href="add_voucher.php">Add new voucher</a>
        </div>
    </div>

</div>