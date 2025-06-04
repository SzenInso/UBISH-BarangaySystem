<?php
    $num_members = isset($_POST['num_members']) ? intval($_POST['num_members']) : 1;

    for ($i = 0; $i < $num_members; $i++) {
?>
<div class="family-member-card">
    <div class="family-member-table">
        <h3 class="member-heading">Family Member <?php echo $i + 1; ?></h3>
        <table class="member-table">
            <!-- Name Row -->
            <thead>
                <tr>
                    <th colspan="4">Household Member(s)</th>
                    <th rowspan="2">Relation to Head/Respondent</th>
                    <th rowspan="2">Sex</th>
                </tr>
                <tr>
                    <th>First Name</th>
                    <th>Middle Initial</th>
                    <th>Last Name</th>
                    <th>Suffix</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="fname[]"></td>
                    <td><input type="text" name="mname[]" maxlength="5"></td>
                    <td><input type="text" name="lname[]"></td>
                    <td><input type="text" name="suffix[]" maxlength="10"></td>
                    <td><input type="text" name="relation[]"></td>
                    <td>
                        <select name="sex[]">
                            <option value="" disabled selected>-- SELECT SEX</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </td>
                </tr>
            </tbody>

            <!-- Personal Info -->
            <thead>
                <tr>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Religion</th>
                    <th>Schooling</th>
                    <th>Educational Attainment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="date" name="birthdate[]" class="birthdate-input"></td>
                    <td>
                        <span class="age-display"></span>
                        <input type="hidden" name="age[]" class="ageHidden">
                    </td>
                    <td>
                        <select name="civilstatus[]">
                            <option value="" disabled selected>-- SELECT CIVIL STATUS</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed/r">Widowed/r</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </td>
                    <td>
                        <select name="religion[]">
                            <option value="" disabled selected>-- SELECT RELIGION</option>
                            <option value="Roman Catholic">Roman Catholic</option>
                            <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                            <option value="Islam">Islam</option>
                            <option value="Seventh Day Adventist">Seventh Day Adventist</option>
                            <option value="Methodist">Methodist</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                    <td>
                        <select name="schooling[]">
                            <option value="" disabled selected>-- SELECT SCHOOLING</option>
                            <option value="In school">In school</option>
                            <option value="Out of school">Out of school</option>
                            <option value="Not yet in school">Not yet in school</option>
                            <option value="Graduate">Graduate</option>
                            <option value="No Data">No Data</option>
                        </select>
                    </td>
                    <td>
                        <select name="attainment[]">
                            <option value="" disabled selected>-- SELECT ATTAINMENT</option>
                            <option value="Elementary">Elementary</option>
                            <option value="High School">High School</option>
                            <option value="College Undergraduate">College Undergraduate</option>
                            <option value="College Graduate">College Graduate</option>
                            <option value="Post-Graduate">Post-Graduate</option>
                            <option value="Vocational">Vocational</option>
                        </select>
                    </td>
                </tr>
            </tbody>

            <!-- Employment -->
            <thead>
                <tr>
                    <th>Occupation</th>
                    <th>Livelihood Training</th>
                    <th>Employment Status</th>
                    <th>Category</th>
                    <th>Monthly Income (Cash)</th>
                    <th>Income (Kind)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="occupation[]"></td>
                    <td><input type="text" name="livelihood_training[]"></td>
                    <td>
                        <select name="emp_status[]">
                            <option value="" disabled selected>-- SELECT STATUS</option>
                            <option value="Permanent">Permanent</option>
                            <option value="Temporary">Temporary</option>
                            <option value="Contractual">Contractual</option>
                            <option value="Self-Employed">Self-Employed</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Retired">Retired</option>
                            <option value="Others">Others</option>
                        </select>
                    </td>
                    <td>
                        <select name="emp_category[]">
                            <option value="" disabled selected>-- SELECT CATEGORY</option>
                            <option value="Private">Private</option>
                            <option value="Government">Government</option>
                            <option value="Self-Employed">Self-Employed</option>
                        </select>
                    </td>
                    <td>₱ <input type="number" name="income_cash[]" min="0" step="0.01"></td>
                    <td><input type="text" name="income_type[]"></td>
                </tr>
            </tbody>

            <!-- Others -->
            <thead>
                <tr>
                    <th colspan="3">Others</th>
                    <th colspan="3">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3">
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="is_PWD[]" value="PWD"> PWD</label>
                            <label><input type="checkbox" name="is_OFW[]" value="OFW"> OFW</label>
                            <label><input type="checkbox" name="is_solo_parent[]" value="Solo Parent"> Solo Parent</label>
                            <label><input type="checkbox" name="is_indigenous[]" value="IP"> IP</label>
                        </div>
                    </td>
                    <td colspan="3">
                        <textarea name="remarks[]" rows="3" class="remarks-textarea"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    .family-member-card {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    background-color: #f9f9f9;
}

.member-heading {
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 10px;
    color: #333;
}

.member-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    background-color: white;
}

.member-table th,
.member-table td {
    padding: 8px 10px;
    text-align: left;
    border: 1px solid #ddd;
    vertical-align: top;
}

.member-table th {
    background-color: #f0f0f0;
    color: #333;
    font-size: 14px;
    text-align: center;
}

.member-table input[type="text"],
.member-table input[type="number"],
.member-table input[type="date"],
.member-table select,
.remarks-textarea {
    width: 100%;
    padding: 6px 8px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 14px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 4px;
}

.remarks-textarea {
    resize: vertical;
    max-height: 200px;
    min-height: 60px;
}

</style>
<?php } ?>