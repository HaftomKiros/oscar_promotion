<style>
.table-responsive {
    width: 100%;
    overflow-x: auto;
}
#loadingSpinner {
    display: none;
    text-align: center;
    padding: 25px;
}
</style>

<div class="content-wrapper">

    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-users"></i></div>
        <div class="header-title">
            <h1>Matched Candidates</h1>
            <small>For Job: <?php echo $job['job_title']; ?></small><br>
            <small>Company: <b><?php echo $job['company_name']; ?></b></small>
              <!-- Embed the job_id and company_id into hidden data attributes -->
            <div id="jobDetails" data-job-id="<?php echo $job['id']; ?>" data-company-id="<?php echo $job['company_id']; ?>"></div>
        </div>
    </section>

    <section class="content">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <h4>Candidates Matching Job Criteria</h4>
            </div>

            <div class="panel-body">

                <div id="loadingSpinner">
                    <i class="fa fa-spinner fa-spin" style="font-size:35px"></i>
                    <p><b>Loading...</b></p>
                </div>

                <div class="table-responsive">
                    <table id="candidateList" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Seeker ID</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Woreda</th>
                                <th>Education</th>
                                <th>Field</th>
                                <th>GPA</th>
                                <th>Experience</th>
                                <th>Skills</th>
                                <th>Graduated Year</th>
                                <th>Resume</th>
                                <th>Status</th>
                                <th>Change Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                        $sl = 1;
                        if (!empty($candidates)) {
                            foreach ($candidates as $c) { ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $c['seeker_id']; ?></td>
                                    <td><?php echo $c['full_name']; ?></td>
                                    <td><?php echo $c['sex']; ?></td>
                                    <td><?php echo $c['age']; ?></td>
                                    <td><?php echo $c['phone_number']; ?></td>
                                    <td><?php echo $c['email']; ?></td>
                                    <td><?php echo $c['location_name']; ?></td>
                                    <td><?php echo $c['woreda']; ?></td>
                                    <td><?php echo $c['education_name']; ?></td>
                                    <td><?php echo $c['field_name']; ?></td>
                                    <td><?php echo $c['gpa']; ?></td>
                                    <td><?php echo $c['experience']; ?> Years</td>
                                    <td><?php echo $c['skills']; ?></td>
                                    <td><?php echo $c['graduated_year']; ?></td>
                                    <td>
                                        <?php if ($c['resume']) { ?>
                                            <a href="<?php echo base_url('Ccandidate/download_resume/'.$c['id']); ?>"
                                               class="btn btn-success btn-sm">Download</a>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php
                                            $labels = [
                                                0 => ['Job Seeker','label-default'],
                                                1 => ['Fetched','label-info'],
                                                2 => ['Applied','label-primary'],
                                                3 => ['Shortlisted','label-warning'],
                                                4 => ['Interview','label-success'],
                                                5 => ['Hired','label-success'],
                                                6 => ['Rejected','label-danger']
                                            ];
                                            $s = $c['status'];
                                            echo '<span class="label '.$labels[$s][1].'">'.$labels[$s][0].'</span>';
                                        ?>
                                    </td>

                                    <td>
                                        <!-- Dropdown to change status -->
                                        <select class="form-control change-status" data-candidate-id="<?php echo $c['id']; ?>">
                                            <option value="0" selected>Job Seeker</option>
                                            <option value="1" <?php echo $c['status'] == 1 ? 'selected' : ''; ?>>Fetched</option>
                                            <option value="2" <?php echo $c['status'] == 2 ? 'selected' : ''; ?>>Applied</option>
                                            <option value="3" <?php echo $c['status'] == 3 ? 'selected' : ''; ?>>Shortlisted</option>
                                            <option value="4" <?php echo $c['status'] == 4 ? 'selected' : ''; ?>>Interview</option>
                                            <option value="5" <?php echo $c['status'] == 5 ? 'selected' : ''; ?>>Hired</option>
                                            <option value="6" <?php echo $c['status'] == 6 ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </td>
                                </tr>
                        <?php }} ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </section>
</div>

<script>
$(document).ready(function () {

    $("#loadingSpinner").show();

    $('#candidateList').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', title: 'Matched Candidates' },
            { extend: 'csvHtml5',   title: 'Matched Candidates' },
            { extend: 'pdfHtml5',   title: 'Matched Candidates', pageSize: 'A4' },
            { extend: 'print',      title: 'Matched Candidates' }
        ],
        "pageLength": 5000,  // This sets the number of records per page to 5000
        initComplete: function(){
            $("#loadingSpinner").fadeOut();
        }
    });

    // Handle status change
    $('.change-status').on('change', function() {
    var candidateId = $(this).data('candidate-id');
    var newStatus = $(this).val();
    var jobId = '<?php echo $job["id"]; ?>';  // Assuming you have job_id available
    var companyId = '<?php echo $job["company_id"]; ?>'; // Assuming you have company_id available

    $.ajax({
        url: '<?php echo base_url("Ccandidate/update_status"); ?>',
        method: 'POST',
        data: {
            candidate_id: candidateId,
            status: newStatus,
            job_id: jobId,
            company_id: companyId
        },
        success: function(response) {
            // Parse the response to JSON
            var res = JSON.parse(response); // Ensure the response is parsed as JSON

            // Check the status and show the appropriate message
            if (res.status == 'success') {
                alert('Status updated successfully.');
            } else {
                alert('Failed to update status: ' + res.message || 'Unknown error.');
            }
        },
        error: function(xhr, status, error) {
            // Handle any error from the AJAX request
            alert('AJAX request failed: ' + error);
        }
    });
});


});
</script>
