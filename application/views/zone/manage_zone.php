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

/* Make table scrollable on small devices */
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
            <h1><?php echo display('zone'); ?></h1>
            <small><?php echo display('manage_zone'); ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="#"><?php echo display('zone'); ?></a></li>
                <li class="active"><?php echo display('manage_zone'); ?></li>
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

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('add_zone','create')->access()){ ?>
                        <a href="<?php echo base_url('Czone'); ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_zone'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Manage Zone Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_zone'); ?></h4>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <table id="zoneList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl'); ?></th>
                                    <th><?php echo display('zone_id'); ?></th>
                                    <th><?php echo display('zone_name'); ?></th>
                                    <th><?php echo display('status'); ?></th>
                                    <th><?php echo display('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $sl = 1;
                            if (!empty($zones)) {
                                foreach ($zones as $zone) {
                            ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $zone['id']; ?></td>
                                    <td><?php echo html_escape($zone['zone_name']); ?></td>

                                    <td>
                                        <?php if ($zone['status'] == 1) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php if ($this->permission1->method('manage_zone', 'update')->access()) { ?>
                                            <a href="<?php echo base_url('Czone/edit/'.$zone['id']); ?>" class="btn btn-info btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->permission1->method('manage_zone', 'delete')->access()) { ?>
                                            <a href="<?php echo base_url('Czone/delete/'.$zone['id']); ?>"
                                               class="btn btn-danger btn-sm"
                                               title="Delete"
                                               onclick="return confirmDelete(this);">
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
    $('#zoneList').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Zone List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'csvHtml5',
                title: 'Zone List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'portrait',
                title: 'Zone List',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'print',
                title: 'Zone List',
                exportOptions: { columns: [0,1,2,3] }
            }
        ],
        pageLength: 10,
        lengthMenu: [5,10,25,50,100],
        ordering: true,
        searching: true,

        language: {
            emptyTable: "No zones found"
        }
    });
});

function confirmDelete(btn) {
    if (confirm("⚠ This zone may have related records.\nDeleting it could remove linked data.\n\nAre you sure?")) {
        btn.onclick = null;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        return true;
    }
    return false;
}
</script>
