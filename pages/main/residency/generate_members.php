<?php
    $num_members = isset($_POST['num_members']) ? intval($_POST['num_members']) : 1;

    for ($i = 0; $i < $num_members; $i++) {
?>
        <div class="family-member" style="border:1px solid #ccc; margin-bottom:24px; border-radius:8px; padding:16px;">
            <div class="family-member-table">
                <h3>Family Member <?php echo $i+1; ?></h3>
                <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                    <thead>
                        <tr>
                            <th colspan="2">Household Member(s)</th>
                            <th rowspan="2">Relation to Head/Respondent</th>
                            <th rowspan="2">Sex</th>
                            <th rowspan="2">Date of Birth</th>
                            <th rowspan="2">Age</th>
                        </tr>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="fname[]"></td>
                            <td><input type="text" name="lname[]"></td>
                            <td><input type="text" name="relation[]"></td>
                            <td>
                                <select name="sex[]">
                                    <option value="" disabled selected>-- SELECT SEX</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </td>
                            <td><input type="date" name="birthdate[]" class="dobInput"></td>
                            <td>
                                <span class="ageDisplay"></span>
                                <input type="hidden" name="age[]" class="ageHidden">
                            </td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th rowspan="2">Civil Status</th>
                            <th rowspan="2">Religion</th>
                            <th rowspan="2">Schooling</th>
                            <th rowspan="2">Highest Educational Attainment</th>
                            <th rowspan="2">Present Job/Occupation</th>
                            <th rowspan="2">Livelihood Training</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                                </select>
                            </td>
                            <td>
                                <select name="attainment[]">
                                    <option value="" disabled selected>-- SELECT ATTAINMENT</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="High School">High School</option>
                                    <option value="College">College</option>
                                    <option value="Post-Graduate">Post-Graduate</option>
                                    <option value="Vocational">Vocational</option>
                                </select>
                            </td>
                            <td><input type="text" name="occupation[]"></td>
                            <td><input type="text" name="livelihood_training[]"></td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th colspan="2">Employment</th>
                            <th colspan="2">Est. Monthly Income</th>
                            <th colspan="2" rowspan="2">
                                <p style="cursor: help;" title="Indicate if PWD, OFW, Solo Parent, and/or IP.">
                                    Others
                                </p>
                            </th>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Cash</th>
                            <th>Kind</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="emp_status[]">
                                    <option value="" disabled selected>-- SELECT EMPLOYMENT STATUS</option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Contractual">Contractual</option>
                                    <option value="Self-Employed">Self-Employed</option>
                                    <option value="Unemployed">Unemployed</option>
                                    <option value="Others">Others</option>
                                </select>
                            </td>
                            <td>
                                <select name="emp_category[]">
                                    <option value="" disabled selected>-- SELECT EMPLOYMENT CATEGORY</option>
                                    <option value="Private">Private</option>
                                    <option value="Government">Government</option>
                                    <option value="Self-Employed">Self-Employed</option>
                                </select>
                            </td>
                            <td>₱&nbsp;<input type="number" min="0" name="income_cash[]"></td>
                            <td><input type="text" name="income_type[]"></td>
                            <td colspan="2">
                                <div class="indicate-others" style="display: flex;">
                                    <input type="checkbox" name="is_PWD[]" value="PWD">&nbsp;PWD&nbsp;
                                    <input type="checkbox" name="is_OFW[]" value="OFW">&nbsp;OFW&nbsp;
                                    <input type="checkbox" name="is_solo_parent[]" value="Solo Parent">&nbsp;Solo&nbsp;Parent&nbsp;
                                    <input type="checkbox" name="is_indigenous[]" value="IP">&nbsp;IP&nbsp;
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php } ?>