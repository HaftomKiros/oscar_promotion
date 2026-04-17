<!-- Edit Candidate Start -->
<style>
/* ── Call Center Checklist ── */
.cc-panel{border-radius:12px;margin-bottom:24px;overflow:hidden;border:2px solid #1a3a5c;}
.cc-panel-header{background:linear-gradient(90deg,#1a3a5c,#1e4976);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.cc-panel-header h4{color:#fff;margin:0;font-size:15px;font-weight:700;}
.cc-panel-header .cc-phone{color:#f5a623;font-size:16px;font-weight:800;letter-spacing:0.5px;}
.cc-body{background:#fff;padding:18px 20px;}
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;}
.cc-item{display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:13px;font-weight:500;}
.cc-item.filled{background:#f0fdf4;border:1px solid #86efac;color:#166534;}
.cc-item.empty{background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;}
.cc-item.partial{background:#fffbeb;border:1px solid #fcd34d;color:#92400e;}
.cc-item .cc-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.cc-item.filled .cc-dot{background:#22c55e;}
.cc-item.empty .cc-dot{background:#ef4444;}
.cc-item.partial .cc-dot{background:#f59e0b;}
.cc-summary{display:flex;align-items:center;gap:16px;margin-top:14px;padding-top:14px;border-top:1px solid #e2e8f0;flex-wrap:wrap;}
.cc-summary-item{font-size:12px;font-weight:600;display:flex;align-items:center;gap:5px;}
.cc-progress{flex:1;min-width:120px;height:8px;background:#e2e8f0;border-radius:20px;overflow:hidden;}
.cc-progress-bar{height:100%;border-radius:20px;background:linear-gradient(90deg,#22c55e,#16a34a);transition:width 0.5s ease;}

/* ── Required field highlight ── */
.field-required label::after{content:' *';color:#ef4444;font-weight:700;}
.field-empty-highlight .form-control,
.field-empty-highlight select.form-control{
    border:2px solid #ef4444 !important;
    background:#fff5f5 !important;
}
.field-empty-highlight .form-group-label{color:#ef4444 !important;font-weight:700;}
.field-filled-highlight .form-control,
.field-filled-highlight select.form-control{
    border:2px solid #22c55e !important;
    background:#f0fdf4 !important;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-user"></i></div>
        <div class="header-title">
            <h1><?php echo display('edit_candidate'); ?></h1>
            <small><?php echo display('update_candidate_information'); ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="#"><?php echo display('candidate'); ?></a></li>
                <li class="active"><?php echo display('edit_candidate'); ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <?php if ($this->session->userdata('message')) { ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->userdata('message'); ?>
            </div>
        <?php $this->session->unset_userdata('message'); } ?>

        <?php if ($this->session->userdata('error_message')) { ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->userdata('error_message'); ?>
            </div>
        <?php $this->session->unset_userdata('error_message'); } ?>

        <?php
        // ── Build checklist ──
        $c = $candidate;
        $checks = [
            'Full Name'            => !empty($c['full_name']),
            'Sex'                  => !empty($c['sex']),
            'Marital Status'       => !empty($c['martial_status']),
            'Date of Birth'        => !empty($c['dob_ethiopian']),
            'Age'                  => (!empty($c['age']) && $c['age'] > 0),
            'Phone Number'         => !empty($c['phone_number']),
            'Location / Zone'      => (!empty($c['location_id']) && $c['location_id'] > 0) || (!empty($c['location']) && $c['location'] > 0),
            'Woreda'               => !empty($c['woreda']),
            'Tabia'                => !empty($c['tabia']),
            'Education Level'      => (!empty($c['education_level']) || !empty($c['education_level_id'])),
            'Field of Study'       => (!empty($c['field_of_study_id']) && $c['field_of_study_id'] > 0),
            'GPA'                  => (!empty($c['gpa']) && $c['gpa'] > 0),
            'Qualification/Skills' => !empty($c['qualification_skills']),
            'Graduated Year'       => (!empty($c['graduated_year']) && $c['graduated_year'] > 0),
            'Experience'           => isset($c['experience']) && $c['experience'] !== '',
            'Family Size'          => (!empty($c['total_family_size']) && $c['total_family_size'] > 0),
            'Household Type'       => !empty($c['household_type']),
            'Disability Status'    => !empty($c['disability_status']),
        ];
        $filled = count(array_filter($checks));
        $total  = count($checks);
        $pct    = round(($filled / $total) * 100);
        $missing = array_keys(array_filter($checks, function($v){ return !$v; }));
        ?>

        <!-- ── Call Center Panel ── -->
        <div class="cc-panel">
            <div class="cc-panel-header">
                <h4>📞 Call Center — Profile Completion Checklist</h4>
                <span class="cc-phone">📱 <?= htmlspecialchars($candidate['phone_number']); ?></span>
            </div>
            <div class="cc-body">
                <?php if (!empty($missing)): ?>
                <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:13px;color:#991b1b;font-weight:600;">
                    ⚠️ <?= count($missing); ?> field(s) need to be filled:
                    <?= implode(', ', $missing); ?>
                </div>
                <?php endif; ?>
                <div class="cc-grid">
                    <?php foreach ($checks as $label => $ok): ?>
                    <div class="cc-item <?= $ok ? 'filled' : 'empty'; ?>">
                        <span class="cc-dot"></span>
                        <?= $ok ? '✓' : '✗'; ?> <?= $label; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="cc-summary">
                    <div class="cc-summary-item" style="color:#166534;">✓ <?= $filled; ?> filled</div>
                    <div class="cc-summary-item" style="color:#991b1b;">✗ <?= $total - $filled; ?> missing</div>
                    <div class="cc-progress"><div class="cc-progress-bar" style="width:<?= $pct; ?>%"></div></div>
                    <div class="cc-summary-item" style="color:#1a3a5c;font-size:14px;font-weight:800;"><?= $pct; ?>% complete</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission1->method('manage_candidate','read')->access()) { ?>
                        <a href="<?= base_url('Ccandidate/manage_candidate'); ?>" class="btn btn-info m-b-5">
                            <i class="ti-align-justify"></i> Manage Candidate
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading"><h4>Edit Candidate</h4></div>

                    <?= form_open_multipart('Ccandidate/update', ['class' => 'form-vertical', 'id' => 'update_candidate']) ?>
                    <div class="panel-body">

                        <input type="hidden" name="id" value="<?= $candidate['id'] ?>">

                        <!-- Seeker ID -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Seeker ID</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="seeker_id" type="text"
                                       value="<?= isset($candidate['seeker_id']) ? $candidate['seeker_id'] : '' ?>" readonly>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Full Name *</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="full_name" type="text"
                                       value="<?= $candidate['full_name'] ?>" required>
                            </div>
                        </div>

                        <!-- Sex -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Sex *</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="sex" required>
                                    <option value="Male"   <?= (isset($candidate['sex']) && $candidate['sex']=='Male')?'selected':'' ?>>Male</option>
                                    <option value="Female" <?= (isset($candidate['sex']) && $candidate['sex']=='Female')?'selected':'' ?>>Female</option>
                                    <option value="Other"  <?= (isset($candidate['sex']) && $candidate['sex']=='Other')?'selected':'' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Martial Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Martial Status *</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="martial_status" required>
                                    <option value="">-- Select --</option>
                                    <option value="Single"   <?= (isset($candidate['martial_status']) && $candidate['martial_status']=='Single')?'selected':'' ?>>Single</option>
                                    <option value="Married"  <?= (isset($candidate['martial_status']) && $candidate['martial_status']=='Married')?'selected':'' ?>>Married</option>
                                    <option value="Divorced" <?= (isset($candidate['martial_status']) && $candidate['martial_status']=='Divorced')?'selected':'' ?>>Divorced</option>
                                    <option value="Widowed"  <?= (isset($candidate['martial_status']) && $candidate['martial_status']=='Widowed')?'selected':'' ?>>Widowed</option>
                                </select>
                            </div>
                        </div>

                        <!-- DOB Ethiopian -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">DOB (Ethiopian)</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="dob_ethiopian" id="dob_ethiopian" type="text"
                                       value="<?= isset($candidate['dob_ethiopian']) ? $candidate['dob_ethiopian'] : '' ?>">
                                <small class="text-muted">Format: YYYY-MM-DD — age will auto-calculate</small>
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Age *</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="age" id="age" type="number"
                                       value="<?= isset($candidate['age']) ? $candidate['age'] : '' ?>" required>
                            </div>
                        </div>

                        <!-- Total Family Size -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Total Family Size</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="total_family_size" type="number"
                                       value="<?= isset($candidate['total_family_size']) ? $candidate['total_family_size'] : '' ?>">
                            </div>
                        </div>

                        <!-- HH Male -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Household Male</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="hh_male" type="number"
                                       value="<?= isset($candidate['hh_male']) ? $candidate['hh_male'] : '' ?>">
                            </div>
                        </div>

                        <!-- HH Female -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Household Female</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="hh_female" type="number"
                                       value="<?= isset($candidate['hh_female']) ? $candidate['hh_female'] : '' ?>">
                            </div>
                        </div>

                        <!-- Household Type -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Household Type</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="household_type" type="text"
                                       value="<?= isset($candidate['household_type']) ? $candidate['household_type'] : '' ?>">
                            </div>
                        </div>

                        <!-- Disability Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Disability Status</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="disability_status">
                                    <option value="No"  <?= (isset($candidate['disability_status']) && $candidate['disability_status']=='No')?'selected':'' ?>>No</option>
                                    <option value="Yes" <?= (isset($candidate['disability_status']) && $candidate['disability_status']=='Yes')?'selected':'' ?>>Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- Disability Male -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Disabled Male</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="disability_male" type="number"
                                       value="<?= isset($candidate['disability_male']) ? $candidate['disability_male'] : '' ?>">
                            </div>
                        </div>

                        <!-- Disability Female -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Disabled Female</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="disability_female" type="number"
                                       value="<?= isset($candidate['disability_female']) ? $candidate['disability_female'] : '' ?>">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone *</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="phone_number"
                                       value="<?= isset($candidate['phone_number']) ? $candidate['phone_number'] : '' ?>" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" type="email"
                                       value="<?= isset($candidate['email']) ? $candidate['email'] : '' ?>">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location *</label>
                            <div class="col-sm-6">
                                <?php
                                $loc_text = isset($candidate['location_text']) ? $candidate['location_text'] : '';
                                $loc_id   = isset($candidate['location_id'])   ? $candidate['location_id']   : (isset($candidate['location']) ? $candidate['location'] : 0);
                                ?>
                                <?php if (!empty($loc_text)): ?>
                                <div style="background:#fffbeb;border:1px solid #f59e0b;border-radius:7px;padding:8px 12px;margin-bottom:8px;font-size:13px;">
                                    <strong style="color:#92400e;">📍 Registered by candidate:</strong>
                                    <span style="color:#1a2e44;font-weight:600;margin-left:6px;"><?= htmlspecialchars($loc_text) ?></span>
                                </div>
                                <?php endif; ?>
                                <select class="form-control select2" name="location" required>
                                    <option value="0">-- Select real zone --</option>
                                    <?php foreach ($zones as $z): ?>
                                        <option value="<?= $z['id']; ?>"
                                            <?= ($loc_id > 0 && $loc_id == $z['id']) ? 'selected' : '' ?>>
                                            <?= $z['zone_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Select the correct zone from the list</small>
                            </div>
                        </div>

                        <!-- Woreda -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Woreda</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="woreda" type="text"
                                       value="<?= isset($candidate['woreda']) ? $candidate['woreda'] : '' ?>">
                            </div>
                        </div>

                        <!-- Tabia -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tabia</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="tabia" type="text"
                                       value="<?= isset($candidate['tabia']) ? $candidate['tabia'] : '' ?>">
                            </div>
                        </div>

                        <!-- Education Level -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Education Level *</label>
                            <div class="col-sm-6">
                                <?php
                                $edu_val = isset($candidate['education_level']) ? $candidate['education_level'] : '';
                                $edu_id  = isset($candidate['education_level_id']) ? $candidate['education_level_id'] : '';
                                ?>
                                <?php if (!empty($edu_val) && !is_numeric($edu_val)): ?>
                                    <div class="alert alert-warning" style="padding:8px 12px;font-size:13px;margin-bottom:8px;">
                                        <strong>Registered value:</strong> <?= htmlspecialchars($edu_val) ?>
                                    </div>
                                <?php endif; ?>
                                <select class="form-control select2" name="education_level" required>
                                    <option value="">Select level...</option>
                                    <?php foreach ($educational_levels as $lvl): ?>
                                        <option value="<?= $lvl['id']; ?>"
                                            <?= ($edu_id == $lvl['id'] || strtolower($edu_val) == strtolower($lvl['level'])) ? 'selected' : '' ?>>
                                            <?= $lvl['level']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Field of Study -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Field of Study</label>
                            <div class="col-sm-6">
                                <?php $fos_id = isset($candidate['field_of_study_id']) ? $candidate['field_of_study_id'] : 0; ?>
                                <select class="form-control select2" name="field_of_study">
                                    <option value="0">-- Not specified --</option>
                                    <?php foreach ($fields_of_study as $fs): ?>
                                        <option value="<?= $fs['id']; ?>"
                                            <?= ($fos_id > 0 && $fos_id == $fs['id']) ? 'selected' : '' ?>>
                                            <?= $fs['field']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- GPA -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">GPA *</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="gpa" type="number" step="0.01"
                                       value="<?= isset($candidate['gpa']) ? $candidate['gpa'] : '' ?>" required>
                            </div>
                        </div>

                        <!-- Qualification / Skills -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Qualification / Skills</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="qualification_skills" rows="3"><?= isset($candidate['qualification_skills']) ? $candidate['qualification_skills'] : '' ?></textarea>
                            </div>
                        </div>

                        <!-- Graduated Year -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Graduated Year</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="graduated_year" type="text"
                                       value="<?= isset($candidate['graduated_year']) ? $candidate['graduated_year'] : '' ?>">
                            </div>
                        </div>

                        <!-- Experience -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Experience *</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="experience" type="number"
                                       value="<?= isset($candidate['experience']) ? $candidate['experience'] : '' ?>" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status *</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status" required>
                                    <option value="0" <?= (isset($candidate['status']) && $candidate['status']==0)?'selected':'' ?>>Job Seeker</option>
                                    <option value="1" <?= (isset($candidate['status']) && $candidate['status']==1)?'selected':'' ?>>Fetched</option>
                                    <option value="2" <?= (isset($candidate['status']) && $candidate['status']==2)?'selected':'' ?>>Applied</option>
                                    <option value="3" <?= (isset($candidate['status']) && $candidate['status']==3)?'selected':'' ?>>Shortlisted</option>
                                    <option value="4" <?= (isset($candidate['status']) && $candidate['status']==4)?'selected':'' ?>>Interviewed</option>
                                    <option value="5" <?= (isset($candidate['status']) && $candidate['status']==5)?'selected':'' ?>>Selected</option>
                                    <option value="6" <?= (isset($candidate['status']) && $candidate['status']==6)?'selected':'' ?>>Rejected</option>
                                    <option value="7" <?= (isset($candidate['status']) && $candidate['status']==7)?'selected':'' ?>>Hired</option>
                                </select>
                            </div>
                        </div>

                        <!-- Resume -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Resume</label>
                            <div class="col-sm-6">
                                <?php if (!empty($candidate['resume'])) { ?>
                                    <a href="<?= base_url('uploads/candidate_resumes/' . $candidate['resume']); ?>" download class="btn btn-success btn-sm">Download Current Resume</a>
                                    <br><br>
                                <?php } ?>
                                <input class="form-control" name="resume" type="file" accept="application/pdf">
                            </div>
                        </div>

                        <!-- Created At (Read-only) -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Created At</label>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" value="<?= isset($candidate['created_at']) ? $candidate['created_at'] : '' ?>" readonly>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" value="Update Candidate">
                            </div>
                        </div>

                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

    </section>
</div>
<!-- Edit Candidate End -->

<script>
// Auto-calculate age from Ethiopian DOB
document.getElementById('dob_ethiopian').addEventListener('blur', function(){
    var dob = this.value.trim();
    if(!dob) return;
    var parts = dob.split('-');
    if(parts.length !== 3) return;
    var year = parseInt(parts[0]), month = parseInt(parts[1]), day = parseInt(parts[2]);
    if(isNaN(year)||isNaN(month)||isNaN(day)) return;
    var now = new Date();
    var ethYear  = now.getFullYear() - 7;
    var ethMonth = now.getMonth() + 1;
    var ethDay   = now.getDate();
    var age = ethYear - year;
    if(month > ethMonth || (month === ethMonth && day > ethDay)) age--;
    if(age >= 0 && age < 150) document.getElementById('age').value = age;
});

// Highlight empty vs filled fields
(function(){
    var fields = document.querySelectorAll('.form-control');
    fields.forEach(function(f){
        var row = f.closest('.form-group');
        if(!row) return;
        function check(){
            var val = f.value ? f.value.trim() : '';
            var isEmpty = (val === '' || val === '0' || val === 'undefined');
            f.style.borderColor   = isEmpty ? '#ef4444' : '#22c55e';
            f.style.background    = isEmpty ? '#fff5f5' : '#f0fdf4';
            // Label color
            var lbl = row.querySelector('label');
            if(lbl) lbl.style.color = isEmpty ? '#ef4444' : '#166534';
        }
        check();
        f.addEventListener('input', check);
        f.addEventListener('change', check);
    });
})();
</script>
