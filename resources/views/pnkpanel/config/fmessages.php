<?php

/* General */
$Messages['Validate'] = [
    'Email' => 'Pleae enter your email address',
    'ValidEmail' => 'Please enter valid email address',
    'Password' => 'Please enter password',
    'ValidPassword' => 'Your password must be at least 6 characters long',
    'ConfirmPassword' => 'Please enter confirm password',
    'OldPassword' => 'Please enter old password',
    'NewPassword' => 'Please enter new password, password minimum length should be 6 character long.Do not use space in password.',
    'ReTypeNewPassword' => 'Please enter valid confirm password, password minimum length should be 6 character long',
    'ValidConfirmPassword' => 'Password and confirm password not match',
    'UpperCaseAndLetter' => 'Password contains at least one uppercase letter and one number',
    'DoesNotMatch' => 'New password does not match with confirm password',
    'FirstName' => 'Please Enter First Name',
    'LastName' => 'Please Enter Last Name',
    'Address' => 'Please enter address',
    'Country' => 'Please enter country',
    'City' => 'Please enter city',
    'State' => 'Please select state',
    'Phone' => 'Please enter phone',
    'OtherState' => 'Please enter other state',
    'ZipCode' => 'Please enter zip code',
    'Agree' => 'Please select terms and privacy policy',
    'GRecaptchaResponse' => 'Please verify the captcha to proceed'
];

/* Change password Page */
$Messages['ChangePassword'] = [
    'Success' => 'Password changed successfully.',
    'WrongOldPassword' => 'Please enter valid old password and try again.',
];

/* My detail Page */
$Messages['EditProfile'] = [
    'Success' => 'Profile updated successfully.'
];

/* Forgot Password Page */
$Messages['Forgot'] = [
    'NotExistEmail' => 'Sorry, we could not find this email in our database or you may have checked out earlier using a guest account.',
    'Success' => 'Your password reset link been sent to your email address.',
    'ValidEmail' => 'Please enter your valid email address',
];

/* Cart Page */
$Messages['Cart'] = [
	'ProductNotAvailable' => 'Sorry, Requested product not available',
	'QuantityNotAvailable' => 'Sorry, Requested product quantity not available',
];

/* Register Page */
$Messages['Register'] = [
    'ValidEmail' => 'Please input a valid email address',
    'ExistingEmail' => 'Email already exists for this account'
];

/* Login Page */
$Messages['Login'] = [
    'Failed' => 'Invalid Email Address Or Password.'
];

/* Contact Us Page */
$Messages['ContactUs'] = [
    'Success' => 'Your email has been sent succesfully'
];

/* General */
$Messages['Order'] = [];

/* Import WishListCategory Page */
$Messages['WishListCategory'] = [
    'AddSuccess' => 'Category added successfully!',
    'ExistCategory' => 'Category with same Name exist, Please change the Name'
];

/* Import WishList Page */
$Messages['WishList'] = [
    'AddSuccess' => 'Product added successfully!',
    'AddDescription' => 'Please enter description',
    'Category' => 'Please select category',
    'Name' => 'Please enter category name.',
];



$Messages['ProductPhoto'] = [
    'AddSuccess' => 'Product photo added successfully!',
    'AddDescription' => 'The photo upload must be an image & photo upload must be a file of type: jpeg, png, jpg.',
];

/* WishListCategory Page */
$Messages['WishCategory'] = [
    'Name' => 'Please enter category name.',
    'Description' => 'Please enter description.',
    'UpdateSuccess' => 'Wish category infomation updated successfully.',
    'DeleteSuccess' => 'Selected wish category deleted successfully.',
    'CheckToDelete' => 'Please check the wish category to be deleted.'

];

/* WishProduct Page */
$Messages['WishProduct'] = [
    'DeleteSuccess' => 'Selected wish product deleted successfully.',
    'CheckToDelete' => 'Please check the wish product to be deleted.'

];

return $Messages;
