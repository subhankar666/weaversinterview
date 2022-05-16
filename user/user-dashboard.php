<?php
$userObj = new UserClass();
?>
<div class="container-register dashboard-container" id="container-register">
<form class="custom-plugin-form" id="dashboardForm">
  <div class="container">
    <h1 style="font-size:20px;padding-bottom:10px">Upload Gallery</h1>


    <?php echo $userObj->selectboxDashboardShow(); ?>

    <input type="file" name="bannerUpload" id="bannerUpload" accept="image/*" style="width:43%">
    <button type="button" id="pluginUpload" class="registerbtn" data-user="<?php echo get_current_user_id(); ?>">Upload</button>
  </div>
</form>
</div>
<?php
$userObj = new UserClass();
$userObj->imageShow();
?>



