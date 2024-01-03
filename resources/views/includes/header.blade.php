<?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[1];

$username = Session::get('username');
?>
<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>

    <title>HRM</title>

    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="Smarthr - PHP Admin Template">
<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
<meta name="author" content="Dreamguys - PHP Admin Template">
<meta name="robots" content="noindex, nofollow">
<!-- App favicon -->
<link rel="shortcut icon" href="assets/img/logo1.png">

        <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

<!-- Lineawesome CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/material.css') }}">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

<?php  if ($page == 'admin-dashboard.php') {   ?>
<!-- Chart CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css') }}">

<?php } if ($page == 'branch' || $page == 'user_scheduling' || $page == 'transportation' || $page == 'bonus' || $page == 'deduction' || $page == 'events.php' || $page == 'employee' || $page == 'admin_leaves' || $page == 'employeeSearch' || $page == 'employeeProfileUpdate' || $page == 'leaves' || $page == 'leave-settings' || $page == 'company-settings' || $page == 'attendance' || $page == 'holidays' || $page == 'company-settings-update' || $page == 'residency' || $page == 'subresidency' || $page == 'department' || $page == 'designation' || $page == 'branch' || $page == 'scheduling' || $page == 'scheduling' || $page == 'shifting' || $page == 'payroll-items'|| $page == 'employee-salary' || $page == 'employee-overtime' || $page == 'client-profile.php' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'ticket-view.php' || $page == 'leaves' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php'|| $page == 'provident-fund.php' || $page == 'taxes.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php'|| $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'settings.php' || $page == 'localization.php' || $page == 'email-settings.php' || $page == 'performance-setting.php' || $page == 'approval-setting.php' || $page == 'toxbox-setting.php' || $page == 'cron-setting.php' || $page == 'profile.php' || $page == 'subscriptions.php' || $page == 'subscribed-companies.php' || $page == 'components.php' || $page == 'form-horizontal.php' || $page == 'form-vertical.php') { ?>
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

<?php } if ($page == 'branch' || $page == 'user_scheduling' || $page == 'emp_attendance_list' || $page == 'events.php' || $page == 'documentsa' || $page == 'employee' || $page == 'employeeSearch' || $page == 'employeeProfileUpdate' || $page == 'employees-list.php' || $page == 'holidays' || $page == 'leavesx' || $page == 'leave-settings' || $page == 'company-settings' || $page == 'attendance' || $page == 'attendance-employee.php' || $page == 'scheduling' || $page == 'scheduling' || $page == 'shifting' || $page == 'overtime.php'|| $page == 'employee-salary' || $page == 'employee-overtime' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'leaves' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'categories.php' || $page == 'sub-category.php' || $page == 'budgets.php' || $page == 'budget-expenses.php' || $page == 'budget-revenues.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'training.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'job-details.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'profile.php' || $page == 'components.php') { ?>
<!-- Datetimepicker CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

<?php } if ($page == 'events.php') { ?>
<!-- Calendar CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}">

<?php } if ($page == 'branch' || $page == 'deduction' || $page == 'emp_attendance_list' || $page == 'dashboard' || $page == 'bonus' || $page == 'employees-list.php' || $page == 'admin_leaves' || $page == 'leave_request' || $page == 'leaves' || $page == 'leave-settings' || $page == 'department' || $page == 'designation' || $page == 'residency' || $page == 'subresidency' || $page == 'branch' || $page == 'scheduling' || $page == 'holidays' || $page == 'shifting' || $page == 'company-settings'|| $page == 'employee-salary' || $page == 'employee-overtime' || $page == 'project-list.php' || $page == 'leads.php' || $page == 'tickets.php' || $page == 'payments.php' || $page == 'expenses.php'|| $page == 'provident-fund.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'leave-type.php' || $page == 'subscribed-companies.php' || $page == 'data-tables.php') { ?>
<!-- Datatable CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">

<?php } if ($page == 'leave-settings.php') { ?>
<!-- Tagsinput CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

<?php } if ($page == 'Branch' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'tasks.php') { ?>
<!-- Ck Editor -->
<link rel="stylesheet" href="{{ asset('assets/css/ckeditor.css') }}">

<?php } if ($page == 'profile.php' || $page == 'components.php') {?>
<!-- Tagsinput CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
<?php } ?>
<!-- Main CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<style type="text/css">

.error
{
	color:red;
}

.hideit {
  display: none!important;
}

.container {
    height: 50vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
h1{
    font-size: 1.5rem;
}
.ring{
    width: 150px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.ring::before{
    content: '';
    width: 150px;
    height: 150px;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 50%;
    animation: ring 2s linear infinite;
}
@keyframes ring {
    0%{
        transform: rotate(0deg);
        box-shadow: 0px 5px 5px #ff2121;
    }
    25%{
        transform: rotate(90deg);
        box-shadow: 0px 5px 5px #fffb21;
    }
    50%{
        transform: rotate(180deg);
        box-shadow: 0px 5px 5px #21c0ff;
    }
    75% {
        transform: rotate(270deg);
        box-shadow: 0px 5px 5px #bc21ff;
    }
    100% {
        transform: rotate(360deg);
        box-shadow: 0px 5px 5px #ff2121;
    }
}

.buttons-pdf
{
    background-color: #ff9b44;
    border: 1px solid #ff9b44;
    color: #ffffff;
    /* float: right; */
    font-weight: 500;
    min-width: 140px;
    border-radius: 50px;
    padding: 0.375rem 1.75rem;
    font-size: 1rem;
}
</style>

    </head>



<body>
    <div class="main-wrapper">
        <!-- Header -->
<div class="header">
 
<!-- Logo -->
<div class="header-left">
     <a href="admin-dashboard.php" class="logo">
        <img src="{{ asset('assets/img/logo1.png') }}" width="40" height="40" alt="">
    </a>
    <a href="admin-dashboard.php" class="logo2">
        <img src="{{ asset('assets/img/logo1.png') }}" width="40" height="40" alt="">
    </a>
</div>
<!-- /Logo -->
			    
			    <a id="toggle_btn" href="javascript:void(0);">
			        <span class="bar-icon">
			            <span></span>
			            <span></span>
			            <span></span>
			        </span>
			    </a>
			    
			    <!-- Header Title -->
			    <div class="page-title-box">
			        <h3>Mado Human Resource Portal</h3>
			    </div>
			    <!-- /Header Title -->
			    
			    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
			    
			    <!-- Header Menu -->
			    <ul class="nav user-menu">
			    
			        <!-- Search -->
			        <li class="nav-item">
			            <div class="top-nav-search">
			                <a href="javascript:void(0);" class="responsive-search">
			                    <i class="fa fa-search"></i>
			               </a>
			                <form action="search.php">
			                    <input class="form-control" type="text" placeholder="Search here">
			                    <button class="btn" type="submit"><i class="fa fa-search"></i></button>
			                </form>
			            </div>
			        </li>
			        <!-- /Search -->
			        
			        <!-- Flag -->
			        <li class="nav-item dropdown has-arrow flag-nav">
			            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
			                <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="20"> <span>English</span>
			            </a>
			            <div class="dropdown-menu dropdown-menu-right">
			                <a href="javascript:void(0);" class="dropdown-item">
			                    <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="16"> English
			                </a>
			                <a href="javascript:void(0);" class="dropdown-item">
			                    <img src="{{ asset('assets/img/flags/Kw.png') }}" alt="" height="16"> Arabic
			                </a>
			               
			            </div>
			        </li>
			        <!-- /Flag -->
			        
			        <!-- Notifications -->
			  
			        <!-- /Notifications -->
			        
			        

			        <li class="nav-item dropdown has-arrow main-drop">
			            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
			                <span class="user-img"><img src="{{ asset('assets/img/profiles/avatar-21.jpg') }}" alt="">
			                <span class="status online"></span></span>
			                <span><?php echo ucfirst($username); ?></span>
			            </a>
			            <div class="dropdown-menu">
			                <a class="dropdown-item" href="{{route('change-password')}}">Change Password</a>
			                <a class="dropdown-item" href="/logout">Logout</a>
			            </div>
			        </li>
			    </ul>
			    <!-- /Header Menu -->
			    
			    <!-- Mobile Menu -->
			    <div class="dropdown mobile-user-menu">
			        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
			        <div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="{{route('change-password')}}">Change Password</a>
			            <a class="dropdown-item" href="/logout">Logout</a>
			        </div>
			    </div>
			    <!-- /Mobile Menu -->
			    
			</div>
			<!-- /Header -->