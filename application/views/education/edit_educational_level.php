<!-- Edit Educational Level -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('educational_level') ?></h1>
            <small><?php echo display('edit_educational_level') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('educational_level') ?></a></li>
                <li class="active"><?php echo display('edit_educational_level') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('error_message');
            }
        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php
                    if($this->permission1->method('manage_educational_level','read')->access()) { ?>
                        <a href="<?php echo base_url('Ceducational_level/manage_educational_level')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manage_educational_level')?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
        if($this->permission1->method('edit_educational_level','update')->access()) { ?>
        <!-- Edit form -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_educational_level') ?> </h4>
                        </div>
                    </div>

                    <?php echo form_open('Ceducational_level/update/'.$educational_level['id'], array('class' => 'form-vertical')) ?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('educational_level') ?>
                                <span style="color:red">*</span>
                            </label>

                            <div class="col-sm-6">
                                <input 
                                    class="form-control" 
                                    name="educational_level" 
                                    id="educational_level" 
                                    type="text" 
                                    value="<?php echo html_escape($educational_level['level']); ?>"
                                    placeholder="<?php echo display('educational_level') ?>" 
                                    required
                                > 
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('status') ?>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status">
                                    <option value="1" <?php echo ($educational_level['status'] == 1 ? 'selected' : ''); ?>>Active</option>
                                    <option value="0" <?php echo ($educational_level['status'] == 0 ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary btn-large" value="<?php echo display('update') ?>"/>
                            </div>
                        </div>

                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="row">
              <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('You do not have permission to access. Please contact with administrator.');?></h4>
                        </div>
                    </div>
                </div>
              </div>
            </div>
        <?php } ?>

    </section>
</div>
