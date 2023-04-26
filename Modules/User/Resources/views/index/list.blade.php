<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Created Date</th>
                <th>Modified Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
        @foreach ($LIST as $item)
            <tr>
                <td>{{ $item['id'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['created_at'] }}</td>
                <td>{{ $item['updated_at'] }}</td>
                <td>
                	@if ($item['is_active'])
                    	<span class="m-badge  m-badge--success m-badge--wide">Active</span>
                	@else
                		<span class="m-badge  m-badge--danger m-badge--wide">Deactive</span>
                	@endif
                </td>
                <td>
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>
                    <button onclick="List.remove(this, {{ $item['id'] }})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></button>
                </td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>

{{ $LIST->links('helpers.paging') }}