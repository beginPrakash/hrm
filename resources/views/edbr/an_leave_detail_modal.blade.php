<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>


<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title">Leave Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(empty($type) && (isset($leaveData) && !empty($leaveData)))
            <form  action="/post_leave_transaction" method="post">
                @csrf
                <input type="hidden" name="id" class="leave_id" value="{{$leaveData->id ?? ''}}">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover user_leave_request_tbl @if(isset($leaveData) && ($leaveData->leave_type == 1)) @else d-none @endif">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">Amount Type</th>
                                        <th>Available</th>
                                        <th>Payment</th>
                                        <th>Claim</th>
                                        <th class="text-end">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Annual Leave</td>
                                        <td>{{$userdetails->opening_leave_days ?? 0}} Days</td>
                                        
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input an_checkbox" name="an_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" {{(isset($leaveData) && $leaveData->claimed_annual_days < 0) ? '' : 'checked'}}>
                                            </div>
                                        </td>
                                        <input type="hidden" id="no_of_days" value="{{$leaveData->leave_days ?? 0}}">
                                        <input type="hidden" value="{{$userdetails->opening_leave_days ?? 0 }}" class="an_avail">
                                        <td><input type="number" onkeypress="return digitKeyOnly(event,this)" value="{{$leaveData->claimed_annual_days ?? 0}}" name="annual_leave_days" class="annual_leave_days"  max="{{$leaveData->leave_days ?? 0}}" min="0" {{(isset($leaveData) && $leaveData->claimed_annual_days < 0) ? 'disabled' : ''}}></td>
                                        <td class="annual_remaining_leave">
                                            {{($userdetails->opening_leave_days ?? 0) - ($leaveData->claimed_annual_days ?? 0)}} Days   
                                        </td>
                                    </tr>
                                    @if(isset($leaveData) && ($leaveData->claimed_public_days > 0))
                                        <tr>
                                            <td>Public Holiday</td>
                                            <td><input type="hidden" class="ph_avail" value="{{$userdetails->public_holidays_balance ?? 0}}">{{$userdetails->public_holidays_balance ?? 0}} Days</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ph_checkbox" name="ph_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" {{(isset($leaveData) && $leaveData->claimed_public_days <= 0) ? '' : 'checked'}}>
                                                </div>
                                            </td>
                                            <td><input type="number" onkeypress="return digitKeyOnlyPH(event,this)" name="public_holidays" class="public_holidays"  value="{{$leaveData->claimed_public_days ?? 0}}" max="{{$userdetails[0]->public_holidays_balance ?? ''}}" min="0" {{(isset($leaveData) && $leaveData->claimed_public_days <= 0) ? 'disabled' : ''}}></td>
                                            <td class="public_remaining_leave">{{($userdetails->public_holidays_balance ?? 0) - ($leaveData->claimed_public_days ?? 0)}} Days</td>
                                        </tr>
                                    @endif
                                </tbody>
                            <table>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="post_trans_btn">Post The Transaction</button>
                </div>
            </form>
            @else
                @if(isset($type) && ($type == 'history'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">Amount Type</th>
                                            <th>Available</th>
                                            <th>Payment</th>
                                            <th>Claim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Annual Leave</td>
                                            <td>{{$leaveData->claimed_annual_days_rem + $leaveData->claimed_annual_days}} Days</td>
                                            <td>Yes</td>
                                            <td>{{$leaveData->claimed_annual_days ?? 0}} Days</td>
                                        </tr>
                                        @if(isset($leaveData) && ($leaveData->claimed_public_days > 0))
                                            <tr>
                                                <td>Public Holiday</td>
                                                <td>{{$leaveData->claimed_public_days_rem + $leaveData->claimed_public_days}} Days</td>
                                                <td>Yes</td>
                                                <td>{{$leaveData->claimed_public_days ?? 0}} Days</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <form  action="/post_leave_transaction" method="post">
                        @csrf
                        <input type="hidden" name="type" id="trans_type" value="download">
                        <input type="hidden" name="id" class="leave_id" value="{{$leaveData->id ?? ''}}">
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn" type="submit" id="download_btn">Download Settlement</button>
                        </div>
                    </form> 

                @endif
            @endif
           
                   
        </div>
    </div>
</div>