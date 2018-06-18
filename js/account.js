$(function(){


		$("#profile_image").on('mouseenter',function(){
			$(".top-left").show();
			$(".top-left").hover(
				function() {
				$(this).css('background-color', 'grey');
				$(this).css('color', 'white');

				},

				function() {
					$(this).css('background-color', '');
					$(this).css('color', 'black');
				}

			);
			
			$(".top-left").on('click', function(){
				$("#uploadimgform").toggle();
			});
			
		});

		function onMouseLeave(event) {
			 e = event.toElement || event.relatedTarget;
    		if (e.parentNode == this || 
                           e == this) {
       			 return;
   		    }
   		    $(".top-left").off('click');
   		    $(".top-left").hide();
		    
		}

		document.getElementById("profile_image").addEventListener('mouseleave',onMouseLeave,false);

		
		
	});