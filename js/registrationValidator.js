$(function(){

		function ValidationCheck(){

			var passedElements = {
				fname : false,
				lname : false,
				email : false,
				password : false,
				passwordAgain : false,
				username: false,
				birth_date : false
			};

					
				

			$(".submitButton").on('click', preventFormSubmit);
			$("#fname").focusout(checkFname);
			$("#lname").focusout(checkLname);
			$("#email").focusout(checkEmail);
			$("#password").focusout(checkPassword);
			$("#passwordAgain").focusout(checkPasswordAgain);
			$("#username").focusout(checkUsername);
			$("#birth_date").focusout(checkBirthDate);


			function checkFname() {

				var firstName = $.trim($("#fname").val());
				$("#fname").val(firstName);

				if(firstName.length < 3) {
					$("#fname_error").html('Ime mora biti dugačko minimalno 3 karaktera');
					$("#fname_error").css('color', 'red');
					passedElements.fname = false;
				} else if(firstName.length > 20) {
					$("#fname_error").html('Ime mora biti dugačko maksimalno 20 karaktera');
					$("#fname_error").css('color', 'red');
					passedElements.fname = false;
				} else {
					var pattern = /^[a-z ']+$/i;
					if(pattern.test(firstName)) {
						$("#fname_error").html('OK');
						$("#fname_error").css('color', 'green');
						passedElements.fname = true;
					} else {
						$("#fname_error").html('Ime ne smije sadržati brojeve ili ilegalne karaktere');
						$("#fname_error").css('color', 'red');
						passedElements.fname = false;
					}
					
				}

				
				checkIfPassed();
			}

			function checkLname() {
				
				var lastName = $.trim($("#lname").val());
				$("#lname").val(lastName);

				if(lastName.length < 3) {
					$("#lname_error").html('Prezime mora biti dugačko minimalno 3 karaktera');
					$("#lname_error").css('color', 'red');
					passedElements.lname = false;
				} else if(lastName.length > 20) {
					$("#lname_error").html('Prezime mora biti dugačko maksimalno 20 karaktera');
					$("#lname_error").css('color', 'red');
					passedElements.lname = false;
				} else {

					var pattern = /^[a-z ']+$/i;
					if(pattern.test(lastName)) {
						$("#lname_error").html('OK');
						$("#lname_error").css('color', 'green');
						passedElements.lname = true;
					} else {
						$("#lname_error").html('Prezime ne smije sadržati brojeve ili ilegalne karaktere');
						$("#lname_error").css('color', 'red');
						passedElements.lname = false;
					}
					
				}
				
				checkIfPassed();
			}
			
			function checkEmail() {
				
				var email = $.trim($("#email").val());
				$("#email").val(email);

				if(email.length < 10) {
					$("#email_error").html('Email mora biti dugačak minimalno 10 karaktera');
					$("#email_error").css('color', 'red');
					passedElements.email = false;
				} else if (email.length > 46) {
					$("#email_error").html('Email mora biti dugačak maksimalno 46 karaktera');
					$("#email_error").css('color', 'red');
					passedElements.email = false;
				} else {

					var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					if(pattern.test(email)) {
						$("#email_error").html('OK');
						$("#email_error").css('color', 'green');
						passedElements.email = true;
					} else {
						$("#email_error").html('Email format mora biti emailID@domainname.domain');
						$("#email_error").css('color', 'red');
						passedElements.email = false;
					}
				}

				checkIfPassed();
			}

			function checkPassword() {
				
				var password = $("#password").val();

				if(password.length < 8) {
					$("#password_error").html('Lozinka mora biti dugačka minimalno 8 karaktera');
					$("#password_error").css('color', 'red');
					passedElements.password = false;
				} else if (password.length > 46) {
					$("#password_error").html('Lozinka mora biti dugačka maksimalno 46 karaktera');
					$("#password_error").css('color', 'red');
					passedElements.password = false;
				} else {

					var pattern = /^(?=.*\d).{8,46}$/;
					if(pattern.test(password)) {
						$("#password_error").html('OK');
						$("#password_error").css('color', 'green');
						passedElements.password = true;
					} else {
						$("#password_error").html('Lozinka mora sadržati barem jednu cifru');
						$("#password_error").css('color', 'red');
						passedElements.password = false;
					}
				}

				
				checkIfPassed();
			}

			function checkPasswordAgain() {
				
				var passwordAgain = $("#passwordAgain").val();

				if(passwordAgain !== $("#password").val()) {
					$("#passwordAgain_error").html('Lozinke se ne poklapaju');
					$("#passwordAgain_error").css('color', 'red');
					passedElements.passwordAgain = false;
				} else {
					$("#passwordAgain_error").html('OK');
					$("#passwordAgain_error").css('color', 'green');
					passedElements.passwordAgain = true;
				}
				
				checkIfPassed();
			
			}

			function checkUsername() {
				
				var username = $.trim($("#username").val());
				username = username.replace(' ', '_');
				$("#username").val(username);

				if(username.length < 6) {
					$("#username_error").html('Korisničko ime mora biti dugačko minimalno 6 karaktera');
					$("#username_error").css('color', 'red');
					passedElements.username = false;
				} else if (username.length > 20) {
					$("#username_error").html('Korisničko ime mora biti dugačko maksimalno 20 karaktera');
					$("#username_error").css('color', 'red');
					passedElements.username = false;
				} else {

					var pattern = /^[a-z]{4,20}[_-]{0,1}[0-9]{0,2}[a-z]{0,}$/;
					if(pattern.test(username)) {
						$("#username_error").html('OK');
						$("#username_error").css('color', 'green');
						passedElements.username = true;
					} else {
						$("#username_error").html('Korisničko ime sadrži ilegalne karaktere');
						$("#username_error").css('color', 'red');
						passedElements.username = false;
					}
				}
				
				checkIfPassed();
			}

			function checkBirthDate() {
				
				var birthDate = $("#birth_date").val();
				var birthYear = new Date(birthDate).getFullYear(); 
				var currentYear = new Date().getFullYear();

				if(birthYear) {
					if((currentYear - birthYear) >= 7) {
						passedElements.birth_date = true;
						$("#birth_date_error").html('OK');
						$("#birth_date_error").css('color', 'green');
					} else {
						passedElements.birth_date = false;
						$("#birth_date_error").html('Osoba mora biti stara najmanje 7 godina');
						$("#birth_date_error").css('color', 'red');
					}
				} else {
					$("#birth_date_error").html('Datum nije unešen');
					$("#birth_date_error").css('color', 'red');
					passedElements.birth_date = false;
				}

				checkIfPassed();
			}


			function checkIfPassed() {

				var allPassed = true;

				for(var key in passedElements) {
					if(passedElements[key] !== true) {
						allPassed = false;
						break;
					} 
				}

				console.log("allpased: " + allPassed + " fname passed: " + passedElements.fname + " lname passed: " + passedElements.lname + " email passed: " + passedElements.email + " password passed: " + passedElements.password + " passwordAgain passed: " + passedElements.passwordAgain);
				if(allPassed) {
					$(".submitButton").off('click', preventFormSubmit);
				}

			}

			function preventFormSubmit(e) {
				for(var key in passedElements) {
					if(passedElements[key] === false) {
						if(!$.trim($("#" + key + "_error").html()) || $("#" + key).val().length === 0)
							$("#" + key + "_error").html('Ovo polje mora biti popunjeno');
					}
					
				}
				e.preventDefault();
			}
		}

		

		ValidationCheck();
	});