<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php"); ?>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTable Users</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <!-- Show entries and Search -->
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Show entries
                                        <select name="dataTable_length" onchange="search(0)" id="sl_show_entries"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="dataTables_length" id="dataTable_role">
                                    <label>Role
                                        <select name="dataTable_role" onchange="search(0)" id="sl_role"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="all">All</option>
                                            <option value="user">User</option>
                                            <option value="manufacturer">Manufacturer</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4" style=" text-align: right;">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <label>Search
                                        <input type="search" id="search" onkeyup="search(0)"
                                            class="form-control form-control-sm" placeholder="" aria-controls="dataTable">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Data table -->
                        <div id="table_data_return">

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

    <!-- searching -->
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            search(0);
        });

        function search(page) {
            var search = document.getElementById("search").value;
            var sl_role = document.getElementById("sl_role").value;
            var sl_show = document.getElementById("sl_show_entries").value;

            var postData = {
                search: search,
                role: sl_role,
                show_entries: sl_show
            };

            if (page != 0) {
                postData.index_page = page;
            }

            <?php
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                echo "postData.id = $id;";
            }
            ?>
            $.post('search_user.php', postData, function (data) {
                $('#table_data_return').html(data);
            });

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
            var url = "delete_user.php?id=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>


<?php }
?>