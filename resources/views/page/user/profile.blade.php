@extends('layouts.master')

@section('title', 'User Profile')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <!-- Profile Image -->
            <div class="card card-teal card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/img/user-profile-default.png') }}" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                <p class="text-muted text-center">{{ $user->roles[0]->name }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> : {{ $user->email }}
                  </li>
                  <li class="list-group-item">
                    <b>Department</b> : Cutting
                  </li>
                </ul>

                <a href="javascript:void(0);" class="btn bg-teal btn-block" id="btn_change_password">Change Password</a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

</div>


<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('profile.change-password') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="change_password_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password">
                                        <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password">
                                        <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirm">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Enter New Confirm Password">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password">
                                        <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const change_password_url ='{{ route("profile.change-password") }}';

</script>
<script type="text/javascript">

$(document).ready(function(){
    $('#btn_change_password').click((e) => {
        $('#change_password_form').find("input[type=text], textarea").val("");
        $('#modal_form').modal('show');
    })

    $(".toggle-password").click(function() {
        $(this).find("i").toggleClass("fa-eye fa-eye-slash");
        let input = $($(this).parent().prev("input"));
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
})
</script>

<script type="text/javascript">
    // ## Form Validation
    let rules = {
        old_password: {
            required: true,
        },
        new_password: {
            required: true,
        },
        new_password_confirm: {
            required: true,
            equalTo: "#new_password"
        }
    };
    let messages = {
        old_password: {
            required: "Please enter the Password",
        },
        new_password: {
            required: "Please enter your New Password",
        },
        new_password_confirm: {
            required: "Please enter your New Password Confirmation",
            equalTo: "Must be same as your New Password"
            
        },
    };
    let validator = $("#change_password_form").validate({
        rules: rules,
        messages: messages,
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            let old_password = $('#old_password').val();
            let new_password = $('#new_password').val();
            let new_password_confirm = $('#new_password_confirm').val();

            let data_params = { 
                token,
                body: { 
                    old_password : $('#old_password').val(),
                    new_password : $('#new_password').val(),
                    new_password_confirm : $('#new_password_confirm').val(),
                }
            };
            using_fetch(change_password_url, data_params, "POST").then((result) => {
                if(result.status == "success"){
                    swal_info({ title : result.message, reload_option: true });
                } else {
                    swal_warning({ title: result.message });
                }
            }).catch((err) => {
                swal_failed({ title: err });
            });
        }
    });
</script>

@endpush