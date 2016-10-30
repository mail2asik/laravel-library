@extends('user_template')

@section('content')
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
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
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ $max_books_eligible }}</h3>
                                    <p>Maximum of books eligible</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-plus-circled"></i>
                                </div>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ $no_of_books_borrowed }}</h3>
                                    <p>Number of books borrowed</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-minus-circled"></i>
                                </div>
                            </div>
                        </div><!-- ./col -->

                        <ul>
                        <li>Each book is loaned for a maximum duration of 2 calendar weeks</li>
                        <li>Failure to return a book before expiry will cause a Fine to be charged to the Member @ $2 per day or part thereof</li>
                        <li>Member can loan a maximum of 6 books but Junior Member (age &lt;= 12 years) can loan a maximum of 3 books</li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection