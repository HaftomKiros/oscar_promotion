<style type="text/css">
.prints {background-color: #31B404;color: #fff;}
.action {color: #fff;}
.dropdown-menu > li > a {color: #fff;}
.table-responsive {width: 100%;overflow-x: auto;-webkit-overflow-scrolling: touch;}
.table-responsive table {min-width: 1200px;white-space: nowrap;}
#loadingSpinner {text-align: center;padding: 30px;display: none;}

/* Incomplete profiles panel */
.incomplete-panel{background:#fff;border-radius:12px;border:2px solid #f59e0b;margin-bottom:24px;overflow:hidden;box-shadow:0 2px 12px rgba(245,158,11,0.15);}
.incomplete-panel-header{background:linear-gradient(90deg,#1a3a5c,#1e4976);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;}
.incomplete-panel-header h4{color:#fff;margin:0;font-size:15px;font-weight:700;display:flex;align-items:center;gap:8px;}
.incomplete-panel-header .badge-count{background:#f5a623;color:#1a3a5c;font-size:12px;font-weight:800;padding:3px 10px;border-radius:20px;}
.incomplete-table{width:100%;border-collapse:collapse;}
.incomplete-table th{background:#f8fafc;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;padding:10px 14px;border-bottom:1px solid #e2e8f0;white-space:nowrap;}
.incomplete-table td{padding:12px 14px;border-bottom:1px solid #f1f5f9;font-size:13px;color:#374151;vertical-align:middle;}
.incomplete-table tr:last-child td{border-bottom:none;}
.incomplete-table tr:hover td{background:#fefce8;}
.badge-incomplete{display:inline-flex;align-items:center;gap:5px;background:#fef3c7;border:1px solid #f59e0b;color:#92400e;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;}
.btn-call{display:inline-flex;align-items:center;gap:5px;background:#1a3a5c;color:#fff;border:none;padding:6px 14px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.2s;cursor:pointer;}
.btn-call:hover{background:#f5a623;color:#1a3a5c;}
.btn-call-complete{display:inline-flex;align-items:center;gap:5px;background:#10b981;color:#fff;border:none;padding:6px 14px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.2s;margin-left:6px;}
.btn-call-complete:hover{background:#059669;color:#fff;}
.no-incomplete{padding:24px;text-align:center;color:#94a3b8;font-size:14px;}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-users"></i></div>
        <div class="header-title">
            <h1>Candidate</h1>
            <small>Manage Candidate</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> Home</a></li>
                <li><a href="#">Candidate</a></li>
                <li class="active">Manage Candidate</li>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('add_candidate','create')->access()){ ?>
                        <a href="<?php echo base_url('Ccandidate'); ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> Add Candidate
                        </a>
                    <?php } ?>
                    
                    <!-- Export All Dropdown -->
                    <div class="btn-group m-b-5 m-r-2">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-file-excel-o"></i> Export All <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="background-color: #28a745;">
                            <li><a href="#" class="export-all-btn" data-sex="">All</a></li>
                            <li><a href="#" class="export-all-btn" data-sex="Male">Male Only</a></li>
                            <li><a href="#" class="export-all-btn" data-sex="Female">Female Only</a></li>
                        </ul>
                    </div>
                    
                    <!-- Woreda Filter and Export by Woreda -->
                    <select id="woredaFilter" class="btn btn-warning m-b-5 m-r-2" style="height:34px;">
                        <option value="">Select Woreda</option>
                        <?php if(!empty($woredas)){ ?>
                            <?php foreach($woredas as $woreda => $data){ ?>
                                <option value="<?php echo $woreda; ?>">
                                    <?php echo $woreda; ?> (<?php echo $data['total']; ?> - M:<?php echo $data['Male']; ?> F:<?php echo $data['Female']; ?>)
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    
                    <!-- Sex Filter -->
                    <select id="sexFilter" class="btn btn-info m-b-5 m-r-2" style="height:34px;">
                        <option value="">All Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    
                    <a href="#" id="exportByWoreda" class="btn btn-primary m-b-5 m-r-2">
                        <i class="fa fa-download"></i> Export by Woreda
                    </a>
                </div>
            </div>
        </div>

        <!-- ── Incomplete Profiles (Call Center) ── -->
        <?php if (!empty($incomplete_candidates)): ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="incomplete-panel">
                    <div class="incomplete-panel-header">
                        <h4>
                            📞 Incomplete Profiles — Call Center Queue
                            <span class="badge-count"><?php echo count($incomplete_candidates); ?> pending</span>
                        </h4>
                        <span style="color:rgba(255,255,255,0.6);font-size:12px;">These registered online — call to complete their profile</span>
                    </div>
                    <div class="table-responsive">
                        <table class="incomplete-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Seeker ID</th>
                                    <th>Full Name</th>
                                    <th>Sex</th>
                                    <th>Phone</th>
                                    <th>Education</th>
                                    <th>Experience</th>
                                    <th>Qualification</th>
                                    <th>Location</th>
                                    <th>Registered</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($incomplete_candidates as $i => $c): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><strong><?php echo $c['seeker_id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                                    <td><?php echo $c['sex']; ?></td>
                                    <td>
                                        <a href="tel:<?php echo $c['phone_number']; ?>" style="color:#1a3a5c;font-weight:600;">
                                            📱 <?php echo $c['phone_number']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $c['education_level']; ?></td>
                                    <td><?php echo $c['experience']; ?> yrs</td>
                                    <td><?php echo htmlspecialchars($c['qualification_skills']); ?></td>
                                    <td><?php echo htmlspecialchars($c['location']); ?></td>
                                    <td style="white-space:nowrap;color:#94a3b8;"><?php echo date('M j, H:i', strtotime($c['created_at'])); ?></td>
                                    <td style="white-space:nowrap;">
                                        <a href="<?php echo base_url('Ccandidate/edit/'.$c['id']); ?>" class="btn-call">
                                            ✏️ Complete Profile
                                        </a>
                                        <a href="<?php echo base_url('Ccandidate/mark_complete/'.$c['id']); ?>" class="btn-call-complete" onclick="return confirm('Mark this profile as complete?')">
                                            ✓ Mark Done
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <h4>Manage Candidate</h4>
                    </div>

                    <div class="panel-body">

                        <!-- SCROLLABLE WRAPPER -->
                        <div class="table-responsive">

                            <table id="candidateList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Seeker ID</th>
                                        <th>Full Name</th>
                                        <th>Sex</th>
                                        <th>Martial Status</th>
                                        <th>DOB (Ethiopian)</th>
                                        <th>Age</th>
                                        <th>Family Size</th>
                                        <th>HH Male</th>
                                        <th>HH Female</th>
                                        <th>Household Type</th>
                                        <th>Disability Status</th>
                                        <th>Disability Male</th>
                                        <th>Disability Female</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Woreda</th>
                                        <th>Tabia</th>
                                        <th>Education</th>
                                        <th>Field of Study</th>
                                        <th>GPA</th>
                                        <th>Qualification / Skills</th>
                                        <th>Graduated Year</th>
                                        <th>Experience</th>
                                        <th>Resume</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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
    // Initialize DataTable with server-side processing
    var table = $('#candidateList').DataTable({
        dom: 'Bfrtip',
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url('Ccandidate/get_candidates_datatable'); ?>',
            type: 'POST',
            data: function(d) {
                d.woreda = $('#woredaFilter').val();
                d.sex = $('#sexFilter').val();
            }
        },
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 },
            { data: 8 },
            { data: 9 },
            { data: 10 },
            { data: 11 },
            { data: 12 },
            { data: 13 },
            { data: 14 },
            { data: 15 },
            { data: 16 },
            { data: 17 },
            { data: 18 },
            { data: 19 },
            { data: 20 },
            { data: 21 },
            { data: 22 },
            { data: 23 },
            { data: 24 },
            { data: 25 },
            { data: 26 },
            { 
                data: 27,
                render: function(data, type, row) {
                    var statusLabels = {
                        0: 'Job Seeker',
                        1: 'Fetched',
                        2: 'Applied',
                        3: 'Shortlisted',
                        4: 'Interview',
                        5: 'Hired',
                        6: 'Rejected'
                    };
                    var labelClass = ['label-default', 'label-info', 'label-primary', 'label-warning', 'label-success', 'label-success', 'label-danger'];
                    var statusText = statusLabels[data] || 'Unknown';
                    var label = labelClass[data] || 'label-default';
                    return '<span class="label ' + label + '">' + statusText + '</span>';
                }
            },
            { 
                data: 0,
                render: function(data, type, row) {
                    var editUrl = '<?php echo base_url('Ccandidate/edit/'); ?>' + data;
                    var deleteUrl = '<?php echo base_url('Ccandidate/delete/'); ?>' + data;
                    var actions = '';
                    
                    <?php if($this->permission1->method('manage_candidate','update')->access()){ ?>
                    actions += '<a href="' + editUrl + '" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a> ';
                    <?php } ?>
                    
                    <?php if($this->permission1->method('manage_candidate','delete')->access()){ ?>
                    actions += '<a href="' + deleteUrl + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this?\');"><i class="fa fa-trash"></i></a>';
                    <?php } ?>
                    
                    return actions;
                },
                orderable: false,
                searchable: false
            }
        ],

        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Candidate List',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csvHtml5',
                title: 'Candidate List',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Candidate List',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                title: 'Candidate List',
                exportOptions: { columns: ':visible' }
            }
        ],

        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 500, -1],
            [10, 25, 50, 100, 500, "Show All"]
        ],

        ordering: true,
        searching: true
    });
    
    // Woreda filter change handler - reload datatable
    $('#woredaFilter').on('change', function() {
        table.ajax.reload();
    });
    
    // Sex filter change handler - reload datatable
    $('#sexFilter').on('change', function() {
        table.ajax.reload();
    });
    
    // Export by woreda button handler
    $('#exportByWoreda').on('click', function(e) {
        e.preventDefault();
        var woreda = $('#woredaFilter').val();
        var sex = $('#sexFilter').val();
        if (!woreda) {
            alert('Please select a woreda first');
            return;
        }
        var url = '<?php echo base_url('Ccandidate/export_candidates_by_woreda'); ?>?woreda=' + encodeURIComponent(woreda);
        if (sex) {
            url += '&sex=' + encodeURIComponent(sex);
        }
        window.location.href = url;
    });
    
    // Export All dropdown button handlers
    $('.export-all-btn').on('click', function(e) {
        e.preventDefault();
        var sex = $(this).data('sex');
        var url = '<?php echo base_url('Ccandidate/export_candidates_excel'); ?>';
        if (sex) {
            url += '?sex=' + encodeURIComponent(sex);
        }
        window.location.href = url;
    });
});
</script>
