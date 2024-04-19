//checks whether a actType 'add' or not
let isActTypeAdd = function() {
	return $("#actType").val() == "add";
}

let check_permision = function() {
	if(document.frmAdmin.admin_type[0].checked==true)
	{
		document.getElementById("all_permision").disabled = false;
		document.getElementById("all_permision").checked = true;
		document.getElementById("checkboxes_for_super_admin").style.display = "block";
		document.getElementById("checkboxes_for_sub_admin").style.display = "none";
	}
	else if(document.frmAdmin.admin_type[1].checked==true)
	{
		document.getElementById("all_permision").disabled = true;
		document.getElementById("all_permision").checked = false;
		document.getElementById("checkboxes_for_super_admin").style.display = "none";
		document.getElementById("checkboxes_for_sub_admin").style.display = "block";
	}
}

var masterCheck = $("#masterCheck");
var listCheckItems = $("#checkboxes_for_sub_admin :checkbox").not("#masterCheck");
let adjustMasterCheckbox = function() {
	var totalItems = listCheckItems.length;
	var checkedItems = listCheckItems.filter(":checked").length;

	if (totalItems == checkedItems) {
		masterCheck.prop("indeterminate", false);
		masterCheck.prop("checked", true);
	}
	else if (checkedItems > 0 && checkedItems < totalItems) {
		masterCheck.prop("indeterminate", true);
	}
	else {
		masterCheck.prop("indeterminate", false);
		masterCheck.prop("checked", false);
	}
}

$(function () {
	
	$('#all_permision').on('click', function(e){
		e.preventDefault();
		return false;
	});

	adjustMasterCheckbox();

	masterCheck.on("click", function() {
		var isMasterChecked = $(this).is(":checked");
		listCheckItems.prop("checked", isMasterChecked);
	});

	listCheckItems.on("change", function() {
		adjustMasterCheckbox();
	});

	jQuery.validator.addMethod("checkSubAdminPrivileges", function(value, element) {
		if(document.frmAdmin.admin_type[1].checked==true)
		{
			if(document.querySelectorAll('input[type="checkbox"][name="rights[]"]:checked').length == 0) {
				return false;
			}
		}
		return true;
	}, "Please select atleast one privileges.");

	let jqValidationOptions = {
		ignore: [],
		rules: {
			email: {
				required: true,
				email: true,
			},
			password: {
				required: isActTypeAdd,
				minlength: 6
			},
			password_confirmation: {
				required: isActTypeAdd,
				equalTo: "#password"
			},
			check_subadmin_rights: { 
				checkSubAdminPrivileges : true
			}
		},
		messages: {
			email: {
				required: "Please enter an email address",
				email: "Please enter a vaild email address"
			},
			password: {
				required: "Please enter a password",
				minlength: "Your password must be at least 6 characters long"
			},
			password_confirmation: {
				required: "Please enter a confirm password",
				equalTo: "Password and confirm password doesn\'t match"
			}
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmAdmin').validate(jqValidationOptions);
	
});
