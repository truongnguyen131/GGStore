<?php
include("../../../mod/database_connection.php");
$backupFileName = "backup_" . date("Y-m-d_H-i-s") . ".sql";
$backupFilePath = "C:\\xampp\\htdocs\\Galaxy_Game_Store\\admin\\pages\\backup\\" . $backupFileName;
$command = "mysqldump --user=root --host=127.0.0.1 --password= galaxy_game_store > $backupFilePath";

exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Database backup successful.";
} else {
    echo "Database backup failure";
}

$conn->close();

?> 