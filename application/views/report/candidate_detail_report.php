<style type="text/css">
    .prints {
        background-color: #31B404;
        color: #fff;
    }
    .action {
        color: #fff;
    }
    .dropdown-menu > li > a {
        color: #fff;
    }
    @media (max-width: 768px) {
        .panel-body-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }
        .panel-body-scroll table {
            min-width: 900px;
            white-space: nowrap;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo $title; ?></h1>
            <small>Candidate Details</small>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('Admin_dashboard') ?>"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="<?php echo base_url('Admin_dashboard/all_report') ?>"><?php echo display('report'); ?></a></li>
                <li class="active"><?php echo $title; ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Back Button -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="javascript:history.back()" class="btn btn-info m-b-5 m-r-2">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Job Filter -->
        <?php if(!empty($jobs)): ?>
        <div class="row" style="margin-top: 15px;">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Filter by Job</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form method="get" action="<?php echo base_url('Admin_dashboard/' . $report_type . '_by_company/' . $company_id) ?>">
                            <div class="row">
                                <div class="col-sm-4">
                                    <select name="job_id" class="form-control">
                                        <option value="">All Jobs</option>
                                        <?php foreach($jobs as $job): ?>
                                            <option value="<?php echo $job['id']; ?>" <?php echo ($job_id == $job['id']) ? 'selected' : ''; ?>>
                                                <?php echo html_escape($job['job_title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Candidates Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo $title; ?></h4>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <table id="candidateList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sno'); ?></th>
                                    <th>Seeker ID</th>
                                    <th>Candidate Name</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Education</th>
                                    <th>Experience</th>
                                    <th>Qualification</th>
                                    <th>Location</th>
                                    <th>Woreda</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php 
    $sl = 1;
    $status_labels = [
        1 => 'Pending',
        2 => 'Screening',
        3 => 'Shortlisted',
        4 => 'Interviewed',
        5 => 'Hired',
        6 => 'Rejected'
    ];
    if (!empty($candidates)) {
        foreach ($candidates as $data) {
    ?>
        <tr>
            <td><?php echo $sl++; ?></td>
            <td><?php echo html_escape($data['seeker_id'] ?? ''); ?></td>
            <td><?php echo html_escape($data['full_name']); ?></td>
            <td><?php echo html_escape($data['sex'] ?? ''); ?></td>
            <td><?php echo html_escape($data['age'] ?? ''); ?></td>
            <td><?php echo html_escape($data['phone_number']); ?></td>
            <td><?php echo html_escape($data['email']); ?></td>
            <td><?php echo html_escape($data['education_level'] ?? ''); ?></td>
            <td><?php echo html_escape($data['experience'] ?? ''); ?></td>
            <td><?php echo html_escape($data['qualification_skills'] ?? ''); ?></td>
            <td><?php echo html_escape($data['location_text'] ?? ''); ?></td>
            <td><?php echo html_escape($data['woreda'] ?? ''); ?></td>
            <td><?php echo html_escape($data['job_title']); ?></td>
            <td><?php echo isset($status_labels[$data['application_status']]) ? $status_labels[$data['application_status']] : 'Unknown'; ?></td>
        </tr>
    <?php 
        }
    }
    ?>
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
$(document).ready(function() {
    $('#candidateList').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: '<?php echo $title; ?>',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csvHtml5',
                title: '<?php echo $title; ?>',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                title: '<?php echo $title; ?>',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                title: '<?php echo $title; ?>',
                exportOptions: { columns: ':visible' }
            }
        ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        language: {
            emptyTable: "No candidates found"
        }
    });
});
</script>
