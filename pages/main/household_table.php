<?php
    include '../../config/dbfetch.php';
    $familyQuery = "
        SELECT * FROM households
        JOIN household_addresses ON households.household_address_id = household_addresses.household_address_id
        JOIN household_respondents ON households.household_respondent_id = household_respondents.household_respondent_id
        JOIN families ON households.household_id = families.household_id
    ";
    $familyStmt = $pdo->query($familyQuery);
    $families = $familyStmt->fetchAll();
    if ($familyStmt->rowCount() < 1) {
        echo '<br><p>No households found.</p>';
    } else {
?>

<div class="info-box">
    <h2>Household Table</h2>
    <p>Here is the list of households in the barangay. You can view the details of each household, including the head/respondent, address, and family ID.</p>
</div>
<div>
    <form action="residency/add_household.php" method="POST" style="display:inline;">
        <button type="submit" class="custom-cancel-button">Add Household</button>
    </form>
</div>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <th>Household ID</th>
        <th>Head/Respondent</th>
        <th>Address</th>
        <th>Family ID</th>
    </thead>
    <tbody>
        <?php
        foreach ($families as $family) {
            $householdId = htmlspecialchars($family['household_id']);
            $respondent = htmlspecialchars($family['last_name']);
            if (!empty($family['suffix'])) { $respondent .= ' ' . htmlspecialchars($family['suffix']); }
            $respondent .= ', ';
            $respondent .= htmlspecialchars($family['first_name']);
            if (!empty($family['middle_initial'])) {
                $middleInitial = strtoupper(substr($family['middle_initial'], 0, 5)) . '.';
                $respondent .= ' ' . htmlspecialchars($middleInitial);
            }
            $addressParts = [];
            if (!empty($family['house_number'])) { $addressParts[] = htmlspecialchars($family['house_number']); }
            if (!empty($family['purok'])) { $addressParts[] = 'Purok ' . htmlspecialchars($family['purok']); }
            if (!empty($family['street'])) { $addressParts[] = htmlspecialchars($family['street']); }
            if (!empty($family['district'])) { $addressParts[] = 'District ' . htmlspecialchars($family['district']); }
            if (!empty($family['barangay'])) { $addressParts[] = htmlspecialchars($family['barangay']); }
            $address = implode(', ', $addressParts);
            $familyId = htmlspecialchars($family['family_id']);
        ?>
        <tr>
            <td><?php echo $householdId; ?></td>
            <td><?php echo $respondent; ?></td>
            <td><?php echo $address; ?></td>
            <td><?php echo $familyId; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php } ?>