@include('includes/header');
@include('includes/sidebar');

<div class="page-wrapper">
            
    <!-- Page Content -->
    <div class="content container-fluid">
        @include('flash-message') 
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Change Password</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Change password</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('post-change-password')}}" method="post" id="changePasswordForm">
                    @csrf                            
                    <div class="form-group">
                        <label>New Password <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password_confirmation">
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

</div>

@include('includes/footer');
<script type="text/javascript">
    $('#changePasswordForm').validate({
        rules:{
            password:{
                required: true,
                minlength: 6,
                maxlength: 20,
            },
            password_confirmation:{
                required: true,
                equalTo: "#password",
            }
        },
        messages: {
            password:{
                required: "Please enter a password",
                minlength: "Please enter a minimum of 6 characters",
                maxlength: "Please enter a maximum of 20 characters",
            },
            password_confirmation:{
                required: "Please confirm the password",
                equalTo: "Confirmation password does not match",
            }
        },
        errorPlacement: function (error, element) {
            if(element.attr("name") == "password"){
            $('#password').css('background-image','none')
            }
            if(element.attr("name") == "password_confirmation"){
            $('#password_confirmation').css('background-image','none')
            }
            if (element.attr("type") == "input") {
                error.insertAfter(element.parent().parent());
            } else if (element.attr("name") == "password") {
                error.insertAfter(element.parent());
            } else if (element.attr("name") == "password_confirmation") {
                error.insertAfter(element.parent());
            }
                else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form){
            var l = Ladda.create($(form).find('button').get(0));
            l.start();
            form.submit();
        }
    });
</script>