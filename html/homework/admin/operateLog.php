<?php
$db = require './common.php';
require 'nav.php';
//https://github.com/utopia-php/audit/blob/main/src/Audit/Audit.php
$searchTerm = $_GET['search'] ?? '';
$results = [];
$page = $_GET['page'] ?? 1;
$page = intval($page);
$itemsPerPage = 10;

if ($searchTerm) {
    $sql = 'select * from log where username = "' . $searchTerm . '" order by id desc limit ' . ($page - 1) * $itemsPerPage . ', ' . $itemsPerPage;
    $results = $db->get_rows($sql);
} else {
    $sql = 'select * from log order by id desc limit ' . ($page - 1) * $itemsPerPage . ', ' . $itemsPerPage;
    $results = $db->get_rows($sql);
}

$totalResults = $db->get_row('select count(*) as c from log' . ($searchTerm ? ' where username = "' . $searchTerm . '"' : ''))['c'];
$totalPages = ceil($totalResults / $itemsPerPage);
?>

<style>
    .search-container {
        margin: 20px auto;
        text-align: center;
    }

    .search-container input[type="text"] {
        width: 300px;
        padding: 10px;
        margin-right: 10px;
        border: 1px solid #9575cd;
        border-radius: 5px;
        outline: none;
        font-size: 14px;
    }

    .search-container input[type="text"]:focus {
        border-color: #9575cd;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .search-container input[type="submit"] {
        padding: 10px 20px;
        background-color: #9575cd;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        outline: none;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    .search-container input[type="submit"]:hover {
        background-color: #9575cd;
    }

    .results-table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    .results-table th,
    .results-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .results-table th {
        background-color: #9575cd;
        color: #fff;
    }

    .results-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .results-table tr:hover {
        background-color: #f1f1f1;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a {
        margin: 0 5px;
        padding: 10px 15px;
        background-color: #9575cd;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .pagination a:hover {
        background-color: #9575cd;
    }

    .pagination a.active {
        background-color: #9575cd;
        pointer-events: none;
    }
</style>

<div class="search-container">
    <form action="operateLog.php" method="get">
        <input type="text" name="search" placeholder="Please Input Officer Name"
               value="<?= htmlspecialchars($searchTerm) ?>">
        <input type="submit" value="Search">
    </form>
</div>

<table class="results-table">
    <thead>
    <tr>
        <th>Officer Name</th>
        <th>Description</th>
        <th>Time</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($results as $row): ?>
        <tr style="text-align: center">
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['reason']) ?></td>
            <td><?= htmlspecialchars($row['time']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="operateLog.php?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>">&lt;</a>
    <?php endif; ?>

    <?php
    for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
        <a href="operateLog.php?page=<?= $i ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="operateLog.php?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>">&gt;</a>
    <?php endif; ?>
</div>
