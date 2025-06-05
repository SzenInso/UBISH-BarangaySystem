<?php
    include '../../../config/dbfetch.php';

    // semester and date
    $today = new DateTime();
    $year = $today->format('Y');
    $month = (int)$today->format('n');
    $semester = ($month <= 6) ? 'First' : 'Second';
    $date_accomplished = $today->format('F j, Y');

    // monitoring report
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

    // prepared by
    $employee_id = $_SESSION['emp_id'] ?? null;
    $prepared_by_name = '';
    $prepared_by_legislature = '';
    if ($employee_id) {
        $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, legislature FROM employee_details WHERE emp_id = ?");
        $stmt->execute([$employee_id]);
        if ($emp = $stmt->fetch()) {
            $middle_initial = $emp['middle_name'] ? strtoupper(substr($emp['middle_name'], 0, 1)) . '.' : '';
            $prepared_by_name = $emp['first_name'] . ' ' . $middle_initial . ' ' . $emp['last_name'];
            $prepared_by_legislature = $emp['legislature'];
        }
    }

    // submitted by
    $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, legislature FROM employee_details WHERE legislature = 'Punong Barangay' LIMIT 1");
    $stmt->execute();
    $chairman_name = '';
    $chairman_legislature = '';
    if ($chair = $stmt->fetch()) {
        $middle_initial = $chair['middle_name'] ? strtoupper(substr($chair['middle_name'], 0, 1)) . '.' : '';
        $chairman_name = $chair['first_name'] . ' ' . $middle_initial . ' ' . $chair['last_name'];
        $chairman_legislature = $chair['legislature'];
    }

    // output as spreadsheet
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=Monitoring_Report_" . date('Y-m-d_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<table border="0" style="margin-bottom:18px;">
    <tr>
        <td colspan="2" style="font-size:1.5em; font-weight:bold; text-align:center;">Monitoring Report</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:1.1em; text-align:center;">
            for <?php echo $semester; ?> Semester of CY <?php echo $year; ?>
        </td>
    </tr>
</table>
<br>
<table border="0">
    <tr>
        <td><b>REGION:</b> CAR</td>
    </tr>
    <tr>
        <td><b>PROVINCE:</b> Benguet</td>
    </tr>
    <tr>
        <td><b>CITY/MUNICIPALITY:</b> City of Baguio</td>
    </tr>
    <tr>
        <td><b>BARANGAY:</b> Greenwater Village</td>
    </tr>
</table>
<br>
<table border="1">
    <tr><th colspan="2" style="background:#356859; color:#fff;">General Summary</th></tr>
    <tr><td>Total No. of Barangay Inhabitants</td><td><?php echo $population; ?></td></tr>
    <tr><td>Total No. of Households</td><td><?php echo $households; ?></td></tr>
    <tr><td>Total No. of Families</td><td><?php echo $families; ?></td></tr>
</table>
<br>
<table border="1">
    <tr>
        <th colspan="4" style="background:#356859; color:#fff;">Population by Age Bracket</th>
    </tr>
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
<br>
<table border="1">
    <tr>
        <th colspan="4" style="background:#356859; color:#fff;">Population by Sector</th>
    </tr>
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
<br>
<table border="0" style="margin-top:18px;">
    <tr>
        <td style="font-weight: bold;">Prepared By:</td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td>
            <?php echo htmlspecialchars($prepared_by_name); ?>
            <?php if ($prepared_by_legislature): ?>
                <br><span style="font-style:italic;"><?php echo htmlspecialchars($prepared_by_legislature); ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td style="font-weight: bold;">Submitted By:</td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td>
            <?php echo htmlspecialchars($chairman_name); ?>
            <?php if ($chairman_legislature): ?>
                <br><span style="font-style:italic;"><?php echo htmlspecialchars($chairman_legislature); ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td style="font-weight:bold;">Date Accomplished:</td>
    </tr>
    <tr>
        <td><?php echo $date_accomplished; ?></td>
    </tr>
</table>