<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $id_news = isset($_GET["id"]) ? $_GET["id"] : "";
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    $show_entries = isset($_POST["show_entries"]) ? $_POST["show_entries"] : 10;
    $from_date = isset($_POST["from_date"]) ? $_POST["from_date"] : "";
    $to_date = isset($_POST["to_date"]) ? $_POST["to_date"] : "";
    $type_id = isset($_POST["type"]) ? $_POST["type"] : "all";

    if (isset($_GET["delete"])) {
        $id = $_GET["delete"];
        $query = "DELETE FROM `news` WHERE id = ?";
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
        echo "<script>location.href='news_management.php';</script>";
    }

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

    $query = "SELECT n.*,t.news_type_name FROM news n, news_type t WHERE n.news_type_id = t.id AND title LIKE ? ";

    $params = array("%" . $search . "%");

    if ($from_date != "") {
        $query .= "AND publish_date >= ? ";
        $params[] = $from_date;
    }

    $to_date_obj = new DateTime($to_date);
    $to_date_obj->modify('+1 day');
    $next_day = $to_date_obj->format('Y-m-d H:i:s');

    if ($id_news != "") {
        $query .= "AND n.id = ? ";
        $params[] = $id_news;
    }

    if ($to_date != "") {
        $query .= "AND publish_date <= ? ";
        $params[] = $next_day;
    }

    if ($type_id != "all") {
        $query .= "AND news_type_id  = ? ";
        $params[] = $type_id;
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
                    <h6 class="m-0 font-weight-bold text-primary">DataTable News</h6>
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
                                    <div class="dataTables_filter">
                                        <label>From date
                                            <input type="date" id="from_date" name="from_date" onchange="submit()"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $from_date; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="dataTables_filter">
                                        <label>To date
                                            <input type="date" id="to_date" onchange="submit()" name="to_date"
                                                class="form-control form-control-sm" placeholder=""
                                                aria-controls="dataTable" value="<?php echo $to_date; ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_filter">
                                        <label>Type of news
                                            <select onchange="submit()" name="type" id="type" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="all">All</option>
                                                <?php
                                                $sql1 = "SELECT * FROM news_type";
                                                $result_sql = $conn->query($sql1);

                                                if ($result_sql->num_rows > 0) {
                                                    while ($row1 = $result_sql->fetch_assoc()) {
                                                        if ($row1["id"] == $type_id) { ?>
                                                            <option selected value="<?php echo $row1["id"]; ?>">
                                                                <?php echo $row1["news_type_name"]; ?>
                                                            </option>
                                                        <?php } else {
                                                            ?>
                                                            <option value="<?php echo $row1["id"]; ?>">
                                                                <?php echo $row1["news_type_name"]; ?>
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
                                                        colspan="1" aria-label="Title: activate to sort column ascending"
                                                        style="width: 210px;">
                                                        Title</th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1" style="width: 130px;"
                                                        aria-label="Category: activate to sort column ascending"
                                                        style="width: 150px;">
                                                        Category
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Publish date: activate to sort column ascending"
                                                        style="width: 110px;">
                                                        Publish date
                                                    </th>
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
                                                    <th rowspan="1" colspan="1">Title</th>
                                                    <th rowspan="1" colspan="1">Category</th>
                                                    <th rowspan="1" colspan="1">Publish date</th>
                                                    <th rowspan="1" colspan="1">Update</th>
                                                    <th rowspan="1" colspan="1">Delete</th>
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
                                                                    <?php echo $row['title']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $row['news_type_name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $row['publish_date']; ?>
                                                                </td>
                                                                <td><a href="update_news.php?id=<?php echo $row['id']; ?>">Update</a>
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
                                                    <a href="javascript:a('news_management.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('news_management.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('news_management.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-3">
                                        <div class="dataTables_info" aria-live="polite">
                                            <a href="./add_news.php">Add news</a>
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
            var url = "news_management.php?delete=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>