$(function() {

    jQuery.validator.addMethod("selectstate", function(value, element, param) {
        if (value == 'selected') {
            return false;
        }
        return true;
    }, 'Please select state');

    let jqValidationOptions = {
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            address1: {
                required: true,
            },
            address2: {
                required: true,
            },
            city: {
                required: true,
            },
            zip: {
                required: true,
            },
            phone: {
                required: true,
            },
            agree: {
                required: true,
            },
            state: {
                required: function() {
                    return $("#country").val() == "US"
                },
                selectstate: true
            },
            other_state: {
                required: function() {
                    return $("#country").val() != "US"
                }
            },
            password: {
                required: true
            },
            confirmpassword: {
                required: true,
                equalTo: '#password'
            },
        },
        messages: {
            email: {
                required: GetMessage('Validate', 'Email'),
                email: GetMessage('Register', 'ValidEmail'),
            },
            first_name: {
                required: GetMessage('Validate', 'FirstName')
            },
            last_name: {
                required: GetMessage('Validate', 'LastName')
            },
            address: {
                required: GetMessage('Validate', 'Address')
            },
            address1: {
                required: GetMessage('Validate', 'Address')
            },
            city: {
                required: GetMessage('Validate', 'City')
            },
            zip: {
                required: GetMessage('Validate', 'ZipCode')
            },
            phone: {
                required: GetMessage('Validate', 'Phone')
            },
            state: {
                required: GetMessage('Validate', 'State'),
                selectstate: 'Please select state',
            },
            other_state: {
                required: GetMessage('Validate', 'OtherState')
            },
            password: {
                required: GetMessage('Validate', 'Password')
            },
            confirmpassword: {
                required: GetMessage('Validate', 'ConfirmPassword'),
                equalTo: GetMessage('Validate', 'DoesNotMatch')
            },
            agree: {
                required: GetMessage('Validate', 'Agree')
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "email" || element.attr("name") == "password" || element.attr("name") == "confirmpassword" || element.attr("name") == "agree" || element.attr("name") == "last_name" || element.attr("name") == "address" || element.attr("name") == "address1" || element.attr("name") == "first_name") {
                if (element.attr("name") == "address" || element.attr("name") == "address1") {
                    error.addClass('invalid-feedback');
                    element.parent().append(error);
                } else {
                    error.addClass('invalid-feedback');
                    element.parent().parent().append(error);
                }
            } else { // This is the default behavior of the script
                //alert(error.insertAfter( element ));
                error.insertAfter(element);
            }
        },
    };

    Object.assign(jqValidationOptions, jqValidationGlobalOptions);
    $('#frmEditProfile').validate(jqValidationOptions);
});

$(document).ready(function() {

    $('#country').on('change', function(e) {
        var selectedCountry = $(this).val();
        console.log(selectedCountry);
        if (selectedCountry == 'US') {
            $("#divotherstate").hide();
            $("#divstate").show();
        } else {
            $("#other_state").val('');
            $("#divotherstate").show();
            $("#divstate").hide();
        }
    });

});