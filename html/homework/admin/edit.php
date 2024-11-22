<?php 
$db = require './common.php';
require 'nav.php';
//reference url:https://github.com/MovieTone/police-reporting-system/blob/main/src/editIncident.php
//https://github.com/MovieTone/police-reporting-system/blob/main/src/addFines.php
$id = $_GET['id'] ?? null;

if (!$id) {
    echo '<script>alert("No ID specified.");window.location.href="searchIncidents.php";</script>';
    exit();
}

$row = $db->get_row("SELECT incident.Incident_ID, Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID, Fine_Amount, Fine_Points FROM `Incident` LEFT JOIN `Fines` ON Fines.Incident_ID = Incident.Incident_ID WHERE Incident.Incident_ID = $id");

if (!$row) {
    echo '<script>alert("Report not found.");window.location.href="searchIncidents.php";</script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incident_date = $_POST['incident_date'] ?? null;
    $incident_report = $_POST['incident_report'] ?? null;
    $offence_id = $_POST['offence_id'] ?? null;
    $vehicle_id = $_POST['vehicle_id'] ?? null;
    $people_id = $_POST['people_id'] ?? null;
    $fine_amount = $_POST['fine_amount'] ?? null;
    $fine_points = $_POST['fine_points'] ?? null;

    $sql = "UPDATE `Incident` SET 
        Incident_Date = '$incident_date',
        Incident_Report = '$incident_report',
        Offence_ID = '$offence_id',
        Vehicle_ID = '$vehicle_id',
        People_ID = '$people_id' 
        WHERE Incident_ID = $id";

    $db->update($sql);

    if ($_SESSION['role'] === 'admin') {
        $fine_exists = $db->get_row("SELECT * FROM `Fines` WHERE Incident_ID = $id");
        if ($fine_exists) {
            $sql = "UPDATE `Fines` SET 
                Fine_Amount = '$fine_amount', 
                Fine_Points = '$fine_points' 
                WHERE Incident_ID = $id";
        } else {
            $new_fine_id = $db->get_row("SELECT MAX(Fine_ID) AS max_id FROM `Fines`")["max_id"] + 1;
            $sql = "INSERT INTO `Fines` (Fine_ID, Fine_Amount, Fine_Points, Incident_ID) 
                    VALUES ($new_fine_id, '$fine_amount', '$fine_points', $id)";
        }
        $db->update($sql);
    }

    echo '<script>alert("Report updated successfully.");window.location.href="searchIncidents.php";</script>';
    exit();
}
?>

<style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background-color: #f0f4f8;
        margin: 0;
        padding: 0;
    }

    .form-container {
        margin: 40px auto;
        max-width: 600px;
        padding: 30px;
        border: 1px solid #9575cd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    .form-container h2 {
        text-align: center;
        color: #9575cd;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .form-container input[type="text"],
    .form-container select,
    .form-container input[type="date"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #9575cd;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-container input[type="text"]:focus,
    .form-container select:focus,
    .form-container input[type="date"]:focus {
        border-color: #9575cd;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .form-container input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #9575cd;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container input[type="submit"]:hover {
        background-color: #9575cd;
    }

    .form-container hr {
        border: none;
        height: 1px;
        background: #9575cd;
        margin: 20px 0;
    }

    label {
        margin-bottom: 5px;
        color: #9575cd;
        font-weight: bold;
        font-size: 14px;
        display: block;
    }
</style>

<div class="form-container">
    <h2>Edit Incident</h2>
    <form action="edit.php?id=<?= htmlspecialchars($id) ?>" method="post">

        <label for="incident_date">Incident Date</label>
        <input type="date" id="incident_date" name="incident_date" required value="<?= htmlspecialchars($row['Incident_Date']) ?>">

        <label for="incident_report">Incident Report</label>
        <input type="text" id="incident_report" name="incident_report" required value="<?= htmlspecialchars($row['Incident_Report']) ?>">

        <label for="offence_id">Offence</label>
        <select id="offence_id" name="offence_id">
            <?php
            $offences = $db->get_rows("SELECT * FROM `Offence`");
            foreach ($offences as $offence) {
                $selected = $offence['Offence_ID'] == $row['Offence_ID'] ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($offence['Offence_ID']) . "' $selected>" . htmlspecialchars($offence['Offence_description']) . "</option>";
            }
            ?>
        </select>

        <label for="vehicle_id">Vehicle</label>
        <select id="vehicle_id" name="vehicle_id">
            <?php
            $vehicles = $db->get_rows("SELECT * FROM `Vehicle`");
            foreach ($vehicles as $vehicle) {
                $selected = $vehicle['Vehicle_ID'] == $row['Vehicle_ID'] ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($vehicle['Vehicle_ID']) . "' $selected>" . htmlspecialchars($vehicle['Vehicle_plate']) . "</option>";
            }
            ?>
        </select>

        <label for="people_id">People</label>
        <select id="people_id" name="people_id">
            <?php
            $people = $db->get_rows("SELECT * FROM `People`");
            foreach ($people as $person) {
                $selected = $person['People_ID'] == $row['People_ID'] ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($person['People_ID']) . "' $selected>" . htmlspecialchars($person['People_name']) . "</option>";
            }
            ?>
        </select>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <label for="fine_amount">Fine Amount</label>
            <input type="text" id="fine_amount" name="fine_amount" required value="<?= htmlspecialchars($row['Fine_Amount']) ?>">

            <label for="fine_points">Fine Points</label>
            <input type="text" id="fine_points" name="fine_points" required value="<?= htmlspecialchars($row['Fine_Points']) ?>">
        <?php endif; ?>

        <input type="submit" value="Update Report">
    </form>
</div>
