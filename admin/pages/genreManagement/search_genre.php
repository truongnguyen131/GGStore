<?php
include("../../../mod/database_connection.php");
$search = isset($_POST["search"]) ? $_POST["search"] : "";
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

$query = "SELECT id, genre_name FROM genres WHERE 
genre_name LIKE ? ";
$params = array("%" . $search . "%");

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
                        aria-label="Genre name: activate to sort column ascending" style="width: 307px;">
                        Genre name
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
                    <th rowspan="1" colspan="1">Genre name</th>
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
                            <td id="genreName<?php echo $row['id']; ?>">
                                <?php echo $row['genre_name']; ?>
                            </td>
                            <td><a href="javascript:update_genre(<?php echo $row['id']; ?>)">Update</a></td>
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
                            data-dt-idx="<?php echo $i; ?>" tabindex="0" class="page-link"><?php echo $i; ?></a>
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
    <div class="col-sm-12 col-md-6 text-md-right">
        <div id="dataTable_filter" class="dataTables_filter">
            <label>
                <input id="add" class="form-control form-control-sm">
            </label>
            <input type="button" onclick="add_genre()"
                style="background-color: white; color: #4e73df;border: #dddfeb solid 1px; border-radius: 10%;"
                value="Add Genre">
        </div>
    </div>

</div>