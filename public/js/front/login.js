$(function () {
  let jqValidationOptions = {
    ignore: [],
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 6,
      },
    },
    messages: {
      email: {
        required: GetMessage("Validate", "Email"),
        email: GetMessage("Validate", "ValidEmail"),
      },
      password: {
        required: GetMessage("Validate", "Password"),
        minlength: GetMessage("Validate", "ValidPassword"),
      },
    },
    errorPlacement: function (error, element) {
      if (
        element.attr("name") == "email" ||
        element.attr("name") == "password"
      ) {
        error.addClass("w-100");
        element.parent().parent().append(error);
      } else {
        // This is the default behavior of the script
        error.insertAfter(element);
      }
    },
  };
  Object.assign(jqValidationOptions, jqValidationGlobalOptions);
  $("#formLogin").validate(jqValidationOptions);
});

$(".svg_eye_slash").on("click", function (event) {
  $("#password").attr("type", "text");
  $(".svg_eye_slash").addClass("dnone");
  $(".svg_eye").removeClass("dnone");
  event.stopPropagation();
  event.stopImmediatePropagation();
});
$(".svg_eye").on("click", function (event) {
  $("#password").attr("type", "password");
  $(".svg_eye_slash").removeClass("dnone");
  $(".svg_eye").addClass("dnone");
  event.stopPropagation();
  event.stopImmediatePropagation();
});
