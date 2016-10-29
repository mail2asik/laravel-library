@extends('user_template')

@section('content')
    <div class="about">
        <div class="container">
            <section class="title-section">
                <h1 class="title-header">Dashboard</h1>
            </section>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <!-- Sidebar -->
            @include('user.sections.sidebar')

            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dashboard</h3>
                    </div>
                    <div class="box-body" style="min-height:350px;">
                        <ul>
                        <li>Each book is loaned for a maximum duration of 2 calendar weeks</li>
                        <li>Failure to return a book before expiry will cause a Fine to be charged to the Member @ $2 per day or part thereof</li>
                        <li>Member can loan a maximum of 6 books but Junior Member (age &lt;= 12 years) can loan a maximum of 3 books</li>
                        </ul>
                        <br/>
                        <p>
                            <strong>Maximum of books eligible: </strong> {{ $logged_in_user['max_books_eligible'] }}
                        </p>
                        <p>
                            <strong>Number of books borrowed: </strong> {{ $logged_in_user['no_of_books_borrowed'] }}
                        </p>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection