<div class="row" >
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <th tabindex="0" rowspan="1" colspan="1" style="width:100px;">Title</th>
                <th tabindex="0" rowspan="1" colspan="1" style="width:150px;">Author</th>
                <th tabindex="0" rowspan="1" colspan="1">ISBN</th>
                <th tabindex="0" rowspan="1" colspan="1">Shelf</th>
                <th tabindex="0" rowspan="1" colspan="1">Quantity</th>
                <th tabindex="0" rowspan="1" colspan="1">Loan</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $book)
                <tr role="row" class="odd">
                    <td><a href="{{ secure_url('admin/books/update', ['book_uid' => $book['uid']]) }}">{{ $book['title'] }}</a></td>
                    <td>{{ $book['author'] }}</td>
                    <td>{{ $book['isbn'] }}</td>
                    <td>{{ $book['shelf_location'] }}</td>
                    <td>{{ $book['quantity'] }}</td>
                    <td>{{ $book['no_of_books_loan'] }}</td>
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