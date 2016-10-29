@extends('admin_template')

@section('content')
    <div class='row'>
        @include('admin.sections.flash-message')
        <form id="change_password_form" role="form" method="post" action="{{ secure_url('/admin/doChangePassword') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password<sup class="error">*</sup></label>
                            <input type="password" placeholder="Password" id="password" name="password" class="form-control"
                                   value="">

                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">New Password<sup class="error">*</sup></label>
                            <input type="password" placeholder="New Password" id="new_password" name="new_password"
                                   class="form-control" value="">

                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Confirm Password<sup class="error">*</sup></label>
                            <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password"
                                   class="form-control" value="">

                            <div></div>
                        </div>
                    </div>
                    @include('admin.common.submit_button', [
                    'box_style' => false,
                    'button_label' => 'Submit'
                    ])
                </div>


            </div>

        </form>
    </div>
    <script>

        $(document).ready(function () {

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
                errorPlacement: function (error, element) {
                    error.appendTo(element.next());
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