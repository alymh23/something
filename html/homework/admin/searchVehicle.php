<?php
$db = require './common.php';
require 'nav.php';
//Reference URL:https://github.com/aksharSolanki/Web-Form-PHP-MySQL/blob/main/index.php
//https://gist.github.com/psxjpm/e5c0ee508290844052aa18f48bfa9733
$searchTerm = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10; // Records per page
$offset = ($page - 1) * $limit;

$results = [];
$totalResults = 0;

// Fetch total results
$totalSql = "SELECT COUNT(*) as total FROM Vehicle";
if ($searchTerm) {
    $totalSql .= " WHERE Vehicle_plate LIKE '%$searchTerm%'";
}
$totalRecords = $db->get_row($totalSql)['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch results with pagination
$sql = "SELECT Vehicle.Vehicle_plate, Vehicle.Vehicle_ID, Vehicle.Vehicle_type, Vehicle.Vehicle_colour, People.People_name, People.People_licence
        FROM Vehicle
        LEFT JOIN Ownership ON Vehicle.Vehicle_ID = Ownership.Vehicle_ID
        LEFT JOIN People ON Ownership.People_ID = People.People_ID";

if ($searchTerm) {
    $sql .= " WHERE Vehicle_plate LIKE '%$searchTerm%'";
}
$sql .= " ORDER BY Vehicle.Vehicle_ID DESC LIMIT $limit OFFSET $offset";

$results = $db->get_rows($sql);
logger('search vehicle');
?>

<style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .search-container {
        margin: 20px auto;
        width: 80%;
        max-width: 800px;
        text-align: center;
    }

    .search-container input[type="text"] {
        width: 60%;
        padding: 10px;
        margin-right: 10px;
        border: 1px solid #9575cd;
        border-radius: 5px;
        font-size: 16px;
        outline: none;
        transition: border-color 0.3s;
    }

    .search-container input[type="text"]:focus {
        border-color: #9575cd;
    }

    .search-container input[type="submit"] {
        padding: 10px 20px;
        background-color: #9575cd;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .search-container input[type="submit"]:hover {
        background-color: #9575cd;
    }

    .results-table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        text-align: left;
        font-size: 16px;
    }

    .results-table th, .results-table td {
        padding: 12px;
        border: 1px solid #ddd;
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
        margin: 20px auto;
    }

    .pagination a {
        margin: 0 5px;
        padding: 10px 15px;
        background-color: #9575cd;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .pagination a:hover {
        background-color: #9575cd;
    }

    .pagination a.active {
        background-color: #9575cd;
        pointer-events: none;
    }

    .pagination span {
        padding: 10px 15px;
        color: #666;
    }
</style>

<div class="search-container">
    <form action="searchVehicle.php" method="get">
        <input type="text" name="search" placeholder="Please input vehicle plate" value="<?= htmlspecialchars($searchTerm) ?>">
        <input type="submit" value="Search">
    </form>
</div>

<?php if ($results): ?>
    <table class="results-table">
        <thead>
        <tr>
            <th>Vehicle ID</th>
            <th>Car-owner Name / Licence</th>
            <th>Vehicle Plate</th>
            <th>Vehicle Color</th>
            <th>Vehicle Type</th>
            <th>Incidences</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Vehicle_ID']) ?></td>
                <td><?= htmlspecialchars($row['People_name'] ?? 'Unknown') ?> / <?= htmlspecialchars($row['People_licence'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($row['Vehicle_plate']) ?></td>
                <td><?= htmlspecialchars($row['Vehicle_colour']) ?></td>
                <td><?= htmlspecialchars($row['Vehicle_type']) ?></td>
                <td><a href="./searchIncidents.php?search=<?= htmlspecialchars($row['Vehicle_ID']) ?>">Details</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>">&lt;</a>
        <?php endif; ?>

        <?php if ($page > 3): ?>
            <a href="?page=1&search=<?= htmlspecialchars($searchTerm) ?>">1</a>
            <span>...</span>
        <?php endif; ?>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($end < $totalPages - 2): ?>
            <a href="?page=<?= $totalPages ?>&search=<?= htmlspecialchars($searchTerm) ?>"><?= $totalPages ?></a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>">&gt;</a>
        <?php endif; ?>
    </div>
<?php elseif ($searchTerm): ?>
    <p style="text-align: center; font-size: 16px; color: #666;">No results found for "<?= htmlspecialchars($searchTerm) ?>"</p>
<?php endif; ?>
