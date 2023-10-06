<?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[1];

$is_admin = Session::get('is_admin');
$user_id = Session::get('user_id');
?>
<style>
    .active-ho-jao {
        color: #ff9b44 !important;
        text-decoration: underline;
    }
</style>
<!-- menu 1 m1 -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical"> 
                <li>
                    <a href="dashboard"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <?php if($is_admin > 0) { ?>
                <li class="submenu">
                    <?php
                        $mangae_edbr = request()->is("employee*") || request()->is("department*") || request()->is("designation*") || request()->is("branch*") || request()->is("residency*") || request()->is("subresidency*");
                    ?>
                    <a href="#" class="<?php echo e($mangae_edbr ? 'active-ho-jao' : ''); ?>"><i class="la la-user"></i> <span> Manage EDBR</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'employee' || $page == 'employeeSearch' || $page == 'employeeProfileUpdate')?'active':'';?>" href="/employee">Employees</a></li>
                        <li><a class="<?php echo ($page == 'department')?'active':'';?>" href="/department">Departments</a></li>
                        
                        <li><a class="<?php echo ($page == 'designation')?'active':'';?>" href="/designation">Job title</a></li>
                        
                        <li><a class="<?php echo ($page == 'branch')?'active':'';?>" href="/branch">Branch</a></li>
                        
                        <li><a class="<?php echo ($page == 'residency')?'active':'';?>" href="/residency">Company</a></li>
                         <li><a class="<?php echo ($page == 'subresidency')?'active':'';?>" href="/subresidency">Licence</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="submenu"> 
                    <?php
                    $mangae_lts = request()->is("attendance*") || request()->is("leaves*") || request()->is("shifting*") || request()->is("scheduling*");
                ?>
                    <a href="#" class="<?php echo e($mangae_lts ? 'active-ho-jao' : ''); ?>"><i class="la la-files-o"></i> <span> Manage LTS </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <?php if($is_admin > 0) { ?>
                        <li><a class="<?php echo ($page == 'attendance' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/attendance">Attendance</a></li>
                        <?php } ?>
                        <li><a class="<?php echo ($page == 'leaves' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/leaves">Leaves</a></li>
                        <?php if($is_admin > 0) { ?>
                        <li><a class="<?php echo ($page == 'shifting' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/shifting">Shifting</a></li>
                        <li><a class="<?php echo ($page == 'scheduling' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/scheduling">Scheduling</a></li>
                        <?php } ?>
                    </ul>
                </li>

                <?php if($is_admin > 0) { ?>
                <li class="submenu">
                    <?php
                    $polices_setting = request()->is("attendance*") || request()->is("leaves*") || request()->is("leave-settings*") || request()->is("scheduling*");
                ?>
                    <a href="#"><i class="la la-file-pdf-o"></i> <span>Polices setting </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'holidays' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/holidays">Holidays</a></li>
                         <li><a class="<?php echo ($page == 'overtime' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/overtime">Overtime</a></li>
                        <li><a class="<?php echo ($page == 'leave-settings' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/leave-settings">Leave </a></li>
                        <li><a class="<?php echo ($page == 'indemnity')?'active':'';?>" href="/indemnity">Indemnity </a></li>
                    </ul>
                </li>
                <li class="submenu">
                  
                    <a href="#"><i class="la la-money"></i> <span> Payroll </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'employee-salary')?'active':'';?>" href="/employee-salary"> Employee Salary </a></li>
                        <li><a class="<?php echo ($page == 'employee-overtime')?'active':'';?>" href="/employee-overtime"> Employee Overtime </a></li>
                        <li><a class="<?php echo ($page == 'payroll-items')?'active':'';?>" href="/payroll-items"> Payroll Items </a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="menu-title"> 
                    <span>Pages</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-user"></i> <span> Profile </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'profile.php')?'active':'';?>" href="profile.php"> Employee Profile </a></li>
                    </ul>
                </li>

                <li>
                    <?php if($is_admin > 0) { ?>
                        <a href="/company-settings"><i class="la la-cog"></i> <span>Company Settings</span></a>
                    <?php } else { 
                        $employeeDetails = App\Models\Employee::where('user_id',$user_id)->first();
                    ?>
                        <a href="/company-settings-edit/<?php echo (isset($employeeDetails))?$employeeDetails->company:4; ?>"><i class="la la-cog"></i> <span>Company Settings</span></a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php /**PATH C:\wamp64_new\www\hrm_new\resources\views/includes/sidebar.blade.php ENDPATH**/ ?>