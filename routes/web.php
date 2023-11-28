<?php

use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DesignationController;use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidencyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\ShiftingController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::group(['middleware' => 'auth'], function () {
	
	Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');

	//Employees
	Route::match(array('GET','POST'),'/employee', [EmployeeController::class, 'index']);


	// Shifting
	Route::get('/shifting', [ShiftingController::class, 'index'])->name('shifting');
	Route::post('/shiftInsert',[ShiftingController::class, 'store'] );
	Route::post('/addschedule',[ShiftingController::class, 'addschedule'] );
	Route::get('getShiftbyId/{id}',[App\Http\Controllers\ShiftingController::class, 'getShiftbyId']);
	Route::get('getShiftbyId/{id}',[App\Http\Controllers\ShiftingController::class, 'getShiftbyId']);
	Route::post('/shiftUpdate', [ShiftingController::class, 'update']);
	Route::post('/shiftDelete', [ShiftingController::class, 'delete']);
	Route::post('/shiftImport', [ShiftingController::class, 'import'])->name('shiftImport');


	// Scheduling
	Route::match(array('GET','POST'),'/scheduling', [SchedulingController::class, 'index'])->name('scheduling');
	Route::post('/scheduleInsert',[SchedulingController::class, 'store'] );
	Route::post('/scheduleUpdate', [SchedulingController::class, 'update']);
	Route::post('/scheduleImport', [SchedulingController::class, 'import']);
	Route::get('/employeeByDepartment/{id}', [SchedulingController::class, 'employeeByDepartment'])->name('employeeByDepartment');
	Route::get('/shiftDetails/{id}', [SchedulingController::class, 'shiftDetails'])->name('shiftDetails');



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	Route::get('/change-password', [ProfileController::class, 'change_password'])->name('change-password');
	Route::post('/change-password', [ProfileController::class, 'post_change_password'])->name('post-change-password');
});

// department 
Route::get('/department', [DepartmentsController::class, 'index'])->name('department');
Route::post('/departmentInsert',[DepartmentsController::class, 'store'] );
Route::post('/departmentUpdate', [DepartmentsController::class, 'update']);
Route::post('/departmentDelete', [DepartmentsController::class, 'delete']);
Route::post('/isDepartmentExists', [DepartmentsController::class, 'isDepartmentExists'])->name('isDepartmentExists');

// designation
Route::get('/designation', [DesignationController::class, 'index'])->name('designation');
Route::post('/designationInsert',[DesignationController::class, 'store']);
Route::post('/designationUpdate', [DesignationController::class, 'update']);
Route::post('/designationDelete', [DesignationController::class, 'delete']);
Route::post('/isDesignationExists', [DesignationController::class, 'isDesignationExists'])->name('isDesignationExists');

// Branch 
Route::get('/branch', [BranchController::class, 'index'])->name('branch');
Route::post('/branchInsert',[App\Http\Controllers\BranchController::class, 'store']);
Route::post('/branchUpdate',[App\Http\Controllers\BranchController::class, 'update']);
Route::post('/branchDelete',[App\Http\Controllers\BranchController::class, 'delete']);
Route::post('/isBranchExists', [App\Http\Controllers\BranchController::class, 'isBranchExists'])->name('isBranchExists');

// Residency
Route::get('/residency', [ResidencyController::class, 'index'])->name('residency');
Route::post('/residencyInsert',[ResidencyController::class, 'store']);
Route::post('/residencyUpdate',[ResidencyController::class, 'update']);
Route::post('/residencyDelete',[ResidencyController::class, 'delete']);
Route::post('/isCompanyExists', [ResidencyController::class, 'isCompanyExists'])->name('isCompanyExists');

// Subresidency 
Route::get('/subresidency', [App\Http\Controllers\SubresidencyController::class, 'index'])->name('subresidency');
Route::post('/subresidencyInsert',[App\Http\Controllers\SubresidencyController::class, 'store']);
Route::post('/subresidencyUpdate',[App\Http\Controllers\SubresidencyController::class, 'update']);
Route::post('/subresidencyDelete',[App\Http\Controllers\SubresidencyController::class, 'delete']);
Route::post('/isSubresidencyExists', [App\Http\Controllers\SubresidencyController::class, 'isSubresidencyExists'])->name('isSubresidencyExists');
// Employee 
// Route::get('/employee', function () {
//     return view('edbr.employee');
// });
Route::get('/employeeProfileUpdate',[App\Http\Controllers\EmployeeController::class, 'update']);
Route::get('/change_manual_punchin_status/{user_id}/{status}',[App\Http\Controllers\EmployeeController::class, 'change_manual_punchin_status']);
Route::post('/employeeSearch', [EmployeeController::class, 'search']);


Route::post('/employeeInsert',[EmployeeController::class, 'store']);

Route::post('/employeeDelete', [EmployeeController::class, 'delete']);

Route::get('/emp_profile_edit',[App\Http\Controllers\EmployeeController::class, 'profile_edit']);
Route::post('employeeDetails/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_details']);
//Route::post('/ProfileInsert',[App\Http\Controllers\ProfileController::class, 'store']);
Route::post('emergencyContact/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_contacts']);
Route::post('employeeEducation/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_education']);
Route::post('employeeExperience/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_Experience']);
Route::post('employeeAccounts/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_accounts']);
Route::post('employeeSalary/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_salary']);
Route::post('employeeInformationUpdate/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_info_update']);
Route::post('employeeLoan/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_loan']);
Route::get('getDesignaton/{id}',[App\Http\Controllers\EmployeeController::class, 'designation_dependent']);
//Route::post('/EmployeeInsert',[App\Http\Controllers\EmployeeController::class, 'store']);
Route::get('/emp_profile',[App\Http\Controllers\EmployeeController::class, 'edit']);
Route::get('/emp_profile_edit',[App\Http\Controllers\EmployeeController::class, 'profile_edit']);
Route::post('employeeDetails/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_details']);
//Route::post('/ProfileInsert',[App\Http\Controllers\ProfileController::class, 'store']);
Route::post('emergencyContact/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_contacts']);
Route::post('employeeEducation/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_education']);
Route::post('employeeEducationDelete',[App\Http\Controllers\EmployeeController::class, 'employee_education_delete']);

Route::post('employeeExperience/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_experience']);
Route::post('employeeExperienceDelete',[App\Http\Controllers\EmployeeController::class, 'employee_experience_delete']);


Route::post('employeeAccounts/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_accounts']);
Route::post('employeeDocuments/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_documents']);
Route::post('employeeDocumentDelete',[App\Http\Controllers\EmployeeController::class, 'employee_document_delete']);

Route::post('employeeSalary/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_salary']);
Route::post('employeeInformationUpdate/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_info_update']);
Route::post('employeeLoan/{id}',[App\Http\Controllers\EmployeeController::class, 'employee_loan']);
Route::get('getDesignaton/{id}',[App\Http\Controllers\EmployeeController::class, 'designation_dependent']);
Route::get('getDepartmentByCompany/{id}',[App\Http\Controllers\EmployeeController::class, 'company_department']);
Route::get('getDesignationByDepartment/{id}',[App\Http\Controllers\EmployeeController::class, 'department_designation']);
Route::post('/isUsernameExists', [App\Http\Controllers\EmployeeController::class, 'isUsernameExists'])->name('isUsernameExists');
Route::post('/isEmailExists', [App\Http\Controllers\EmployeeController::class, 'isEmailExists'])->name('isEmailExists');
Route::post('/isEmployeeIdExists', [App\Http\Controllers\EmployeeController::class, 'isEmployeeIdExists'])->name('isEmployeeIdExists');
Route::get('getByCompany/{id}',[App\Http\Controllers\EmployeeController::class, 'getByCompany']);

Route::post('/employeeOpeningLeaveUpdate/{id}', [EmployeeController::class, 'employeeOpeningLeaveUpdate']);
Route::post('/employeeLeaveAmountUpdate/{id}', [EmployeeController::class, 'employeeLeaveAmountUpdate']);
Route::post('/employeeImport', [EmployeeController::class, 'import'])->name('employeeImport');
Route::post('/employeephUpdate/{id}', [EmployeeController::class, 'employeephUpdate']);


// attendance
Route::match(array('GET','POST'),'/attendance',[App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance');
Route::post('/attendanceInsert',[App\Http\Controllers\AttendanceController::class, 'store']);
Route::post('/isAttendanceExists', [App\Http\Controllers\AttendanceController::class, 'isAttendanceExists'])->name('isAttendanceExists');
Route::post('/getAttendanceDetails',[App\Http\Controllers\AttendanceController::class, 'getAttendanceDetails']);
Route::post('/getempAttendanceDetails',[App\Http\Controllers\AttendanceController::class, 'getempAttendanceDetails']);
Route::get('/getAttendanceTime',[App\Http\Controllers\AttendanceController::class, 'attendanceHoursCalculation']);
Route::post('/create_attendance_by_date',[App\Http\Controllers\AttendanceController::class, 'create_attendance_by_date'])->name('create_attendance_by_date');
Route::get('/save_clock_data/{type}',[App\Http\Controllers\AttendanceController::class, 'save_clock_data'])->name('save_clock_data');
Route::match(array('GET','POST'),'/emp_attendance_list',[App\Http\Controllers\AttendanceController::class, 'emp_attendance_list'])->name('emp_attendance_list');


Route::post('/approveOt',[App\Http\Controllers\AttendanceController::class, 'approveOt'])->name('approveOt');
Route::get('/testit',[App\Http\Controllers\AttendanceController::class, 'testit'])->name('testit');
// LEAVES
// Route::get('/leaves', function () {
//     return view('lts.leaves');
// });
Route::match(array('GET','POST'),'/leaves',[LeavesController::class, 'index'])->name('leaves');
Route::post('/leaveInsert',[LeavesController::class, 'store'] );
Route::post('/leaveApprove',[LeavesController::class, 'leaveApprove'] );
Route::post('/leaveReject',[LeavesController::class, 'leaveReject'] );
Route::post('/leaveCancel',[LeavesController::class, 'leaveCancel'] );
Route::post('/getLeaveDetails',[LeavesController::class, 'getLeaveDetails'] );
Route::get('/leave_request',[LeavesController::class, 'leave_request'] );
Route::post('/getmainLeaveDetailsById',[LeavesController::class, 'getLeaveDetailsById'] );


Route::match(array('GET','POST'),'/bonus',[BonusController::class, 'index'])->name('bonus');
Route::post('/store_bonus',[BonusController::class, 'store'] )->name('bonus.store');
Route::post('/bonus_details',[BonusController::class, 'details'] );
Route::post('/delete_bonus',[BonusController::class, 'delete_bonus'] );


// ****************************** Policies Module ****************************** //

//Employee Salary
Route::match(array('GET','POST'),'/employee-salary', [App\Http\Controllers\PayrollController::class, 'employee_salary']);
Route::get('/employee-salary-details/{id}', [App\Http\Controllers\PayrollController::class, 'employee_salary_details']);
Route::post('/salary_add_ded', [App\Http\Controllers\PayrollController::class, 'salary_add_ded']);
Route::get('/generate-salary-slip/{id}', [App\Http\Controllers\PayrollController::class, 'generate_salary_slip']);
Route::get('/generateFnf/{id}/{status}', [App\Http\Controllers\PayrollController::class, 'generateFnf']);
Route::get('/employee-salary-pdf', [App\Http\Controllers\PayrollController::class, 'employee_salary_pdf']);
Route::get('/changelockpdfstatus/{month}/{year}/{type}', [App\Http\Controllers\PayrollController::class, 'changelockpdfstatus']);

Route::match(array('GET','POST'),'/employee-overtime', [App\Http\Controllers\PayrollController::class, 'employee_overtime']);
Route::get('/generate-overtime-slip/{id}', [App\Http\Controllers\PayrollController::class, 'generate_overtime_slip']);
Route::get('/changeovertimelockpdfstatus/{month}/{year}/{type}', [App\Http\Controllers\PayrollController::class, 'changeovertimelockpdfstatus']);

// Holidays
Route::match(array('GET','POST'),'/holidays', [App\Http\Controllers\HolidaysController::class, 'index'])->name('holidays');
Route::post('/holidayInsert',[App\Http\Controllers\HolidaysController::class, 'store'] );
Route::post('/holidayUpdate', [App\Http\Controllers\HolidaysController::class, 'update']);
Route::post('/holidayDelete', [App\Http\Controllers\HolidaysController::class, 'delete']);

//admin leaves
Route::match(array('GET','POST'),'/admin_leaves',[App\Http\Controllers\AdminLeaveController::class, 'index'])->name('admin_leaves');
Route::post('/store',[App\Http\Controllers\AdminLeaveController::class, 'store'] )->name('admin_leaves.store');
//Route::post('/getLeaveDetails',[App\Http\Controllers\AdminLeaveController::class, 'getLeaveDetails'] );
Route::post('/getLeaveDetailsById',[App\Http\Controllers\AdminLeaveController::class, 'getLeaveDetailsById'] );

// Leave Settings
Route::get('/leave-settings', [App\Http\Controllers\LeaveTypeController::class, 'index'])->name('leave-settings');
Route::post('/updateLeaveDetails',[App\Http\Controllers\LeaveTypeController::class, 'updateLeaveDetails'] );

// Overtime
Route::get('/overtime', [App\Http\Controllers\OvertimeController::class, 'index']);
Route::post('/overtimeUpdate', [App\Http\Controllers\OvertimeController::class, 'update']);


// Indemnity
Route::get('/indemnity', [App\Http\Controllers\IndemnityController::class, 'index']);
Route::post('/updateIndemnity', [App\Http\Controllers\IndemnityController::class, 'update']);


// Indemnity
Route::get('/company-settings', [App\Http\Controllers\SettingsController::class, 'companySettings'])->name('company-settings');
Route::get('/company-settings-edit/{id}', [App\Http\Controllers\SettingsController::class, 'companySettingsEdit']);
Route::post('/company-settings-update/{id}', [App\Http\Controllers\SettingsController::class, 'companySettingsUpdate']);


// Payroll Items
Route::get('/payroll-items', [App\Http\Controllers\PayrollController::class, 'items']);
//Addition
Route::post('/additionInsert',[App\Http\Controllers\PayrollController::class, 'additionInsert'] );
Route::post('/additionUpdate',[App\Http\Controllers\PayrollController::class, 'additionUpdate'] );
Route::post('/additionDelete', [App\Http\Controllers\PayrollController::class, 'additionDelete']);
//Overtime
Route::post('/overtimeSettingsInsert',[App\Http\Controllers\PayrollController::class, 'overtimeSettingsInsert'] );
Route::post('/overtimeSettingsUpdate',[App\Http\Controllers\PayrollController::class, 'overtimeSettingsUpdate'] );
Route::post('/overtimeSettingDelete', [App\Http\Controllers\PayrollController::class, 'overtimeSettingDelete']);
//Deduction
Route::post('/deductionInsert',[App\Http\Controllers\PayrollController::class, 'deductionInsert'] );
Route::post('/deductionUpdate',[App\Http\Controllers\PayrollController::class, 'deductionUpdate'] );
Route::post('/deductionDelete', [App\Http\Controllers\PayrollController::class, 'deductionDelete']);



Route::get('/generateSalaryList/{mid}', [App\Http\Controllers\CronjobController::class, 'salaryEntry']);
Route::get('/leaveSalaryCalculate', [App\Http\Controllers\CronjobController::class, 'leaveSalaryCalculate']);
Route::get('/generateOvertimeList/{mid}', [App\Http\Controllers\CronjobController::class, 'overtimeEntry']);
});

Route::get('/ind-test/{id}', [App\Http\Controllers\PayrollController::class, 'calculateIndemnity']);


// Route::post('/generate-list', [App\Http\Controllers\CronjobController::class, 'salaryOvertime']);
Route::get('/cron-list-salary', [App\Http\Controllers\CronjobController::class, 'salaryEntry']);
Route::get('/cron-list-overtime', [App\Http\Controllers\CronjobController::class, 'overtimeEntry']);

Route::get('/docs/', [App\Http\Controllers\BasicController::class, 'index']);
require __DIR__.'/auth.php';
