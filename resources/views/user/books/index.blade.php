@extends('user_template')

@section('content')
    <div class="banner text-center">
        <div class="container">
            <div class="banner-info">
                <h2>Welcome to our website!</h2>
                <p>Each book is loaned for a maximum duration of 2 calendar weeks.Failure to return a book before expiry will cause a Fine to be charged to the Member @ $2 per day.</p>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-xs-10 col-xs-offset-1" style="padding: 25px 0px;">

            <div class="box-body">@include('user.sections.flash-message')</div>
            <div class="box">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <h3 class="box-title">Find your Books</h3>
                </div>
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-3">
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
                            <div class="col-sm-9">
                                <div id="example1_filter" class="dataTables_filter pull-right">
                                    <label>Search: <input type="search" id="search_by_keywords" class="form-control input-sm" value="{{ $search_by_keywords }}"></label>
                                </div>
                            </div>
                        </div>
                        <div id="ajaxResponse" class="overflowAutoSmallScreen">

                        </div>
                        <div class="overlay hide" id="ajaxLoader">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div><!-- /.row -->
    <script>
        // Get url
        var getBooksPageURL = function(page, limit, search_by_keywords, role) {
            var url =  '/?page='+ page +'&limit=' + limit + '&search_by_keywords=' + encodeURIComponent(search_by_keywords) ;
            return url;
        };

        var url = getBooksPageURL('{{ $page }}', '{{ $limit }}', '{{ $search_by_keywords }}');

        $(document).ready(function () {

            // Pagination
            $(document).on('click', '.pagination a', function (e) {
                url = $(this).attr('href');
                addBooksPushState(url);
                getBooksPage(url);
                e.preventDefault();
            });

            // Rows per page
            $(document).on('change', '#rowsPerPage', function (e) {
                var updateUrl = getBooksPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
                addBooksPushState(updateUrl);
                getBooksPage(updateUrl);
                e.preventDefault();
            });

            // Search by keyword
            $(document).on('keyup', '#search_by_keywords', function (e) {
                if(e.keyCode == 13) {
                    var updateUrl = getBooksPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
                    addBooksPushState(updateUrl);
                    getBooksPage(updateUrl);
                    e.preventDefault();
                }
            });

            // Back button action
            $(window).on('popstate', function() {
                if (history.state !== null) {
                    if (typeof(history.state.usersPageUrl) == 'string') {
                        getBooksPage(history.state.usersPageUrl);
                    }
                } else {
                    getBooksPage(url);
                }
            });

        });

        // Ajax request
        var getBooksPage = function(url) {
            $('#ajaxLoader').removeClass('hide');
            $.get(url, function(data, status, xhr){
                $('#ajaxLoader').addClass('hide');

                if (xhr.getResponseHeader('Content-Type') === 'application/json' && data.error) {
                    window.location = '{{ helpers::getNotFoundPageURL() }}';
                }

                if(status == 'success'){
                    $('#ajaxResponse').html(data);
                }
            });
        };

        // Add push state
        var addBooksPushState = function(url){
            window.history.pushState({booksPageUrl: url}, 'Users', url);
        };

        setTimeout(function() { getBooksPage(url); window.history.replaceState({booksPageUrl: url}, '', window.location.href); }, 100);
    </script>

@endsection