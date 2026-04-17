<style type="text/css">
    .prints { background-color: #31B404; color: #fff; }
    .action { color: #fff; }
    .dropdown-menu > li > a { color: #fff; }
    @media (max-width: 768px) {
        .panel-body-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; }
        .panel-body-scroll table { min-width: 900px; white-space: nowrap; }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1>Applied Report</h1>
            <small>Show Applied Report</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> Home</a></li>
                <li><a href="#">Applied Report</a></li>
                <li class="active">Show Applied Report</li>
            </ol>
        </div>
    </section>

    <section class="content">
        <?php if($message = $this->session->userdata('message')) { ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button><?php echo $message; ?>
        </div>
        <?php $this->session->unset_userdata('message'); } ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Applied Report</h4>
                            <a href="<?php echo base_url('Admin_dashboard/export_report_all/applied'); ?>" class="btn btn-success btn-sm" style="margin-top:8px;">
                                <i class="fa fa-download"></i> Export All Candidates (xlsx)
                            </a>
                        </div>
                    </div>
                    <div class="panel-body panel-body-scroll">
                        <table id="appliedList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Company ID</th>
                                    <th>Company Name</th>
                                    <th>Total Applied</th>
                                    <th>Total Jobs Posted</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $sl = 1; if(!empty($applied_report)) { foreach($applied_report as $data) { ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo html_escape($data['id']); ?></td>
                                    <td><?php echo html_escape($data['company_name']); ?></td>
                                    <td><a href="<?php echo base_url('Admin_dashboard/applied_by_company/'.$data['id']) ?>"><?php echo html_escape($data['total_applied']); ?></a></td>
                                    <td><a href="<?php echo base_url('Admin_dashboard/applied_by_company/'.$data['id']) ?>"><?php echo html_escape($data['total_jobs_posted']); ?></a></td>
                                </tr>
                            <?php } } ?>
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
    $('#appliedList').DataTable({
        dom: 'Bfrtip',
        buttons: [{extend: 'excelHtml5', title: 'Applied Report', exportOptions: {columns: [0,1,2,3,4]}},
                  {extend: 'csvHtml5', title: 'Applied Report', exportOptions: {columns: [0,1,2,3,4]}},
                  {extend: 'pdfHtml5', orientation: 'portrait', title: 'Applied Report', exportOptions: {columns: [0,1,2,3,4]}},
                  {extend: 'print', title: 'Applied Report', exportOptions: {columns: [0,1,2,3,4]}}],
        pageLength: 10, lengthMenu: [5,10,25,50,100], ordering: true, searching: true,
        language: { emptyTable: "No applied candidates found" }
    });
});
</script>
