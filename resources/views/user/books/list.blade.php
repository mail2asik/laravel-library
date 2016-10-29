<div class="row" >
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <th tabindex="0" rowspan="1" colspan="1">Book Title</th>
                <th tabindex="0" rowspan="1" colspan="1">Author</th>
                <th tabindex="0" rowspan="1" colspan="1">ISBN</th>
                <th tabindex="0" rowspan="1" colspan="1">Shelf Location</th>
                <th tabindex="0" rowspan="1" colspan="1"></th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $book)
                <tr role="row" class="odd">
                    <td>{{ $book['title'] }}</td>
                    <td>{{ $book['author'] }}</td>
                    <td>{{ $book['isbn'] }}</td>
                    <td>{{ $book['shelf_location'] }}</td>
                    <td class="text-center">
                        <a href="{{ secure_url('books/borrow', ['book_uid' => $book['uid']]) }}">
                            <button>Borrow</button>
                        </a>
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