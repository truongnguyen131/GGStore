<?php
include("../../../mod/database_connection.php");
$search = isset($_POST["search"]) ? $_POST["search"] : "";
$id_manufacturer = isset($_POST["id_manufacturer"]) ? $_POST["id_manufacturer"] : "";
$arrangement = isset($_POST["arrangement"]) ? $_POST["arrangement"] : "";
$show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : "";
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

$query = "SELECT * FROM products WHERE product_name LIKE ? ";
$params = array("%" . $search . "%");

if ($id_manufacturer != "all") {
    $query .= "AND id_manufacturer = ? ";
    $params[] = $id_manufacturer;
}

if ($arrangement == "ascending") {
    $query .= "ORDER BY (price) ASC ";
}
if ($arrangement == "descending") {
    $query .= "ORDER BY (price) DESC ";
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

<!-- Data -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid"
            aria-describedby="dataTable_info" style="width: 100%;">

            <thead>
                <tr role="row">
                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-sort="ascending" aria-label="Product Name: activate to sort column descending"
                        style="width: 160px;">
                        Product Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Image avt: activate to sort column ascending" style="width: 110px;">
                        Avatar</th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Description: activate to sort column ascending" style="width: 160px;">
                        Description</th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Price: activate to sort column ascending" style="width: 110px;">
                        Price
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
                        aria-label="Release date: activate to sort column ascending" style="width: 90px;">
                        Units sold</th>
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
                    <th rowspan="1" colspan="1">Product Name</th>
                    <th rowspan="1" colspan="1">Avatar</th>
                    <th rowspan="1" colspan="1">Description</th>
                    <th rowspan="1" colspan="1">Price</th>
                    <th rowspan="1" colspan="1">Units sold</th>
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
                                <?php echo $row['product_name']; ?>
                            </td>
                            <td>
                                <?php echo $row['image_avt_url']; ?>
                            </td>
                            <td>
                                <div id="limitedText<?php echo $row['id']; ?>">
                                    <?php
                                    $description = $row['description'];
                                    $limitedText = substr($description, 0, 12) . ".....";
                                    echo $limitedText;
                                    ?>
                                </div>
                                <a id="showMoreButton<?php echo $row['id']; ?>"
                                    href="javascript:showFullText('<?php echo $row['id']; ?>','<?php echo $description; ?>')">More</a>
                                <a id="hideButton<?php echo $row['id']; ?>"
                                    href="javascript:hideFullText('<?php echo $row['id']; ?>','<?php echo $limitedText; ?>')"
                                    style="display: none;">Hide</a>
                            </td>
                            <td>
                                <?php echo $row['price']; ?>
                            </td>
                            <td>
                                <?php echo $row['units_sold']; ?>
                            </td>
                            <td><a href="update_product.php?id=<?php echo $row['id']; ?>">Update</a></td>
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
        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
            <a href="./add_product.php">Add a new product</a>
        </div>
    </div>
</div>