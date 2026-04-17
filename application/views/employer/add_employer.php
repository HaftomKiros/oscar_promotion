<!-- Add new employer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('employer') ?></h1>
            <small><?php echo display('add_employer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('employer') ?></a></li>
                <li class="active"><?php echo display('add_employer') ?></li>
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
                    <?php if($this->permission1->method('manage_employer','read')->access()) { ?>
                        <a href="<?php echo base_url('Cemployer/manage_employer')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manage_employer')?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if($this->permission1->method('add_empployer','create')->access()) { ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_employer') ?></h4>
                        </div>
                    </div>

                    <?php echo form_open('Cemployer/insert_employer', array('class' => 'form-vertical','id' => 'insert_employer'))?>

                    <div class="panel-body">

                        <!-- Company Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('company_name') ?> <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="company_name"
                                    type="text"
                                    placeholder="<?php echo display('company_name') ?>"
                                    required
                                >
                            </div>
                        </div>
                        <!-- industry sector-->
                          <!-- Company Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('sector') ?>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="sector"
                                    type="text"
                                    placeholder="<?php echo display('sector') ?>"
                                >
                            </div>
                        </div>
                         <!-- end industry-->
                           <!-- number of employees -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('no_emp') ?>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="no_emp"
                                    type="number"
                                    placeholder="<?php echo display('no_emp') ?>"
                                >
                            </div>
                        </div>
                        <!-- number of employees -->
                         <!-- Company Size -->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        <?php echo display('company_size') ?>
    </label>

    <div class="col-sm-6">
        <select 
            class="form-control" 
            name="company_size" 
        >
            <option value=""><?php echo display('select_company_size'); ?></option>
            <option value="Large">Large</option>
            <option value="Medium">Medium</option>
            <option value="Small">Small</option>
        </select>
    </div>
</div>

<!-- Year in Operation (Since) -->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        <?php echo display('operation_since') ?>
    </label>

    <div class="col-sm-6">
        <input 
            class="form-control"
            name="operation_since"
            type="number"
            placeholder="<?php echo display('operation_since') ?>"
            min="1900"
            max="<?php echo date('Y'); ?>"
        >
    </div>
</div>

<!-- Description of Services / Products -->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        <?php echo display('description_services_products') ?>
    </label>

    <div class="col-sm-6">
        <textarea
            class="form-control"
            name="description_services_products"
            rows="4"
            placeholder="<?php echo display('description_services_products'); ?>"
        ></textarea>
    </div>
</div>

<!-- Mission / Vision Statement -->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        <?php echo display('mission_vision_statement') ?>
    </label>

    <div class="col-sm-6">
        <textarea
            class="form-control"
            name="mission_vision_statement"
            rows="4"
            placeholder="<?php echo display('mission_vision_statement'); ?>"
        ></textarea>
    </div>
</div>


                        <!-- Phone Number -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('phone_number') ?>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="phone_number"
                                    type="text"
                                    placeholder="<?php echo display('phone_number') ?>"
                                >
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('email') ?>
                            </label>
                            <div class="col-sm-6">
                                <input 
                                    class="form-control"
                                    name="email"
                                    type="email"
                                    placeholder="<?php echo display('email') ?>"
                                >
                            </div>
                        </div>
                        <!-- Website -->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        <?php echo display('website') ?>
    </label>
    <div class="col-sm-6">
        <input 
            class="form-control"
            name="website"
            type="url"
            placeholder="<?php echo display('website') ?>"
        >
    </div>
</div>

                        <!-- Address -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                <?php echo display('address') ?>
                            </label>
                            <div class="col-sm-6">
                                <textarea 
                                    class="form-control"
                                    name="address"
                                    placeholder="<?php echo display('address') ?>"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Submit -->
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
<!-- Add new employer end -->
