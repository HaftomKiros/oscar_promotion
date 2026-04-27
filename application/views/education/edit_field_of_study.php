<!-- Edit field of study start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('field_of_study') ?></h1>
            <small><?php echo display('edit_field_of_study') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('field_of_study') ?></a></li>
                <li class="active"><?php echo display('edit_field_of_study') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Message -->
        <?php if ($message = $this->session->userdata('message')) { ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $message ?>
            </div>
        <?php $this->session->unset_userdata('message'); } ?>

        <?php if ($error_message = $this->session->userdata('error_message')) { ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $error_message ?>
            </div>
        <?php $this->session->unset_userdata('error_message'); } ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission1->method('manage_field_of_study','read')->access()) { ?>
                        <a href="<?php echo base_url('Cfield_of_study/manage_field_of_study'); ?>" 
                           class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> 
                            <?php echo display('manage_field_of_study') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if ($this->permission1->method('manage_field_of_study','update')->access()) { ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_field_of_study') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open('Cfield_of_study/update_field_of_study', array('class' => 'form-vertical')); ?>

                    <div class="panel-body">

                        <input type="hidden" name="id" value="<?php echo html_escape($field->id); ?>">

                        <!-- Field of Study Input -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('field_of_study') ?> <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="field_of_study"
                                    id="field_of_study"
                                    type="text"
                                    value="<?php echo html_escape($field->field); ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('status') ?> <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="status" class="form-control" required>
                                    <option value="1" <?php echo ($field->status == 1) ? 'selected' : ''; ?>>
                                        Active
                                    </option>
                                    <option value="0" <?php echo ($field->status == 0) ? 'selected' : ''; ?>>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input 
                                    type="submit"
                                    class="btn btn-primary btn-large"
                                    value="<?php echo display('update') ?>"
                                />
                            </div>
                        </div>

                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <?php } else { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('You do not have permission to access. Please contact with administrator.'); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </section>
</div>
<!-- Edit field of study end -->
