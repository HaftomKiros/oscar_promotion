<!-- Edit employer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('employer') ?></h1>
            <small><?php echo display('edit_employer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('employer') ?></a></li>
                <li class="active"><?php echo display('edit_employer') ?></li>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="<?php echo base_url('Cemployer/manage_employer')?>" class="btn btn-info m-b-5">
                        <i class="ti-align-justify"></i> <?php echo display('manage_employer')?>
                    </a>
                </div>
            </div>
        </div>

        <?php if($this->permission1->method('edit_employer','update')->access()) { ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">

                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_employer') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open('Cemployer/update_employer/'.$employer['id'], ['class' => 'form-vertical']); ?>

                    <div class="panel-body">

                        <!-- Company Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('company_name') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="company_name" type="text"
                                       value="<?php echo html_escape($employer['company_name']); ?>" required>
                            </div>
                        </div>

                        <!-- Sector -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('sector'); ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="sector" type="text"
                                       value="<?php echo html_escape($employer['sector']); ?>">
                            </div>
                        </div>

                        <!-- Number of Employees -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('no_emp'); ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="no_emp" type="number"
                                       value="<?php echo html_escape($employer['no_emp']); ?>">
                            </div>
                        </div>

                        <!-- Company Size -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('company_size'); ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="company_size" type="text"
                                       value="<?php echo html_escape($employer['company_size']); ?>">
                            </div>
                        </div>

                        <!-- Operation Since -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('operation_since'); ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="operation_since" type="text"
                                       value="<?php echo html_escape($employer['operation_since']); ?>">
                            </div>
                        </div>

                        <!-- Description / Services / Products -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('description_services_products'); ?>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="description_services_products"
                                          rows="3"><?php echo html_escape($employer['description_services_products']); ?></textarea>
                            </div>
                        </div>

                        <!-- Mission / Vision -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('mission_vision_statement'); ?>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="mission_vision_statement"
                                          rows="3"><?php echo html_escape($employer['mission_vision_statement']); ?></textarea>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('phone_number') ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="phone_number" type="text"
                                       value="<?php echo html_escape($employer['phone_number']); ?>">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('email') ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" type="email"
                                       value="<?php echo html_escape($employer['email']); ?>">
                            </div>
                        </div>

                        <!-- Website -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('website'); ?>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="website" type="text"
                                       value="<?php echo html_escape($employer['website']); ?>">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('address') ?>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="address"
                                          rows="3"><?php echo html_escape($employer['address']); ?></textarea>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo display('status') ?></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status">
                                    <option value="1" <?php echo ($employer['status'] == 1 ? "selected" : "") ?>>
                                        <?php echo display('active') ?>
                                    </option>
                                    <option value="0" <?php echo ($employer['status'] == 0 ? "selected" : "") ?>>
                                        <?php echo display('inactive') ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo display('update') ?>
                                </button>
                            </div>
                        </div>

                    </div>

                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>

        <?php } ?>

    </section>
</div>
<!-- Edit employer end -->
