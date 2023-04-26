<table class="table table-striped">
    <thead>
    <tr>
        <th></th>
        <th>{{ $trans['create']['group']['header']['NAME'] }}</th>
        <th>{{ $trans['create']['group']['header']['TYPE'] }}</th>
        <th>{{ $trans['create']['group']['header']['TIME'] }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($groupList as $group)
        <tr>
            <td>
                <label class="kt-radio">
                    <input type="radio" class="group-radio" name="group_radio"
{{--                           value="{{ $group['code'] }}" data-name="{{ $group['name'] }}" data-id="{{ $group['id'] }}"--}}
                           value="{{ $group['id'] }}" data-name="{{ $group['name'] }}" data-id="{{ $group['id'] }}"
                    >
                    <span></span>
                </label>
            </td>
            <td>
                <p title="{{ $group['name'] }}">
                    {{ subString($group['name']) }}
                </p>
            </td>
            <td>
                {{ $group['filter_group_type'] == 'auto'
                ? $trans['create']['group']['type']['AUTO']
                : $trans['create']['group']['type']['USER_DEFINE'] }}
            </td>
            <td>{{ $group['created_at'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $groupList->links('helpers.paging') }}
