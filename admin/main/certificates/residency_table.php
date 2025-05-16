<div class="certificate-table-container">
    <h2>Certificate of Residency Requests</h2>

    <h3>Pending Requests</h3> </br>

    <input type="text" id="search-input" placeholder="Search pending requests..." class="search-bar">
    <div class="table-wrapper">
        <table class="cert-table" id="pending-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Age</th>
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
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Years of Residency</th>
                    <th>Purpose</th>
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

<script src="residency_table.js"></script>
<script src="<?= BASE_URL ?>assets/js/sweetalert2.js"></script>
<script src="residency_modal.js"></script>
