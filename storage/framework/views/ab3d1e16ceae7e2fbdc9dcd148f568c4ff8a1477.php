<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php 
$tabActive = 1;
if(isset($tab))
{
    $tabActive = $tab;
} 


$currentYear = date('Y');
$currentMonth = date('n');

// Determine the financial year based on the month (Assuming the financial year starts from April)
if ($currentMonth >= 4) {
    $startYear = $currentYear;
    $endYear = $currentYear + 1;
} else {
    $startYear = $currentYear - 1;
    $endYear = $currentYear;
}
 $currentMonthYear = "April ".$startYear."-March ".$endYear;
?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    
    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
        <!-- Page Header -->
        <?php echo $__env->make('includes/breadcrumbs', ['title' => $title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- /Page Header -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img alt="" src="<?php echo e(($user->profile!=null)?'uploads/profile/'.$user->profile:'assets/img/profiles/avatar.png'); ?>"></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0"><?php echo e($user->first_name ? ucfirst($user->first_name).' '.ucfirst($user->last_name)  : "--"); ?>

                                                <?php echo ($user->status=='resigned')?'<span class="badge bg-inverse-danger">Deactivated</span>':'<span class="badge bg-inverse-success">Active</span>'; ?>
                                            </h3>
                                            
                                            <h6 class="text-muted">
                                                <?php echo e($user->employee_designation ? ucfirst($user->employee_designation->name) : ""); ?>

                                                <?php echo e($user->employee_department ?'('. ucfirst($user->employee_department->name).')' : ""); ?>

                                            </h6>

                                            <small class="text-muted"><?php echo e($user->employee_company ? $user->employee_company->name : ""); ?></small>
                                            <div class="staff-id">Branch : <?php echo e((isset($user->employee_branch))?$user->employee_branch->name:''); ?></div>
                                            <div class="staff-id">Employee ID :<?php echo e($user->emp_generated_id ? $user->emp_generated_id : "--"); ?></div>
                                            <div class="small doj text-muted">Date of Join : <?php echo e($user->joining_date ? dateDisplayFormat($user->joining_date) : "--"); ?></div>
                                            <div class="staff-id">Civil Id : <?php echo e(isset($user->employee_details->c_id) ? $user->employee_details->c_id : " --"); ?></div>
                                            
                                               
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                <div class="title">Phone:</div>
                                                <div class="text"><a href=""><?php echo e($user->phone ? $user->phone : "--"); ?></a></div>
                                            </li>
                                            <li>
                                                <div class="title">Email:</div>
                                                <div class="text"><a href=""><?php echo e($user->email ? $user->email : "--"); ?></a></div>
                                            </li>
                                            <li>
                                                <div class="title">Birthday:</div>
                                                <div class="text"><?php echo e($user->employee_details ? dateDisplayFormat($user->employee_details->birthday)  : "--"); ?></div>
                                            </li>
                                            <li>
                                                <div class="title">Address:</div>
                                                <div class="text"><?php echo e($user->local_address ? $user->local_address : "--"); ?></div>
                                            </li>
                                            <li>
                                                <div class="title">Gender:</div>
                                                <div class="text"><?php echo e($user->employee_details ? ucfirst($user->employee_details->gender)  : "--"); ?></div>
                                            </li>
                                            <li>
                                                <div class="title"></div>
                                                <div class="text">
                                                   <div class="avatar-box">
                                                      <div class="avatar avatar-xs">
                                                          
                                                      </div>
                                                   </div>
                                                   
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon" href="<?php echo e('/emp_profile_edit?id='.$user->id); ?>" ><i class="fa fa-pencil"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card tab-box mb-0">
            <div class="row user-tabs ">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==1)?'active':''; ?>">Profile</a></li>
                        <li class="nav-item"><a href="#emp_projects" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==2)?'active':''; ?>">Document</a></li>
                        <li class="nav-item"><a href="#bank_statutory" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==3)?'active':''; ?>">Salary And Loan<small class="text-danger"></small></a></li>

                        <li class="nav-item"><a href="#fnf_settlement" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==4)?'active':''; ?>">FNF Settlement<small class="text-danger"></small></a></li>
                        <li class="nav-item"><a href="#leave_management" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==9)?'active':''; ?>">Leave Management<small class="text-danger"></small></a></li>
                        <li class="nav-item"><a href="#settings" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==8)?'active':''; ?>">Settings<small class="text-danger"></small></a></li>
                        <li class="nav-item"><a href="#cost" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==18)?'active':''; ?>">Cost<small class="text-danger"></small></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content">
        
            <!-- Profile Info Tab -->
            <div id="emp_profile" class="pro-overview tab-pane fade show active">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Personal Informations <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#personal_info_modal"><i class="fa fa-pencil"></i></a></h3>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Visa No.</div>
                                        <div class="text"><?php echo e($user->visa_no ? $user->visa_no : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Passport No.</div>
                                        <div class="text"><?php echo e($user->passport_no ? $user->passport_no : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Passport Exp.</div>
                                        <div class="text"><?php echo e($user->passport_expiry ? dateDisplayFormat($user->passport_expiry) : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Address</div>
                                        <div class="text"><?php echo e((isset($user->local_address)) ? $user->local_address : "--"); ?></div>

                                    </li>
                                    <li>
                                        <div class="title">Religion</div>
                                        <div class="text"><?php echo e($user->employee_details? $user->employee_details->religion : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Marital status</div>
                                        <div class="text"><?php echo e($user->employee_details ? $user->employee_details->marital_status : "--"); ?></div>
                                    </li>

                                    <li>
                                        <div class="title">Hiring Type</div>
                                        <div class="text"><?php echo e(ucfirst($user->hiring_type ?? '--')); ?></div>
                                    </li>
                                   
                                    <li>
                                        <div class="title">No. of children</div>
                                        <div class="text"><?php echo e($user->employee_details ? $user->employee_details->child : "--"); ?></div>
                                    </li>
                                    
                                     <li>
                                        <div class="title">Employment of spouse</div>
                                        <div class="text"><?php echo e($user->employee_details ? $user->employee_details->spouse_employment : "--"); ?></div>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Emergency Contact <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#emergency_contact_modal"><i class="fa fa-pencil"></i></a></h3>
                                <h5 class="section-title">Primary</h5>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name</div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->pri_con_name : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship</div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->pri_con_relation : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Phone </div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->pri_con_phone : "--"); ?><?php echo e($user->employee_contacts ?','. $user->employee_contacts->pri_con_phone2 : ""); ?></div>
                                    </li>
                                </ul>
                                <hr>
                                <h5 class="section-title">Secondary</h5>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name</div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->sec_con_name : "--"); ?> </div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship</div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->sec_con_relation : "--"); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Phone </div>
                                        <div class="text"><?php echo e($user->employee_contacts ? $user->employee_contacts->sec_con_phone : "--"); ?> <?php echo e($user->employee_contacts ? ','.$user->employee_contacts->sec_con_phone2 : ""); ?></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Education Informations <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#education_info"><i class="fa fa-pencil"></i></a></h3>
                                <div class="experience-box">
                                    <ul class="experience-list">  
                                        
                                        <?php $__empty_1 = true; $__currentLoopData = $user->employee_education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $education): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $check_column = $education->institution || $education->degree || $education->start ||  $education->end;
                                            $data_avl = '';
                                            if(!$check_column){
                                                $data_avl = "No education to show";  
                                            }
                                        ?>
                                        <li> 
                                            <?php if(!$data_avl): ?>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name"><?php echo e($education->institution ?? "NA"); ?></a>
                                                        <!-- <div><?php echo e("$user->employee_education->degree"); ?></div> -->
                                                        <div>   
                                                            <?php echo e($education->degree ?? "NA"); ?>

                                                        </div>
                                                        <span class="time"><?php echo e($education->start ?? "NA"); ?>  - <?php echo e($education->end ?? "NA"); ?></span>


                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?php echo e($data_avl); ?>

                                            <?php endif; ?>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        No Information to show
                                        <?php endif; ?>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Experience <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#experience_info"><i class="fa fa-pencil"></i></a></h3>
                                <div class="experience-box">
                                    <ul class="experience-list">
                                         <?php $__empty_1 = true; $__currentLoopData = $user->employee_experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><li>
                                         <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name"><?php echo e($experience_value->company); ?></a>
                                                    <span class="time"><?php echo e($experience_value->period_from); ?> - <?php echo e($experience_value->period_to); ?> </span>
                                                </div>
                                            </div>
                                        </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                No experience to show
                            <?php endif; ?>
                                         
                                       
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Profile Info Tab -->

            <!-- Projects Tab -->
            <div class="tab-pane fade" id="emp_projects">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Documents <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#new_documents_modal"><i class="fa fa-plus"></i></a></h3>
                                <div class="dropdown profile-action">
                                </div>
                                <p class="text-muted">
                                    <div class="table-responsive">
                                        <table class="table custom-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>File</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if($user->employee_document)
                                            {
                                                // echo '<pre>';print_r($user->employee_document);
                                                foreach($user->employee_document as $edoc)
                                                {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <h2><a href="project-view.html"><?php echo ucfirst($edoc->document_title); ?></a></h2>
                                                        <small class="block text-ellipsis">
                                                            <span class="text-muted">Uploaded on : <?php echo dateDisplayFormat($edoc->created_at); ?></span>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <a href="uploads/document/<?php echo $edoc->document_file; ?>" class="text-info" target="_blank"><i class="fa fa-file"></i><?php //echo $edoc->document_file; ?></a>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <!-- <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-pencil m-r-5"></i> Edit</a> -->
                                                                <a class="dropdown-item deleteDocButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_doc" data-data="<?php echo e($edoc->id); ?>"><i class="fa fa-trash-o m-r-5 text-danger"></i></a>
                                                            <!-- </div> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
            </div>
            <!-- /Projects Tab -->

            <!-- Bank Statutory Tab -->
            
            <div class="tab-pane fade" id="bank_statutory">
                
                
            <!----------------------------------------banking&slaray---------------------------------------->  

            <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Banking Information <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#banking_info_modal"><i class="fa fa-pencil"></i></a></h3>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Bank Name.</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->bank_name:'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Branch.</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->branch_name:'--'); ?></div>
                                    </li>
                                    
                                    <li>
                                        <div class="title">Branch Code</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->branch_code:'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Acoount Number</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->account_number:'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">IFSC NUMBER</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->ifsc_number:'--'); ?></div>
                                    </li>
                                   
                                    <li>
                                        <div class="title">Swift Code</div>
                                        <div class="text"><?php echo e($user->employee_accounts?$user->employee_accounts->swift_code:'--'); ?></div>
                                    </li>
                                    
                                    
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Salary Details <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#salary_info_modal"><i class="fa fa-pencil"></i></a></h3>
                                <?php
                                // $fy_array = array(
                                //     '1' =>  'April 2022-March 2023',
                                //     '2' =>  'April 2023-March 2024',
                                //     '3' =>  'April 2024-March 2025'
                                // );
                                // print_r($user->employee_salary[0]);
                                ?>
                                <ul class="personal-info salary-info">
                                    <li>
                                        <div class="title">Financal Year</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && (isset($user->employee_salary->financal_year)) && $user->employee_salary->financal_year > 0) ?$financial_year[$user->employee_salary->financal_year-1]['year_range'] : date('Y').'-'.date('Y')+1); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Basic Salary</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->basic_salary!=NULL) ?'₹ '.number_format($user->employee_salary->basic_salary,2) : '--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Travel Allowance</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->travel_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->travel_allowance,2):'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Food Allowance </div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->food_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->food_allowance,2) : '--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">House Allowance</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->house_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->house_allowance,2):'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Position Allowance </div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->position_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->position_allowance,2) : '--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Phone Allowance</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->phone_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->phone_allowance,2):'--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Other Allowance </div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->other_allowance!=NULL) ?'₹ '.number_format($user->employee_salary->other_allowance,2) : '--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Total Salary</div>
                                        <div class="text"><?php echo e((isset($user->employee_salary) && $user->employee_salary->total_salary!=NULL) ?'₹ '.number_format($user->employee_salary->total_salary,2) : '--'); ?></div>
                                    </li>
                                </ul>
                               
                            </div>
                        </div>
                    </div>
                </div>

                <!----------------------------------------banking&slaray----------------------------------------> 

                <!----------------------------------------Lone---------------------------------------->    
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill ">
                            <div class="card-body">
                                <?php
                                $emiCount = 0;
                                $paid = 0;
                                if(isset($loanDeductions))
                                {
                                    foreach($loanDeductions as $ld)
                                    {
                                        $emiCount++;
                                        $paid += $ld->entry_value;
                                    }
                                }
                                $pendingAmt = (isset($user->employee_loan) && $user->employee_loan->loan_amount!=NULL)?$user->employee_loan->loan_amount-$paid:0; 
                                $pendingEmiCount = (isset($user->employee_loan) && $user->employee_loan->installment!=NULL)?$user->employee_loan->installment-$emiCount:0; 
                                ?>
                                <h3 class="card-title">Loan information <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#loan_info_modal"><i class="fa fa-pencil"></i></a></h3>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Amount</div>
                                        <div class="text"><?php echo e((isset($user->employee_loan) && $user->employee_loan->loan_amount!=NULL) ? $user->employee_loan->loan_amount : '--'); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Date .</div>
                                        <div class="text"><?php echo e((isset($user->employee_loan) && $user->employee_loan->loan_date!=NULL) ? $user->employee_loan->loan_date:'--'); ?></div>
                                    </li>
                                    
                                    <li>
                                        <div class="title">Installments</div>
                                        <div class="text"><?php echo e((isset($user->employee_loan) && $user->employee_loan->installment!=NULL) ? ($user->employee_loan->installment ?$user->employee_loan->installment:'--'):'--'); ?></div>
                                    </li>
                                    
                                    <Hr/>
                                        <h4>Repayment Information</h4>
                                        <Hr/>
                                    <li>
                                        <div class="title">Total  paid</div>
                                        <div class="text"><?php echo e(number_format($paid,2)); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Insta. pending</div>
                                        <div class="text"><?php echo e(($pendingAmt==0)?0:$pendingEmiCount); ?></div>
                                    </li>
                                   
                                    <li>
                                        <div class="title">Outstanding KWD</div>
                                        <div class="text"><?php echo e(number_format($pendingAmt,2)); ?></div>
                                    </li>
                                    
                                    
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!----------------------------------------lone----------------------------------------> 
            </div>
            <!-- /Bank Statutory Tab -->

            <div class="tab-pane fade" id="fnf_settlement">
                <div class="row">
                    <div class="col-md-8 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Full and Final Settlement<a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#personal_info_modal"><i class="fa fa-pencil"></i></a></h3>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <?php if($user->status == 'active') { ?>
                                            <button class="btn btn-warning btn-sm" id="generate_fnf" data-id="<?php echo $user->user_id; ?>" data-bs-toggle="modal" data-bs-target="#confirmModal">Generate FNF</button>

                                            <!-- <button type="button" class="btn btn-warning btn-sm" id="generate_fnf" data-id="<?php echo $user->user_id; ?>">Generate FNF</button> -->
                                        <?php } ?>
                                    </div>

                                    <?php //if($user->status == 'resigned') { ?>
                                    <div class="col-md-12">
                                        <div>
                                            <h4 class="m-b-10"><strong>Salary Information</strong></h4>
                                            <?php if(!empty($salaryDetails)){ ?>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td>Month <span class="float-end"><?php echo date('F', strtotime('2023-'.$salaryDetails->es_month.'-01')); ?> <?php echo $salaryDetails->es_year; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hours Paid <span class="float-end"><?php echo $salaryDetails->total_work_hours; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Month Days <span class="float-end"><?php echo $salaryDetails->month_w_days; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Basic Salary <span class="float-end">KWD <?php echo $salaryDetails->month_salary; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Earnings <span class="float-end"><strong>KWD <?php echo $salaryDetails->total_salary; ?></strong></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                            </div>

                                            <ul class="personal-info">
                                                <?php
                                                    $addtot = 0;
                                                    $dedtot = 0; 
                                                    $totsalary = 0;
                                                    if(!empty($salaryDetails)){ 
                                                        $totsalary = $salaryDetails->total_salary;?>

                                                    <h4 class="m-b-10"><strong>Bonus Information</strong></h4>
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Bonus</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                        <?php
                                                        if(!empty($additions) && isset($additions))
                                                        {
                                                            foreach($additions as $addkey=>$add)
                                                            {
                                                                $addtot += $add->entry_value;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $addkey+1; ?></td>
                                                                <td><?php echo $add->remarks; ?></td>
                                                                <td>KWD <?php echo $add->entry_value; ?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                        }?>
                                                        <tr>
                                                            <th colspan="2">Total</th>
                                                            <th>KWD <?php echo $addtot; ?></th>
                                                        </tr>
                                                    </table>

                                                    <h4 class="m-b-10"><strong>Deduction Information</strong></h4>
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Deduction</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                        <?php
                                                        if(!empty($deductions) && isset($deductions))
                                                        {
                                                            foreach($deductions as $dedkey=>$ded)
                                                            {
                                                                $dedtot += $ded->entry_value;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $dedkey+1; ?></td>
                                                                <td><?php echo $ded->remarks; ?></td>
                                                                <td>KWD <?php echo $ded->entry_value; ?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                        }?>
                                                        <tr>
                                                            <th colspan="2">Total</th>
                                                            <th>KWD <?php echo $dedtot; ?></th>
                                                        </tr>
                                                    </table>
                                                
                                                    <h4 class="m-b-10"><strong>Overtime Information</strong></h4>
                                                    <hr/>
                                                    <li>
                                                        <div class="title">Total OT Hours</div>
                                                        <div class="text"><?php echo (isset($salaryDetails->total_work_overtime))?$salaryDetails->total_work_overtime:''; ?></div>
                                                    </li>

                                                    <li>
                                                        <div class="title">Total OT Amount</div>
                                                        <div class="text"><?php echo (isset($salaryDetails->total_overtime_salary))?number_format($salaryDetails->total_overtime_salary,2):''; ?></div>
                                                    </li>
                                                <?php } ?>

                                                <?php $totIndemnity = 0; 
                                                if(!empty($indemnityDetails) && count($indemnityDetails) > 0){ 
                                                    $totIndemnity = (!empty($indemnityDetails))?$indemnityDetails[count($indemnityDetails)-1]->total_amount:0;?>
                                                    <Hr/>
                                                    <h4 class="m-b-10"><strong>Indemnity Information</strong></h4>
                                                    <Hr/>
                                                    <li>
                                                        <div class="title">Last W.Day</div>
                                                        <div class="text"><?php echo (!empty($indemnityDetails) && isset($indemnityDetails[0]->today_date))?dateDisplayFormat($indemnityDetails[0]->today_date):''; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Salary</div>
                                                        <div class="text">KWD <?php echo (!empty($indemnityDetails) && (isset($indemnityDetails[0]->current_salary)))?number_format($indemnityDetails[0]->current_salary,2):''; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Total Years</div>
                                                        <div class="text"><?php echo (!empty($indemnityDetails) && (isset($indemnityDetails[0]->years_diff)))?round($indemnityDetails[0]->years_diff/365,2):''; ?></div>
                                                    </li>

                                                    <li>
                                                        <div class="title">Total Indemnity Payable</div>
                                                        <div class="text">KWD <?php echo number_format($totIndemnity,2); ?></div>
                                                    </li>

                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Salary/day</th>
                                                            <th>Months</th>
                                                            <th>Indemnity Amount</th>
                                                            <!-- <th>Indemnity %</th> -->
                                                            <th>Total</th>
                                                        </tr>
                                                    <?php
                                                    foreach($indemnityDetails as $indem)
                                                    {
                                                    ?>
                                                        <tr>
                                                            <td>KWD <?php echo number_format($indem->perday_salary,2); ?></td>
                                                            <td><?php echo $indem->months_taken; ?></td>
                                                            <td><?php echo $indem->indemnity_amount; ?></td>
                                                            <!-- <td>
                                                            <?php //echo $indem->indemnity_perc; ?>
                                                            </td> -->
                                                            <td>KWD <?php echo number_format($indem->total_amount,2); ?></td>
                                                        </tr>
                                                    <?php
                                                    } ?>
                                                    <tr>
                                                        <th colspan="3">Total Payable for <?php echo (!empty($indemnityDetails) && (isset($indemnityDetails[0]->years_diff)))?round($indemnityDetails[0]->years_diff/365,2):''; ?> years</th>
                                                        <th>KWD <?php echo (!empty($indemnityDetails))?number_format($indemnityDetails[count($indemnityDetails)-1]->total_amount,2):''; ?></th>
                                                    </tr>
                                                    </table>
                                                <?php } ?>

                                                    <Hr/>
                                                    <h4 class="m-b-10"><strong>Annual Leaves</strong></h4>
                                                    <Hr/>

                                                    <li>
                                                        <div class="title">Total Leave Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['totalLeaveDays']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Used Leave Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['used']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Leave Balance Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['leaveBalance']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Leave Balance Amount</div>
                                                        <div class="text">KWD <?php echo (isset($annualleavedetails))?number_format($annualleavedetails['leaveAmount'], 2):0; ?></div>
                                                    </li>
                                                    <Hr/>
                                                    <h4 class="m-b-10"><strong>Public Holidays</strong></h4>
                                                    <Hr/>

                                                   <li>
                                                        <div class="title">Worked Days</div>
                                                        <div class="text"><?php echo (isset($user->public_holidays_balance))?$user->public_holidays_balance:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Balance Amount</div>
                                                        <div class="text">KWD <?php echo (isset($user->public_holidays_amount))?$user->public_holidays_amount:0; ?></div>
                                                    </li>

                                                    <hr/>
                                                    <h4 class="m-b-10"><strong>Earnings Summary</strong></h4>
                                                    <div>
                                                        <?php
                                                        $totadditions = $addtot + $annualleavedetails['leaveAmount'] + $user->public_holidays_amount;
                                                        $total_overtime_salary = (isset($salaryDetails->total_overtime_salary) && $salaryDetails->total_overtime_salary >0)?$salaryDetails->total_overtime_salary:0;
                                                        $totpayable = ($totsalary + $addtot + $total_overtime_salary + $totIndemnity + $annualleavedetails['leaveAmount'] + $user->public_holidays_amount) - $dedtot;
                                                        ?>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td><strong>Total Salary</strong> <span class="float-end">KWD <?php echo number_format($totsalary, 2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Bonus</strong> <span class="float-end">KWD <?php echo number_format($addtot, 2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Overtime</strong> <span class="float-end">KWD <?php echo number_format($total_overtime_salary, 2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Indemnity</strong> <span class="float-end">KWD <?php echo number_format($totIndemnity,2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Annual Leave</strong> <span class="float-end">KWD <?php echo number_format($annualleavedetails['leaveAmount'],2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Public Holiday</strong> <span class="float-end">KWD <?php echo number_format($user->public_holidays_amount,2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Deductions</strong> <span class="float-end">KWD <?php echo number_format($dedtot, 2); ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total Payable</strong> <span class="float-end"><strong>KWD <?php echo number_format($totpayable, 2); ?></strong></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </ul>

                                                <div class="col-sm-12">
                                                    <p><strong>Net Payable: KWD <?php echo number_format($totpayable, 2); ?></strong> (<?php echo ucwords(numberToWords($totpayable)); ?>)</p>
                                                </div>
                                            </div>
                                        <?php //} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <div class="tab-pane fade" id="leave_management">
                        <div class="card tab-box mb-0">
                            <div class="row user-tabs">
                                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                                    <ul class="nav nav-tabs nav-tabs-bottom">
                                        <li class="nav-item"><a href="#vacation_settlement" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==1)?'active':''; ?>">Vacation Settlements<small class="text-danger"></small></a></li>
                                        <li class="nav-item"><a href="#vacation_history" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==10)?'active':''; ?>">Vacation History<small class="text-danger"></small></a></li>
                                        <li class="nav-item"><a href="#annual_leave" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==5)?'active':''; ?>">Annual Leave<small class="text-danger"></small></a></li>
                                        <li class="nav-item"><a href="#Worked_holidays" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==6)?'active':''; ?>">Public Holidays<small class="text-danger"></small></a></li>
                                        <li class="nav-item"><a href="#sick_leave" data-bs-toggle="tab" class="nav-link <?php echo ($tabActive==7)?'active':''; ?>">Sick Leave<small class="text-danger"></small></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content">
                        <div class="tab-pane fade show active" id="vacation_settlement">
                            <div class="row">
                                <div class="col-md-6 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <div class="">
                                                <ul class="personal-info">
                                                    
                                                    <?php if(isset($emp_leaves) && count($emp_leaves) > 0): ?>
                                                        <?php $__currentLoopData = $emp_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                        <li>
                                                            
                                                            <div class="text"><a href="#" class="an_leave_btn" data-bs-toggle="modal" data-bs-target="#an_leave_detail_modal" data-id="<?php echo e($val->id); ?>" data-userid="<?php echo e($user->user_id); ?>"><i class="fa fa-clock"></i> <?php echo e(date('d M y', strtotime($val->leave_from))); ?> - <?php echo e(date('d M y', strtotime($val->leave_to))); ?> (<?php echo e($val->leave_days); ?> Days)</a></div>
                                                        </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <li>No data found</li>
                                                    <?php endif; ?>

                                                </ul>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                        <div class="tab-pane fade" id="vacation_history">
                            <div class="row">
                                <div class="col-md-6 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <div class="">
                                                <ul class="personal-info">
                                                    
                                                    <?php if(isset($emp_leaves_history) && count($emp_leaves_history) > 0): ?>
                                                        <?php $__currentLoopData = $emp_leaves_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                        <li>
                                                            
                                                            <div class="text"><a href="#" class="an_leave_btn" data-bs-toggle="modal" data-bs-target="#an_leave_detail_modal" data-id="<?php echo e($val->id); ?>" data-userid="<?php echo e($user->user_id); ?>" data-type="history"><i class="fa fa-clock"></i> <?php echo e(date('d M y', strtotime($val->leave_from))); ?> - <?php echo e(date('d M y', strtotime($val->leave_to))); ?> (<?php echo e($val->leave_days); ?> Days)</a></div>
                                                        </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <li>No data found</li>
                                                    <?php endif; ?>

                                                </ul>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                        <div class="tab-pane fade" id="annual_leave">
                            <div class="row">
                                <div class="col-md-12 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h3 class="card-title">Annual Leave Information<?php //echo date('Y'); ?>
                                            <!-- <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#opening_leave_info_modal"><i class="fa fa-pencil"></i></a> -->
                                        </h3>
                                            <div class="">
                                                <ul class="personal-info">
                                                    
                                                    <?php
                                                    $al_balance = $annualleavedetails['totalLeaveDays'] ?? 0;
                                                    $al_used = (isset($annualleavedetails['totalLeaveDays']) && $annualleavedetails['totalLeaveDays']>0 && isset($al_balance) && $al_balance > 0)?$annualleavedetails['totalLeaveDays'] - $al_balance:0;
                                                    ?>

                                                    <li>
                                                        <div class="title">Total Leave Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails) && $annualleavedetails['totalLeaveDays']>0 )?$annualleavedetails['totalLeaveDays']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Used Leave Days</div>
                                                        <div class="text"><?php echo e($user->used_leave ?? 0); ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Balance Days</div>
                                                        <?php $cal_leave = (isset($annualleavedetails) && $annualleavedetails['totalLeaveDays']>0 )?$annualleavedetails['totalLeaveDays']:0; 
                                                            $used_leave = $user->used_leave ?? 0;
                                                            $bal_leave = $cal_leave - $used_leave;?>
                                                        <div class="text"><?php echo e($bal_leave ?? 0); ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Balance Amount</div>
                                                        <div class="text"> 
                                                            <?php $e_sal = (isset($user->employee_salary) && !empty($user->employee_salary)) ? $user->employee_salary->basic_salary : 0; ?>
                                                            <?php echo e(number_format(_calculate_salary_by_days($e_sal,$bal_leave ?? 0),2)); ?> KWD
                                                        </div>
                                                    </li>


                                                    <table class="table">
                                                        <tr>
                                                            <th>Date</th>
                                                            <th># Leave was in Bucket</th>
                                                            <th>Applied for</th>
                                                            <th>Claimed for</th>
                                                            <th>Opening Leave Balance</th>
                                                            <th>Claimed Amount</th>
                                                            <th>Closing Leave Balance</th>
                                                        </tr>

                                                        <?php //echo '<pre>';print_r($user->employee_leaves); 
                                                        if(isset($an_emp_leaves) && count($an_emp_leaves) > 0) { 
                                                            foreach($an_emp_leaves as $el) { 
                                                                if($el->leave_type == 1) { 
                                                                    $cur_year = date('Y');
                                                                    $leave_year = date('Y', strtotime($el->leave_to)); 
                                                                    ?>
                                                            <?php if($cur_year == $leave_year): ?>
                                                                <tr>
                                                                    <td><?php echo e(date('d M y', strtotime($el->leave_from))); ?> - <?php echo e(date('d M y', strtotime($el->leave_to))); ?></td>
                                                                    <td><?php echo e(($el->claimed_annual_days_rem ?? 0)); ?> Days</td>
                                                                    <td><?php echo e($el->leave_days ?? 0); ?> Days</td>
                                                                    <td><?php echo e($el->claimed_annual_days ?? 0); ?> Days</td>
                                                                    <td><?php echo e(number_format((_calculate_salary_by_days($el->basic_salary,$el->claimed_annual_days ?? 0)) + (_calculate_salary_by_days($el->basic_salary,$el->claimed_annual_days_rem ?? 0)),2)); ?> KWD</td>
                                                                    <td><?php echo e(number_format((_calculate_salary_by_days($el->basic_salary,$el->claimed_annual_days ?? 0)),2)); ?> KWD</td>
                                                                    <td><?php echo e(number_format((_calculate_salary_by_days($el->basic_salary,$el->claimed_annual_days_rem ?? 0)),2)); ?> KWD</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php } } } else { ?>
                                                            <tr>
                                                                <td colspan="7" align="center">No data found</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>

                                                </ul>
                                                
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 d-flex" style="display: none!important;">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <div class="">
                                                <ul class="personal-info">
                                                    <Hr/>
                                                        <h4>Public Holiday Information</h4>
                                                    <Hr/>
                                                    
                                                    <li>
                                                        <div class="title">Total Public Holidays</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['totalLeaveDays']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Worked Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['used']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Leave Balance Days</div>
                                                        <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['leaveBalance']:0; ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Leave Balance Amount</div>
                                                        <div class="text">KWD <?php echo (isset($annualleavedetails))?number_format($annualleavedetails['leaveAmount'], 2):0; ?></div>
                                                    </li>

                                                    <Hr/>
                                                        <h4>Leave Requests - <?php echo date('Y'); ?></h4>
                                                    <Hr/>

                                                    <table class="table">
                                                        <tr>
                                                            <th>Dates</th>
                                                            <th>No. of Days</th>
                                                            <th>Amount</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        <?php //echo '<pre>';print_r($user->employee_leaves); 
                                                        if(isset($user->employee_leaves)) { 
                                                            foreach($user->employee_leaves as $el) { 
                                                                if($el->leave_type == 1) { ?>
                                                            <tr>
                                                                <td><?php echo $el->leave_from; ?> to <?php echo $el->leave_to; ?></td>
                                                                <td><?php echo $el->leave_days; ?></td>
                                                                <td><?php echo number_format($perday * $el->leave_days , 2); ?></td>
                                                                <td><?php echo $el->leave_status; ?></td>
                                                                <td>
                                                                    <?php if($el->leave_status!=='paid' && $el->leave_status!=='hold'){ ?>
                                                                        <form method="post" action="/employeeLeaveAmountUpdate/<?php echo $el->id; ?>">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="userid" value="<?php echo $user->id; ?>">
                                                                            <input type="hidden" name="nodays" value="<?php echo $el->leave_days; ?>">
                                                                            <input type="hidden" name="lamount" value="<?php echo $perday * $el->leave_days; ?>">
                                                                            <button type="submit" name="status_type" value="paid" class="btn btn-success text-white">Paid</button>
                                                                            <button type="submit" name="status_type" value="hold" class="btn btn-warning text-white">Hold</button>
                                                                        </form>
                                                                    <?php } if($el->leave_status=='paid'){ ?>
                                                                        <span class="badge bg-inverse-success">Paid</span>
                                                                    <?php } if($el->leave_status=='hold'){ ?>
                                                                        <span class="badge bg-inverse-warning">Hold</span>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } } } ?>
                                                    </table>

                                                </ul>
                                                
                                                <div class="col-md-12">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="tab-pane fade" id="Worked_holidays">
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title">Public Holidays </h3>
                                        <div class="">
                                            <ul class="personal-info">
                                                <Hr/>
                                                    <h4>Public Holiday Information</h4>
                                                <Hr/>
                                                <li>
                                                    <div class="title" style="width: 50%;">Available public holidays</div>
                                                    <div class="text"> <?php echo (isset($user->public_holidays_balance))?$user->public_holidays_balance:0; ?></div>
                                                </li>
                                                <?php 
                                                $days = $user->public_holidays_balance ?? 0;
                                                $sal = $user->employee_salary ?$user->employee_salary->basic_salary : 0;
                                                $bal = _calculate_salary_by_days($sal,$days);
                                                ?>
                                                <li>
                                                    <div class="title" style="width: 50%;">Public holidays Balance</div>
                                                    <div class="text"> <?php echo e(number_format($bal,2)); ?> KWD</div>
                                                </li>
                                                <!-- <Hr/>
                                                    <h4 class="pr-3">Work Information</h4>
                                                <Hr/> -->
                                                

                                                <table class="table">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Day</th>
                                                        <th>Holiday</th>
                                                        <th>PH Leave Balance</th>
                                                    </tr>
                                                    
                                                    <?php //echo '<pre>';print_r($user->employee_leaves); 
                                                    if(isset($holidayWork)) { 
                                                        foreach($holidayWork as $hw) {
                                                            ?>
                                                        <tr>
                                                            <td><?php echo e(date('d-m-Y', strtotime($hw->attendance_on))); ?></td>
                                                            <td><?php echo $hw->holiday_day; ?></td>
                                                            <td><?php echo $hw->title ?></td>
                                                            <td>+1</td>
                                                        </tr>
                                                    <?php } } ?>
                                                    <?php if(isset($an_emp_leaves) && count($an_emp_leaves) > 0): ?> 
                                                        <?php $__currentLoopData = $an_emp_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                            <?php if($val->is_post_transaction == 1 && $val->claimed_public_days > 0): ?>
                                                                <tr>
                                                                    <td><?php echo e(date('d-m-Y', strtotime($val->leave_from))); ?> to <?php echo e(date('d-m-Y',
                                                                    strtotime($val->leave_to))); ?></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>-<?php echo e($val->claimed_public_days); ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                    <tr>    
                                                        <td colspan="3">Today days worked <small>(Based on scheduling)</small></td>
                                                        <td><?php echo e($user->public_holidays_balance ?? 0); ?> - days </td>
                                                    </tr>
                                                </table>

                                            </ul>
                                            
                                            <div class="col-md-12">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex" style="display: none!important;">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <div class="row">
                                            <ul class="personal-info">
                                                <Hr/>
                                                    <h4>Public Holiday Information</h4>
                                                <Hr/>
                                                
                                                <li>
                                                    <div class="title">Total Public Holidays</div>
                                                    <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['totalLeaveDays']:0; ?></div>
                                                </li>
                                                <li>
                                                    <div class="title">Worked Days</div>
                                                    <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['used']:0; ?></div>
                                                </li>
                                                <li>
                                                    <div class="title">Leave Balance Days</div>
                                                    <div class="text"><?php echo (isset($annualleavedetails))?$annualleavedetails['leaveBalance']:0; ?></div>
                                                </li>
                                                <li>
                                                    <div class="title">Leave Balance Amount</div>
                                                    <div class="text">KWD <?php echo (isset($annualleavedetails))?number_format($annualleavedetails['leaveAmount'], 2):0; ?></div>
                                                </li>

                                                <Hr/>
                                                    <h4>Leave Requests - <?php echo date('Y'); ?></h4>
                                                

                                                <table class="table">
                                                    <tr>
                                                        <th>Dates</th>
                                                        <th>No. of Days</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    <?php //echo '<pre>';print_r($user->employee_leaves); 
                                                    if(isset($user->employee_leaves)) { 
                                                        foreach($user->employee_leaves as $el) { 
                                                            if($el->leave_type == 1) { ?>
                                                        <tr>
                                                            <td><?php echo $el->leave_from; ?> to <?php echo $el->leave_to; ?></td>
                                                            <td><?php echo $el->leave_days; ?></td>
                                                            <td><?php echo number_format($perday * $el->leave_days , 2); ?></td>
                                                            <td><?php echo $el->leave_status; ?></td>
                                                            <td>
                                                                <?php if($el->leave_status!=='paid' && $el->leave_status!=='hold'){ ?>
                                                                    <form method="post" action="/employeeLeaveAmountUpdate/<?php echo $el->id; ?>">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="userid" value="<?php echo $user->id; ?>">
                                                                        <input type="hidden" name="nodays" value="<?php echo $el->leave_days; ?>">
                                                                        <input type="hidden" name="lamount" value="<?php echo $perday * $el->leave_days; ?>">
                                                                        <button type="submit" name="status_type" value="paid" class="btn btn-success text-white">Paid</button>
                                                                        <button type="submit" name="status_type" value="hold" class="btn btn-warning text-white">Hold</button>
                                                                    </form>
                                                                <?php } if($el->leave_status=='paid'){ ?>
                                                                    <span class="badge bg-inverse-success">Paid</span>
                                                                <?php } if($el->leave_status=='hold'){ ?>
                                                                    <span class="badge bg-inverse-warning">Hold</span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } } } ?>
                                                </table>

                                            </ul>
                                            
                                            <div class="col-md-12">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="sick_leave">
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3>Sick Leave Information</h3>
                                        <div class="mt-3">
                                            <ul class="personal-info">
                                                

                                                <li>
                                                    <div class="title">Total Leave Days</div>
                                                    <div class="text"><?php echo (isset($sickleavedetails) && $sickleavedetails['totalLeaveDays']>0 )?$sickleavedetails['totalLeaveDays']:0; ?></div>
                                                </li>
                                                <li>
                                                    <div class="title">Used Leave Days</div>
                                                    <div class="text"><?php echo e((isset($user->sick_leave_days) && !empty($user->sick_leave_days)) ? $user->sick_leave_days : 0); ?></div>
                                                </li>
                                                <li>
                                                    <div class="title">Balance Days</div>
                                                    <div class="text"><?php echo e($sickleavedetails['remaining_leave_withoutreq']  ?? 0); ?></div>
                                                </li>
                                                <!-- <li>
                                                    <div class="title">Balance Amount</div>
                                                    <div class="text">KWD 
                                                        <?php 
                                                        $sick_days = $sickleavedetails['remaining_leave_withoutreq']  ?? 0;
                                                        $total_amount = $sick_days * $perday;
                                                        echo number_format($total_amount, 2);
                                                         ?>
                                                    </div>
                                                </li> -->

                                                
                                                    <h4 class="mt-3">Leave Requests - <?php echo date('Y'); ?></h4>
                                              

                                                <table class="table">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>No. of Days</th>
                                                        <th>Status</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>

                                                    <?php //echo '<pre>';print_r($user->employee_leaves); 
                                                    if(isset($user->employee_leaves)) { 
                                                        foreach($user->employee_leaves as $el) { 
                                                            if($el->leave_type == 2) { 
                                                                $cur_year = date('Y');
                                                                $leave_year = date('Y', strtotime($el->leave_to)); 
                                                                ?>
                                                        <?php if($cur_year == $leave_year): ?>
                                                            <tr>
                                                                <td><?php echo $el->leave_from; ?> to <?php echo $el->leave_to; ?></td>
                                                                <td><?php echo $el->leave_days; ?></td>
                                                                <!-- <td><?php //echo number_format($perday * $el->leave_days , 2); ?></td> -->
                                                                <td><?php echo $el->leave_status; ?></td>
                                                                <!-- <td>
                                                                    <?php if($el->leave_status!=='paid' && $el->leave_status!=='hold'){ ?>
                                                                        <form method="post" action="/employeeLeaveAmountUpdate/<?php echo $el->id; ?>">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="userid" value="<?php echo $user->id; ?>">
                                                                            <input type="hidden" name="nodays" value="<?php echo $el->leave_days; ?>">
                                                                            <input type="hidden" name="lamount" value="<?php echo $perday * $el->leave_days; ?>">
                                                                            <button type="submit" name="status_type" value="paid" class="btn btn-success text-white">Paid</button>
                                                                            <button type="submit" name="status_type" value="hold" class="btn btn-warning text-white">Hold</button>
                                                                        </form>
                                                                    <?php } if($el->leave_status=='paid'){ ?>
                                                                        <span class="badge bg-inverse-success">Paid</span>
                                                                    <?php } if($el->leave_status=='hold'){ ?>
                                                                        <span class="badge bg-inverse-warning">Hold</span>
                                                                    <?php } ?>
                                                                </td> -->
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php } } } ?>
                                                </table>

                                            </ul>
                                            
                                            <div class="col-md-12">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                    </div>
                        
                    </div>

                    

                    <div class="tab-pane fade" id="settings">
                        <div class="row">
                            <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="success_message">
                                    <strong>Status changed successfully.</strong>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="is_manual_punchin" data-id="<?php echo $user->user_id; ?>" id="is_manual_punchin" value="<?php echo e($user->is_manual_punchin ?? 1); ?>" <?php if(isset($user->is_manual_punchin) && $user->is_manual_punchin==1): ?> checked <?php endif; ?>>
                                            <label class="form-check-label" for="is_manual_punchin">
                                                Enable Manual Punch-In and Out
                                            </label>
        
                                        </div>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="is_passport" data-id="<?php echo $user->user_id; ?>" id="is_passport" value="<?php echo e($user->is_passport ?? 1); ?>" <?php if(isset($user->is_passport) && $user->is_passport==1): ?> checked <?php endif; ?>>
                                            <label class="form-check-label" for="is_passport">
                                                Is passport
                                            </label>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="cost">
                        <div class="row">
                            
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3>Document Cost</h3>
                                        <form  action="<?php echo e(route('save_cost')); ?>" method="post" id="employee_info_update" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="user_id" value="<?php echo e(request()->get('id') ?? ''); ?>">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Civil ID Cost</label>
                                                    <input class="form-control allowfloatnumber" type="text" name="civil_cost" value="<?php echo e($user->employee_details ? $user->employee_details->civil_cost : ''); ?>">
                                                </div>    
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Baladiya Cost</label>
                                                    <input class="form-control allowfloatnumber" type="text" name="baladiya_cost" value="<?php echo e($user->employee_details ? $user->employee_details->baladiya_cost : ''); ?>">
                                                </div>    
                                            </div>
                                            <div class="submit-section">
                                                <button class="btn btn-primary submit-btn">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
            
       
        </div>
    </div>
    <!-- /Page Content -->
    <div id="opening_leave_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Opening Leave Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/employeeOpeningLeaveUpdate/<?php echo $user->id; ?>" method="post" id="employee_opening_leave_update" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            
                                            
            <!-- name -->                                
                                            <label>Leave Balance Days</label>
                                            <input type="text" class="form-control" value="<?php echo e($user->opening_leave_days ? $user->opening_leave_days : ''); ?>" name="leave_balance_days" required>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Leave Balance Amount</label>
                                            <input type="text" class="form-control" value="<?php echo e($user->opening_leave_amount ? $user->opening_leave_amount : ''); ?>" name="leave_balance_amount">
                                        </div>
                                    </div> -->
                                    
                                    
                                   
                                 </div>
                            </div>
                        </div>
                         
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="ph_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Public Holidays Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/employeephUpdate/<?php echo $user->id; ?>" method="post" id="employee_ph_update" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            
                                            
            <!-- name -->                                
                                            <label>Leave Balance Days</label>
                                            <input type="text" class="form-control" value="<?php echo e((isset($user->public_holidays_balance))?$user->public_holidays_balance:0); ?>" name="ph_balance_days" required>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Leave Balance Amount</label>
                                            <input type="text" class="form-control" value="<?php echo e((isset($user->public_holidays_amount))?$user->public_holidays_amount:0); ?>" name="ph_balance_amount">
                                        </div>
                                    </div>
                                    
                                    
                                   
                                 </div>
                            </div>
                        </div>
                         
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Modal -->
    <div id="profile_info" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/employeeInformationUpdate/<?php echo $user->id; ?>" method="post" id="employee_info_update" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-img-wrap edit-img">
                                      
                                     <!-- Profile picture -->
                                    <img class="inline-block" src="<?php echo e(($user->profile!=null)?'uploads/profile/'.$user->profile:'assets/img/profiles/avatar.png'); ?>" alt="user" id="blah">
                                    <div class="fileupload btn">
                                        <span class="btn-text">edit</span>
                                        <input class="upload" type="file" id="imgInp" name="profile">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <!-- name -->                                
                                            <label>First Name</label>
                                            <input type="text" class="form-control" value="<?php echo e($user->first_name ? $user->first_name : ''); ?>" name="first_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" value="<?php echo e($user->last_name ? $user->last_name : ''); ?>" name="last_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Birth Date</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text" value="<?php echo e($user->employee_details ? dateDisplayFormat($user->employee_details->birthday)  : ''); ?>" name="birthday">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="select form-control " name="gender" >
                                                <!-- <option value="<?php echo e($user->employee_details ? $user->employee_details->gender : 'Selectgender'); ?>">
                                               <?php echo e($user->employee_details ? $user->employee_details->gender : ''); ?></option> -->
                                                <option value="male" <?php echo e(isset($user->employee_details->gender) && $user->employee_details->gender=='male' ? 'selected' : ''); ?>>Male</option>
                                                <option value="female" <?php echo e(isset($user->employee_details->gender) && $user->employee_details->gender=='female' ? 'selected' : ''); ?>>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                   
                                 </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?php echo e($user->employee_details ? $user->employee_details->country : ''); ?>" name="country" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?php echo e($user->employee_details ? $user->employee_details->state : ''); ?>" name="state" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?php echo e($user->local_address ? $user->local_address : ''); ?>" name="local_address" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pin Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?php echo e($user->employee_details ? $user->employee_details->pin_code : ''); ?>" name="pin_code" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control"  value="<?php echo e($user->phone ? $user->phone : ''); ?>" name="phone">
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control"  value="<?php echo e($user->email ? $user->email : ''); ?>" name="email">
                                </div>
                            </div>
                             <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of joining</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text" value="<?php echo e($user->joining_date ? dateDisplayFormat($user->joining_date) : ''); ?>" name="joining_date">
                                            </div>
                                        </div>
                                    </div>
                            <HR>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Civil Id <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  value="<?php echo e($user->employee_details ? $user->employee_details->c_id : ''); ?>" name="c_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expiry date of Civil ID</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text"  value="<?php echo e($user->employee_details? dateDisplayFormat($user->employee_details->expi_c_id) : ''); ?>" name="expi_c_id">
                                            </div>
                                        </div>
                                    </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Baladiya Id <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  value="<?php echo e($user->employee_details ? $user->employee_details->b_id : ''); ?>" name="b_id">
                                </div>
                            </div>
                             <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expiry of Baladiya ID</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text"  value="<?php echo e($user->employee_details ? dateDisplayFormat($user->employee_details->expi_b_id): ''); ?>" name="expi_b_id">
                                            </div>
                                        </div>
                                    </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>License </label>
                                    <input type="text" class="form-control"  value="<?php echo e($user->employee_details ? $user->employee_details->license: ''); ?>" name="license">
                                </div>
                            </div>
                          <div class="col-md-6">
                                        <div class="form-group">
                                            <label>license Exp.</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text"  value="<?php echo e($user->employee_details ? dateDisplayFormat($user->employee_details->license_exp): ''); ?>" name="license_exp">
                                            </div>
                                        </div>
                                    </div>
                            
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company <span class="text-danger">*</span></label>
                                    <select class="select" id="company" name="company">
                                        <option  value="">Select Company</option>
                                        <?php
                                        foreach ($company_dropdown as $company_value) {?>
                                            <option  value="<?=$company_value->id?>" <?php echo e($user->company==$company_value->id ? 'selected' : ''); ?>><?=$company_value->name?></option>
                                             <?php  } ?>    
                                             
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <select class="select" id="branch" name="branch">>
                                        <option  value="">Select Branch</option>
                                        <?php
                                        if(isset($user->branch))
                                        {
                                            foreach ($branch_dropdown as $branch_value) {?>
                                            <option value="<?=$branch_value->id?>" <?php echo e($user->branch==$branch_value->id ? 'selected' : ''); ?>><?=$branch_value->name?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>

                            <?php if($user->designation==0 || $user->designation > 2){ ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select class="select"  id="department"  name="department">
                                        <option  value="">Select Department</option>
                                        <?php
                                        if(isset($user->department))
                                        {
                                            foreach ($department_dropdown as $department_value) {?>
                                            <option value="<?=$department_value->id?>" <?php echo e($user->department==$department_value->id ? 'selected' : ''); ?>><?=$department_value->name?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job title <span class="text-danger">*</span></label>
                                    <select class="select" name="designation" id="designation">
                                        <option  value="">Select Job title</option>
                                        <?php
                                        if(isset($user->designation))
                                        {
                                            foreach ($designation_dropdown as $designation_value) {?>
                                            <option value="<?=$designation_value->id?>" <?php echo e($user->designation==$designation_value->id ? 'selected' : ''); ?>><?=$designation_value->name?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Licence <!-- <span class="text-danger">*</span> --></label>
                                    <select class="select"  name="subcompany"  id="subcompany">
                                        <option  value="">Select Licence</option>
                                        <?php
                                        if(isset($user->subcompany))
                                        {
                                            foreach ($subcompany_dropdown as $subcompany_value) {?>
                                            <option value="<?=$subcompany_value->id?>" <?php echo e($user->subcompany==$subcompany_value->id ? 'selected' : ''); ?>><?=$subcompany_value->name?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Profile Modal -->

    <div id="new_documents_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Documents</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/employeeDocuments/<?php echo $user->id; ?>" method="post" id="employee_accounts" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row docdiv">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Document Title</label>
                                    <input type="text" class="form-control" name="title[]" value="">
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Document</label>
                                    <input class="form-control" type="file" name="document[]" value="">
                                </div>
                                
                            </div>
                            
                        </div> 
                        <div id="div_doc_addmore"></div> 

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-success pull-right" id="doc_addmore">Add More</button>
                            </div>
                        </div> 
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
                    
   
            </div>
            </div>
        </div>
    </div>  
    <div id="an_leave_detail_modal" class="modal custom-modal fade " role="dialog">
        <?php echo $__env->make('edbr/an_leave_detail_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>  

    <!-- banking Info Modal -->
    
    <div id="banking_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Banking Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
 
                
                
                
                <div class="modal-body">
                    <form  action="/employeeAccounts/<?php echo $user->id; ?>" method="post" id="bank_account">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_name" value="<?php echo e($user->employee_accounts?$user->employee_accounts->bank_name:''); ?>">
                                </div>
                            </div>
                           
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="account_number" value="<?php echo e($user->employee_accounts?$user->employee_accounts->account_number:''); ?>">
                                </div>
                                
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch Code <span class="text-danger">*</span></label>
                                    <div >
                                        <input class="form-control" type="text" name="branch_code" value="<?php echo e($user->employee_accounts?$user->employee_accounts->branch_code:''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>IFC NUMBER <span class="text-danger">*</span></label>
                                    <div >
                                        <input class="form-control" type="text" name="ifsc_number" value="<?php echo e($user->employee_accounts?$user->employee_accounts->ifsc_number:''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>swift code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  name="swift_code" value="<?php echo e($user->employee_accounts?$user->employee_accounts->swift_code:''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  name="branch_name" value="<?php echo e($user->employee_accounts?$user->employee_accounts->branch_name:''); ?>">
                                </div>
                            </div>
                            
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
                    
   
            </div>
            </div>
        </div>
    </div>
        
  <!-- banking Info Modal -->
        
        
        
        
        
        
        
        
                  
    <!-- /lone Info Modal -->
        
             
        <div id="loan_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
             
                <div class="modal-body"> 
                    <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"> Loan Details</h3>
                        <form  action="/employeeLoan/<?php echo $user->user_id; ?>" method="post" id="employee_loan">
                            <?php echo csrf_field(); ?>
                            <div class="row"> </div>
                            
                            <hr>
                          <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="loan_amount" name="loan_amount" value="<?php echo e($user->employee_loan ? $user->employee_loan->loan_amount : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="loan_date" value="<?php echo e($user->employee_loan ? $user->employee_loan->loan_date : ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Number of installments <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="installment" value="<?php echo e($user->employee_loan ? $user->employee_loan->installment : ''); ?>">
                                    </div>
                                </div>
                              </div>
                            
                            <div class="row"> 
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Total Amount paid <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="total_amount_paid" name="total_amount_paid" value="<?php echo e($user->employee_loan ? $user->employee_loan->total_paid : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Installment pending <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="install_pending" value="<?php echo e($user->employee_loan ? $user->employee_loan->install_pending : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Amount pending <span class="text-danger">*</span></label>
                                        
                                        <input class="form-control" type="text" id="amount_pending" name="amount_pending" value="<?php echo e($user->employee_loan ? $user->employee_loan->amount_pending : ''); ?>">
                                    </div>
                                </div>
                            </div>       
                            <div class="row">       
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks</label> 
                                        <textarea class="form-control" name="remarks"><?php echo e($user->employee_loan ? $user->employee_loan->remarks : ''); ?></textarea>
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
  
            </div>
            </div>
        </div>
    </div>
        
   <!-- /lone Info Modal -->  
        
        
        

        
    <!-- /salary Info Modal -->
        
             
        <div id="salary_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salary Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
             
                <div class="modal-body">
                    <form  action="/employeeSalary/<?php echo $user->id; ?>" method="post" id="employee_salary">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Financial Year</label>
                                    <select class="select form-control" name="financial_year">
                                <?php $__currentLoopData = $financial_year; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year['id']); ?>" <?php echo e($currentMonthYear == $year['year_range'] ? 'selected' : ''); ?>>
                                        <?php echo e($year['year_range']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                                </div>
                            </div>
                           
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Basic Salary</label>
                                    <input class="form-control salary_add" type="text" name="basic_salary" value="<?php echo e($user->employee_salary ?$user->employee_salary->basic_salary : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Travel Allowance </label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="travel_allowance" value="<?php echo e($user->employee_salary ?$user->employee_salary->travel_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Food Allowance</label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="food" value="<?php echo e($user->employee_salary ?$user->employee_salary->food_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>House Allowance</label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="house" value="<?php echo e($user->employee_salary ?$user->employee_salary->house_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position Allowance</label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="position" value="<?php echo e($user->employee_salary ?$user->employee_salary->position_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Allowance</label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="phone" value="<?php echo e($user->employee_salary ?$user->employee_salary->phone_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Other Allowance</label>
                                    <div >
                                        <input class="form-control salary_add" type="text" name="other" value="<?php echo e($user->employee_salary ?$user->employee_salary->other_allowance : 0); ?>" onkeypress="return /[0-9]/i.test(event.key)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Salary</label>
                                    <input type="text" class="form-control" id="total_salary" name="total_salary" value="<?php echo e($user->employee_salary ?$user->employee_salary->total_salary : 0); ?>" readonly>
                                </div>
                            </div> 
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
                    
                
  
            </div>
            </div>
        </div>
    </div>
        
   <!-- /salary Info Modal -->  

   <!-- banking Info Modal -->
    
    <div id="banking_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Banking Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
     
                
                
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                           
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input class="form-control" type="text">
                                </div>
                                
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch Code</label>
                                    <div >
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>IFC NUMBER</label>
                                    <div >
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>swift code</label>
                                    <input type="text" class="form-control" value="srinagar Kahmir">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <input type="text" class="form-control" value="srinagar Kahmir">
                                </div>
                            </div>
                            
                            
                
                
                           
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
                    
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            </div>
            </div>
        </div>
    </div>
    <!-- /banking Info Modal -->
        
               
     <!-- Personal Info Modal -->
    
    <div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Personal Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                
                <div class="modal-body">
                    <form  action="/employeeDetails/<?php echo $user->id; ?>" method="post" id="employeeDetails">
                            <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Passport No</label>
                                    <input type="text" class="form-control"  name="passport_no" value="<?php echo e($user->passport_no ? $user->passport_no : ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Passport Expiry</label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker"  name="pass_expiry"  type="text" value="<?php echo e($user->passport_expiry ? dateDisplayFormat($user->passport_expiry) : ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Religion</label>
                                    <input class="form-control" type="text"  name="religion" value="<?php echo e($user->employee_details ? $user->employee_details->religion : ''); ?>">
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Blood Group</label>
                                    <div >
                                        <input class="form-control" type="text"  name="blood_group" value="<?php echo e($user->employee_details ? $user->employee_details->blood_group : ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="pi_address"  value="<?php echo e((isset($user->employee_details->pi_address)) ? $user->employee_details->pi_address : ''); ?>">
                                </div>
                            </div>
                             
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marital status</label>
                                    <select class="select form-control <?php echo e($user->employee_details ? 'selected' : ''); ?>" name="marital_status" >
                                        <option value="<?php echo e($user->employee_details ? $user->employee_details->marital_status : ''); ?>">
                                       <?php echo e($user->employee_details ? $user->employee_details->marital_status : '-'); ?></option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hiring Type</label>
                                    <select class="select form-control <?php echo e($user->employee_details ? 'selected' : ''); ?>" name="hiring_type" >
                                        <option value="">Select Hiring Type</option>
                                        <option value="local" <?php echo e((isset($user) && ($user->hiring_type == 'local')) ? 'selected' : ''); ?>>Local</option>
                                        <option value="oversease" <?php echo e((isset($user) && ($user->hiring_type == 'oversease')) ? 'selected' : ''); ?>>Oversease</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employment of spouse</label>
                                    <input class="form-control" type="text" name="spouse" value="<?php echo e($user->employee_details ? $user->employee_details->spouse_employment : ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. of children </label>
                                    <input class="form-control" type="text" name="children" value="<?php echo e($user->employee_details ? $user->employee_details->child : ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
                    
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            </div>
            </div>
        </div>
    </div>
    <!-- /Personal Info Modal -->
        
        
        
        
     <!-- Family Info Modal -->
    <div id="family_info_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Family Informations</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-scroll">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Family Member <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Relationship <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of birth <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Relationship <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of birth <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-more">
                                        <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Family Info Modal -->
    
    <!-- Emergency Contact Modal -->
    <div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emergency Contact</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form  action="/emergencyContact/<?php echo $user->id; ?>" method="post" id="emergency_contact">
                            <?php echo csrf_field(); ?>
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Primary Contact</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="pri_con_name"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->pri_con_name:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Relationship <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="pri_con_relation"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->pri_con_relation:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="pri_con_phone"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->pri_con_phone:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone 2</label>
                                            <input class="form-control" type="text" name="pri_con_phone2"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->pri_con_phone2:''); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Secondary Contact</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="sec_con_name"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->sec_con_name:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Relationship <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="sec_con_relation"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->sec_con_relation:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="sec_con_phone"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->sec_con_phone:''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone 2</label>
                                            <input class="form-control" type="text" name="sec_con_phone2"  value="<?php echo e($user->employee_contacts?$user->employee_contacts->sec_con_phone2:''); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Emergency Contact Modal -->
    
    <!-- Education Modal -->
    <div id="education_info" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Education Informations</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form  action="/employeeEducation/<?php echo $user->id; ?>" method="post" id="employee_education">
                 <?php echo csrf_field(); ?>

                        <div class="form-scroll"> 
                            
                            <?php $__currentLoopData = $user->employee_education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $education_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Education Informations 
                                        <a href="javascript:void(0);" class="delete-icon deleteEduButton" data-bs-toggle="modal" data-bs-target="#delete_edu" data-data="<?php echo e($education_value->id); ?>"><i class="fa fa-trash-o"></i></a>
                                    </h3>
                                    <input type="hidden" name="edu_info_id[]" value="<?php echo e($education_value->id); ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" class="form-control floating" name="institute[]" value="<?php echo e($education_value->institution); ?>">
                                                <label class="focus-label">Institution</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" class="form-control floating" name="subject[]" value="<?php echo e($education_value->subject); ?>">
                                                <label class="focus-label">Specialization</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <div class="cal-icon">
                                                    <input type="text"  class="form-control floating datetimepicker" name="started[]" value="<?php echo e(dateDisplayFormat($education_value->start)); ?>">
                                                </div>
                                                <label class="focus-label">Starting Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <div class="cal-icon">
                                                    <input type="text" class="form-control floating datetimepicker" name="completed[]" value="<?php echo e($education_value->end); ?>">
                                                </div>
                                                <label class="focus-label">Complete Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text"  class="form-control floating" name="degree[]" value="<?php echo e($education_value->degree); ?>">
                                                <label class="focus-label">Degree</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text"  class="form-control floating" name="grade[]" value="<?php echo e($education_value->grade); ?>">
                                                <label class="focus-label">Grade</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <div class="card" id="rowEdudiv0">
                                <div class="card-body">
                                    <h3 class="card-title">Education Informations 
                                        <?php if(count($user->employee_education) > 0) { ?>
                                            <a href="javascript:void(0);" class="delete-icon" onclick="removeEduDiv(0)"><i class="fa fa-trash-o"></i></a>
                                        <?php } ?>
                                    </h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" value="" class="form-control floating" name="institute[]" required>
                                                <label class="focus-label">Institution <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" value="" class="form-control floating" name="subject[]" required>
                                                <label class="focus-label">Specialization <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <div class="cal-icon">
                                                    <input type="text"  class="form-control floating datetimepicker" name="started[]" required>
                                                </div>
                                                <label class="focus-label">Starting Date <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <div class="cal-icon">
                                                    <input type="text"  class="form-control floating datetimepicker" name="completed[]" required>
                                                </div>
                                                <label class="focus-label">Complete Date <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" value="" class="form-control floating" name="degree[]" required>
                                                <label class="focus-label">Degree <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus focused">
                                                <input type="text" value="" class="form-control floating" name="grade[]" required>
                                                <label class="focus-label">Grade <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="addmoreEdu"></div>
                            <div class="add-more">
                                <a href="javascript:void(0);" id="addmoreEduClick"><i class="fa fa-plus-circle"></i> Add More</a>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Education Modal -->
    
    <!-- Experience Modal -->
    <div id="experience_info" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Experience Informations</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/employeeExperience/<?php echo $user->id; ?>" method="post" id="employee_experience">
                        <?php echo csrf_field(); ?>
                        <div class="form-scroll">

                            <?php $__currentLoopData = $user->employee_experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Experience Informations 
                                        <!-- <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a> -->
                                        <a href="javascript:void(0);" class="delete-icon deleteExpButton" data-bs-toggle="modal" data-bs-target="#delete_exp" data-data="<?php echo e($experience_value->id); ?>"><i class="fa fa-trash-o"></i></a>
                                    </h3>
                                    <input type="hidden" name="exp_info_id[]" value="<?php echo e($experience_value->id); ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="company[]" value="<?php echo e($experience_value->company); ?>" required>
                                                <label class="focus-label">Company Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="location[]" value="<?php echo e($experience_value->location); ?>" required>
                                                <label class="focus-label">Location <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="job[]" value="<?php echo e($experience_value->job_position); ?>" required>
                                                <label class="focus-label">Job Position <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <div class="cal-icon">
                                                    <input type="text" class="form-control floating datetimepicker" name="from[]" value="<?php echo e($experience_value->period_from); ?>" required>
                                                </div>
                                                <label class="focus-label">Period From <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <div class="cal-icon">
                                                    <input type="text" class="form-control floating datetimepicker" name="to[]" required value="<?php echo e($experience_value->period_to); ?>">
                                                </div>
                                                <label class="focus-label">Period To <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="card" id="rowExpdiv0">
                                <div class="card-body">
                                    <h3 class="card-title">Experience Informations 
                                        <?php if(count($user->employee_experiences) > 0) { ?>
                                            <a href="javascript:void(0);" class="delete-icon" onclick="removeExpDiv(0)"><i class="fa fa-trash-o"></i></a>
                                        <?php } ?>
                                    </h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="company[]" value="" required>
                                                <label class="focus-label">Company Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="location[]" value="" required>
                                                <label class="focus-label">Location <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating" name="job[]" value="" required>
                                                <label class="focus-label">Job Position <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <div class="cal-icon">
                                                    <input type="text" class="form-control floating datetimepicker" name="from[]" value="" required>
                                                </div>
                                                <label class="focus-label">Period From <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <div class="cal-icon">
                                                    <input type="text" class="form-control floating datetimepicker" name="to[]" required>
                                                </div>
                                                <label class="focus-label">Period To <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="addmoreExp"></div>

                            <div class="add-more">
                                <a href="javascript:void(0);" id="addmoreExpClick"><i class="fa fa-plus-circle"></i> Add More</a>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Experience Modal -->
    
</div>
<!-- /Page Wrapper -->

<!-- Delete Document Modal -->
<div class="modal custom-modal fade" id="delete_doc" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Document</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <form method="post" action="/employeeDocumentDelete">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-6">
                                    <input type="hidden" name="document_id" id="document_id" value="">
                                    <button type="submit" class="btn btn-primary continue-btn col-12">Delete</button>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Document Modal -->

<!-- Delete Education Modal -->
<div class="modal custom-modal fade" id="delete_edu" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Education</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <form method="post" action="/employeeEducationDelete">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-6">
                                    <input type="hidden" name="education_id" id="education_id" value="">
                                    <button type="submit" class="btn btn-primary continue-btn col-12">Delete</button>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Education Modal -->

<!-- Delete Experience Modal -->
<div class="modal custom-modal fade" id="delete_exp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Experience</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <form method="post" action="/employeeExperienceDelete">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-6">
                                    <input type="hidden" name="experience_id" id="experience_id" value="">
                                    <button type="submit" class="btn btn-primary continue-btn col-12">Delete</button>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Experience Modal -->
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Resign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="userId" id="userId" value="">
        Are you sure you want to Deactivate/Generate FNF?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="resign(1)">Generate FNF</button>
        <button type="button" class="btn btn-danger" onclick="resign(0)">Resign</button>
      </div>
    </div>
  </div>
</div>

</div>

</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script type="text/javascript">
    $(document).on('click','#is_manual_punchin',function(){
        var check_val = $(this).val();
        var user_id = $(this).data('id');
        $.ajax({
                    url: '/change_manual_punchin_status/'+user_id+'/'+check_val,
                    type: "GET",
                    dataType: "json",
                    success:function(response)
                    {
                        $('#success_message').removeClass('d-none');
                    }
        });
    });

    $(document).on('click','#is_passport',function(){
        var check_val = $(this).val();
        var user_id = $(this).data('id');
        $.ajax({
                    url: '/change_passport_status/'+user_id+'/'+check_val,
                    type: "GET",
                    dataType: "json",
                    success:function(response)
                    {
                        $('#success_message').removeClass('d-none');
                    }
        });
    });
    $(document).ready(function() {

        $("#employee_info_update").validate({
            rules: {
               company: 'required',
               branch: 'required',
               department: 'required',
               designation: 'required', 
               local_address: 'required', 
               // subcompany: 'required',
               c_id : 'required',
               b_id : 'required',
               country : 'required',
               state : 'required',
               address : 'required' ,
               pin_code : 'required'
               
                 
                
            },
            messages: {
              company: 'Company is required',
              branch: 'Branch is required',
              department: 'Department is required',
              designation: 'Designaton is required',
              local_address: 'Address is required',
              // subcompany: 'Sub Company is required',
              c_id   : 'Civil id is required',
              b_id   : 'Baladiya id is required',
              country : 'Country is required',
              State  : 'State is required',
              address : 'Address id required' ,
              pin_code : 'Pin code required'
                
                
            },
        });

        $("#emergency_contact").validate({
            rules: {
                pri_con_name: 'required',
                pri_con_relation: 'required',
                pri_con_phone: 'required',
                sec_con_name: 'required',
                sec_con_relation: 'required',
                sec_con_phone: 'required',
                
            },
            messages: {
                pri_con_name: 'Name is required',
                pri_con_relation: 'Relationship is required',
                pri_con_phone: 'Phone is required',
                sec_con_name: 'Name is required',
                sec_con_relation: 'Relationship is required',
                sec_con_phone: 'Phone is required',
            },
       });
       
        
        $("#employee_loan").validate({
            rules: {
               loan_amount: 'required',
               loan_date: 'required',
               installment: 'required',
               total_amount_paid: 'required',
               install_pending: 'required',
               amount_pending: 'required'                   
            },
            messages: {
                loan_amount: 'Loan Amount is required',    
                loan_date: 'Loan Date is required',    
                installment: 'Installment is required',    
                total_amount_paid: 'Paid Amount is required',    
                install_pending: 'Pending Installment is required',    
                amount_pending: 'Pending Amount is required'
            },
       });
    });
</script>

<script type="text/javascript">
    $(document).on('blur', '#loan_amount', function()
    {
        var loan = $(this).val();
        var paid = 0;
        var pending = loan;

        $('#total_amount_paid').val(paid);
        $('#amount_pending').val(pending);
    });

    $(document).on('blur', '#installment', function()
    {
        var total_installment = $(this).val();
        var loan = $('#loan_amount').val();
        // var total_installment = loan/;
        var pending = loan;

        $('#total_amount_paid').val(paid);
        $('#amount_pending').val(pending);
    });
  
    $(document).on('click','#post_trans_btn',function(){
        $('#trans_type').val('');
    });

    $(document).on('click','#download_btn',function(){
        $('#trans_type').val('download');
    });

    
</script>

<script type="text/javascript">
    $(document).ready(function() {

        $("#bank_account").validate({
            rules: {
               bank_name: 'required',
               account_number: 'required',
               branch_code: 'required',
               ifsc_number: 'required', 
               swift_code: 'required',
               branch_name : 'required'
            },
            messages: {
              bank_name: 'Bank Name is required',
              account_number: 'Account Number is required',
              branch_code: 'Branch code is required',
              ifsc_number: 'IFSC code is required',
              swift_code: 'Swift code is required',
              branch_name   : 'Branch name is required'
            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#company').on('change', function() {
           var companyID = $(this).val();
           if(companyID) {
               $.ajax({
                    url: '/getDepartmentByCompany/'+companyID,
                    type: "GET",
                    dataType: "json",
                    success:function(response)
                    {
                        // $('#department').empty();
                        // $('#designation').empty();
                        // $("#department").append('<option>Select Department</option>');
                        //  if(response.department)
                        //  {
                        //     $.each(response.department,function(key,value){
                        //         $("#department").append('<option value="'+value.id+'">'+value.name+'</option>');
                        //     });
                        // }else{
                        //     $('#department').empty();
                        // }

                        $('#branch').empty();
                        $('#subcompany').empty();
                        if(response.branch)
                        {
                            $.each(response.branch,function(key,value){
                                $("#branch").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                        if(response.subcompany)
                        {
                            $.each(response.subcompany,function(key,value){
                                $("#subcompany").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                    }
                });
            }else{
                $('#course').empty();
            }
        });
    });
</script>
<script>    
    // $(document).ready(function() {
    //     $('#department').on('change', function() {
    //         var departmentID = $(this).val();
    //        if(departmentID) {
    //            $.ajax({
    //                url: '/getDesignationByDepartment/'+departmentID,
    //                type: "GET",
    //                dataType: "json",
    //                success:function(response)
    //                {
    //                     $('#designation').empty();
    //                     $("#designation").append('<option>Select Designation</option>');
    //                  if(response)
    //                  { 
    //                     $.each(response,function(key,value){
    //                         $("#designation").append('<option value="'+value.id+'">'+value.name+'</option>');
    //                     });
    //                 }else{
    //                     $('#designation').empty();
    //                 }
    //              }
    //            });
    //        }else{
    //          $('#designation').empty();
    //        }
    //     });
    // });
    
</script>

<script type="text/javascript">
    function digitKeyOnly(e,eln) {       
        var k = parseInt($('#no_of_days').val());
        var value = Number(e.target.value + e.key) || 0;      
        if (value > k) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    function digitKeyOnlyPH(e,eln) {       
        var k = parseInt($('.ph_avail').val());
        var value = Number(e.target.value + e.key) || 0;      
        if (value > k) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    $(document).on('change','.public_holidays',function(){
        var ph_avail = $('.ph_avail').val();
        var ph_taken = $('.public_holidays').val();
        if(ph_taken <= ph_avail){
            var total = ph_avail - ph_taken;
            $('.public_remaining_leave').text(total+' Days');
        }
    });

    $(document).on('change keyup','.annual_leave_days',function(){
        var an_avail = parseInt($('.an_avail').val());
        var an_taken = parseInt($('.annual_leave_days').val());
        if(an_taken <= an_avail){
            var total = an_avail - an_taken;
            $('.annual_remaining_leave').text(total+' Days');
        }
    });

       $(document).on('click','.an_leave_btn',function(){
        //console.log('sdfds');
            $('#an_leave_detail_modal').html('');
            var id= $(this).attr('data-id');
            var user_id = $(this).attr('data-userid');
            var type = $(this).attr('data-type');
            $.ajax({
            url: "<?php echo e(route('getanLeaveDetailsById')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", id:id,user_id:user_id,type:type},
            success:function(response)
                {
                    $('#an_leave_detail_modal').html(response.html).fadeIn();
                }
            });
        });

       $('#an_leave_detail_modal').on('hidden.bs.modal', function () {
            $('#an_leave_detail_modal').html('');
        });
       


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgInp").change(function(){
    readURL(this);
});
</script>

<script type="text/javascript">
    $('.salary_add').on("input", function() {
      let total = 0;
      $('.salary_add').each(function() {
        total += $(this).val()/1;
      })
      $('#total_salary').val(total);
    });
</script>

<script type="text/javascript">
    var len = 0;
    $('#doc_addmore').click(function()
    {
        len++;// = $('.rowdiv').length;
        $('#div_doc_addmore').before('<div class="row rowdiv" id="rowdiv'+len+'"><div class="col-md-6"><div class="form-group"><label>Document Title</label><input type="text" class="form-control" name="title[]" value=""></div></div><div class="col-md-5"><div class="form-group"><label>Document</label><input class="form-control" type="file" name="document[]" value=""></div></div><div class="col-md-1"><span class="mt-4 trashDiv" onclick="removeDiv('+len+')"><i class="fa fa-trash text-danger"></i></span></div></div>');
    })
</script>
<script type="text/javascript">
   function removeDiv(tid) {
        $('#rowdiv'+tid).remove();
    }
</script>

<script>
    $(document).on('click','.deleteDocButton',function(){
        var id = $(this).data('data');
        // var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        // $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#document_id').val(id);
        // });
    });
</script>

<script type="text/javascript">
    var lenEdu = 0;
    $('#addmoreEduClick').click(function()
    {
        lenEdu++;
        $('#addmoreEdu').before('<div class="card rowEdudiv" id="rowEdudiv'+lenEdu+'"><div class="card-body"><h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon" onclick="removeEduDiv('+lenEdu+')"><i class="fa fa-trash-o"></i></a></h3><div class="row"><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="institute[]" required><label class="focus-label">Institution <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="subject[]" required><label class="focus-label">Subject <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text"  class="form-control floating datetimepicker" name="started[]" required></div><label class="focus-label">Starting Date <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text"  class="form-control floating datetimepicker" name="completed[]" required></div><label class="focus-label">Complete Date <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="degree[]" required><label class="focus-label">Degree <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="grade[]" required><label class="focus-label">Grade <span class="text-danger">*</span></label></div></div></div></div></div>');
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
<script type="text/javascript">
   function removeEduDiv(reid) {
        $('#rowEdudiv'+reid).remove();
    }
</script>

<script>
    $(document).on('click','.deleteEduButton',function(){
        var id = $(this).data('data');
        $('#education_id').val(id);
    });
</script>

<script type="text/javascript">
    var lenExp = 0;
    $('#addmoreExpClick').click(function()
    {
        lenExp++;
        $('#addmoreExp').before('<div class="card rowExpdiv" id="rowExpdiv'+lenExp+'"><div class="card-body"><h3 class="card-title">Experience Informations <a href="javascript:void(0);" class="delete-icon" onclick="removeExpDiv('+lenExp+')"><i class="fa fa-trash-o"></i></a></h3><div class="row"><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" name="company[]" value="" required><label class="focus-label">Company Name <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" name="location[]" value="" required><label class="focus-label">Location <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" name="job[]" value="" required><label class="focus-label">Job Position <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" name="from[]" value="" required></div><label class="focus-label">Period From <span class="text-danger">*</span></label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" name="to[]" required></div><label class="focus-label">Period To <span class="text-danger">*</span></label></div></div></div></div></div>');
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    })
</script>
<script type="text/javascript">
   function removeExpDiv(rexid) {
        $('#rowExpdiv'+rexid).remove();
    }
</script>
<script>
    $(document).on('click','.deleteExpButton',function(){
        var id = $(this).data('data');
        $('#experience_id').val(id);
    });

    $('#total_amount_paid').keyup(function()
    {   
        var loan_amount = $('#loan_amount').val();
        var total_amount_paid = $('#total_amount_paid').val();
        var amount_pending = loan_amount - total_amount_paid;
        $('#amount_pending').val(amount_pending); 
    })
</script>

<script type="text/javascript">
$(document).on('click', '#generate_fnf', function(){
    var user_id = $(this).data('id');
    $('#userId').val(user_id);
    // $.ajax({
    //     url: '/generateFnf/'+user_id,
    //     type: "GET",
    //     dataType: "json",
    //     success:function(response)
    //     {
    //         if(response == 'done')
    //         { 
    //             location.reload();
    //         }
    //     }
    // });
});
function resign(status) {
    var user_id = $('#userId').val();
    $.ajax({
        url: '/generateFnf/'+user_id+'/'+status,
        type: "GET",
        dataType: "json",
        success:function(response)
        {
            if(response == 'done')
            { 
                location.reload();
            }
        }
    });
}

$(".allowfloatnumber").keypress(function (eve) {
    if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
        eve.preventDefault();
    }

   // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
   $('.allowfloatnumber').keyup(function(eve) {
        if ($(this).val().indexOf('.') == 0) {
          $(this).val($(this).val().substring(1));
        }
      });
});
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/edbr/profile.blade.php ENDPATH**/ ?>