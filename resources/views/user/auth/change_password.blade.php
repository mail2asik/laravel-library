@extends('user_template')

@section('content')
    <div class="about">
        <div class="container">
            <section class="title-section">
                <h1 class="title-header">Change Password</h1>
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
                        <h3 class="box-title">Change Password</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form autocomplete="off" id="change_password_form" role="form" method="post" action="{{ secure_url('/doChangePassword') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="box-body">
                            @include('user.sections.flash-message')
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" placeholder="Password" id="password" name="password" class="form-control" value="">
                                <div></div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">New Password</label>
                                <input type="password" placeholder="New Password" id="new_password" name="new_password" class="form-control" value="">
                                <div></div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Confirm Password</label>
                                <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" class="form-control" value="">
                                <div></div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="ladda-button" type="submit" id="submitBtn" data-style="expand-right" data-size="xs">
                                <span class="ladda-label">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
    <script>

        $(document).ready(function(){

            // validate reset password form on key-up and submit
            var validator = $("#change_password_form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    },
                    new_password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    password: {
                        required: "Please enter password",
                        minlength: jQuery.validator.format("Please enter correct password")
                    },
                    new_password: {
                        required: "Please enter new password",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    confirm_password: {
                        required: "Please enter re-password",
                        minlength: jQuery.validator.format("Enter at least {0} characters"),
                        equalTo: "Enter the same password as above"
                    }
                },
                errorClass: 'text-red',
                // the errorPlacement has to take the table layout into account
                errorPlacement: function(error, element) {
                    error.appendTo(element.next());
                },
                submitHandler: function(form) {
                    var l = Ladda.create( document.querySelector( '#submitBtn' ) );
                    l.start();

                    $('#submitBtn').attr('disabled','disabled');
                    form.submit();
                }

            });
        });
    </script>
@endsection