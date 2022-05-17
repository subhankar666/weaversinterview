<?php
$adminObj = new AdminUser();
?>
<div class="datatable-container">
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Students ID</th>
                <th>Students Email</th>
                <th>Courses</th>


            </tr>
        </thead>
        <tbody id="dataBody">
            <?php echo $adminObj->loadViewStudentsList(); ?>
        </tbody>

    </table>
</div>
