<!-- Add new field of study start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('field_of_study') ?></h1>
            <small><?php echo display('add_field_of_study') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('field_of_study') ?></a></li>
                <li class="active"><?php echo display('add_field_of_study') ?></li>
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
                    if($this->permission1->method('manage_field_of_study','read')->access()) { ?>
                        <a href="<?php echo base_url('Cfield_of_study/manage_field_of_study')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manage_field_of_study')?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if($this->permission1->method('add_field_of_study','create')->access()) { ?>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_field_of_study') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open('Cfield_of_study/insert_field_of_study', array('class' => 'form-vertical','id' => 'insert_field'))?>

                    <div class="panel-body">

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
                                    placeholder="<?php echo display('field_of_study') ?>"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input 
                                    type="submit"
                                    class="btn btn-primary btn-large"
                                    value="<?php echo display('save') ?>"
                                />
                            </div>
                        </div>

                    </div>
                    <?php echo form_close()?>
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
<!-- Add new field of study end -->
