var formValidation = function () {
    if (document.dealLoginForm.username.value == "") {
        document.getElementById('userErrorDiv').innerHTML = "Please provide UserName!";
        document.dealLoginForm.username.focus();
        return false;
    } else {
        document.getElementById('userErrorDiv').innerHTML = "";
    }
    if (document.dealLoginForm.password.value == "") {
        document.getElementById('userPasswordDiv').innerHTML = "Please provide Password!";
        document.dealLoginForm.password.focus();
        return false;
    } else {
        document.getElementById('userPasswordDiv').innerHTML = "";
    }

    return true;
}

var dealSignupValidation = function () {
    var emailValue = document.dealSignUpForm.email.value;
    var emailReturnValue = validateEmail(emailValue);
    var passwordValue = dealSignUpForm.passwd.value;
    var confPasswordValue = dealSignUpForm.confpasswd.value;
    if (document.dealSignUpForm.userid.value == "") {
        document.getElementById('userIdErrorDiv').innerHTML = "Please provide UserID!";
        document.dealSignUpForm.userid.focus();
        return false;
    } else {
        document.getElementById('userIdErrorDiv').innerHTML = "";
    }
    if (document.dealSignUpForm.email.value == "") {
        document.getElementById('emailErrorDiv').innerHTML = "Email field can'not be empty!";
        document.dealSignUpForm.email.focus();
        return false;
    } else if (emailReturnValue == false) {
        document.getElementById('emailErrorDiv').innerHTML = "Please provide valid e-mail address!";
        document.dealSignUpForm.email.focus();
        return false;

    } else if (emailReturnValue == true) {
        document.getElementById('emailErrorDiv').innerHTML = "";
    } else {
        document.getElementById('emailErrorDiv').innerHTML = "";
    }
    if (document.dealSignUpForm.firstname.value == "") {
        document.getElementById('firstNameErrorDiv').innerHTML = "Please provide Firstname!";
        document.dealSignUpForm.firstname.focus();
        return false;
    } else {
        document.getElementById('firstNameErrorDiv').innerHTML = "";
    }
    if (document.dealSignUpForm.lastname.value == "") {
        document.getElementById('lastNameErrorDiv').innerHTML = "Please provide Lastname!";
        document.dealSignUpForm.lastname.focus();
        return false;
    } else {
        document.getElementById('lastNameErrorDiv').innerHTML = "";
    }
    if (document.dealSignUpForm.passwd.value == "") {
        document.getElementById('passwordErrorDiv').innerHTML = "Please provide Password!";
        document.dealSignUpForm.passwd.focus();
        return false;
    } else {
        document.getElementById('passwordErrorDiv').innerHTML = "";
    }
    if (document.dealSignUpForm.confpasswd.value == "") {
        document.getElementById('confPasswordErrorDiv').innerHTML = "Please provide Password!";
        document.dealSignUpForm.confpasswd.focus();
        return false;
    } else if (passwordValue != confPasswordValue) {
        document.getElementById('confPasswordErrorDiv').innerHTML = "Password didnot match!";
        document.dealSignUpForm.confpasswd.focus();
        return false;
    } else {
        document.getElementById('confPasswordErrorDiv').innerHTML = "";
    }
    return true;
}

var validateEmail = function (email) {
    var emailRegularExpression = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var validate = emailRegularExpression.test(email);
    return validate
}

var reomveErrorMessage = function (elem) {
    var elemId = elem.id;
    var elemRef = document.getElementById(elemId);
    var errorDiv = elemRef.nextElementSibling.id;
    document.getElementById(errorDiv).innerHTML = "";
}

function reomveLoginErrorMessage(elemId) {
    if (elemId.id == "login-username") {
        document.getElementById('userErrorDiv').innerHTML = "";
    } else if (elemId.id == "login-password") {
        document.getElementById('userPasswordDiv').innerHTML = "";
    }
}

function login() {
    if (formValidation()) {
        var objData = {
            "user_id": $('#login-username').val(),
            "password": $('#login-password').val()
        };
        var Url = "http://localhost/dealwebsite/api/local/login";
        $.ajax({
            url: Url,
            type: "POST",
            data: JSON.stringify(objData),
            success: function (data, textStatus, jqXHR) {
                //{"userID":"123455", "oauthtoken":"0930dbe772ab0cf9"}
                var lo = JSON.parse(data);
                if(lo.userid.length > 1){
                    localStorage.setItem("dealuser", lo);
                    window.location.href ="?page=home";
                }else{
                    alert("UserID/Password is incorrect.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
}

function signup() {
    if (dealSignupValidation()) {
        var objData = {
            "user_id": $('#userid').val(),
            "email": $('#email').val(),
            "firstname": $('#firstname').val(),
            "lastname": $('#lastname').val(),
            "passwd": $('#passwd').val()
        };
        var Url = "http://localhost/dealwebsite/api/local/register";
        $.ajax({
            url: Url,
            type: "POST",
            data: JSON.stringify(objData),
            success: function (data, textStatus, jqXHR) {
                var lo = JSON.parse(data);
                if (lo.userid.length > 1) {
                    localStorage.setItem("dealuser", lo);
                    window.location.href = "?page=home";
                } else {
                    alert(data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
}