<div class="row" >
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <th tabindex="0" rowspan="1" colspan="1">Name</th>
                <th tabindex="0" rowspan="1" colspan="1">Email</th>
                <th tabindex="0" rowspan="1" colspan="1">Gender</th>
                <th tabindex="0" rowspan="1" colspan="1">DOB (Age)</th>
                <th tabindex="0" rowspan="1" colspan="1">Max Books Eligible</th>
                <th tabindex="0" rowspan="1" colspan="1">No of books borrowed</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $user)
                <tr role="row" class="odd">
                    <td><a href="{{ secure_url('admin/users/update', ['user_uid' => $user['uid']]) }}">{{ $user['first_name'] }} {{ $user['last_name'] }}</a></td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ ucfirst($user['gender']) }}</td>
                    <td>{{ $user['dob'] }} ({{ $user['age'] }})</td>
                    <td>{{ $user['max_books_eligible'] }}</td>
                    <td>
                        <a href="{{ secure_url('admin/reports?user_uid='. $user['uid']) }}">
                            <span class="label label-danger">
                             {{ $user['no_of_books_borrowed'] }}
                            </span>
                        </a>
                    </td>
                    <td>
                        @if($user['is_activated'] == '1')
                            <span class="label label-success">ACTIVE</span>
                        @else
                            <span class="label label-warning">INACTIVE</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">No records found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{ $from }} to {{ $to }} of
            {{ $total }} entries
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers">
            @if (!empty($data))
                {!! $paginator->render() !!}
            @endif
        </div>
    </div>
</div>