<?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[1];

$is_admin = Session::get('is_admin');
$user_id = Session::get('user_id');
$is_store_user = _is_user_role_owner($user_id);

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
                        
                        <!-- <li><a class="<?php echo ($page == 'residency')?'active':'';?>" href="/residency">Company</a></li> -->
                         <li><a class="<?php echo ($page == 'subresidency')?'active':'';?>" href="/subresidency">Licence</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="submenu"> 
                    <?php
                    $mangae_lts = request()->is("attendance*") || request()->is("leaves*") || request()->is("shifting*") ||  request()->is("user_scheduling*") ||  request()->is("store_daily_sales*") || request()->is("scheduling*");
                ?>
                    <a href="#" class="<?php echo e($mangae_lts ? 'active-ho-jao' : ''); ?>"><i class="la la-files-o"></i> <span> Manage LTS </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <?php if($is_admin > 0) { ?>
                        <li><a class="<?php echo ($page == 'attendance' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/attendance">Attendance</a></li>
                        <?php } ?>
                        <li><a class="<?php echo ($page == 'leaves' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/leaves">Leaves</a></li>
                        <?php if($is_admin != 1) { ?>
                        <li><a class="<?php echo ($page == 'leave_request')?'active':'';?>" href="/leave_request">Leave Request</a></li>
                        <!-- <li><a class="<?php echo ($page == 'emp_attendance_list')?'active':'';?>" href="/emp_attendance_list">Attendance</a></li> -->
                        <?php } ?>
                        <?php if($is_admin > 0) { ?>
                            <li><a class="<?php echo ($page == 'admin_leaves')?'active':'';?>" href="/admin_leaves">Leave Hierarchy</a></li>
                        <li><a class="<?php echo ($page == 'shifting' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/shifting">Shifting</a></li>
                        <li><a class="<?php echo ($page == 'scheduling' || $page == 'estimate-view.php' || $page == 'edit-estimate.php' || $page == 'create-estimate.php')?'active':'';?>" href="/scheduling">Scheduling</a></li>
                        <?php } ?>
                        <?php if(!empty($is_store_user)): ?>
                        <li><a class="<?php echo ($page == 'user_scheduling')?'active':'';?>" href="/user_scheduling">Scheduling</a></li>
                        <li><a class="<?php echo ($page == 'store_daily_sales')?'active':'';?>" href="/store_daily_sales">Daily Sales</a></li>
                        <?php endif; ?>
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
                        <?php if($is_admin > 0) { ?>
                        <li><a class="<?php echo ($page == 'bonus' )?'active':'';?>" href="/bonus">Bonus</a></li>
                        <li><a class="<?php echo ($page == 'deduction')?'active':'';?>" href="/deduction">Deduction</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>
                <li class="menu-title"> 
                    <span>Pages</span>
                </li>
                <!-- <li class="submenu">
                    <a href="#"><i class="la la-user"></i> <span> Profile </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'profile.php')?'active':'';?>" href="profile.php"> Employee Profile </a></li>
                    </ul>
                </li> -->
                <?php if($is_admin > 0) { ?>
                <li class="submenu">
                    <a href="#"><i class="la la-cog"></i> <span> Company </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        
                            <a class="<?php echo ($page == 'company-settings')?'active':'';?>" href="/company-settings"><i class="la la-cog"></i> <span>Company</span></a>
                            <a class="<?php echo ($page == 'transportation')?'active':'';?>" href="/transportation"><i class="la la-cog"></i> <span>Transportation</span></a>
                       
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-cog"></i> <span> Accounts </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        
                            <a class="<?php echo ($page == 'civil_reports')?'active':'';?>" href="/civil_reports"><i class="la la-cog"></i> <span>Civil ID</span></a>
                            <a class="<?php echo ($page == 'baladiya_reports')?'active':'';?>" href="/baladiya_reports"><i class="la la-cog"></i> <span>Baladeya ID</span></a>
                            <a class="<?php echo ($page == 'company_reports')?'active':'';?>" href="/company_reports"><i class="la la-cog"></i> <span>Company Licenses</span></a>
                            <a class="<?php echo ($page == 'transport_reports')?'active':'';?>" href="/transport_reports"><i class="la la-cog"></i> <span>Transport Licenses</span></a>
                            <a class="<?php echo ($page == 'passport_reports')?'active':'';?>" href="/passport_reports"><i class="la la-cog"></i> <span>Passport</span></a>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-cog"></i> <span> Sales</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        
                            <a class="<?php echo ($page == 'selling_period')?'active':'';?>" href="/selling_period"><i class="la la-cog"></i> <span>Selling Period</span></a>
                            <a class="<?php echo ($page == 'sales_target')?'active':'';?>" href="/sales_target"><i class="la la-cog"></i> <span>Sales Target</span></a>
                            <a class="<?php echo ($page == 'tracking_heading')?'active':'';?>" href="/tracking_heading"><i class="la la-cog"></i> <span>Tracking Heading</span></a>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<?php /**PATH C:\wamp64_new\www\hrm\resources\views/includes/sidebar.blade.php ENDPATH**/ ?>