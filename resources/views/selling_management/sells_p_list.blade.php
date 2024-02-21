<ul class="dropdown-menu checkbox-menu allow-focus sells_menu" aria-labelledby="dropdownMenu1">
    @if(isset($data) && count($data) > 0)
        @foreach($data as $key => $val)
            <li>
                <label>
                    <input type="checkbox" name="sells_list[]" value="{{$val->id}}">{{$val->item_name}}
                </label>
            </li>    
        
        @endforeach
    @endif
</ul>