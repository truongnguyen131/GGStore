<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    ?>

    <div class="container-fluid">
        <form method="post" name="frmExport">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">DataTable For Report</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                            <!-- Search -->
                            <div class="row">


                                <div class="col-sm-12 col-md-8">
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

                                <div class="col-sm-12 col-md-1">
                                    <div class="dataTables_filter">
                                        <label>Year
                                            <select name="sl_year" id="sl_year" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <?php
                                                for ($i = 2023; $i <= date('Y'); $i++) {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="dataTables_filter">
                                        <label>Quarter
                                            <select name="sl_quarter" id="sl_quarter" onchange="change_quarter()"
                                                aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="none">None</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1" style=" text-align: right;">
                                    <div class="dataTables_filter">
                                        <label>Month
                                            <select name="sl_month" id="sl_month" aria-controls="dataTable"
                                                onchange="change_month()"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="none">None</option>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1" style=" text-align: right;">
                                    <div class="dataTables_filter">
                                        <label>Day
                                            <select name="sl_date" id="sl_date" aria-controls="dataTable"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="none">None</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="row mt-4 ml-2 mb-3">
                                <div class="col-md-6">
                                    <input type="button" class="btn btn-info" onclick="export_pdf()" name="btnPDF"
                                        value="Export PDF">
                                    <input type="button" class="btn btn-info" onclick="export_excel()" name="btnExcel" value="Export Excel">
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <!-- change_quarter() -->
    <script>
        function change_quarter() {
            if (document.getElementById('sl_month').value != "none") {
                document.getElementById('sl_quarter').value = "none";
                alert("Quarters and months cannot be retrieved together!!!");
                return false;
            }
        }
    </script>

    <!-- change_month() -->
    <script>
        function isLeapYear(year) {
            return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
        }

        function change_month() {
            var sl_month = document.getElementById('sl_month').value;
            if (document.getElementById('sl_quarter').value != "none") {
                document.getElementById('sl_month').value = "none";
                alert("Quarters and months cannot be retrieved together!!!");
                return false;
            }
            const dateSelect = document.getElementById('sl_date');
            while (dateSelect.firstChild) {
                dateSelect.removeChild(dateSelect.firstChild);
            }

            if (sl_month == 'none') {
                var option = document.createElement('option');
                option.value = "none";
                option.text = "None";
                dateSelect.add(option);
            } else {
                const maxDays = [];
                for (let i = 0; i < 12; i++) {
                    if (i == 1) {
                        let days = 28;
                        const currentYear = new Date().getFullYear();
                        if (isLeapYear(currentYear)) {
                            days = 29;
                        }
                        maxDays[i] = days;
                    } else {
                        maxDays[i] = 31;
                    }

                    if (i == 3 || i == 5 || i == 8 || i == 10) {
                        maxDays[i] = 30;
                    }
                }
                var maxDay = maxDays[sl_month - 1];
                var option = document.createElement('option');
                option.value = "none";
                option.text = "None";

                dateSelect.add(option);
                for (var i = 1; i <= maxDay; i++) {

                    var option = document.createElement('option');
                    option.value = i;
                    option.text = i;

                    dateSelect.add(option);
                }
            }


        }
    </script>

    <!-- export -->
    <script>
        function export_pdf() {
            document.frmExport.action = "export_report_PDF.php";
            document.frmExport.submit();
        }
        function export_excel() {
            document.frmExport.action = "export_report_Excel.php";
            document.frmExport.submit();
        }
    </script>


<?php }
?>