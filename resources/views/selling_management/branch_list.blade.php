<ul class="dropdown-menu checkbox-menu allow-focus branch_menu" aria-labelledby="dropdownMenu1">
    @if(isset($data) && count($data) > 0)
        @php $res = ''; @endphp
        @foreach($data as $key => $val)
        @if($res != $val->residency)
                @php $res = $val->residency; @endphp
                @if($key != 0)
                <hr class="hr_line">
                @endif
            
            @endif
            <li>
                <label>
                    <input type="checkbox" class="branch_check" name="brnach_list[]" value="{{$val->id}}">{{$val->name}}
                </label>
            </li>    
        
        @endforeach
    @endif
</ul>