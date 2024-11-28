<?php
$db = require './common.php';
require 'nav.php';
// https://gist.github.com/psxjpm/412358c557fce4dfb061cae5e8fffd74
//https://github.com/MovieTone/police-reporting-system/blob/main/src/lookupPeople.php
$searchTerm = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10; 
$offset = ($page - 1) * $limit;


$results = [];
$totalResults = 0;

if ($searchTerm) {
    $sql = "SELECT * FROM People WHERE People_name LIKE '%$searchTerm%' OR People_licence LIKE '%$searchTerm%' LIMIT $limit OFFSET $offset";
    $results = $db->get_rows($sql);
    $totalResults = $db->get_row("SELECT COUNT(*) as count FROM People WHERE People_name LIKE '%$searchTerm%' OR People_licence LIKE '%$searchTerm%'")['count'];
} else {
    $sql = "SELECT * FROM People LIMIT $limit OFFSET $offset";
    $results = $db->get_rows($sql);
    $totalResults = $db->get_row("SELECT COUNT(*) as count FROM People")['count'];
}

$totalPages = ceil($totalResults / $limit);
logger('search people');
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
    <form action="searchPeople.php" method="get">
        <input type="text" name="search" placeholder="Please input license number or name" value="<?= htmlspecialchars($searchTerm) ?>">
        <input type="submit" value="Search">
    </form>
</div>

<?php if ($results): ?>
    <table class="results-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>License Number</th>
            <th>Name</th>
            <th>Address</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['People_ID']) ?></td>
                <td><?= htmlspecialchars($row['People_licence']) ?></td>
                <td><?= htmlspecialchars($row['People_name']) ?></td>
                <td><?= htmlspecialchars($row['People_address']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?search=<?= htmlspecialchars($searchTerm) ?>&page=<?= $page - 1 ?>">&lt;</a>
        <?php endif; ?>

        <?php if ($page > 3): ?>
            <a href="?search=<?= htmlspecialchars($searchTerm) ?>&page=1">1</a>
            <span>...</span>
        <?php endif; ?>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?search=<?= htmlspecialchars($searchTerm) ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($end < $totalPages - 2): ?>
            <a href="?search=<?= htmlspecialchars($searchTerm) ?>&page=<?= $totalPages ?>"><?= $totalPages ?></a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?search=<?= htmlspecialchars($searchTerm) ?>&page=<?= $page + 1 ?>">&gt;</a>
        <?php endif; ?>
    </div>
<?php elseif ($searchTerm): ?>
    <p style="text-align: center; font-size: 16px; color: #666;">No results found for "<?= htmlspecialchars($searchTerm) ?>"</p>
<?php endif; ?>
