<?php include '../../baseURL.php';?>

<form id="residency-form" method="POST" action="<?= BASE_URL ?>pages/services/submit_residency_request.php">
  <div id="modal-step1" class="modal" hidden aria-hidden="true" role="dialog" aria-labelledby="modalTitle1" tabindex="-1">
    <div class="modal-content" role="document">
      <h2 id="modalTitle1">Request Certificate of Residency</h2>
      
      <input type="hidden" name="form_id" value="residency-form" />
      
      <label>First Name
        <input type="text" name="firstname" required autocomplete="given-name" />
      </label>
      <label>Middle Initial
        <input type="text" name="middle_initial" maxlength="1" autocomplete="additional-name" />
      </label>
      <label>Last Name
        <input type="text" name="lastname" required autocomplete="family-name" />
      </label>
      <label>Suffix (Optional)
        <input type="text" name="suffix" autocomplete="off" />
      </label>
      <label>Age
        <input type="number" name="age" required min="0" />
      </label>
      <label>Street
        <input type="text" name="street" required autocomplete="address-line1" />
      </label>
      <label>Barangay
        <input type="text" name="barangay" required autocomplete="address-level3" />
      </label>
      <fieldset>
        <legend>Gender</legend>
        <label><input type="radio" name="gender" value="Male" required /> Male</label>
        <label><input type="radio" name="gender" value="Female" /> Female</label>
      </fieldset>

      <label>Residency Duration
        <div style="display:flex; gap:8px; align-items:center;">
          <input type="number" name="years_residency" required min="1" step="1" placeholder="Enter number" style="flex:1;" />
          <select name="duration_unit" required>
            <option value="years">Years</option>
            <option value="months">Months</option>
          </select>
        </div>
      </label>

      <label>Purpose
        <textarea name="purpose" required rows="3"></textarea>
      </label>

      <button type="submit" id="step1-submit">Next</button>
    </div>
    <button class="modal-close" id="close-step1" aria-label="Close">&times;</button>
  </div>

  <!-- Confirm modal -->
  <div id="modal-step2" class="modal" hidden aria-hidden="true" role="dialog" aria-labelledby="modalTitle2" tabindex="-1">
    <div class="modal-content" role="document">
      <h2 id="modalTitle2">Confirm Your Information</h2>
      <div id="review-info"></div>
      <div class="confirm-buttons">
        <button type="button" id="back-edit">Edit</button>
        <!-- This is the actual submit button -->
        <button type="submit" id="final-submit">Submit</button>
      </div>
    </div>
    <button class="modal-close" id="close-step2" aria-label="Close">&times;</button>
  </div>
</form>
