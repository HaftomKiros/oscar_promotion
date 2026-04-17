<!-- Edit Job Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-portfolio"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('job') ?></h1>
            <small><?php echo display('edit_job') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('job') ?></a></li>
                <li class="active"><?php echo display('edit_job') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alerts -->
        <?php if($this->session->userdata('message')) { ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $this->session->userdata('message'); ?>
            </div>
            <?php $this->session->unset_userdata('message'); ?>
        <?php } ?>

        <?php if($this->session->userdata('error_message')) { ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $this->session->userdata('error_message'); ?>
            </div>
            <?php $this->session->unset_userdata('error_message'); ?>
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="<?php echo base_url('Cjob/manage_job') ?>" class="btn btn-info m-b-5 m-r-2">
                        <i class="ti-align-justify"></i> <?php echo display('manage_job') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_job') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open_multipart('Cjob/update_job/'.$job['id'], ['class'=>'form-vertical']); ?>
                    <div class="panel-body">

                        <!-- COMPANY -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Company/Employer <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="company_id" required>
                                    <option value="">Select Company</option>
                                    <?php foreach($companies as $company): ?>
                                        <option value="<?= $company['id'] ?>"
                                            <?= ($company['id'] == $job['company_id']) ? 'selected' : '' ?>>
                                            <?= $company['company_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- JOB TITLE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Job Title <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="job_title" required
                                       value="<?= $job['job_title'] ?>">
                            </div>
                        </div>

                        <!-- NUMBER OF POSITIONS -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Number of Positions</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="positions" type="number" min="1" value="<?= $job['positions'] ?>">
                            </div>
                        </div>

                        <!-- SEX -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Sex</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="sex">
                                    <option value="Both"   <?= ($job['sex']=='Both')?'selected':'' ?>>Both</option>
                                    <option value="Male"   <?= ($job['sex']=='Male')?'selected':'' ?>>Male</option>
                                    <option value="Female" <?= ($job['sex']=='Female')?'selected':'' ?>>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- AGE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Age Requirement</label>

                            <div class="col-sm-3">
                                <select name="age_operator" class="form-control">
                                    <?php 
                                    $ops = ['>=','<=','>','<','='];
                                    foreach($ops as $op): ?>
                                        <option value="<?= $op ?>" 
                                            <?= ($job['age_operator']==$op)?'selected':'' ?>><?= $op ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <input class="form-control" name="age_value" type="number"
                                       value="<?= $job['age_value'] ?>">
                            </div>
                        </div>

                        <!-- LOCATION (MULTIPLE) -->
                        <?php $selected_locations = array_map('strval', json_decode($job['location'] ?? '', true) ?: []); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="location[]" multiple required>
                                    <?php foreach ($zones as $z): ?>
                                        <option value="<?= $z['id'] ?>"
                                            <?= (in_array((string)$z['id'], $selected_locations, true))?'selected':'' ?>>
                                            <?= $z['zone_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- EDUCATION (MULTIPLE) -->
                        <?php $selected_education = array_map('strval', json_decode($job['education_level'] ?? '', true) ?: []); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Education Level <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="education_level[]" multiple required>
                                    <?php foreach ($educational_levels as $level): ?>
                                        <option value="<?= $level['id'] ?>"
                                            <?= (in_array((string)$level['id'], $selected_education, true))?'selected':'' ?>>
                                            <?= $level['level'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- FIELD OF STUDY -->
                        <?php $selected_fields = array_map('strval', json_decode($job['field_of_study'] ?? '', true) ?: []); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Field of Study <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="field_of_study[]" multiple required>
                                    <?php foreach ($fields_of_study as $field): ?>
                                        <option value="<?= $field['id'] ?>"
                                            <?= (in_array((string)$field['id'], $selected_fields, true))?'selected':'' ?>>
                                            <?= $field['field'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- GPA -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">GPA</label>

                            <div class="col-sm-3">
                                <select name="gpa_operator" class="form-control">
                                    <?php foreach($ops as $op): ?>
                                        <option value="<?= $op ?>"
                                            <?= ($job['gpa_operator']==$op)?'selected':'' ?>><?= $op ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <input class="form-control" name="gpa_value" type="number" step="0.01"
                                       value="<?= $job['gpa_value'] ?>">
                            </div>
                        </div>

                        <!-- EXPERIENCE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Experience (Years)</label>

                            <div class="col-sm-3">
                                <select name="experience_operator" class="form-control">
                                    <?php foreach($ops as $op): ?>
                                        <option value="<?= $op ?>" 
                                            <?= ($job['experience_operator']==$op)?'selected':'' ?>><?= $op ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <input class="form-control" name="experience_value" type="number"
                                       value="<?= $job['experience_value'] ?>">
                            </div>
                        </div>

                        <!-- SKILLS -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Skills (comma-separated)</label>
                            <div class="col-sm-6">
                                <input type="text" name="skills" class="form-control" 
                                       value="<?= $job['skills'] ?>">
                            </div>
                        </div>

                        <!-- EMPLOYMENT TYPE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Employment Type</label>
                            <div class="col-sm-6">
                                <select name="employment_type" class="form-control">
                                    <option value="All" <?= ($job['employment_type']=='All')?'selected':'' ?>>All</option>
                                    <?php if(!empty($employment_types)): ?>
                                        <?php foreach($employment_types as $emp): ?>
                                            <option value="<?= $emp['emp_name']; ?>" <?= ($job['employment_type']==$emp['emp_name'])?'selected':'' ?>><?= $emp['emp_name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- EMPLOYMENT PERIOD -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Employment Period</label>
                            <div class="col-sm-6">
                                <input type="text" name="employment_period" class="form-control" 
                                       value="<?= $job['employment_period'] ?>">
                            </div>
                        </div>

                        <!-- SALARY RANGE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Salary Range <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <select name="salary" class="form-control" required>
                                    <option value="">Select Salary Range</option>
                                    <?php if(!empty($salary_ranges)): ?>
                                        <?php foreach($salary_ranges as $sal): ?>
                                            <option value="<?= $sal['sal_range']; ?>" <?= ($job['salary']==$sal['sal_range'])?'selected':'' ?>><?= $sal['sal_range']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- WORK LOCATION / WORK MODE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                Work Mode <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="work_location" class="form-control" required>
                                    <option value="">Select Work Mode</option>
                                    <option value="On-site" <?= ($job['work_location']=='On-site')?'selected':'' ?>>On-site</option>
                                    <option value="Remote" <?= ($job['work_location']=='Remote')?'selected':'' ?>>Remote</option>
                                    <option value="Hybrid" <?= ($job['work_location']=='Hybrid')?'selected':'' ?>>Hybrid</option>
                                    <option value="Field Work" <?= ($job['work_location']=='Field Work')?'selected':'' ?>>Field Work</option>
                                    <option value="Traveling" <?= ($job['work_location']=='Traveling')?'selected':'' ?>>Traveling</option>
                                    <option value="Flexible" <?= ($job['work_location']=='Flexible')?'selected':'' ?>>Flexible Location</option>
                                    <option value="Work From Home" <?= ($job['work_location']=='Work From Home')?'selected':'' ?>>Work From Home (WFH)</option>
                                </select>
                            </div>
                        </div>

                        <!-- SPECIAL SKILL -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Special Skill / Software</label>
                            <div class="col-sm-6">
                                <input type="text" name="special_skill" class="form-control" 
                                       value="<?= $job['special_skill'] ?>">
                            </div>
                        </div>

                        <!-- START DATE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Job Start Date <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <input type="date" name="job_start_date" class="form-control"
                                       value="<?= $job['job_start_date'] ?>" required>
                            </div>
                        </div>

                        <!-- END DATE -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Job End Date <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                                <input type="date" name="job_end_date" class="form-control"
                                       value="<?= $job['job_end_date'] ?>" required>
                            </div>
                        </div>

                        <!-- SUBMIT -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Update Job</button>
                            </div>
                        </div>

                    </div>
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>

    </section>
</div>
<!-- Edit Job End -->
