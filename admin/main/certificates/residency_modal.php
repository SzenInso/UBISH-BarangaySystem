<div id="viewModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span id="modalCloseBtn" class="close-btn">&times;</span>
    <h3>Residency Request Details</h3>
    <form id="editRequestForm">
      <input type="hidden" name="request_id" id="modal_request_id">

      <label>First Name:</label>
      <input type="text" name="firstname" id="modal_firstname" required>

      <label>Middle Initial:</label>
      <input type="text" name="middle_initial" id="modal_middle_initial" maxlength="1">

      <label>Last Name:</label>
      <input type="text" name="lastname" id="modal_lastname" required>

      <label>Suffix:</label>
      <input type="text" name="suffix" id="modal_suffix">

      <label>Age:</label>
      <input type="number" name="age" id="modal_age" required>

      <label>Street:</label>
      <input type="text" name="street" id="modal_street" required>

      <label>Barangay:</label>
      <input type="text" name="barangay" id="modal_barangay" required>

      <label>Gender:</label>
      <select name="gender" id="modal_gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label>Years of Residency:</label>
      <input type="number" name="years_residency" id="modal_years_residency" required>

      <label>Purpose:</label>
      <textarea name="purpose" id="modal_purpose" required></textarea>

      <div style="margin-top:1rem;">
        <button type="submit" name="action" value="approve" class="action-btn approve">Approve</button>
        <button type="submit" name="action" value="reject" class="action-btn deny">Deny</button>
      </div>
    </form>
  </div>
</div>
