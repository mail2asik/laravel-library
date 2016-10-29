@extends('user_template')

@section('content')
    <div class="pageHeader">
        <div class="container">
            <section class="title-section">
                <h1 class="title-header">Reset Password</h1>
            </section>
        </div>
    </div>
    @include('user.sections.flash-message')
    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title">Reset Password</div>
                </div>

                <div style="padding-top:30px" class="panel-body" >

                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                    <form autocomplete="off" id="reset_password_form" class="form-horizontal" role="form" method="post" action="{{ secure_url('/doResetPassword') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="user_uid" value="{{ $user_uid }}" />
                        <input type="hidden" name="token" value="{{ $token }}" />
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="password" type="password" class="form-control" name="password" placeholder="New Password">
                        </div>
                        <div style="margin-bottom: 25px"></div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="confirm_password" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        </div>
                        <div style="margin-bottom: 25px"></div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->

                            <div class="col-sm-12 controls">
                                <button class="ladda-button" type="submit" id="submitBtn" data-style="expand-right" data-size="xs">
                                    <span class="ladda-label">Submit</span>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function(){

            // validate reset password form on key-up and submit
            var validator = $("#reset_password_form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "Please enter password",
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
                    error.appendTo(element.parent().next());
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