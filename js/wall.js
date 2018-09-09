
function submitLike(event, id, username) {
	
	event.preventDefault();

	let toSend = JSON.stringify({
		username : username,
		post : id
	}); 

	$.ajax({
		url : 'includes/wall.php',
		type : 'post',
		dataType : 'text',
		data : {like : toSend},
		success : function(data) {
			//console.log(data);
			if(data) {
				$("#likes" + id).html(data);
			}
			
		},
		error : function(err) {
			console.log(err);
		}
		
	});
}

function submitDislike(event, id, username) {

	event.preventDefault();

	let toSend = JSON.stringify({
		username : username,
		post : id
	}); 

	$.ajax({
		url : 'includes/wall.php',
		type : 'post',
		dataType : 'text',
		data : {dislike : toSend},
		success : function(data) {
			//console.log(data);
			if(data) {
				$("#dislikes" + id).html(data);
			}
		},
		error : function(err) {
			console.log(err);
		}
		
	});
}

function fetchUsersVoted(postid, liked) {

	let vote = liked === 0 ? 'disliked' : 'liked';

	let url = "includes/wall.php?" + vote + "=" + postid;

	let fetchLikesPromise = new Promise((resolve, reject) => {

		let xhr = new XMLHttpRequest();
		xhr.open("GET", url, true);

		xhr.onreadystatechange = () => {
			if(xhr.readyState === 4 && xhr.status === 200) {
				setTimeout(() =>{
					resolve(xhr.responseText);
				}, 1000);
				
			}

			if(xhr.readyState === 4 && xhr.status !== 200) {
				
				reject(JSON.stringify({error : "Error: " + xhr.responseText}));
			}
		};

		xhr.send();
		
	});

	return fetchLikesPromise;
}

function showLikes(event) {
	
	createModal();

	const postId = event.target.id.substring(2);


	// Modal element and close button
	const modal = document.querySelector("#simpleModal"),
		  closeBtn = document.querySelector(".closeBtn");

	modal.style.display = "block";

	closeBtn.addEventListener("click", closeModal);
	window.addEventListener("click", (event) => {
		if(event.target == modal)
			event.target.remove();
	}, true);


	let loadingImage = document.querySelector(".modal-content img");
	let mainContentUl = document.querySelector(".modal-content .modal-main ul");

	fetchUsersVoted(postId, 1).then((value) => {
		appendReceivedTo(mainContentUl, value);
		loadingImage.remove();
	}).catch((error) => {

		appendReceivedTo(mainContentUl, error);
		loadingImage.remove();
	});

	event.preventDefault();
}

function showDislikes(event) {
	createModal();

	const postId = event.target.id.substring(2);


	// Modal element and close button
	const modal = document.querySelector("#simpleModal"),
		  closeBtn = document.querySelector(".closeBtn");

	modal.style.display = "block";

	closeBtn.addEventListener("click", closeModal);
	window.addEventListener("click", (event) => {
		if(event.target == modal)
			event.target.remove();
	}, true);


	let loadingImage = document.querySelector(".modal-content img");
	let mainContentUl = document.querySelector(".modal-content .modal-main ul");

	fetchUsersVoted(postId, 0).then((value) => {
		appendReceivedTo(mainContentUl, value);
		loadingImage.remove();
	}).catch((error) => {

		appendReceivedTo(mainContentUl, error);
		loadingImage.remove();
	});


	event.preventDefault();
}

function appendReceivedTo(element, content) {

	element.style.padding = "12px";
	
	const contentParsed = JSON.parse(content);
	const filterKeys = ['err', 'username', 'fname', 'lname', 'profile_image'];

	let transformedContent = contentParsed.map((obj) => {
		let objKeys = Object.keys(obj);
		objKeys = objKeys.filter(key => filterKeys.includes(key));
		let transformedObj = objKeys.reduce((acc, curr) => {
			acc[curr] = obj[curr];
			return acc;
		}, {});

		return transformedObj;
	});
	

	for(let property in transformedContent) {

		let row = transformedContent[property];

		let li = document.createElement("li");
		li.classList.add("row");

		if(row['err']) {
			li.innerHTML = row['err'];
			element.appendChild(li);
			break;
		}

		let profileImage = new Image(70,70);
		profileImage.src = 'images/uploads/profile_images/' + row.profile_image;
		profileImage.classList.add("img-responsive");
		profileImage.classList.add("rounded-circle");

		
		li.appendChild(profileImage);
		

		let fullName = row.fname + " " + row.lname;
		let profileLink = document.createElement("a");
		profileLink.href = 'index.php?account=' + row.username;
		profileLink.innerText = fullName;
		profileLink.classList.add("pull-right");

		li.appendChild(profileLink);
		
		li.appendChild(document.createElement("hr"));
		element.appendChild(li);
		
	}

}

function closeModal(event) {

	event.target.parentNode.parentNode.parentNode.remove();
}

function createModal() {

	const modal = document.createElement("div");
	modal.setAttribute("id", "simpleModal");
	modal.classList.add("modal");

	const modalContent = document.createElement("div");
	modalContent.classList.add("modal-content");

	const span = document.createElement('span');
	span.classList.add("closeBtn");
	span.innerHTML = "&times;";

	const h2 = document.createElement("h2");
	h2.innerText = "Osobe";

	const modalHeader = document.createElement("div");
	modalHeader.classList.add("modal-header");
	modalHeader.appendChild(h2);
	modalHeader.appendChild(span);

	const image = new Image(32, 32);
	image.src = "images/actions/lg.rotating-balls-spinner.gif";

	const contentDiv = document.createElement("div");
	contentDiv.classList.add("modal-main");

	const contentDivUl = document.createElement("ul");

	contentDiv.appendChild(contentDivUl);

	modalContent.appendChild(modalHeader);
	modalContent.appendChild(image);
	modalContent.appendChild(contentDiv);
	modal.appendChild(modalContent);

	document.querySelector("body").appendChild(modal);
}

$(function(){
		
		let lastPostId = 0;

		/*
			postHTMLGenerator objekat sadrzi nekoliko metoda za kreiranje potrebnih HTML elemenata
			za kreiranje postova i helpers - pomocne metode za procesiranje sadrzaja
			postova.
		*/
 
		var postHTMLGenerator = {
				initStatus : function(i, appendToElement, type, ajaxPost) {
					if(ajaxPost) {
						if(type === 'status') 
							return $("<section class='" + type + "' id='status" + i + "'></section>").prependTo(appendToElement);
						else if(type === 'comment') {

							let element = $(appendToElement)[0];

							//console.log(element.childNodes);

							if(!element.childNodes[6])
								return $("<section class='" + type + "' id='status" + i + "'></section>").appendTo(appendToElement);
							else{

								//console.log(element);
								let newElement = ($("<section class='" + type + "' id='status" + i + "'></section>"))[0];

								//console.log(newElement);
								//return $("<section class='" + type + "' id='status" + i + "'></section>").insertBefore(appendToElement, element.childNodes[6]);
								return element.insertBefore(newElement, element.childNodes[6]);
								

							}
							
						} else if(type === 'comment reply') {

							let element = $(appendToElement)[0];
							//console.log(element.childNodes);

							if(!element.childNodes[5])
								return $("<section class='" + type + "' id='status" + i + "'></section>").appendTo(appendToElement);
							else {
								let newElement = ($("<section class='" + type + "' id='status" + i + "'></section>"))[0];
								//console.log(element.childNodes);
								return element.insertBefore(newElement, element.childNodes[5]);
							}
						}
					}
					else{

						let element = $("<section class='" + type + "' id='status" + i + "'></section>").appendTo(appendToElement);
						
						return element;
					}

				},
				generatePostHeader : function() {
					return "<div class='row status_head'> </div>";
				},
				generatePostHeaderProfileImageDiv : function(image) {
					return "<div class='col-md-2' style='margin-right: -20px;'><div class='imgcontainer'><img class='img-responsive rounded-circle border' src='images/uploads/profile_images/" + image + "'></div></div>";
				},
				generatePostHeaderFnameLnameDisplayDiv : function(username, firstName, lastName, postDate) {
					return "<div class='col-md-3'> <a href='?account=" + username + "' target='_blank'><span> <strong>" + firstName + " " + lastName + "</strong> </span></a> <span style='font-size: 12px;''><i>" + postDate + "</i></span> </div>";
				},
				generatepostBody : function(postText, replyUsername) {
					var replySpan = replyUsername === null ? '' : '<span style="color: darkred"><strong> Za: </strong> <a href=?account=' + replyUsername + '>' + replyUsername + '</a> </span><br>';
					return "<div class='row status_body'><div class='col-md-6'><p>" + replySpan + " " + postText + "</p></div></div>";
				},
				generatepostAttachment : function() {
					return "<div class='row status_attachment'> </div> <hr>";
				},
				generatepostOptions : function(id, type, likes, dislikes) {
					var colMdSize = type === 'status' ? 2 : 1;
					return "<div class='row status_options'> <div class='col-md-" + colMdSize + "' style=''> <a href='#' onclick='submitLike(event," + id + ",\"" + uname + "\")'><img class='clip-like' src='images/actions/thumbs.png' title='Like'></a>   </div>  <div class='col-md-" + colMdSize + "' style='margin-right: 50px;'> <a href='#' onclick='submitDislike(event," + id + ",\"" + uname + "\")'><img class='clip-dislike' src='images/actions/thumbs.png' title='Dislike'></a> </div> <div class='col-md-" + colMdSize + "' style='padding-top: 10px;'> <a id='commentBtn" + id + "' href='#'> <img src='images/actions/comment.png' title='Komentariši'> </a> </div> </div>" +
					       "<div class='row'> <div id='likes" + id + "' style='margin-left: 23px; margin-top: -20px;' class='col-md-" + colMdSize + " text-success'><a class='showlikes' id='sl" + id + "' href='#'>  " + likes + "</a> </div> <div id='dislikes" + id + "' style='margin-left: 2px; margin-top: -20px;' class='col-md-" + colMdSize + " text-danger'> <a id='sd" + id + "' class='showdislikes' href='#'> " + dislikes + "</a> </div> </div>";
				},
				generateLoadingDiv : function(id) {
					$("#status" + id).after("<div class='row text-center loading'> <div class='col-md-12'> <img src='images/actions/load.gif' width='64'> </div> </div>");
				},
				generateYoutubeVideoDiv : function(videoId, type) {
					var height = type === 'comment reply' ? 250 : 450;
					return "<div class='col-md-12'> <iframe width='100%' height='" + height + "' src='https://www.youtube.com/embed/" + videoId + "'> </iframe> </div>"
				},
				generateCommentDiv : function(appendTo, toUser = null, postId, type) {
					var commentsDivHead = type === 'status' ? '<div class="row text-center"> <div class="col-md-12"> Komentari <hr> </div>  </div>' : '';
					var ButtonColMd1 = type === 'comment reply' ? 3 : 9,
						ButtonColMd2 = type === 'comment reply' ? 6 : 3;
					return '<div id=commentDiv' + postId + ' class="row status_comment text-center"> <form method="post" action="includes/statusform.php">' +
							'<div class="form-group"> <textarea class="form-control" style="padding-top: 10px;" name="status_text" id="status_text" rows="2" cols="84"></textarea>' +
					'<span id="status_text_error"><strong><i>Status mora biti dugačak najmanje 6 karaktera</i></strong></span> </div>' +
					'<div class="form-group row"> <div class="col-md-' + ButtonColMd1 + '"> </div> <div class="col-md-' + ButtonColMd2 + '"> <button id="postsubmit" class="btn btn-primary form-control" type="submit">Odgovori</button>' +
					'</div> </div> <input type="hidden" name="to_user" value="' + toUser + '"> <input type="hidden" name="to" value="' + appendTo + '"> <input type="hidden" name="token" value="' + token + '" > </form> </div>' + 
					commentsDivHead;
				},
				helpers : {
					checkForAttachment : function (status) {
						return status.split(' ').reduce((acc, curr) => {
							if(curr.search('http://') != -1 || curr.search('https://') != -1)
								acc.push(curr);
							return acc;
						}, []);
					},
					checkForYoutubeVideoUrl : function(attachment) {

						var youtubeLink = '';
						var pattern = /http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?[\w\?‌​=]*)?/;

						for(var att in attachment) {
							if(attachment[att].match(pattern)) {
								youtubeLink = attachment[att];
								break;
							}
						}

						//console.log('\n');
						return youtubeLink;
					},
					convertToVideoId : function(youtubeLink) {
						var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
					   
					    var match = youtubeLink.match(regExp);

					    if (match && match[2].length == 11) {
					        return match[2];
					    } else {
					        return 'error';
					    }
					}

				}
		};

		

		/* 
			get5 funkcija loaduje 5 (funkcija get5) najnovijih statusa iz baze podataka. 
			Kada korisnik skrolovanjem stigne do dna stranice, get5 metoda se opet aktivira 
			i loaduje jos 5 statusa iz baze. Kada server odgovori XMLHttpRequest, dobijeni
			podaci se cuvaju u varijabli posts. Zatim se ta variabla proslijedjuje funkciji
			updateWall.
		*/

		function get5(skip) {
				
			skip = skip === undefined ? 0 : skip + 5;

			postHTMLGenerator.generateLoadingDiv(lastPostId);
			
			setTimeout(function(){
				$.ajax({
				  type: 'GET',
				  url: 'includes/wall.php',
				  data: {getPosts: skip},
				  dataType: 'json',
				  success: function(data){
				  	if(data.length === undefined) {

				  		$(window).off('scroll');
				  		$(".loading").children(":first").html("<p style='color:white; '><strong> Nema više statusa </strong> </p>");
				  	} else {

				  		$(window).on('scroll', (event) => {
				  			scrollEventHandler(skip);
				  		});
				  		posts = data;

					  	$(".loading").remove();
					  	//console.log(lastPostId);
					  	updateWall();
					  	
				  	}
				  },
				  error: function(err) {
				  	//console.log(err);
				  }
				  
				});
			},0);
		}

		function scrollEventHandler(skip) {
				if($(window).scrollTop() + $(window).height() > $(document).height() - 2) {
				   get5(skip);
				   $(window).off('scroll');
				}
		}

		/*
			Funkcija updateWall prolazi kroz niz najnovijih 5 statusa i za staki status zove funkciju generatePost koja je odgovorna 
			za kreiranje statusa.

			Nakon toga, funckija updateWall salje XMLHttpRequest serveru u kojem zahtjeva komentare za svaki status. Ovi komentari 
			predstavljaju samo komentare koji su komentarisali direktno na status, a ne komentari koji su komentarisali neki drugi
			post. Nakon odgovora od strane servera, poziva se generateComments sa dva parametra. Prvi - komentari za status posts[i]
			, drugi - lpid koji predstavlja id statusa.
		*/

		function updateWall() {
				
			for(var i = 0; i < posts.length; i++) {
				
				let post = posts[i],
					comments = [];
					lastPostId = post.id;
					
				generatePost(post.profile_image, post.username, post.fname, post.lname, post.post_date, post.status_text, 'status', lastPostId, lastPostId, '.posts', null, post.likes, post.dislikes, false);
				

				(function(lpid) {
					$.ajax({
					type : 'GET',
					url : 'includes/wall.php',
					data : {getComments : lpid},
					dataType : 'json',
					success : function(data) {
						if(data.length !== 0) {
							comments = data;
							
							generateComments(comments, lpid);
						}
					},
					error : function(err) {
						//console.log(err);
					}

					});
				})(lastPostId);

			}

		}

		

		/*
			Funkcija generateComment prolazi kroz komentare koje je funkcija dobila kroz ulazne parametre.
			Za zvaki komentar comments[i] se poziva generatePost koja kreira komentare u DOM-u.
			AppendTo parametar predstavlja id glavnog statusa u koji komentar comments[i] treba da se "appenduje".

			Zatim se salje novi XMLHttpRequest serveru za odgovore na komentar comments[key] (replies).
	
		*/

		function generateComments(comments, appendTo) {


			for (var key in comments) {

				var comment = comments[key],
					replies = [];

				generatePost(comment.profile_image, comment.username, comment.fname, comment.lname, comment.post_date, comment.status_text, 'comment', comment.id, comment.id, '#status' + appendTo, null, comment.likes, comment.dislikes);
				

				(function(commentId) {
						$.ajax({
						type : 'GET',
						url : 'includes/wall.php',
						data : {getComments : commentId},
						dataType : 'json',
						success : function(data) {
							if(data.length !== 0) {
								replies = data;
								//console.log(replies);
								generateReplies(replies, commentId);
							}
						},
						error : function(err) {
							//console.log(err);
						}

						});
					})(comment.id);
				
			}
			
		}

		/*
			Funkcija generateReplies prima odgovore na dati komentar kao ulazi parametar. 
			Zatim poziva generatePost nakon cega se isti kreiraju u DOM-u.
		*/

		function generateReplies(replies, appendTo) {
			for(var key in replies) {
				var reply = replies[/*(replies.length - 1) - */key];

				generatePost(reply.profile_image, reply.username, reply.fname, reply.lname, reply.post_date, reply.status_text, 'comment reply', reply.id, appendTo, '#status' + appendTo, reply.to_username, reply.likes, reply.dislikes);
			}
		}

		/*
			Funkcija generatePost kreira tri vrste postova: statuse, komentare na statuse i komentare na komentare. Za kreiranje
			HTML elemenata i dodatne funkcije poziva funkcije iz objekta postHTMLGenerator
		*/

		function generatePost(profileImage, username, fname, lname, postDate, postText, type, postId, appendTo, appendToElement, toUsername, likes, dislikes, ajaxPost = false) {
			

			// POST HEADER VARIJABLE - HTML elementi
			var postHeader = postHTMLGenerator.generatePostHeader(),
				postHeaderProfileImage = postHTMLGenerator.generatePostHeaderProfileImageDiv(profileImage),
				postHeaderFnameLnameDisplay = postHTMLGenerator.generatePostHeaderFnameLnameDisplayDiv(username, fname, lname, postDate),
				hasAttachment = false,
				hasYoutubeVideoUrl = false;

			// POST BODY HTML elementi

			var postTextFinal = postText;

			var attachmentContent = postHTMLGenerator.helpers.checkForAttachment(postTextFinal);
			

			if(attachmentContent.length > 0) {
				postTextFinal = postTextFinal.split(' ');
				for(var s in postTextFinal) {
					for(var att in attachmentContent) {
						if(postTextFinal[s] === attachmentContent[att])
							postTextFinal[s] = "<a href='" + attachmentContent[att] + "' target='_blank'>" + attachmentContent[att] + "</a>";
						
					}
				}

				postTextFinal = postTextFinal.join(' ');
				

				hasAttachment = true;
			}

			var postBody = postHTMLGenerator.generatepostBody(postTextFinal, toUsername);

			// POST ATTACHMENT HTML elementi

			var postAttachment = type === 'status' ? postHTMLGenerator.generatepostAttachment() : '';

			var youtubeLink = postHTMLGenerator.helpers.checkForYoutubeVideoUrl(attachmentContent);

			if(youtubeLink != '') {
				youtubeLink = postHTMLGenerator.helpers.convertToVideoId(youtubeLink);
				hasYoutubeVideoUrl = true;
			}
			
			// POST OPTIONS HTML elementi

			var postOptions = postHTMLGenerator.generatepostOptions(postId, type, likes, dislikes);


			var postHTML = postHTMLGenerator.initStatus(postId, appendToElement, type, ajaxPost);

			$(postHTML).append(postHeader);
			$("#status" + postId + " > .status_head").append(postHeaderProfileImage).append(postHeaderFnameLnameDisplay);

			$(postHTML).append(postBody);
			if(hasYoutubeVideoUrl) {
				$("#status" + postId + " > .status_body").append(postHTMLGenerator.generateYoutubeVideoDiv(youtubeLink, type));
			}


			if(hasAttachment && type === 'status') {
				$(postHTML).append(postAttachment);
				for(var att in attachmentContent) {
					$("#status" + postId + " > .status_attachment").append("<div style='margin-left: 10px;'><a href=" + attachmentContent[att] + " target='_blank'><blockquote class='blockquote text-center'> <p class='mb-0'> Link " + (parseInt(att) + 1) + " </p> <footer class='blockquote-footer'>  <cite title='Source Title'>Link</cite></footer> </blockquote></a></div>");
				}
			}


			$(postHTML).append(postOptions);

			
			$(postHTML).append(postHTMLGenerator.generateCommentDiv(appendTo, username, postId, type));

			//console.log($(postHTML));

			let form = $(postHTML).find("form");
			
			if($(postHTML)[0].classList.contains('status'))
				form[0].addEventListener('submit', ajaxCommentGenerator);
			else if($(postHTML)[0].classList.contains('comment'))
				form[0].addEventListener('submit', ajaxCommentReplyGenerator);


			let showlikes = $(postHTML).find('.showlikes');
			showlikes[0].addEventListener('click', showLikes);

			let showdislikes = $(postHTML).find('.showdislikes');
			showdislikes[0].addEventListener('click', showDislikes);

			
			$("#commentBtn" + postId).click(function(e){
				e.preventDefault();
				$("#commentDiv" + postId).toggle();
			});



		}

		function loadPosts() {
			let skip,
				posts;
				
				if(lastPostId === 0)
					get5(skip);
		}
			
		
		loadPosts();

		let status = document.querySelector("#status form");
		status.addEventListener('submit', ajaxStatusGenerator);

		function ajaxStatusGenerator(event) {
			
			event.preventDefault();

			let text = status[0].value;

			let statusPromise = new Promise((resolve, reject) => {

				var xhr = new XMLHttpRequest();
				xhr.open("POST", "includes/statusform.php", true);

				xhr.setRequestHeader("Accept", "application/json");

				xhr.onreadystatechange = () => {
					if((xhr.readyState === 4) && xhr.status === 200) {
						resolve({
							status : xhr.status,
							statusText : xhr.statusText,
							readyState : xhr.readyState,
							dolaziIz : 'resolve',
							response : JSON.parse(xhr.responseText)
						});
					} 
				};

				xhr.onerror = () => {
					reject({
						status : xhr.status,
						statusText : xhr.statusText
					});
				};

				xhr.send(new FormData(status));
				

			});

			statusPromise.then((message) => {
				let data = message.response;

				//console.log(data.id);
				status[0].value = "";
				generatePost(data.profile_image, data.username, data.fname, data.lname, data.post_date, data.status_text,'status', data.id, data.id, '.posts', null, data.likes, data.dislikes, true);
			}).catch((reason) => {
				console.log(reason);
			});


		}

		function ajaxCommentReplyGenerator(event) {
			event.preventDefault();
			
			let form = event.target;

			let text = form[0].value;

			let statusPromise = new Promise((resolve, reject) => {
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "includes/statusform.php", true);

				xhr.setRequestHeader("Accept", "application/json");

				xhr.onreadystatechange = () => {
					if((xhr.readyState === 4) && xhr.status === 200) {
						resolve({
							status : xhr.status,
							statusText : xhr.statusText,
							readyState : xhr.readyState,
							dolaziIz : 'resolve',
							response : JSON.parse(xhr.responseText)
						});
					} 
				};

				xhr.onerror = () => {
					reject({
						status : xhr.status,
						statusText : xhr.statusText
					});
				};

				xhr.send(new FormData(form));


			});

			statusPromise.then((message) => {
				let data = message.response;

				//console.log(data.comment_to + " " + data.id + " " + data.to_username);
				form[0].value = "";
				generatePost(data.profile_image, data.username, data.fname, data.lname, data.post_date, data.status_text, 'comment reply', data.id, data.comment_to, '#status' + data.comment_to, data.to_username, data.likes, data.dislikes, true);
			}).catch((reason) => {
				console.log(reason);
			});
		}

		function ajaxCommentGenerator(event) {
			
			event.preventDefault();

			let form = event.target;

			let text = form[0].value;
			
			let statusPromise = new Promise((resolve, reject) => {

				var xhr = new XMLHttpRequest();
				xhr.open("POST", "includes/statusform.php", true);

				xhr.setRequestHeader("Accept", "application/json");

				xhr.onreadystatechange = () => {
					if((xhr.readyState === 4) && xhr.status === 200) {
						
						console.log("Pravi token : " + token);
						resolve({
							status : xhr.status,
							statusText : xhr.statusText,
							readyState : xhr.readyState,
							dolaziIz : 'resolve',
							response : JSON.parse(xhr.responseText)
						});


					} 
				};

				xhr.onerror = () => {
					reject({
						status : xhr.status,
						statusText : xhr.statusText,
						readyState : xhr.readyState,
						dolaziIz : 'reject',
						response : JSON.parse(xhr.responseText)
					});
				};


				let newForm = new FormData(form);

				//console.log(newForm.get('token') + " " + newForm.get('status_text'));
				xhr.send(newForm);

				

			});


			statusPromise.then((message) => {
				let data = message.response;

				console.log(message);
				

				
				generatePost(data.profile_image, data.username, data.fname, data.lname, data.post_date, data.status_text, 'comment', data.id, data.id, '#status' + data.comment_to, null, data.likes, data.dislikes, true);
				form[0].value = "";
			}).catch((reason) => {
				console.log(reason);
			});


		}
});
