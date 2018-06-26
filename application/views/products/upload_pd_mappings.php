<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Upload Part-Defect Code Mappings
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."products/pd_master"; ?>">
                    Manage Part-Defect Code Mappings 
                </a>
            </li>
            <li class="active">Upload Part-Defect Code Mappings</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered inspection-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Upload Part-Defect Code Mappings Form
                    </div>
                    <div class="actions">
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/formats/Part_defect_code_Mapping.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format
                        </a>
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <input type="hidden" name="post_value" value="1" />
                            
                            <!--<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Product: </b></label>
                                        <p class="form-control-static">
                                            <b><?php echo $product['org_name']; ?> - <?php echo $product['name']; ?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>-->
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pd_excel" class="control-label">Upload Part-Defect Code Mapping:
                                            <span class="required">*</span>
                                        </label>
                                        <input type="file" name="pd_excel" class="required">
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'products/pd_master'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>