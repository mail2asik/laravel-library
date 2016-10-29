@extends('admin_template')

@section('content')
    <div class='row'>
        @include('admin.sections.flash-message')
        <form id="create_book_form" role="form" method="post" action="{{ secure_url('/admin/doCreateBook') }}">
            <!-- form start -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Book Details</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Title<sup class="error">*</sup></label>
                            <input type="text" class="form-control" name="title" id="title"
                                   placeholder="Title"
                                   value="{{ old('title') }}"/>

                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Author<sup class="error">*</sup></label>
                            <input type="text" class="form-control alphaOnly" name="author" id="author"
                                   placeholder="Author"
                                   value="{{ old('author') }}"/>
                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">ISBN<sup class="error">*</sup></label>
                            <input type="text" class="form-control" name="isbn" id="isbn"
                                   placeholder="isbn"
                                   value="{{ old('isbn') }}"/>
                            <div></div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Other Details</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Shelf Location<sup class="error">*</sup></label>
                            <input type="text" class="form-control" name="shelf_location" id="shelf_location"
                                   placeholder="Shelf Location"
                                   value="{{ old('shelf_location') }}"/>
                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Quantity<sup class="error">*</sup></label>
                            <input type="text" class="form-control" name="quantity" id="quantity"
                                   placeholder="Quantity"
                                   value="{{ old('quantity') }}"/>
                            <div></div>
                        </div>
                    </div>
                </div>

                @include('admin.common.submit_button', [
                'box_style' => true,
                'button_label' => 'Submit'
                ])
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {

            var validator = $("#create_book_form").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    author: {
                        required: true,
                        minlength: 3
                    },
                    isbn: {
                        required: true
                    },
                    shelf_location: {
                        required: true
                    },
                    quantity: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "Please enter title",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    author: {
                        required: "Please enter author name",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    isbn: {
                        required: "Please enter isbn"
                    },
                    shelf_location: {
                        required: "Please enter shelf location"
                    },
                    quantity: {
                        required: "Please enter quantity"
                    }
                },
                errorClass: 'text-red',
                // the errorPlacement has to take the table layout into account
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "status") {
                        $("#status-error").html(error);
                    } else if (element.attr("name") == "gender") {
                        $("#gender-error").html(error);
                    } else {
                        error.appendTo(element.next());
                    }
                },
                submitHandler: function (form) {
                    var l = Ladda.create(document.querySelector('#submitBtn'));
                    l.start();

                    $('#submitBtn').attr('disabled', 'disabled');
                    form.submit();
                }

            });

        });
    </script>
@endsection