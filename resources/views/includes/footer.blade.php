		</div>
        <!-- /Main Wrapper -->
        <?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[1];
?>
	<!-- jQuery -->
	<script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>

	<!-- Bootstrap Core JS -->
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

	<!-- Theme Settings JS -->
	<script src="{{ asset('assets/js/layout.js') }}"></script>
	<script src="{{ asset('assets/js/theme-settings.js') }}"></script>
	<script src="{{ asset('assets/js/greedynav.js') }}"></script>

	<!-- Slimscroll JS -->
	<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

	<?php  if ($page == 'admin-dashboard.php') {   ?>
	<!-- Chart JS -->
	<script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
	<script src="{{ asset('assets/js/chart.js') }}"></script>
	<script src="{{ asset('assets/js/greedynav.js') }}"></script>

	<?php } if ($page == 'events.php' || $page == 'user_scheduling' || $page == 'deduction' || $page == 'employee' || $page == 'bonus' || $page == 'admin_leaves' || $page == 'employeeSearch' || $page == 'employeeProfileUpdate' || $page == 'company-settings' || $page == 'leaves' || $page == 'leaves-employee.php' || $page == 'leave-settings' || $page == 'attendance' || $page == 'holidays' || $page == 'company-settings-update' || $page == 'residency' || $page == 'designation' || $page == 'branch'  || $page == 'subresidency' || $page == 'scheduling' || $page == 'employee-overtime' || $page == 'shifting' || $page == 'payroll-items' || $page == 'employee-salary' || $page == 'clients-list.php' || $page == 'client-profile' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'ticket-view.php' || $page == 'leaves' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'taxes.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'settings.php' || $page == 'localization.php' || $page == 'email-settings.php' || $page == 'performance-setting.php' || $page == 'approval-setting.php' || $page == 'toxbox-setting.php' || $page == 'cron-setting.php' || $page == 'profile' || $page == 'subscriptions.php' || $page == 'subscribed-companies.php' || $page == 'components.php' || $page == 'form-horizontal.php' || $page == 'form-vertical.php') { ?>
<!-- Select2 JS -->
	<script src="{{ asset('assets/js/select2.min.js') }}"></script>

	<?php } if ($page == 'events.php' || $page == 'user_scheduling' || $page == 'documentss' || $page == 'emp_attendance_list' || $page == 'employee' || $page == 'employeeSearch' || $page == 'employeeProfileUpdate' || $page == 'employees-list.php' || $page == 'holidays' || $page == 'leavesx' || $page == 'company-settings' || $page == 'leave-settings' || $page == 'attendance' || $page == 'employee-salary' || $page == 'scheduling' || $page == 'shift-scheduling.php' || $page == 'shifting' || $page == 'employee-overtime' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'leaves' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'categories.php' || $page == 'sub-category.php' || $page == 'budgets.php' || $page == 'budget-expenses.php' || $page == 'budget-revenues.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'expense-reports.php' || $page == 'expense-reports.php' || $page == 'payments-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'training.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'job-details.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'profile' || $page == 'components.php') { ?>
<!-- Datetimepicker JS -->
	<script src="{{ asset('assets/js/moment.min.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

	<?php } if ($page == 'events.php' || $page == 'employee' || $page == 'employees-list.php' ) { ?>
<!-- Calendar JS -->
	<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.fullcalendar.js') }}"></script>

	<?php } if ($page == 'employees-list.php' || $page == 'deduction' || $page == 'emp_attendance_list' || $page == 'dashboard' || $page == 'leaves' || $page == 'bonus' || $page == 'admin_leaves' || $page == 'leave_request' || $page == 'leaves-employee.php' || $page == 'department' || $page == 'residency' || $page == 'designation' || $page == 'branch'  || $page == 'subresidency' || $page == 'scheduling' || $page == 'company-settings' || $page == 'shifting' || $page == 'overtime.php' || $page == 'holidays' || $page == 'employee-salary' || $page == 'employee-overtime' || $page == 'leads.php' || $page == 'tickets.php' || $page == 'payments.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'expense-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'leave-type.php' || $page == 'subscribed-companies.php' || $page == 'data-tables.php') { ?>
<!-- Datatable JS -->
	<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
	<!-- <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script> -->
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


	<?php } if ($page == 'leave-settings') { ?>
<!-- Multiselect JS -->
	<script src="{{ asset('assets/js/multiselect.min.js') }}"></script>

	<?php } if ($page == 'client-profile' || $page == 'project-view.php' || $page == 'tasks.php') { ?>
<!-- Task JS -->
	<script src="{{ asset('assets/js/task.js') }}"></script>

	<?php } if ($page == 'projects.php' || $page == 'project-list.php' || $page == 'tasks.php') { ?>
<!-- Ck Editor JS -->
	<script src="{{ asset('assets/js/ckeditor.js') }}"></script>

	<?php } if ($page == 'user-dashboard.php' || $page == 'jobs-dashboard.php') { ?>
<!-- Chart JS -->
	<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
	<script src="{{ asset('assets/js/line-chart.js') }}"></script>

	<?php } if ($page == 'profile' || $page == 'components.php') { ?>
<!-- Tagsinput JS -->
	<script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

	<?php } if ($page == 'components.php') {  ?>
<!-- Sticky-kit -->
	<script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>

	<?php } if ($page == 'form-mask.php') { ?>
<!-- Mask JS -->
	<script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/mask.js') }}"></script>

    <?php } if ($page == 'form-validation.php') { ?>
<!-- Form Validation JS -->
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <?php } ?>


	<!-- Form Validation JS -->
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script> 
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script> 

<!-- Custom JS -->
	<script src="{{ asset('assets/js/app.js') }}"></script>

	@include('includes/custom_js')

    </body>

</html>


	



