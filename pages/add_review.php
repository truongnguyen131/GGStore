<?php
session_start();
include_once('../mod/database_connection.php');

echo '<script>
notification_dialog("Success", "Add review successful!!!");
</script>';

?>