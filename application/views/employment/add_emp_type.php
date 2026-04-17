<!-- Add Employment Type start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('employment_type') ?></h1>
            <small><?php echo display('add_employment_type') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('employment_type') ?></a></li>
                <li class="active"><?php echo display('add_employment_type') ?></li>
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
                    if($this->permission1->method('manage_emp_type','read')->access()) { ?>
                        <a href="<?php echo base_url('Cemployment/manage_emp_type')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_employment_type')?> </a>
                    <?php } ?>
                    
                    <?php
                    if($this->permission1->method('manage_sal_range','read')->access()) { ?>
                        <a href="<?php echo base_url('Cemployment/manage_sal_range')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_salary_range')?> </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
        if($this->permission1->method('add_emp_type','create')->access()) { ?>
        <!-- New Employment Type -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_employment_type') ?> </h4>
                        </div>
                    </div>
                  <?php echo form_open('Cemployment/insert_emp_type', array('class' => 'form-vertical','id' => 'insert_emp_type'))?>
                    <div class="panel-body">
                      
                        <div class="form-group row">
                            <label for="emp_type" class="col-sm-3 col-form-label">
                                <?php echo display('employment_type') ?> 
                                <span style="color:red">*</span>
                            </label>

                            <div class="col-sm-6">
                                <input 
                                    class="form-control" 
                                    name="emp_type" 
                                    id="emp_type" 
                                    type="text" 
                                    placeholder="<?php echo display('employment_type') ?>" 
                                    tabindex="2"
                                    required
                                > 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-emp-type" class="btn btn-primary btn-large" name="add-emp-type" value="<?php echo display('save') ?>" tabindex="7"/>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
            <?php
        }
        else{
            ?>
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
            <?php
        }
        ?>
    </section>
</div>
<!-- Add Employment Type end -->
