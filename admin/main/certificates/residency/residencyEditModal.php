<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Edit Request</h3>
    <form id="editForm">
      <input type="hidden" id="editId">

      <label for="editPurpose">Purpose</label>
      <input type="text" id="editPurpose" required>

      <label for="editYears">Years of Residency</label>
      <input type="number" id="editYears" min="0">

      <label for="editMonths">Months of Residency</label>
      <input type="number" id="editMonths" min="6" max="11">

      <button type="submit" class="btn-save">Save Changes</button>
    </form>
  </div>
</div>
<style>
    /* Modal Background */
        .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        }

        /* Modal Content Box */
        .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        width: 400px;
        border-radius: 8px;
        position: relative;
        }

        /* Close Button */
        .modal-content .close {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        }

        /* Input Fields */
        #editForm input[type="text"],
        #editForm input[type="number"] {
        width: 100%;
        padding: 8px;
        margin: 6px 0 12px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        }

        /* Save Button */
        .btn-save {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        }

</style>