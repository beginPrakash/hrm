<script type="text/javascript">
$(document).on('change', '#designation', function() {

    // for department hide/show
    var prio = $(this).find(":selected").data("priority");
    $('#department_div').show();
    $('#company_div').show();
    if(prio == '1' || prio == '2')
    {
        $('#department_div').hide();
        $('#dep_hid').val(1);

        $('#company_div').hide();
        $('#com_hid').val(1);
    }

    //for multi user check
});
</script>
<script>
// $(document).on('change', '#company', function() {
//     var companyID = $(this).val();
//     if(companyID) {
//        $.ajax({
//            url: '/getDepartmentByCompany/'+companyID,
//            type: "GET",
//            dataType: "json",
//            success:function(response)
//            {
//             $('#department').empty();
//             $('#designation').empty();
//             $('#branch').empty();
//             $("#department").append('<option>Select Department</option>');
            
//             if(response.department)
//             {
//               var defaultOption = '<option  value="">Select Branch</option>';
//                 $.each(response.department,function(key,value){
//                   defaultOption += '<option value="'+value.id+'">'+value.name+'</option>';
                    
//                 });
//                 $("#department").append(defaultOption);
//             }else{
//               var defaultOption = '<option  value="">Select Branch</option>';
//                 $('#department').empty();
//             }

//             if(response.branch)
//             {
//               var defaultOption = '<option  value="">Select Branch</option>';
//                 $.each(response.branch,function(key,value){
//                     defaultOption += '<option value="'+value.id+'">'+value.name+'</option>';
//                 });
//                 $("#branch").append(defaultOption);
//             }
//             if(response.subcompany)
//             {
//               var defaultOption = '<option  value="">Select Branch</option>';
//                 $.each(response.subcompany,function(key,value){
//                   defaultOption += '<option value="'+value.id+'">'+value.name+'</option>';
//                 });
//                 $("#subcompany").append(defaultOption);
//             }
//          }
//        });
//    }else{
//      $('#course').empty();
//    }
// });
</script>