

jQuery.validator.addMethod("email", function(value, element) {
    return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
}, GetMessage('Register', 'ValidEmail'));

$('#frmLogin').validate({
    rules:
    {
        email: { required: true, email: true },
        password: { required: true }
    },
    messages:
    {
        email: {
            required: GetMessage('Validate', 'Email'),
            email: GetMessage('Validate', 'ValidEmail'),
        },
        password: {
            required: GetMessage('Validate', 'Password'),
            minlength: GetMessage('Validate', 'ValidPassword'),
        },
    },
    invalidHandler: function (form, validator) {
        $("#frmLogin .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmLogin #error_" + id).html(message);
                $("#frmLogin #error_" + id).show();
            }
        }
        else {
            $("#frmLogin .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {        
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var check_value = document.getElementById("check_value").value;
        var is_login_popup = document.getElementById("isAction").value;        
        var is_action = 'wish_login';
        if(is_login_popup == '1'){
            is_action = 'login_popup';
        }
            
        //var str = "email=" + email + "&password=" + password + "&isAction=" + 'wish_login' + '&check_value=' + check_value;
        //var str = "email=" + email + "&password=" + password + "&isAction=" + 'login_popup' + '&check_value=' + check_value;
        var str = "email=" + email + "&password=" + password + "&isAction=" + is_action + '&check_value=' + check_value;

        $.ajax({
            type: "POST",
            url: site_url + "/popup",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (msg) {
                if(is_action == 'login_popup' && msg == 1){
                    location.reload();
                } else {
                    $("#cover-spin").hide();
                    $("#myModalPopUpLogin").html(msg);
                    $('#myModalPopUpLogin').modal('show');
                }
                // $("#cover-spin").hide();
                // $("#myModalPopUpLogin").html(msg);
                // $('#myModalPopUpLogin').modal('show');
            },
            error: function (err) {
                $('#error_email').text('');
                $('#error_password').text('');

                $('#error_email').text(err.responseJSON.errors.email);
                $('#error_password').text(err.responseJSON.errors.password);

                $("#error_email").show();
                $("#error_password").show();
            }
        });
    }
});

$("#close_forgotpassword").on('click', function(){
	$('#myModalPopUpLogin').modal('hide');	
})

$('#frmForgotPwd').validate({
    rules:
    {
        email: { required: true, email: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') }
    },
    invalidHandler: function (form, validator) {
        $("#frmForgotPwd .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmForgotPwd #error_" + id).html(message);
                $("#frmForgotPwd #error_" + id).show();
            }
        }
        else {
            $("#frmForgotPwd .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var email = document.getElementById("email").value;

        var str = "email=" + email + "&isAction=" + 'wish_forget';

        $.ajax({
            type: "POST",
            url: site_url + "/popup",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (msg) {
                $("#cover-spin").hide();
                $("#myModalPopUpLogin").html(msg);
                $('#myModalPopUpLogin').modal('show');
            },
            error: function (err) {
                $('#error_email').text('');

                $('#error_email').text(err.responseJSON.errors.email);

                $("#error_email").show();
            }
        });
    }
});

