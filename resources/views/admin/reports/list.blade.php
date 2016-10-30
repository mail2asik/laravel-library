<div class="row" >
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <th tabindex="0" rowspan="1" colspan="1">Book Title</th>
                <th tabindex="0" rowspan="1" colspan="1">Member Name</th>
                <th tabindex="0" rowspan="1" colspan="1">Started At</th>
                <th tabindex="0" rowspan="1" colspan="1">Ended At</th>
                <th tabindex="0" rowspan="1" colspan="1">Returned At</th>
                <th tabindex="0" rowspan="1" colspan="1">Fine</th>
                <th tabindex="0" rowspan="1" colspan="1">Status</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $report)
                <tr role="row" class="odd">
                    <td><a href="{{ secure_url('admin/books/update', ['book_uid' => $report['book_uid']]) }}">{{ $report['title'] }}</a></td>
                    <td><a href="{{ secure_url('admin/users/update', ['user_uid' => $report['user_uid']]) }}">{{ $report['first_name'] }} {{ $report['last_name'] }}</a></td>
                    <td>{{ date('m/d/y', strtotime($report['started_at'])) }}</td>
                    <td>{{ date('m/d/y', strtotime($report['ended_at'])) }}</td>
                    <td>
                        @if($report['returned_at'] != '0000-00-00 00:00:00')
                            {{ date('m/d/y', strtotime($report['returned_at'])) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($report['fine'] != '0')
                            <span class="label label-danger">SGD {{ $report['fine'] }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($report['status'] == 'RETURNED')
                            <span class="label label-success">{{ $report['status'] }}</span>
                        @elseif($report['status'] == 'BORROWED')
                            <span class="label label-warning">{{ $report['status'] }}</span><br/>
                            <a href="{{ secure_url('admin/books/collect', ['book_uid' => $report['book_uid'], 'user_uid' => $report['user_uid']]) }}">
                                Mark as returned
                            </a>
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