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
            <h1><?php echo display('field_of_study') ?></h1>
            <small><?php echo display('manage_field_of_study') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('field_of_study') ?></a></li>
                <li class="active"><?php echo display('manage_field_of_study') ?></li>
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
                    <?php if($this->permission1->method('add_field_of_study','create')->access()){ ?>
                        <a href="<?php echo base_url('Cfield_of_study') ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_field_of_study') ?>
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
                        <div class="panel-title"><h4><?php echo display('manage_field_of_study') ?></h4></div>
                    </div>

                    <div class="panel-body panel-body-scroll">

                        <table id="fieldList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th>ID</th>
                                    <th><?php echo display('field_of_study') ?></th>
                                    <th><?php echo display('status') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                $sl = 1;

                                if (!empty($fields_of_study)) {
                                    foreach ($fields_of_study as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $sl++; ?></td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo html_escape($row['field']); ?></td>

                                        <td>
                                            <?php if ($row['status'] == 1) { ?>
                                                <span class="label label-success">Active</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Inactive</span>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php if ($this->permission1->method('manage_field_of_study','update')->access()) { ?>
                                                <a href="<?php echo base_url('Cfield_of_study/edit/'.$row['id']); ?>" 
                                                   class="btn btn-info btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php } ?>

                                            <?php if ($this->permission1->method('manage_field_of_study','delete')->access()) { ?>
                                                <a href="<?php echo base_url('Cfield_of_study/delete/'.$row['id']); ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this field of study?\n\nWarning: All related data may be lost. This action cannot be undone.');">
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

    $('#fieldList').DataTable({
        dom: 'Bfrtip',

        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Fields of Study List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'csvHtml5',
                title: 'Fields of Study List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'portrait',
                pageSize: 'A4',
                title: 'Fields of Study List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'print',
                title: 'Fields of Study List',
                exportOptions: { columns: [0,1,2,3] }
            }
        ],

        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,

        // Best proper empty message
        language: {
            emptyTable: "No fields of study found."
        }
    });

});
</script>
