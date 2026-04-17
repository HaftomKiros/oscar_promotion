<!-- Add new candidate start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-user"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_candidate') ?></h1>
            <small><?php echo display('add_new_candidate') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('candidate') ?></a></li>
                <li class="active"><?php echo display('add_candidate') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alerts Start -->
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
        <!-- Alerts End -->

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="<?php echo base_url('Ccandidate/manage_candidate')?>" class="btn btn-info m-b-5 m-r-2">
                        <i class="ti-align-justify"></i> <?php echo display('manage_candidate') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_new_candidate') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open_multipart('Ccandidate/insert_candidate', ['class' => 'form-vertical']); ?>
                    <div class="panel-body">

                        <!-- Full Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Full Name <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="full_name" type="text" required>
                            </div>
                        </div>

                        <!-- Sex -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Sex <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="sex" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Martial Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Martial Status <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="martial_status" required>
                                    <option value="Single" selected>Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Ethiopian Date of Birth -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Date of Birth (Ethio) <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="dob_ethiopian" type="text" placeholder="YYYY-MM-DD" required>
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Age <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="age" type="number" required>
                            </div>
                        </div>

                        <!-- Total Family Size -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Total Family Size (incl. HH Head) <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="total_family_size" type="number" required>
                            </div>
                        </div>

                        <!-- Household Composition -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Household Composition</label>
                            <div class="col-sm-3">
                                <input class="form-control" name="hh_male" type="number" placeholder="Male">
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" name="hh_female" type="number" placeholder="Female">
                            </div>
                        </div>

                        <!-- Household Type -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Household Type</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="household_type">
                                    <option value="">Select</option>
                                    <option value="IDP">IDP</option>
                                    <option value="Host">Host</option>
                                </select>
                            </div>
                        </div>

                        <!-- Disability -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Disability Status</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="disability_status" id="disability_status">
                                    <option value="">Select</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- Disability Male / Female (SHOW ONLY IF YES) -->
                        <div class="form-group row" id="disability_fields" style="display:none;">
                            <label class="col-sm-3 col-form-label">Disability Male / Female</label>

                            <div class="col-sm-3">
                                <input class="form-control" name="disability_male" id="disability_male" 
                                       type="number" min="0" value="0" placeholder="Male">
                            </div>

                            <div class="col-sm-3">
                                <input class="form-control" name="disability_female" id="disability_female" 
                                       type="number" min="0" value="0" placeholder="Female">
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="phone_number" type="text" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" type="email">
                            </div>
                        </div>

                        <!-- Zone -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">LOCATION <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="location" required>
                                    <option value="">Select Location</option>
                                    <?php foreach ($zones as $zone): ?>
                                        <option value="<?= $zone['id']; ?>"><?= $zone['zone_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Woreda -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Woreda <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="woreda" type="text" required>
                            </div>
                        </div>

                        <!-- Tabia -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tabia <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="tabia" type="text" required>
                            </div>
                        </div>

                        <!-- Education Level -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Education Level <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="education_level" required>
                                    <option value="">Select Education Level</option>
                                    <?php foreach ($educational_levels as $level): ?>
                                        <option value="<?= $level['id']; ?>"><?= $level['level']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Field of Study -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Field of Study <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="field_of_study" required>
                                    <option value="">Select Field of Study</option>
                                    <?php foreach ($fields_of_study as $field): ?>
                                        <option value="<?= $field['id']; ?>"><?= $field['field']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- GPA -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">GPA <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="gpa" type="number" step="0.01" required>
                            </div>
                        </div>

                        <!-- Qualification / Skills -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Qualification / Skills <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="qualification_skills" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Graduated Year -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Graduated Year <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="graduated_year" type="number" required>
                            </div>
                        </div>

                        <!-- Experience -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Experience (Years) <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="experience" type="number" required>
                            </div>
                        </div>

                        <!-- Resume -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Resume</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="resume" type="file" accept="application/pdf">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>

                    </div>
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>

    </section>
</div>
<!-- Add new candidate end -->

<!-- JS - Show/Hide Disability Male/Female (Robust version: works with/without jQuery & Select2) -->
<script>
(function () {
    // helper: toggle fields based on status
    function toggleDisabilityFields(status) {
        var fields = document.getElementById('disability_fields');
        var male = document.getElementById('disability_male');
        var female = document.getElementById('disability_female');

        if (!fields || !male || !female) return;

        if (status === 'Yes') {
            // show but keep any existing values (don't force to 0 if user had values)
            if (window.jQuery) {
                // use jQuery .show for nicer handling
                jQuery(fields).show();
            } else {
                fields.style.display = '';
            }
        } else {
            // hide and set to 0
            if (window.jQuery) {
                jQuery(fields).hide();
            } else {
                fields.style.display = 'none';
            }
            male.value = 0;
            female.value = 0;
        }
    }

    // init: wire events after DOM ready
    function init() {
        var select = document.getElementById('disability_status');

        if (!select) return;

        // initial toggle based on current value (useful when form is repopulated)
        toggleDisabilityFields(select.value);

        // If jQuery is available prefer its event handling (works nicer with Select2)
        if (window.jQuery && typeof jQuery === 'function') {
            var $select = jQuery(select);

            // standard change (Select2 triggers change on original select)
            $select.on('change', function () {
                toggleDisabilityFields(this.value);
            });

            // also listen to select2 events (in case select2 is used and configured)
            $select.on('select2:select select2:unselect', function (e) {
                toggleDisabilityFields(this.value);
            });
        } else {
            // Vanilla fallback
            select.addEventListener('change', function () {
                toggleDisabilityFields(this.value);
            });
        }

        // Optional: ensure values are set to 0 before submit if hidden (extra safety)
        var form = select.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                var status = select.value;
                if (status !== 'Yes') {
                    var male = document.getElementById('disability_male');
                    var female = document.getElementById('disability_female');
                    if (male) male.value = 0;
                    if (female) female.value = 0;
                }
            });
        }
    }

    // DOM ready: prefer jQuery ready if present
    if (window.jQuery && jQuery.fn && jQuery.fn.ready) {
        jQuery(document).ready(init);
    } else {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    }
})();
</script>
