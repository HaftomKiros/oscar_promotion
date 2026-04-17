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

/* Horizontal scrolling wrapper */
.panel-body-scroll {
    overflow-x: auto;
    width: 100%;
    -webkit-overflow-scrolling: touch;
}

/* Ensure table behaves correctly */
.panel-body-scroll table {
    width: 100%;
    border-collapse: collapse;
}

/* Scrollable long text inside cells */
.table-scrollable-cell {
    max-width: 250px;
    max-height: 120px;
    overflow-y: auto;
    overflow-x: hidden;
    display: block;
    white-space: normal;
    padding-right: 5px;
}

/* DataTables horizontal scroll fix */
.dataTables_wrapper .dataTables_scroll {
    overflow: auto;
}

/* Mobile layout */
@media (max-width: 768px) {
    .panel-body-scroll table {
        min-width: 1400px;
        white-space: nowrap;
    }
}
</style>


<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('employer'); ?></h1>
            <small><?php echo display('manage_employer'); ?></small>

            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="#"><?php echo display('employer'); ?></a></li>
                <li class="active"><?php echo display('manage_employer'); ?></li>
            </ol>
        </div>
    </section>


    <section class="content">

        <!-- Alerts -->
        <?php
        if ($message = $this->session->userdata('message')) {
            echo '<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$message.'
                  </div>';
            $this->session->unset_userdata('message');
        }

        if ($error_message = $this->session->userdata('error_message')) {
            echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$error_message.'
                  </div>';
            $this->session->unset_userdata('error_message');
        }
        ?>


        <!-- Add Employer Button -->
        <div class="row">
            <div class="col-sm-12">
                <?php if ($this->permission1->method('add_employer','create')->access()) { ?>
                    <a href="<?php echo base_url('Cemployer'); ?>" class="btn btn-info m-b-5">
                        <i class="ti-plus"></i> <?php echo display('add_employer'); ?>
                    </a>
                <?php } ?>
            </div>
        </div>


        <!-- Employer Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_employer'); ?></h4>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <table id="employerList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?php echo display('sl'); ?></th>
                                <th>ID</th>
                                <th><?php echo display('company_name'); ?></th>
                                <th><?php echo display('sector'); ?></th>
                                <th><?php echo display('no_emp'); ?></th>
                                <th><?php echo display('company_size'); ?></th>
                                <th><?php echo display('operation_since'); ?></th>
                                <th><?php echo display('description_services_products'); ?></th>
                                <th><?php echo display('mission_vision_statement'); ?></th>
                                <th><?php echo display('phone_number'); ?></th>
                                <th><?php echo display('email'); ?></th>
                                <th><?php echo display('website'); ?></th>
                                <th><?php echo display('address'); ?></th>
                                <th><?php echo display('status'); ?></th>
                                <th><?php echo display('action'); ?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php 
                            $sl = 1;
                            if (!empty($employer_list)) {
                                foreach ($employer_list as $emp) { ?>
                                    <tr>
                                        <td><?php echo $sl++; ?></td>
                                        <td><?php echo $emp['id']; ?></td>

                                        <td><div class="table-scrollable-cell"><?php echo html_escape($emp['company_name']); ?></div></td>
                                        <td><?php echo html_escape($emp['sector']); ?></td>
                                        <td><?php echo html_escape($emp['no_emp']); ?></td>
                                        <td><?php echo html_escape($emp['company_size']); ?></td>
                                        <td><?php echo html_escape($emp['operation_since']); ?></td>

                                        <td><div class="table-scrollable-cell"><?php echo html_escape($emp['description_services_products']); ?></div></td>

                                        <td><div class="table-scrollable-cell"><?php echo html_escape($emp['mission_vision_statement']); ?></div></td>

                                        <td><?php echo html_escape($emp['phone_number']); ?></td>
                                        <td><?php echo html_escape($emp['email']); ?></td>

                                        <td><div class="table-scrollable-cell"><?php echo html_escape($emp['website']); ?></div></td>

                                        <td><div class="table-scrollable-cell"><?php echo html_escape($emp['address']); ?></div></td>

                                        <td>
                                            <?php if ($emp['status'] == 1) { ?>
                                                <span class="label label-success"><?php echo display('active'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-danger"><?php echo display('inactive'); ?></span>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php if ($this->permission1->method('manage_employer','update')->access()) { ?>
                                                <a href="<?php echo base_url('Cemployer/edit/'.$emp['id']); ?>"
                                                   class="btn btn-info btn-sm"
                                                   title="<?php echo display('edit_employer'); ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php } ?>

                                            <?php if ($this->permission1->method('manage_employer','delete')->access()) { ?>
                                                <a href="<?php echo base_url('Cemployer/delete/'.$emp['id']); ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this employer?');"
                                                   title="Delete Employer">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>


<!-- DATATABLES SCRIPT -->
<script>
$(document).ready(function() {

    $('#employerList').DataTable({
        dom: 'Bfrtip',

        scrollX: true,  // <-- ENABLE HORIZONTAL SCROLL

        language: {
            emptyTable: "<?php echo display('no_records_found'); ?>"
        },

        buttons: [
            { extend: 'excelHtml5', title: 'Employer List', exportOptions: { columns: ':visible' }},
            { extend: 'csvHtml5', title: 'Employer List', exportOptions: { columns: ':visible' }},
            { extend: 'pdfHtml5', title: 'Employer List', orientation: 'landscape', pageSize: 'A3',
              exportOptions: { columns: ':visible' }},
            { extend: 'print', title: 'Employer List', exportOptions: { columns: ':visible' }}
        ],

        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true
    });

});
</script>
