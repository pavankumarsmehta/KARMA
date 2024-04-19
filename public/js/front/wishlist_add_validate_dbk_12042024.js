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

        var str = "email=" + email + "&password=" + password + "&isAction=" + 'wish_login' + '&check_value=' + check_value;

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
                $('#error_password').text('');

                $('#error_email').text(err.responseJSON.errors.email);
                $('#error_password').text(err.responseJSON.errors.password);

                $("#error_email").show();
                $("#error_password").show();
            }
        });
    }
});


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


$('#frmproduct').validate({
    rules:
    {
        // description: { required: true },
        wishlist_category_id: { required: true }
    },
    messages:
    {
        // description: { required: GetMessage('WishList', 'AddDescription') },
        wishlist_category_id: { required: GetMessage('WishList', 'Category') }
    },
    invalidHandler: function (form, validator) {
        $("#frmproduct .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmproduct #error_" + id).html(message);
                $("#frmproduct #error_" + id).show();
            }
        }
        else {
            $("#frmproduct .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#frmproduct").serialize();
        const productId = $('#productId').val();
        console.log('productId', productId)
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "/popup",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.wishlistCount) {
                    $('#cart-item-count').addClass('cart-item-count');
                    $('.cart-item-count').text(result.wishlistCount);
                } else {
                    $('#cart-item-count').removeClass('cart-item-count');
                    $("#cart-item-count").hide();
                } 
                $("#cover-spin").hide();
                $(`.displaypopupboxwishlist[data-productId="${productId}"]`).addClass('active');
                // $("#myModalPopUpLogin").hide();
                 $("#myModalPopUpLogin").modal('hide');

                // $("#myModalPopUpLogin").html(msg);
                // $('#myModalPopUpLogin').hide();
                // $('#myModalPopUpLogin').modal('show');
            },
            error: function (err) {
                $('#error_description').text('');
                $('#error_wishlist_category_id').text('');

                $('#error_description').text(err.responseJSON.errors.description);
                $('#error_wishlist_category_id').text(err.responseJSON.errors.wishlist_category_id);

                $("#error_description").show();
                $("#error_wishlist_category_id").show();
            }
        });
    }
});

$('#frmcategory').validate({
    rules:
    {
        category_name: { required: true },
        // description: { required: true }
    },
    messages:
    {
        // description: { required: GetMessage('WishList', 'AddDescription') },
        category_name: { required: GetMessage('WishList', 'Name') }
    },
    invalidHandler: function (form, validator) {
        $("#frmcategory .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmcategory #error_" + id).html(message);
                $("#frmcategory #error_" + id).show();
            }
        }
        else {
            $("#frmcategory .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#frmcategory").serialize();
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "/popup",
            data: data,
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
                $('#error_category_name').text('');
                $('#error_description').text('');

                $('#error_category_name').text(err.responseJSON.errors.category_name);
                $('#error_description').text(err.responseJSON.errors.description);

                $("#error_category_name").show();
                $("#error_description").show();
            }
        });
    }
});

$('#myModalPopUpLogin').on('hidden.bs.modal', function (event) {
    /*if (!event.target.firstChild.classList.contains("login-popup")) {
        location.reload();
    }*/
});

