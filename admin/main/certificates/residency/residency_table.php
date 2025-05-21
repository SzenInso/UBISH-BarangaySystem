<div class="certificate-table-container">
    <h2>Certificate of Residency Requests</h2>

    <h3>Pending Requests</h3> </br>

    <input type="input" id="search-input" placeholder="Search requests..." class="search-bar">
    <div class="table-wrapper">
        <table class="cert-table" id="pending-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Years of Residency</th>
                    <th>Purpose</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="residency-requests-tbody">
                <tr><td colspan="7" style="text-align:center;">Loading...</td></tr>
            </tbody>
        </table>
    </div>

    <h3>Approved Requests</h3>
    <div class="table-wrapper">
        <table class="cert-table" id="approved-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Years of Residency</th>
                    <th>Purpose</th>
                    <th>Actions</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody id="approved-requests-tbody">
                <tr><td colspan="7" style="text-align:center;">No approved requests.</td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php include 'residency_modal.php'; ?>
<script src="<?= BASE_URL ?>admin/main/certificates/residency/residency_modal.js"></script>
<script src="<?= BASE_URL ?>admin/main/certificates/residency/residency_table.js"></script>

<script src="<?= BASE_URL ?>assets/js/sweetalert2.js"></script>
<style>
.search-bar {
  width: 300px;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 25px;
  font-size: 14px;
  transition: all 0.3s ease;
  outline: none;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.search-bar:focus {
  border-color: #28a745; 
  box-shadow: 0 0 5px rgba(40, 167, 69, 0.4); 
}

</style>

