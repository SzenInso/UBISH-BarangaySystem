<?php 
    include '../../config/dbfetch.php';

    $population = $pdo->query("SELECT COUNT(DISTINCT member_id) AS fm FROM family_members")->fetch()['fm'];
    $households = $pdo->query("SELECT COUNT(DISTINCT household_id) AS hh FROM households")->fetch()['hh'];
    $families = $pdo->query("SELECT COUNT(DISTINCT family_id) AS fa FROM families")->fetch()['fa'];

    // age bracketing
    $age_brackets = [
        'Under 5 years old' => [0, 4],
        '5-9 years old' => [5, 9],
        '10-14 years old' => [10, 14],
        '15-19 years old' => [15, 19],
        '20-24 years old' => [20, 24],
        '25-29 years old' => [25, 29],
        '30-34 years old' => [30, 34],
        '35-39 years old' => [35, 39],
        '40-44 years old' => [40, 44],
        '45-49 years old' => [45, 49],
        '50-54 years old' => [50, 54],
        '55-59 years old' => [55, 59],
        '60-64 years old' => [60, 64],
        '65-69 years old' => [65, 69],
        '70-74 years old' => [70, 74],
        '75-79 years old' => [75, 79],
        '80 years old and over' => [80, 200]
    ];
    $age_counts = [];
    foreach ($age_brackets as $label => [$min, $max]) {
        foreach (['M' => 'Male', 'F' => 'Female'] as $sex_code => $sex_label) {
            $stmt = $pdo->prepare("SELECT COUNT(DISTINCT member_id) FROM family_members WHERE sex = :sex AND TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN :min AND :max");
            $stmt->execute(['sex' => $sex_code, 'min' => $min, 'max' => $max]);
            $age_counts[$label][$sex_label] = $stmt->fetchColumn();
        }
    }

    // sector bracketing
    $sector_queries = [
        'Labor Force' => "emp_status IN ('Permanent','Temporary','Contractual','Self-Employed')",
        'Unemployed' => "emp_status = 'Unemployed'",
        'Out of school' => "schooling = 'Out of school'",
        'Not yet in school' => "schooling = 'Not yet in school'",
        'Senior Citizens' => "TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60",
        'Person with Disabilities (PWDs)' => "is_pwd = 1",
        'Overseas Filipino Workers (OFWs)' => "is_ofw = 1",
        'Solo Parents' => "is_solo_parent = 1",
        'Indigenous Peoples (IPs)' => "is_indigenous = 1",
        'Civil Status: Single' => "civil_status = 'Single'",
        'Civil Status: Married' => "civil_status = 'Married'",
    ];
    $sector_counts = [];
    foreach ($sector_queries as $label => $where) {
        foreach (['M' => 'Male', 'F' => 'Female'] as $sex_code => $sex_label) {
            $stmt = $pdo->prepare("SELECT COUNT(DISTINCT member_id) FROM family_members WHERE sex = :sex AND $where");
            $stmt->execute(['sex' => $sex_code]);
            $sector_counts[$label][$sex_label] = $stmt->fetchColumn();
        }
    }
?>

<div class="info-box">
    <h2>Residency Demographics</h2>
    <p>
        This page provides a general demographic overview of Greenwater Village, including total population, number of households, families, age distribution, and sectoral groupings. The data is intended to help barangay officials and planners understand the composition and characteristics of the community for planning, reporting, and decision-making purposes.<br><br>
        <strong style="cursor: help;"><u>Disclaimer</u>:</strong> All figures shown are generated directly from the current database records. Please note that the accuracy of these numbers depends on the quality of the data entered. Duplicate or incomplete records of residents or households may affect the results. For official reporting, do verify and clean your data as needed.
    </p>
    <form action="residency/download_demographics.php" method="POST" style="margin: 16px 0 4px;">
        <button type="submit" name="download-excel" class="custom-cancel-button" style="cursor: pointer;">
            Download Demographics Spreadsheet
        </button>
    </form>
</div>

<div class="demographics-summary" style="
    background: #f4f9f4;
    border: 2px solid gray;
    border-radius: 10px;
    padding: 24px 32px;
    margin: 24px 0 32px 0;
    width: 100%
">
    <h3 style="margin-top:0; color:#356859; letter-spacing:1px;">General Summary</h3>
    <ul style="list-style:none; padding:0; margin:0;">
        <li style="margin-bottom:10px;">
            <span style="font-weight:bold; color:#356859;">Total No. of Barangay Inhabitants:</span>
            <span style="font-size:1.2em; color:#356859;"><?php echo $population; ?></span>
        </li>
        <li style="margin-bottom:10px;">
            <span style="font-weight:bold; color:#356859;">Total No. of Households:</span>
            <span style="font-size:1.2em; color:#356859;"><?php echo $households; ?></span>
        </li>
        <li>
            <span style="font-weight:bold; color:#356859;">Total No. of Families:</span>
            <span style="font-size:1.2em; color:#356859;"><?php echo $families; ?></span>
        </li>
    </ul>
</div>

<div style="display: flex; gap: 32px; flex-wrap: wrap;">
    <div class="demographics-section" style="flex: 1 1 350px;">
        <h3>Population by Age Bracket</h3>
        <table border="1" cellpadding="6">
            <tr>
                <th>Age Bracket</th>
                <th>Male</th>
                <th>Female</th>
                <th>Total</th>
            </tr>
            <?php foreach ($age_counts as $label => $counts): ?>
            <tr>
                <td><?php echo $label; ?></td>
                <td><?php echo $counts['Male']; ?></td>
                <td><?php echo $counts['Female']; ?></td>
                <td><?php echo $counts['Male'] + $counts['Female']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="demographics-section" style="flex: 1 1 350px;">
        <h3>Population by Sector</h3>
        <table border="1" cellpadding="6">
            <tr>
                <th>Sector</th>
                <th>Male</th>
                <th>Female</th>
                <th>Total</th>
            </tr>
            <?php foreach ($sector_counts as $label => $counts): ?>
            <tr>
                <td><?php echo $label; ?></td>
                <td><?php echo $counts['Male']; ?></td>
                <td><?php echo $counts['Female']; ?></td>
                <td><?php echo $counts['Male'] + $counts['Female']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>