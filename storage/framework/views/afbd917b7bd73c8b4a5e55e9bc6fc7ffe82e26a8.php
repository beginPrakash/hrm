<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">
	<!-- Page Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Company Settings</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <form action="/company-settings-update/<?php echo $residency->id; ?>" method="POST" enctype="multipart/form-data">
                    	<?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="<?php echo (isset($residency) && isset($residency->name))?$residency->name:''; ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Logo</label>
                                    <div class="image-upload">
                                      	<label for="file-input4">
                                        	<img src="<?php echo (isset($residency) && $residency->logo!=NULL)?'../uploads/logo/'.$residency->logo:""; ?>" width="200" id="img1"/>
                                      	</label>
                                      	<input id="file-input1" name="image1" type="file" onchange="previewFile(this, 'img1');"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" type="text" name="address"><?php echo (isset($residency) && isset($residency->address))?$residency->address:''; ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country" value="<?php echo (isset($residency) && isset($residency->country))?$residency->country:''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo (isset($residency) && isset($residency->city))?$residency->city:''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>State/Province</label>
                                    <input type="text" class="form-control" name="state" value="<?php echo (isset($residency) && isset($residency->state))?$residency->state:''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control" name="postal_code" value="<?php echo (isset($residency) && isset($residency->postal_code))?$residency->postal_code:''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo (isset($residency) && isset($residency->email))?$residency->email:''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" value="<?php echo (isset($residency) && isset($residency->phone_number))?$residency->phone_number:''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input class="form-control" value="818-635-5579" type="text">
                                </div>
                            </div> -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" class="form-control" name="fax" value="<?php echo (isset($residency) && isset($residency->fax))?$residency->fax:''; ?>">
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Website Url</label>
                                    <input type="text" class="form-control" name="website" value="<?php echo (isset($residency) && isset($residency->website))?$residency->website:''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" name="update" class="btn btn-primary submit-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
	</div>
	<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
function previewFile(input, id){ 
    var file = $(input).get(0).files[0];

    if(file){
      var reader = new FileReader();

      reader.onload = function(){
          $("#"+id).attr("src", reader.result);
      }

      reader.readAsDataURL(file);
    }
}
</script><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/settings/residencysettingsUpdate.blade.php ENDPATH**/ ?>