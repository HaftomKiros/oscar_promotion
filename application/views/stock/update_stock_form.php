<!-- Add new supplier start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('stock') ?></h1>
            <small><?php echo display('add_stock') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('add_stock') ?></li>
            </ol>
        </div>
    </section>
    <section class="content">
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
                    
                </div>
            </div>
        </div>

        <!-- New supplier -->
        <?php
        if($this->permission1->method('edit_stock','update')->access()) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('update_stock') ?> </h4>
                            </div>
                        </div>
                        <?php echo form_open_multipart('Creport/update_stock/'.$id, array('id' => 'update_stock')) ?>
<div class="panel-body">

    <div class="form-group row">
        <label for="stock_name" class="col-sm-3 col-form-label"><?php echo display('stock_name') ?> <i class="text-danger">*</i></label>
        <div class="col-sm-6">
            <input class="form-control" name="stock_name" id="stock_name"
                   type="text" placeholder="<?php echo display('stock_name') ?>"
                   value="<?php echo set_value('stock_name', $stock_name); ?>" required tabindex="1">
        </div>
    </div>

    <div class="form-group row">
        <label for="stock_address" class="col-sm-3 col-form-label"><?php echo display('stock_address') ?></label>
        <div class="col-sm-6">
            <input class="form-control" name="stock_address" id="stock_address" type="text"
                   placeholder="<?php echo display('stock_address') ?>"
                   value="<?php echo set_value('stock_address', $stock_address); ?>" tabindex="2">
        </div>
    </div>

    <!-- assign users -->
    <div class="form-group row">
        <label for="assign_users" class="col-sm-3 col-form-label"><?php echo display('assign_users') ?></label>
        <div class="col-sm-6">
            <select name="user_id[]" id="user_id" class="form-control" multiple required tabindex="3">
                <?php foreach ($all_users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>"
                        <?php echo in_array($user['user_id'], $assign_users) ? 'selected' : ''; ?>>
                        <?php echo $user['first_name'].' '.$user['last_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-6 offset-sm-3">
            <input type="submit" id="update-stock" class="btn btn-primary btn-large"
                   name="update-stock" value="<?php echo display('update') ?>" tabindex="4"/>
        </div>
    </div>

</div>
<?php echo form_close(); ?>


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
<div id="supplier_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Csv supplier</h4>
      </div>
      <div class="modal-body">

                <div class="panel panel-bd">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo 'CSV supplier'; ?></h4>
                        </div>
                    </div>
                    
                    <div class="panel-body">
                     <div class="col-sm-12"><a href="<?php echo base_url('assets/data/csv/supplier_csv_sample.csv') ?>" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Sample File</a></div>
                      <?php echo form_open_multipart('Csupplier/uploadCsv_supplier',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_supplier'))?>
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="upload_csv_file" class="col-sm-4 col-form-label"><?php echo display('upload_csv_file') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="upload_csv_file" type="file" id="upload_csv_file" placeholder="<?php echo display('upload_csv_file') ?>" required>
                                    </div>
                                </div>
                            </div>
                        
                       <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('submit') ?>" />
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                               
                            </div>
                        </div>
                        </div>
                          <?php echo form_close()?>
                    </div>
                    </div>
                  
               
     
      </div>
     
    </div>

  </div>
</div>
    </section>
</div>



