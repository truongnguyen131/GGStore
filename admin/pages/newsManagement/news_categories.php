<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $search = isset($_POST["txtSearch"]) ? $_POST["txtSearch"] : "";
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

    $query = "SELECT * FROM news_type WHERE 
    news_type_name LIKE ? ";
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

    if (isset($_GET['add'])) {
        $addValue = $_GET['add'];
        $check_query = "SELECT * FROM news_type WHERE news_type_name = ?";
        $check_stmt = $conn->prepare($check_query);

        if ($check_stmt === false) {
            die("Error preparing statement");
        }

        $check_stmt->bind_param("s", $addValue);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Category already exists! Add Category Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='news_categories.php';</script>";
        } else {
            $query = "INSERT INTO `news_type`(`news_type_name`) VALUES (?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("s", $addValue);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Add Category Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='news_categories.php';</script>";
        }
    }

    if (isset($_GET['update'])) {
        $updateValue = $_GET['update'];
        $id = $_GET['id'];

        $check_query = "SELECT * FROM news_type WHERE news_type_name = ?";
        $check_stmt = $conn->prepare($check_query);

        if ($check_stmt === false) {
            die("Error preparing statement");
        }

        $check_stmt->bind_param("s", $updateValue);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Category name already exists! Update Category Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='news_categories.php';</script>";
        } else {
            $query = "UPDATE `news_type` SET `news_type_name`= ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("si", $updateValue, $id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Update Category Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='news_categories.php';</script>";
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM `news_type` WHERE id = ?";
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
        echo "<script>location.href='news_categories.php';</script>";
    }

    ?>

    <div class="container-fluid">
        <form method="post" name="frmDataDiscount">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable News Categories</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Show entries and Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length">
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
                                                <input type="search" name="txtSearch" value="<?= $search ?>"
                                                    class="form-control form-control-sm" placeholder=""
                                                    aria-controls="dataTable">
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
                                                        style="width: 203px;">
                                                        ID
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                        colspan="1"
                                                        aria-label="Category name: activate to sort column ascending"
                                                        style="width: 307px;">
                                                        Category name
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
                                                    <th rowspan="1" colspan="1">Category name</th>
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
                                                                <?php echo $row['news_type_name']; ?>
                                                            </td>
                                                            <td><a href="javascript:update(<?php echo $row['id']; ?>)">Update</a>
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
                                                    <a href="javascript:a('news_categories.php?page=<?= ($page > 1) ? $page - 1 : $page; ?>')"
                                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                        class="page-link">Previous</a>
                                                </li>
                                                <?php
                                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                                    <li class="paginate_button page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                        <a href="javascript:a('news_categories.php?page=<?= $i; ?>')"
                                                            aria-controls="dataTable" data-dt-idx="<?= $i; ?>" tabindex="0"
                                                            class="page-link">
                                                            <?= $i; ?>
                                                        </a>
                                                    </li>
                                                <?php }
                                                ?>
                                                <li class="paginate_button page-item next" id="dataTable_next">
                                                    <a href="javascript:a('news_categories.php?page=<?= ($page < $total_page) ? $page + 1 : $page ?>')"
                                                        aria-controls="dataTable" data-dt-idx="<?= $total_page + 1; ?>"
                                                        tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 text-md-right mt-1">
                                        <div class="dataTables_filter">
                                            <label>
                                                <input id="add" class="form-control form-control-sm">
                                            </label>
                                            <input type="button" onclick="add()"
                                                style="background-color: white; color: #4e73df;border: #dddfeb solid 1px; border-radius: 10%;"
                                                value="Add Type">

                                        </div>
                                        <div id="error" style="color: red;"></div>
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

    <!-- add -->
    <script>
        function add() {
            var addValue = document.getElementById("add").value;
            if (addValue && addValue.length >= 5 && isNaN(addValue)) {
                var url = "news_categories.php?add=" + encodeURIComponent(addValue);
                window.location.href = url;
            } else {
                document.getElementById("error").innerText = "The category name must be more than 5 characters!!!";
            }

        }
    </script>

    <!-- update -->
    <script>
        function update(id) {
            var genreName = "genreName" + id;
            var genreName_id = document.getElementById(genreName);
            var currentGenre = genreName_id.innerText;

            // create input
            var input = document.createElement("input");
            input.type = "text";
            input.style.border = "#dddfeb solid 1px";
            input.value = currentGenre;

            //add <input> into <td>
            genreName_id.innerHTML = "";
            genreName_id.appendChild(input);

            //create save button
            var saveButton = document.createElement("button");
            saveButton.innerHTML = "Save";
            saveButton.style.backgroundColor = "white";
            saveButton.style.color = "#4e73df";
            saveButton.style.border = "#dddfeb solid 1px";
            saveButton.style.borderRadius = "10%";

            saveButton.onclick = function () {
                var updatedGenre = input.value;
                var url = "news_categories.php?update=" + encodeURIComponent(updatedGenre) + "&id=" + encodeURIComponent(id);
                window.location.href = url;
            };

            //create cancel button
            var cancelButton = document.createElement("button");
            cancelButton.innerHTML = "Cancel";
            cancelButton.style.backgroundColor = "white";
            cancelButton.style.color = "#42444b";
            cancelButton.style.border = "#dddfeb solid 1px";
            cancelButton.style.borderRadius = "10%";

            cancelButton.onclick = function () {
                genreName_id.innerHTML = currentGenre;
            };

            //add buttons into <td>
            genreName_id.appendChild(saveButton);
            genreName_id.appendChild(cancelButton);
            input.focus();
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
            var url = "news_categories.php?delete=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>