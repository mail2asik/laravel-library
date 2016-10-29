@extends('admin_template')

@section('content')
    <div class='row'>
        @include('admin.sections.flash-message')
        <form id="change_profile_form" role="form" method="post" action="{{ secure_url('/admin/doChangeProfile') }}"
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Details</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputPassword1">First Name<sup class="error">*</sup></label>
                            <input type="text" class="form-control alphaOnly" name="first_name" id="first_name"
                                   placeholder="First Name"
                                   value="{{ old('first_name', $logged_in_user['first_name']) }}"/>

                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Last Name<sup class="error">*</sup></label>
                            <input type="text" class="form-control alphaOnly" name="last_name" id="last_name"
                                   placeholder="Last Name"
                                   value="{{ old('last_name', $logged_in_user['last_name']) }}"/>

                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Gender<sup class="error">*</sup></label>

                            <div class="radio" style="margin-top:0px;">
                                <label>
                                    <input type="radio" name="gender" id="gender"
                                           value="male" {{ (Input::old('gender') == 'male' || $logged_in_user['gender'] == 'male') ? 'checked' : '' }} />
                                    Male
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" id="gender"
                                           value="female" {{ (Input::old('gender') == 'female' || $logged_in_user['gender'] == 'female') ? 'checked' : '' }} />
                                    Female
                                </label>
                            </div>
                            <div id="gender-error" style="display: inline;"></div>
                        </div>
                    </div>
                    @include('admin.common.submit_button', [
                    'box_style'    => false,
                    'button_label' => 'Submit'
                    ])
                </div>

            </div>
        </form>
    </div>


    <script>
        $(document).ready(function () {
            // validate reset password form on key-up and submit
            var validator = $("#change_profile_form").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 3
                    },
                    gender: {
                        required: true
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter first name",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    last_name: {
                        required: "Please enter last name",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    gender: {
                        required: "Please select gender"
                    }
                },
                errorClass: 'text-red',
                // the errorPlacement has to take the table layout into account
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "gender") {
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