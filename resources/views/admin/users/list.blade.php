<div class="row" >
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <th tabindex="0" rowspan="1" colspan="1" style="width:100px;">First Name</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:100px;">Last Name</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:150px;">Email</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:150px;">Gender</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:150px;">DOB</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:150px;">Age</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $user)
                <tr role="row" class="odd">
                    <td><a href="{{ secure_url('admin/users/update', ['user_uid' => $user['uid']]) }}">{{ $user['first_name'] }}</a></td>
                    <td>{{ $user['last_name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ ucfirst($user['gender']) }}</td>
                    <td>{{ $user['dob'] }}</td>
                    <td>{{ $user['age'] }}</td>
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