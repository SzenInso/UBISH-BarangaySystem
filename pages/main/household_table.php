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
?>

<div class="info-box">
    <h2>Household Table</h2>
    <p>Here is the list of households in the barangay. You can view the details of each household, including the head/respondent, address, and family details.</p>
</div>
<div>
    <form action="residency/add_household.php" method="POST" style="display:inline;">
        <button type="submit" class="custom-cancel-button">Add Household</button>
    </form>
</div>

<?php
if ($familyStmt->rowCount() < 1) {
    echo '<br><p>No households found.</p>';
} else {
?>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <th>Household ID</th>
        <th>Head/Respondent</th>
        <th>Address</th>
        <th>Family ID</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php
        foreach ($families as $family) {
            $householdId = htmlspecialchars($family['household_id']);
            $addressId = htmlspecialchars($family['household_address_id']);
            $respondentId = htmlspecialchars($family['household_respondent_id']);
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
            <td>
                <div class="household-table-actions" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <form action="household/view_household.php" method="POST">
                        <input type="hidden" name="household_id" value="<?php echo $householdId; ?>">
                        <input type="hidden" name="address_id" value="<?php echo $addressId; ?>">
                        <input type="hidden" name="respondent_id" value="<?php echo $respondentId; ?>">
                        <button type="submit" name="view-household" class="custom-cancel-button">View Household</button>
                    </form>
                    <form action="household/delete_household.php" method="POST">
                        <input type="hidden" name="household_id" value="<?php echo $householdId; ?>">
                        <input type="hidden" name="address_id" value="<?php echo $addressId; ?>">
                        <input type="hidden" name="respondent_id" value="<?php echo $respondentId; ?>">
                        <button type="submit" name="delete-household" class="custom-cancel-button delete-household-btn">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>