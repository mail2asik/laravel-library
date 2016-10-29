<div class="row" >
<div class="col-sm-12">
<table class="table table-bordered table-striped dataTable" role="grid">
    <thead>
    <tr role="row">
        <th tabindex="0" rowspan="1" colspan="1">Book Title</th>
        <th tabindex="0" rowspan="1" colspan="1">Author</th>
        <th tabindex="0" rowspan="1" colspan="1">ISBN</th>
        <th tabindex="0" rowspan="1" colspan="1">Shelf Location</th>
        <th tabindex="0" rowspan="1" colspan="1">Taken At</th>
        <th tabindex="0" rowspan="1" colspan="1">Expire At</th>
        <th tabindex="0" rowspan="1" colspan="1">Status</th>
    </tr>
    </thead>
    <tbody>
    @if (!empty($data))
        @foreach($data as $userBook)
            <tr role="row" class="odd">
                <td>{{ $userBook['title'] }}</td>
                <td>{{ $userBook['author'] }}</td>
                <td>{{ $userBook['isbn'] }}</td>
                <td>{{ $userBook['shelf_location'] }}</td>
                <td>{{ date('m/d/y', strtotime($userBook['started_at'])) }}</td>
                <td>{{ date('m/d/y', strtotime($userBook['ended_at'])) }}</td>
                <td>
                    @if($userBook['status'] == 'RETURNED')
                        <span class="label label-success">{{ $userBook['status'] }}</span>
                    @elseif($userBook['status'] == 'BORROWED')
                        <span class="label label-warning">{{ $userBook['status'] }}</span><br/>
                        <a href="{{ secure_url('books/return', ['book_uid' => $userBook['book_uid']]) }}">
                            Return this now!
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="8">No records found.</td>
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