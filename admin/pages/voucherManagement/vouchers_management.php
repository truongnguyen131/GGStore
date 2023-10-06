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
                            <div class="col-sm-12 col-md-6">
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

                            <div class="col-sm-12 col-md-6" style=" text-align: right;">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <label>Sort percents
                                        <select name="arrangement" onchange="search(0)" id="sl_arrangement" aria-controls="dataTable"
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

            var postData = {
                arrangement: arrangement,
                show_entries: sl_show
            };

            if (page != 0) {
                postData.index_page = page;
            }

            $.post('search_voucher.php', postData, function (data) {
                $('#table_data_return').html(data);
            });

        }

    </script>

    <!-- add -->
    <script>
        function add() {
            var addValue = document.getElementById("add").value;
            var url = "add_voucher.php?add=" + encodeURIComponent(addValue);
            window.location.href = url;
        }
    </script>

    <!-- update -->
    <script>
        function update(id) {
            var DP = "discount_percentage_" + id;
            var DP_id = document.getElementById(DP);
            var current_DP = DP_id.innerText;

            // create input
            var input = document.createElement("input");
            input.type = "text";
            input.style.border = "#dddfeb solid 1px";
            input.value = current_DP.replace(/%/g, '');;

            //add <input> into <td>
            DP_id.innerHTML = "";
            DP_id.appendChild(input);

            //create save button
            var saveButton = document.createElement("button");
            saveButton.innerHTML = "Save";
            saveButton.style.backgroundColor = "white";
            saveButton.style.color = "#4e73df";
            saveButton.style.border = "#dddfeb solid 1px";
            saveButton.style.borderRadius = "10%";

            saveButton.onclick = function () {
                var updated_DP = input.value;
                var url = "update_voucher.php?update=" + encodeURIComponent(updated_DP) + "&id=" + encodeURIComponent(id);
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
                DP_id.innerHTML = current_DP;
            };

            //add buttons into <td>
            DP_id.appendChild(saveButton);
            DP_id.appendChild(cancelButton);
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