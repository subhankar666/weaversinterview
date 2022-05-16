<?php
$adminObj = new AdminUser();
?>
<div class="datatable-container">
<button id="myPluginBtn">Add</button>

<!-- The plugin-modal -->
<div id="myplugin-modal" class="plugin-modal">

  <!-- plugin-modal content -->
  <div class="plugin-modal-content">
    <span class="PluginClose">&times;</span>
    <form action="#" id="addBannerForm">
			<h1>Add Your Record</h1>
			<input type="Text" placeholder="Banner Name" name="bannerName" id="bannerName"/>
            <input type="number" placeholder="Banner Width (px)" name="bannerWidth" id="bannerWidth" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
            <input type="number" placeholder="Banner Height (px)" name="bannerHeight" id="bannerHeight" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>

			<button id="pluginAddBanner">Add</button>
	</form>
  </div>

</div>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Banner Name</th>
                <th>Height</th>
                <th>Width</th>
                <th>Update</th>
                <th>Delete</th>

            </tr>
        </thead>
        <tbody id="dataBody">
            <?php echo $adminObj->loadViewBannerList(); ?>
        </tbody>

    </table>
</div>
