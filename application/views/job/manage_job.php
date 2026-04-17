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

/* SCROLLABLE TABLE */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table-responsive table {
    min-width: 1500px;
    white-space: nowrap;
}

/* LOADING SPINNER */
#loadingSpinner {
    text-align: center;
    padding: 30px;
    display: none;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-portfolio"></i></div>
        <div class="header-title">
            <h1>Jobs</h1>
            <small>Manage Jobs</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> Home</a></li>
                <li><a href="#">Jobs</a></li>
                <li class="active">Manage Jobs</li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Messages -->
        <?php
        if ($message = $this->session->userdata('message')) {
            echo '<div class="alert alert-info alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.$message.'</div>';
            $this->session->unset_userdata('message');
        }
        if ($error_message = $this->session->userdata('error_message')) {
            echo '<div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.$error_message.'</div>';
            $this->session->unset_userdata('error_message');
        }
        ?>

        <!-- Add button -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('add_job','create')->access()){ ?>
                        <a href="<?php echo base_url('Cjob'); ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> Add Job
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <h4>Manage Jobs</h4>
                    </div>

                    <div class="panel-body">

                        <!-- LOADING -->
                        <div id="loadingSpinner">
                            <i class="fa fa-spinner fa-spin" style="font-size:40px;"></i>
                            <p><b>Loading data, please wait...</b></p>
                        </div>

                        <div class="table-responsive">

                            <table id="jobList" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company</th>
                                        <th>Title</th>
                                        <th>Sex</th>
                                        <th>Age</th>
                                        <th>Location</th>
                                        <th>Education</th>
                                        <th>Field</th>
                                        <th>GPA</th>
                                        <th>Experience</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php 
                                if (!empty($jobs)) {
                                    foreach ($jobs as $job) {
                                ?>

                                <tr>
                                    <td><?= $job['id']; ?></td>

                                    <!-- Company Name -->
                                    <td><?= $job['company_name']; ?></td>

                                    <td><?= $job['job_title']; ?></td>
                                    <td><?= $job['sex']; ?></td>

                                    <td><?= $job['age_operator'].' '.$job['age_value']; ?></td>

                                    <!-- LOCATIONS (NAMES) -->
                                    <td><?= $job['location_names']; ?></td>

                                    <!-- EDUCATION LEVEL NAME -->
                                    <td><?= $job['education_name']; ?></td>

                                    <!-- FIELD OF STUDY NAMES -->
                                    <td><?= $job['field_names']; ?></td>

                                    <td><?= $job['gpa_operator'].' '.$job['gpa_value']; ?></td>

                                    <td><?= $job['experience_operator'].' '.$job['experience_value']; ?> Years</td>

                                    <td><?= $job['job_start_date']; ?></td>
                                    <td><?= $job['job_end_date']; ?></td>

                                    <td>
                                        <?php if ($this->permission1->method('manage_job','update')->access()) { ?>
                                            <a href="<?= base_url('Cjob/edit/'.$job['id']); ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php } ?>

                                        <?php if ($this->permission1->method('manage_job','delete')->access()) { ?>
                                            <a href="<?= base_url('Cjob/delete/'.$job['id']); ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this job?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php } ?>
                                      <?php if ($this->permission1->method('filter_candidates','read')->access()) { ?>
    <a href="<?= base_url('Cjob/matching_candidates/'.$job['id']); ?>" 
       class="btn btn-success btn-sm"
       title="Matching Candidates">
        <i class="fa fa-users"></i>
    </a>
<?php } ?>


                                    </td>
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
        </div>

    </section>
</div>

<script>
$(document).ready(function() {

    $("#loadingSpinner").show();

    $('#jobList').DataTable({
        dom: 'Bfrtip',
        processing: true,

        buttons: [
            { extend: 'excelHtml5', title: 'Job List', exportOptions: { columns: ':visible' }},
            { extend: 'csvHtml5', title: 'Job List', exportOptions: { columns: ':visible' }},
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', title: 'Job List',
              exportOptions: { columns: ':visible' }},
            { extend: 'print', title: 'Job List', exportOptions: { columns: ':visible' }}
        ],

        columnDefs: [
            { targets: [12], exportable: false }
        ],

        pageLength: 5000,
        lengthMenu: [
            [10, 25, 50, 100, 500, 1000, 5000, -1],
            [10, 25, 50, 100, 500, 1000, 5000, "Show All"]
        ],

        ordering: true,
        searching: true,

        initComplete: function () {
            $("#loadingSpinner").fadeOut();
        }
    });
});
</script>
