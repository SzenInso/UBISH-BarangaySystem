<?php
    include '../../config/dbfetch.php';
    $residencyQuery = "SELECT * FROM family_members ORDER BY last_name, first_name";
    $residencyStmt = $pdo->query($residencyQuery);
    $residency = $residencyStmt->fetchAll();
?>

<div class="info-box">
    <h2>Residency Table</h2>
    <p>Here is the list of residents in the barangay. You can view, update, or delete resident information.</p>
</div>

<?php
if ($residencyStmt->rowCount() < 1) {
    echo '<br><p>No residents found.</p>';
} else {
?>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <th>Residency ID</th>
        <th>Name</th>
        <th>Sex</th>
        <th>Birhtdate</th>
        <th>Age</th>
        <th>Civil Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php
        foreach ($residency as $member) {
            $r_id = htmlspecialchars($member['member_id']);
            $name = htmlspecialchars($member['last_name']);
            $name .= ', ';
            $name .= htmlspecialchars($member['first_name']);
            if (!empty($member['middle_initial'])) {
                $name .= ' ' . htmlspecialchars(strtoupper($member['middle_initial'])) . '.';
            }
            if (!empty($member['suffix'])) {
                $name .= ', ' . htmlspecialchars($member['suffix']);
            }
            $sex = ($member['sex'] === 'M') ? "Male" : "Female";
            $birthdate = htmlspecialchars(date('F j, Y', strtotime($member['birthdate'])));
            $age = date_diff(date_create($member['birthdate']), date_create('today'))->y;
            $civilStatus = htmlspecialchars($member['civil_status']);
        ?>
        <tr>
            <td><?php echo $r_id; ?></td>
            <td><?php echo $name; ?></td>
            <td><?php echo $sex; ?></td>
            <td><?php echo $birthdate; ?></td>
            <td><?php echo $age; ?></td>
            <td><?php echo $civilStatus; ?></td>
            <td>
                <div class="residency-actions">
                    <form action="residency/view_resident.php" method="POST">
                        <input type="hidden" name="resident_id" value="<?php echo $r_id; ?>">
                        <button class="custom-cancel-button">View Resident</button>
                    </form>
                    <form action="residency/edit_resident.php" method="POST">
                        <input type="hidden" name="resident_id" value="<?php echo $r_id; ?>">
                        <button class="custom-cancel-button" name="edit-resident">Update</button>
                    </form>
                    <form action="residency/delete_resident.php" method="POST">
                        <input type="hidden" name="resident_id" value="<?php echo $r_id; ?>">
                        <button class="custom-cancel-button" name="delete-resident">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>