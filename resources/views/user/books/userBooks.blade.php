@extends('user_template')

@section('content')
    <div class="about">
        <div class="container">
            <section class="title-section">
                <h1 class="title-header">My Books</h1>
            </section>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <!-- Sidebar -->
            @include('user.sections.sidebar')

            <div class="col-md-8">
                <div class="box-body">@include('user.sections.flash-message')</div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Books</h3>
                    </div>
                    <div class="box-body" style="min-height:350px;">

                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_length">
                                        <label>Show
                                            <select id="rowsPerPage" class="form-control input-sm">
                                                <option value="5" {{ ($limit == '5') ? 'selected' : '' }}>5</option>
                                                <option value="10" {{ ($limit == '10') ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ ($limit == '25') ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ ($limit == '50') ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ ($limit == '100') ? 'selected' : '' }}>100</option>
                                            </select> entries</label></div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="example1_filter" class="dataTables_filter text-right">
                                        <label>Search: <input type="search" id="search_by_keywords" class="form-control input-sm"
                                                              value="{{ $search_by_keywords }}"></label>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div id="ajaxResponse" class="overflowAutoSmallScreen">

                            </div>
                            <div class="overlay hide" id="ajaxLoader">
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
        var url = getUserBooksPageURL('{{ $page }}', '{{ $limit }}', '{{ $search_by_keywords }}');

        $(document).ready(function () {

            // Pagination
            $(document).on('click', '.pagination a', function (e) {
                url = $(this).attr('href');
                addUserBooksPushState(url);
                getUserBooksPage(url);
                e.preventDefault();
            });

            // Rows per page
            $(document).on('change', '#rowsPerPage', function (e) {
                var updateUrl = getUserBooksPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
                addUserBooksPushState(updateUrl);
                getUserBooksPage(updateUrl);
                e.preventDefault();
            });

            // Search by keyword
            $(document).on('keyup', '#search_by_keywords', function (e) {
                if (e.keyCode == 13) {
                    var updateUrl = getUserBooksPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
                    addUserBooksPushState(updateUrl);
                    getUserBooksPage(updateUrl);
                    e.preventDefault();
                }
            });

            // Back button action
            $(window).on('popstate', function () {
                if (history.state !== null) {
                    if (typeof(history.state.userBooksPageUrl) == 'string') {
                        getUserBooksPage(history.state.userBooksPageUrl);
                    }
                } else {
                    getUserBooksPage(url);
                }
            });

        });

        // Ajax request
        var getUserBooksPage = function (url) {
            $('#ajaxLoader').removeClass('hide');
            $.get(url, function (data, status, xhr) {
                $('#ajaxLoader').addClass('hide');

                if (xhr.getResponseHeader('Content-Type') === 'application/json' && data.error) {
                    window.location = '{{ helpers::getNotFoundPageURL() }}';
                }

                if (status == 'success') {
                    $('#ajaxResponse').html(data);
                }
            });
        };

        // Add push state
        var addUserBooksPushState = function (url) {
            window.history.pushState({userBooksPageUrl: url}, 'Partners', url);
        };

        // Get url
        function getUserBooksPageURL(page, limit, search_by_keywords) {
            return '/books?page=' + page + '&limit=' + limit + '&search_by_keywords=' + search_by_keywords
        };

        setTimeout(function () {
            getUserBooksPage(url);
            window.history.replaceState({userBooksPageUrl: url}, '', window.location.href);
        }, 100);

        // Application button loader
        function returnBookButtonLoader(buttonId) {
            var l = Ladda.create(document.querySelector('#'+buttonId));
            l.start();
        }
    </script>
@endsection