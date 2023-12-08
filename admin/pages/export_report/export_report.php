<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    ?>

    <div class="container-fluid">
        <form method="post" name="frmDataDiscount">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable For Report</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Search -->
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="dataTables_length">
                                        <label>Report type
                                            <select name="type_report" id="type_report" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="all">All</option>
                                                <option value="order">Sales</option>
                                                <option value="exchange">Trading</option>
                                                <option value="recharge">Deposit</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="dataTables_filter">
                                        <label>Year
                                            <select name="type_report" id="type_report" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="1">2013</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="dataTables_filter">
                                        <label>Quarter
                                            <select name="type_report" id="type_report" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="1">None</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2" style=" text-align: right;">
                                    <div class="dataTables_filter">
                                        <label>Month
                                            <select name="type_report" id="type_report" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="1">None</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2" style=" text-align: right;">
                                    <div class="dataTables_filter">
                                        <label>Date
                                            <select name="type_report" id="type_report" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="1">None</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="row mt-4 ml-2 mb-3">
                                <div class="col-md-6">
                                    <input type="button" class="btn btn-info" name="btnPDF" value="Export PDF">
                                    <input type="button" class="btn btn-info" name="btnExcel" value="Export Excel">
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>





<?php }
?>