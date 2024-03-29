<div class="row">

    <div class="col-md-6">

        <div class="card punch-status">

            <div class="card-body">

                <h5 class="card-title">Timesheet <small class="text-muted"><?php echo date('d F, Y',strtotime($attnDate)); ?> </small></h5>

            </div>

        </div>

    </div>

    <div class="col-md-12">

        <div class="card">

            <div class="card-body">

                <form action="{{route('create_attendance_by_date')}}" method="post" id="create_attn_form">

                    @csrf

                    <input type="hidden" name="attnUserId" id="attnUserId" value="<?php echo $userId; ?>">

                    <input type="hidden" name="attnDate" id="attnDate" value="<?php echo $attnDate; ?>">

                    

                    Punch Timings <br>

                    <div class="input-group timex timepickerx">
                        @php $start_date = $emloyeeSchedule[0]['min_start_time'] ?? ''; @endphp   
                        Start Time @if(!empty($start_date)) ({{date('d F, Y',strtotime($start_date))}}) @endif: <input type="text" class="form-control att_timepicker" name="start_time" id="start_time" autocomplete="off"><span class="input-group-text"><i class="fa fa-clock-o"></i></span><br>
                        <input type="hidden" name="schd_start_date" value="@if(!empty($start_date)) {{date('Y-m-d',strtotime($start_date))}} @endif">
                    </div>

                    <div class="input-group timex timepickerx">
                        @php $end_date = $emloyeeSchedule[0]['min_end_time'] ?? ''; @endphp
                        End Time @if(!empty($end_date)) ({{date('d F, Y',strtotime($end_date))}}) @endif : <input type="text" class="form-control att_timepicker" name="end_time" id="end_time" autocomplete="off"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                        <input type="hidden" name="schd_end_date" value="@if(!empty($end_date)) {{date('Y-m-d',strtotime($end_date))}} @endif">

                    </div>

                        <br>

                    <button type="button" class="btn btn-info pull-right mb-3 text-white manual_att_btn">Update</button>



                </form>

            </div>

        </div>

    </div>

</div>



<script type="text/javascript">

    var dateNow = new Date();

    $(".att_timepicker").datetimepicker({

        format: 'HH:mm a',

        defaultDate:moment(dateNow).hours(0).minutes(0)

    });

    

     $(document).ready(function() {

           $("#create_attn_form").validate({

            rules: {

                start_time: {

                    required : true

                },

                end_time: {

                    required : true

                },

            },

            messages: {

                start_time: {

                    required : 'Please select In time',

                },

                end_time: {

                    required : 'Please select Out time',

                },          

            },

       });

    })

$(document).on('change', '#start_time, #end_time', function()

{

    var start_time = $('#start_time').val();

    var end_time = $('#end_time').val();

    var break_time_val = $('#break_time_val').val();

    var normal_wt_val = $('#normal_wt_val').val();

// console.log('start_time'+start_time);console.log('end_time'+end_time);

    if (start_time === "" || end_time === "") {

        // $("#result").text("Please enter both start and end times.");

        return;

    }



    var startDate = parseCustomDate(start_time);

    var endDate = parseCustomDate(end_time);



    var timeDiff = endDate - startDate;

    timeDiff -= (normal_wt_val * 60 * 60 * 1000) + (break_time_val * 60 * 1000);



    var hours = Math.floor(timeDiff / (1000 * 60 * 60)); // Convert milliseconds to hours

    var minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)); // Convert milliseconds to minutes



// console.log('startDate'+startDate);console.log('endDate'+endDate);

    // console.log('hours'+hours);console.log('minutes'+minutes);

    $('#ottime').text(hours+'.'+minutes);

    $('#ottime_val').val(hours+'.'+minutes);

    // $("#result").text("Time difference: " + hours + " hours and " + minutes + " minutes.");

});



function parseCustomDate(dateString) {

    var months = {

        "Jan": 0, "Feb": 1, "Mar": 2, "Apr": 3, "May": 4, "Jun": 5,

        "Jul": 6, "Aug": 7, "Sep": 8, "Oct": 9, "Nov": 10, "Dec": 11

    };



    var parts = dateString.split(" ");

    var day = parseInt(parts[1]);

    var month = months[parts[2]];

    var year = parseInt(parts[3]);

    var timeParts = parts[4].split(":");

    var hours = parseInt(timeParts[0]);

    var minutes = parseInt(timeParts[1]);



    if (parts[5] === "PM" && hours !== 12) {

        hours += 12;

    } else if (parts[5] === "AM" && hours === 12) {

        hours = 0;

    }



    return new Date(year, month, day, hours, minutes);

}

</script>