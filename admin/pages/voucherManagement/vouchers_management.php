<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php"); ?>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTable Vouchers</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <!-- Show entries and Search -->
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="dataTables_length">
                                    <label>Show entries
                                        <select name="sl_show_entries" onchange="search(0)" id="sl_show_entries"
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

                            <div class="col-sm-12 col-md-3">
                                <div class="dataTables_length">
                                    <label>Voucher type
                                        <select name="voucher_type" onchange="search(0)" id="voucher_type"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="all">All</option>
                                            <option value="percent">Percent</option>
                                            <option value="gcoin">Gcoin</option>
                                            <option value="freeship">Freeship</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <div class="dataTables_length">
                                    <label>Status
                                        <select name="status" onchange="search(0)" id="status"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="all">All</option>
                                            <option value="expiration">Expiration</option>
                                            <option value="expired">Expired</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-3" style=" text-align: right;">
                                <div>
                                    <label>Sort value
                                        <select name="sl_arrangement" onchange="search(0)" id="sl_arrangement"
                                            aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="">--None--</option>
                                            <option value="ascending">Ascending</option>
                                            <option value="descending">Descending</option>
                                        </select>
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
            var arrangement = document.getElementById("sl_arrangement").value;
            var sl_show = document.getElementById("sl_show_entries").value;
            var voucher_type = document.getElementById("voucher_type").value;
            var status = document.getElementById("status").value;

            var postData = {
                arrangement: arrangement,
                show_entries: sl_show,
                voucher_type: voucher_type,
                status: status
            };

            if (page != 0) {
                postData.index_page = page;
            }

            $.post('search_voucher.php', postData, function (data) {
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
            var url = "delete_voucher.php?id=" + encodeURIComponent(id);
            window.location.href = url;
            $('#deleteConfirmationModal').modal('hide');
        }

        function cancelDelete() {
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>

<?php }
?>