<?php
include_once('./database_connection.php');
$search_textbox = isset($_GET['search']) ? $_GET['search'] : "";
$search_type = isset($_GET['type']) ? $_GET['type'] : "";
$sql_name = "SELECT * FROM (
  SELECT id , product_name as name, classify FROM products WHERE release_date <= NOW()
  UNION
  SELECT id , genre_name as name, 'genre' as classify FROM genres
  UNION
  SELECT id , title as name, 'news' as classify FROM news
) AS combined_results
WHERE name LIKE '%" . $search_textbox . "%'";

if ($search_type != 'all' && $search_type != '') {
  $sql_type = " AND classify = '" . $search_type . "'";
} else {
  $sql_type = '';
}

$sql = $sql_name . $sql_type;

$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
  while ($row = mysqli_fetch_array($result)) {
    if ($row['classify'] == 'game' || $row['classify'] == 'gear') {
      $url = "../Galaxy_Game_Store/product_details?id=" . $row['id'] . "";
    } else if ($row['classify'] == 'genre') {
      $url = "../Galaxy_Game_Store/store?id_category=" . $row['id'] . "";
    } else {
      $url = "../Galaxy_Game_Store/news?id=" . $row['id'] . "";
    }

    echo '<a href="' . $url . '" title="' . $row['name'] . '">';
    echo '<div class="item_suggest">';
    echo ' <div class="type_suggest_ver1">';
    echo '<span>' . $row['classify'] . ':</span>';
    echo ' </div>';
    echo ' <div class="name_suggest_ver1">';
    echo '<span>' . $row['name'] . '</span>';
    echo '</div>';
    echo '</div>';
    echo '</a>';
  }
} else {
  echo '<div class="item_suggest">';
  echo 'No suggestion';
  echo '</div>';
}
