$(function(){

		function ValidationCheck(){

			var passedElements = {
				password_current : false,
				password_new: false,
				password_new_again : false
			};

			$(".submitButton").on('click', preventFormSubmit);
			$("#password_current").focusout(checkPasswordCurrent);
			$("#password_new").focusout(checkPasswordNew);
			$("#password_new_again").focusout(checkPasswordNewAgain);

			

			function checkPasswordCurrent() {
				
				var passwordCurrent = $("#password_current").val();

				if(passwordCurrent.length < 8) {
					$("#password_current_error").html('Lozinka mora biti dugačka minimalno 8 karaktera');
					$("#password_current_error").css('color', 'red');
					passedElements.password_current = false;
				} else if (passwordCurrent.length > 46) {
					$("#password_current_error").html('Lozinka mora biti dugačka maksimalno 46 karaktera');
					$("#password_current_error").css('color', 'red');
					passedElements.password_current = false;
				} else {

					var pattern = /^(?=.*\d).{8,46}$/;
					if(pattern.test(passwordCurrent)) {
						$("#password_current_error").html('OK');
						$("#password_current_error").css('color', 'green');
						passedElements.password_current = true;
					} else {
						$("#password_current_error").html('Lozinka mora sadržati barem jednu cifru');
						$("#password_current_error").css('color', 'red');
						passedElements.password_current = false;
					}
				}

				
				checkIfPassed();
			}

			function checkPasswordNew() {
				var passwordNew = $("#password_new").val();

				if(passwordNew.length < 8) {
					$("#password_new_error").html('Lozinka mora biti dugačka minimalno 8 karaktera');
					$("#password_new_error").css('color', 'red');
					passedElements.password_new = false;
				} else if (passwordNew.length > 46) {
					$("#password_new_error").html('Lozinka mora biti dugačka maksimalno 46 karaktera');
					$("#password_new_error").css('color', 'red');
					passedElements.password_new = false;
				} else {

					var pattern = /^(?=.*\d).{8,46}$/;
					if(pattern.test(passwordNew)) {
						$("#password_new_error").html('OK');
						$("#password_new_error").css('color', 'green');
						passedElements.password_new = true;
					} else {
						$("#password_new_error").html('Lozinka mora sadržati barem jednu cifru');
						$("#password_new_error").css('color', 'red');
						passedElements.password_new = false;
					}
				}

				
				checkIfPassed();
			}

			function checkPasswordNewAgain() {
				
				var passwordNewAgain = $("#password_new_again").val();

				if(passwordNewAgain !== $("#password_new").val() || passwordNewAgain === '') {
					$("#password_new_again_error").html('Lozinke se ne poklapaju');
					$("#password_new_again_error").css('color', 'red');
					passedElements.password_new_again = false;
				} else {
					$("#password_new_again_error").html('OK');
					$("#password_new_again_error").css('color', 'green');
					passedElements.password_new_again = true;
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

				console.log("allpased: " + allPassed + " password_current passed: " + passedElements.password_current + " password_new passed: " + passedElements.password_new + " password_new_again passed: " + passedElements.password_new_again);
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