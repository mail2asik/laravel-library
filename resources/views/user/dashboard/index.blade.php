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
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection