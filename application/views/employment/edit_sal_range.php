<!-- Edit Salary Range start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('salary_range') ?></h1>
            <small><?php echo display('edit_salary_range') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('salary_range') ?></a></li>
                <li class="active"><?php echo display('edit_salary_range') ?></li>
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
                    if($this->permission1->method('manage_sal_range','read')->access()) { ?>
                        <a href="<?php echo base_url('Cemployment/manage_sal_range')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_salary_range')?> </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
        if($this->permission1->method('manage_sal_range','update')->access()) { ?>
        <!-- Edit Salary Range -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_salary_range') ?> </h4>
                        </div>
                    </div>
                  <?php echo form_open('Cemployment/update_sal_range/'.$sal_range['id'], array('class' => 'form-vertical','id' => 'update_sal_range'))?>
                    <div class="panel-body">
                      
                        <div class="form-group row">
                            <label for="sal_range" class="col-sm-3 col-form-label">
                                <?php echo display('salary_range') ?> 
                                <span style="color:red">*</span>
                            </label>

                            <div class="col-sm-6">
                                <input 
                                    class="form-control" 
                                    name="sal_range" 
                                    id="sal_range" 
                                    type="text" 
                                    placeholder="<?php echo display('salary_range') ?>" 
                                    value="<?php echo html_escape($sal_range['sal_range']); ?>"
                                    tabindex="2"
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
                                    <option value="1" <?php if($sal_range['status'] == 1) echo 'selected'; ?>>Active</option>
                                    <option value="0" <?php if($sal_range['status'] == 0) echo 'selected'; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="update-sal-range" class="btn btn-primary btn-large" name="update-sal-range" value="<?php echo display('update') ?>" tabindex="7"/>
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
<!-- Edit Salary Range end -->
