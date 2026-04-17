<style type="text/css">
   ="text/css">
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
            <h1><?php echo display('employment_type') ?></h1>
            <small><?php echo display('manage_employment_type') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('employment_type') ?></a></li>
                <li class="active"><?php echo display('manage_employment_type') ?></li>
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

        <!-- Add Button -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('add_emp_type','create')->access()){ ?>
                        <a href="<?php echo base_url('Cemployment') ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_employment_type') ?>
                        </a>
                    <?php } ?>
                    
                    <?php if($this->permission1->method('manage_sal_range','read')->access()){ ?>
                        <a href="<?php echo base_url('Cemployment/manage_sal_range') ?>" class="btn btn-primary m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_salary_range') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Manage Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title"><h4><?php echo display('manage_employment_type') ?></h4></div>
                    </div>

                    <div class="panel-body panel-body-scroll">

                        <table id="empTypeList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th>ID</th>
                                    <th><?php echo display('employment_type') ?></th>
                                    <th><?php echo display('status') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                $sl = 1;
                                if (!empty($emp_types)) {
                                    foreach ($emp_types as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $sl++; ?></td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo html_escape($row['emp_name']); ?></td>

                                        <td>
                                            <?php if ($row['status'] == 1) { ?>
                                                <span class="label label-success">Active</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Inactive</span>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php if ($this->permission1->method('manage_emp_type','update')->access()) { ?>
                                                <a href="<?php echo base_url('Cemployment/edit_emp_type/'.$row['id']); ?>" 
                                                   class="btn btn-info btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php } ?>

                                            <?php if ($this->permission1->method('manage_emp_type','delete')->access()) { ?>
                                                <a href="<?php echo base_url('Cemployment/delete_emp_type/'.$row['id']); ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this employment type?\n\nWarning: All related data will no longer be accessible. This action cannot be undone.');">
                                                    <i class="fa fa-trash"></i>
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

    </section>
</div>

<script>
$(document).ready(function() {
    $('#empTypeList').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: 'Employment Type List',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-text"></i>',
                titleAttr: 'CSV',
                title: 'Employment Type List',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: 'Employment Type List',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: 'Employment Type List',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ]
    });
});
</script>
