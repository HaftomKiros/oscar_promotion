<!-- Edit Zone Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('edit_zone') ?></h1>
            <small><?php echo display('update_zone') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('zone') ?></a></li>
                <li class="active"><?php echo display('edit_zone') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Messages -->
        <?php
        if ($message = $this->session->userdata('message')) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $message ?>
            </div>
        <?php 
            $this->session->unset_userdata('message');
        }

        if ($error_message = $this->session->userdata('error_message')) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $error_message ?>
            </div>
        <?php 
            $this->session->unset_userdata('error_message');
        }
        ?>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission1->method('manage_zone','read')->access()) { ?>
                        <a href="<?php echo base_url('Czone/manage_zone')?>" class="btn btn-info m-b-5">
                            <i class="ti-align-justify"></i> <?php echo display('manage_zone') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Edit Zone Form -->
        <?php if ($this->permission1->method('edit_zone','update')->access()) { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_zone') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open('Czone/update_zone/'.$zone['id'], array('class' => 'form-vertical', 'id' => 'update_zone')) ?>
                    <div class="panel-body">
                        
                        <div class="form-group row">
                            <label for="zone_name" class="col-sm-3 col-form-label">
                                <?php echo display('zone_name') ?> 
                                <span style="color:red">*</span>
                            </label>

                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="zone_name"
                                    id="zone_name"
                                    type="text"
                                    value="<?php echo html_escape($zone['zone_name']); ?>"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label">
                                <?php echo display('status') ?>
                            </label>
                            <div class="col-sm-6">
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo $zone['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?php echo $zone['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" value="<?php echo display('update') ?>">
                            </div>
                        </div>

                    </div>
                    <script>
    document.getElementById('update_zone').addEventListener('submit', function () {
        const btn = this.querySelector('input[type="submit"]');
        btn.disabled = true;
        btn.value = "Updating..."; // optional nice UI
    });
</script>

                    <?php echo form_close() ?>
                </div>
            </div>
        </div>

        <?php } else { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <h4><?php echo display('You do not have permission to access. Please contact with administrator.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </section>
</div>
<!-- Edit Zone End -->
