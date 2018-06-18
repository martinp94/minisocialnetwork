
$(function(){

		var postReplyHTMLGenerator = {
				initStatus : function(i, appendToElement) {
					
					return $("<section class='comment reply' id='status" + i + "'></section>").appendTo(appendToElement);
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
					var replySpan = replyUsername === '' ? '' : '<span style="color: darkred"><strong> Za: </strong> <a href=?account=' + replyUsername + '>' + replyUsername + '</a> </span><br>';
					return "<div class='row status_body'><div class='col-md-6'><p>" + replySpan + " " + postText + "</p></div></div>";
				},
				generatepostOptions : function(id) {
					return "<div class='row status_options'> <div class='col-md-1' style='margin-right: -40px;''> <a href='#'><img class='clip-like' src='images/actions/thumbs.png' title='Like'></a> </div> <div class='col-md-1' style='margin-right: 40px;'> <a href='#'><img class='clip-dislike' src='images/actions/thumbs.png' title='Dislike'></a> </div> <div class='col-md-1' style='padding-top: 10px;'> <a id='commentBtn" + id + "' href='#'> <img src='images/actions/comment.png' title='Komentariši'> </a> </div> </div>";
				},
				generateLoadingDiv : function(id) {
					$("#status" + id).after("<div class='row text-center loading'> <div class='col-md-12'> <img src='images/actions/load.gif' width='64'> </div> </div>");
				},
				generateYoutubeVideoDiv : function(videoId) {
					return "<div class='col-md-12'> <iframe width='100%' height='450' src='https://www.youtube.com/embed/" + videoId + "'> </iframe> </div>"
				},
				generateCommentDiv : function(to, toUser = null, postId) {
					return '<div id=commentDiv' + postId + ' class="row status_comment"> <form method="post" action="includes/statusform.php">' +
							'<div class="form-group row"> <textarea class="form-control col-md-6" style="padding-top: 10px;" name="status_text" id="status_text" rows="2" cols="84"></textarea>' +
					'<span id="status_text_error"><strong><i>Status mora biti dugačak najmanje 6 karaktera</i></strong></span> </div>' +
					'<div class="form-group row"> <div class="col-md-3"> </div> <div class="col-md-6"> <button id="postsubmit" class="btn btn-primary form-control" type="submit">Odgovori</button>' +
					'</div> </div> <input type="hidden" name="to_user" value="' + toUser + '"> <input type="hidden" name="to" value="' + to + '"> <input type="hidden" name="token" value="' + token + '" > </form> </div>';
				}
		};
		
		var postHTMLGenerator = {
				initStatus : function(i, appendToElement) {
					
					return $("<section class='comment' id='status" + i + "'></section>").appendTo(appendToElement);
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
					var replySpan = replyUsername === '' ? '' : '<span style="color: darkred"><strong> Za: </strong> <a href=?account=' + replyUsername + '>' + replyUsername + '</a> </span><br>';
					return "<div class='row status_body'><div class='col-md-6'><p>" + replySpan + " " + postText + "</p></div></div>";
				},
				generatepostOptions : function(id) {
					return "<div class='row status_options'> <div class='col-md-1' style='margin-right: -40px;''> <a href='#'><img class='clip-like' src='images/actions/thumbs.png' title='Like'></a> </div> <div class='col-md-1' style='margin-right: 40px;'> <a href='#'><img class='clip-dislike' src='images/actions/thumbs.png' title='Dislike'></a> </div> <div class='col-md-1' style='padding-top: 10px;'> <a id='commentBtn" + id + "' href='#'> <img src='images/actions/comment.png' title='Komentariši'> </a> </div> </div>";
				},
				generateLoadingDiv : function(id) {
					$("#status" + id).after("<div class='row text-center loading'> <div class='col-md-12'> <img src='images/actions/load.gif' width='64'> </div> </div>");
				},
				generateYoutubeVideoDiv : function(videoId) {
					return "<div class='col-md-12'> <iframe width='100%' height='450' src='https://www.youtube.com/embed/" + videoId + "'> </iframe> </div>"
				},
				generateCommentDiv : function(toUser = null, postId) {
					return '<div id=commentDiv' + postId + ' class="row status_comment text-center"> <form method="post" action="includes/statusform.php">' +
							'<div class="form-group"> <textarea class="form-control" style="padding-top: 10px;" name="status_text" id="status_text" rows="2" cols="84"></textarea>' +
					'<span id="status_text_error"><strong><i>Status mora biti dugačak najmanje 6 karaktera</i></strong></span> </div>' +
					'<div class="form-group row"> <div class="col-md-9"> </div> <div class="col-md-3"> <button id="postsubmit" class="btn btn-primary form-control" type="submit">Odgovori</button>' +
					'</div> </div> <input type="hidden" name="to_user" value="' + toUser + '"> <input type="hidden" name="to" value="' + postId + '"> <input type="hidden" name="token" value="' + token + '" > </form> </div>';
				}
		};

		var statusHTMLGenerator = {
				initStatus : function(i) {
					
					return $("<section class='status' id='status" + i + "'></section>").appendTo(".posts");
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
				generatepostBody : function(postText) {
					
					return "<div class='row status_body'><div class='col-md-12'><p>" + postText + "</p></div></div>";
				},
				generatepostAttachment : function() {
					return "<div class='row status_attachment'> </div> <hr>";
				},
				generatepostOptions : function(id) {
					return "<div class='row status_options'> <div class='col-md-2' style='margin-right: -40px;''> <a href='#'><img class='clip-like' src='images/actions/thumbs.png' title='Like'></a> </div> <div class='col-md-2' style='margin-right: 40px;'> <a href='#'><img class='clip-dislike' src='images/actions/thumbs.png' title='Dislike'></a> </div> <div class='col-md-2' style='padding-top: 10px;'> <a id='commentBtn" + id + "' href='#'> <img src='images/actions/comment.png' title='Komentariši'> </a> </div> </div>";
				},
				generateLoadingDiv : function(id) {
					$("#status" + id).after("<div class='row text-center loading'> <div class='col-md-12'> <img src='images/actions/load.gif' width='64'> </div> </div>");
				},
				generateYoutubeVideoDiv : function(videoId) {
					return "<div class='col-md-12'> <iframe width='100%' height='450' src='https://www.youtube.com/embed/" + videoId + "'> </iframe> </div>"
				},
				generateCommentDiv : function(to, toUser = null, postId) {
					return '<div id=commentDiv' + postId + ' class="row status_comment text-center"> <form method="post" action="includes/statusform.php">' +
							'<div class="form-group"> <textarea class="form-control" style="padding-top: 10px;" name="status_text" id="status_text" rows="2" cols="84"></textarea>' +
					'<span id="status_text_error"><strong><i>Status mora biti dugačak najmanje 6 karaktera</i></strong></span> </div>' +
					'<div class="form-group row"> <div class="col-md-9"> </div> <div class="col-md-3"> <button id="postsubmit" class="btn btn-primary form-control" type="submit">Odgovori</button>' +
					'</div> </div> <input type="hidden" name="to_user" value="' + toUser + '"> <input type="hidden" name="to" value="' + to + '"> <input type="hidden" name="token" value="' + token + '" > </form> </div>' +
					'<div class="row text-center"> <div class="col-md-12"> Komentari <hr> </div>  </div>';
				}
		};


		function checkForAttachment(status) {
				return status.split(' ').reduce((acc, curr) => {
					if(curr.search('http://') != -1 || curr.search('https://') != -1)
						acc.push(curr);
					return acc;
				}, []);
		}

		function checkForYoutubeVideoUrl(attachment) {

			var youtubeLink = '';
			var pattern = /http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?[\w\?‌​=]*)?/;

			for(var att in attachment) {
				if(attachment[att].match(pattern)) {
					youtubeLink = attachment[att];
					break;
				}
			}

			console.log('\n');
			return youtubeLink;

		}

		function convertToVideoId(youtubeLink) {
				var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
			   
			    var match = youtubeLink.match(regExp);

			    if (match && match[2].length == 11) {
			        return match[2];
			    } else {
			        return 'error';
			    }
		}


		function loadPosts() {
			var skip;
			var posts,
				lastPostId = 0;
			
			if(lastPostId === 0)
				get5();

			function scrollEvent() {
				if($(window).scrollTop() + $(window).height() > $(document).height() - 2) {
				   get5();
				   $(window).off('scroll');
				}
			}

			function get5() {
				
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

					  		$(window).on('scroll',scrollEvent);
					  		posts = data;
						  	$(".loading").remove();
						  	updateWall();
					  	}
					  },
					  error: function(err) {
					  	console.log(err);
					  }
					  
					});
				},2000);
			}

			function updateWall() {
				
				for(var i = 0; i < posts.length; i++) {

					var post = posts[i],
						comments = [];
						lastPostId = post.id;
					generateStatus(post.profile_image, post.username, post.fname, post.lname, post.post_date, post.status_text, lastPostId);
					
					
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
							console.log(err);
						}

						});
					})(lastPostId);

				}
			}
		}

		function generateStatus(profileImage, username, fname, lname, postDate, postText, postId) {
			// POST HEADER HTML
			var postHeader = statusHTMLGenerator.generatePostHeader(),
				postHeaderProfileImage = statusHTMLGenerator.generatePostHeaderProfileImageDiv(profileImage),
				postHeaderFnameLnameDisplay = statusHTMLGenerator.generatePostHeaderFnameLnameDisplayDiv(username, fname, lname, postDate),
				hasAttachment = false,
				hasYoutubeVideoUrl = false;

					// POST BODY HTML

			var postTextFinal = postText;

			var attachmentContent = checkForAttachment(postTextFinal);
			

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

			var postBody = statusHTMLGenerator.generatepostBody(postTextFinal);

			// POST ATTACHMENT HTML

			var postAttachment = statusHTMLGenerator.generatepostAttachment();

			var youtubeLink = checkForYoutubeVideoUrl(attachmentContent);

			if(youtubeLink != '') {
				youtubeLink = convertToVideoId(youtubeLink);
				hasYoutubeVideoUrl = true;
			}
			
			// POST OPTIONS HTML

			var postOptions = statusHTMLGenerator.generatepostOptions(postId);


			var postHTML = statusHTMLGenerator.initStatus(postId);

			$(postHTML).append(postHeader);
			$("#status" + postId + " > .status_head").append(postHeaderProfileImage).append(postHeaderFnameLnameDisplay);

			$(postHTML).append(postBody);
			if(hasYoutubeVideoUrl) {
				$("#status" + postId + " > .status_body").append(statusHTMLGenerator.generateYoutubeVideoDiv(youtubeLink));
			}

			
			if(hasAttachment) {
				$(postHTML).append(postAttachment);
				for(var att in attachmentContent) {
					$("#status" + postId + " > .status_attachment").append("<div style='margin-left: 10px;'><a href=" + attachmentContent[att] + " target='_blank'><blockquote class='blockquote text-center'> <p class='mb-0'> Link " + (parseInt(att) + 1) + " </p> <footer class='blockquote-footer'>  <cite title='Source Title'>Link</cite></footer> </blockquote></a></div>");
				}
			}
			


			$(postHTML).append(postOptions);
			$(postHTML).append(statusHTMLGenerator.generateCommentDiv(postId, null, postId));

			

			$("#commentBtn" + postId).click(function(e){
				e.preventDefault();
				$("#commentDiv" + postId).toggle();
			});
		}

		function generateComments(comments, appendTo) {

			for (var key in comments) {

				var comment = comments[key],
					replies = [];

				generatePost(comment.profile_image, comment.username, comment.fname, comment.lname, comment.post_date, comment.status_text, 'comment', comment.id, appendTo);
				

				(function(commentId) {
						$.ajax({
						type : 'GET',
						url : 'includes/wall.php',
						data : {getComments : commentId},
						dataType : 'json',
						success : function(data) {
							if(data.length !== 0) {
								replies = data;
								console.log(replies);
								generateReplies(replies, commentId);
							}
						},
						error : function(err) {
							console.log(err);
						}

						});
					})(comment.id);
				console.log("\n");
				
			}
			
		}

		function generateReplies(replies, appendTo) {
			for(var key in replies) {
				var reply = replies[(replies.length - 1) - key];

				generatePostReply(reply.profile_image, reply.username, reply.fname, reply.lname, reply.post_date, reply.status_text, 'comment reply', reply.id, appendTo, reply.to_username);
			}
		}



		function generatePostReply(profileImage, username, fname, lname, postDate, postText, type, postId, appendTo = 0, toUser) {
			// POST HEADER HTML
			var postHeader = postReplyHTMLGenerator.generatePostHeader(),
				postHeaderProfileImage = postReplyHTMLGenerator.generatePostHeaderProfileImageDiv(profileImage),
				postHeaderFnameLnameDisplay = postReplyHTMLGenerator.generatePostHeaderFnameLnameDisplayDiv(username, fname, lname, postDate),
				hasAttachment = false,
				hasYoutubeVideoUrl = false;

					// POST BODY HTML

			var postTextFinal = postText;

			var attachmentContent = checkForAttachment(postTextFinal);
			

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

			var postBody = postReplyHTMLGenerator.generatepostBody(postTextFinal, toUser);

			var youtubeLink = checkForYoutubeVideoUrl(attachmentContent);

			if(youtubeLink != '') {
				youtubeLink = convertToVideoId(youtubeLink);
				hasYoutubeVideoUrl = true;
			}
			
			// POST OPTIONS HTML

			var postOptions = postReplyHTMLGenerator.generatepostOptions(postId);


			var postHTML = postReplyHTMLGenerator.initStatus(postId, "#status" + appendTo, type);

			$(postHTML).append(postHeader);
			$("#status" + postId + " > .status_head").append(postHeaderProfileImage).append(postHeaderFnameLnameDisplay);

			$(postHTML).append(postBody);
			if(hasYoutubeVideoUrl) {
				$("#status" + postId + " > .status_body").append(postReplyHTMLGenerator.generateYoutubeVideoDiv(youtubeLink));
			}


			$(postHTML).append(postOptions);

			
			$(postHTML).append(postReplyHTMLGenerator.generateCommentDiv(appendTo,username, postId));
			/*var div = $(postHTMLGenerator.generateCommentSectionDiv()).appendTo("#status" + appendTo);
			$(postHTML).appendTo($(div).children(":first"));*/
			

			console.log(postHTML);


			

			$("#commentBtn" + postId).click(function(e){
				e.preventDefault();
				$("#commentDiv" + postId).toggle();
			});
		}


		function generatePost(profileImage, username, fname, lname, postDate, postText, type, postId, appendTo = 0, toUser = null) {
			// POST HEADER HTML
			var postHeader = postHTMLGenerator.generatePostHeader(),
				postHeaderProfileImage = postHTMLGenerator.generatePostHeaderProfileImageDiv(profileImage),
				postHeaderFnameLnameDisplay = postHTMLGenerator.generatePostHeaderFnameLnameDisplayDiv(username, fname, lname, postDate),
				hasAttachment = false,
				hasYoutubeVideoUrl = false;

					// POST BODY HTML

			var postTextFinal = postText;

			var attachmentContent = checkForAttachment(postTextFinal);
			

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

			var postBody = (toUser === null || toUser === undefined) ? postHTMLGenerator.generatepostBody(postTextFinal, '') : postHTMLGenerator.generatepostBody(postTextFinal, toUser);

			// POST ATTACHMENT HTML

			var postAttachment = type === 'status' ? postHTMLGenerator.generatepostAttachment() : '';

			var youtubeLink = checkForYoutubeVideoUrl(attachmentContent);

			if(youtubeLink != '') {
				youtubeLink = convertToVideoId(youtubeLink);
				hasYoutubeVideoUrl = true;
			}
			
			// POST OPTIONS HTML

			var postOptions = postHTMLGenerator.generatepostOptions(postId);


			var postHTML = postHTMLGenerator.initStatus(postId, "#status" + appendTo, type);

			$(postHTML).append(postHeader);
			$("#status" + postId + " > .status_head").append(postHeaderProfileImage).append(postHeaderFnameLnameDisplay);

			$(postHTML).append(postBody);
			if(hasYoutubeVideoUrl) {
				$("#status" + postId + " > .status_body").append(postHTMLGenerator.generateYoutubeVideoDiv(youtubeLink));
			}


			$(postHTML).append(postOptions);

			
			$(postHTML).append(postHTMLGenerator.generateCommentDiv(username, postId));
			

			/*console.log(postHTML);*/


			

			$("#commentBtn" + postId).click(function(e){
				e.preventDefault();
				$("#commentDiv" + postId).toggle();
			});
		}

		loadPosts();



















		function generatePostBackup(profileImage, username, fname, lname, postDate, postText, type, postId, appendTo = 0, toUser) {
			// POST HEADER HTML
			var postHeader = postHTMLGenerator.generatePostHeader(),
				postHeaderProfileImage = postHTMLGenerator.generatePostHeaderProfileImageDiv(profileImage),
				postHeaderFnameLnameDisplay = postHTMLGenerator.generatePostHeaderFnameLnameDisplayDiv(username, fname, lname, postDate),
				hasAttachment = false,
				hasYoutubeVideoUrl = false;

					// POST BODY HTML

			var postTextFinal = postText;

			var attachmentContent = checkForAttachment(postTextFinal);
			

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

			var postBody = (toUser === null || toUser === undefined) ? postHTMLGenerator.generatepostBody(postTextFinal, '') : postHTMLGenerator.generatepostBody(postTextFinal, toUser);

			// POST ATTACHMENT HTML

			var postAttachment = type === 'status' ? postHTMLGenerator.generatepostAttachment() : '';

			var youtubeLink = checkForYoutubeVideoUrl(attachmentContent);

			if(youtubeLink != '') {
				youtubeLink = convertToVideoId(youtubeLink);
				hasYoutubeVideoUrl = true;
			}
			
			// POST OPTIONS HTML

			var postOptions = postHTMLGenerator.generatepostOptions(postId);


			var postHTML = type === 'status' ? postHTMLGenerator.initStatus(postId, ".posts") : postHTMLGenerator.initStatus(postId, "#status" + appendTo, 'comment');

			$(postHTML).append(postHeader);
			$("#status" + postId + " > .status_head").append(postHeaderProfileImage).append(postHeaderFnameLnameDisplay);

			$(postHTML).append(postBody);
			if(hasYoutubeVideoUrl) {
				$("#status" + postId + " > .status_body").append(postHTMLGenerator.generateYoutubeVideoDiv(youtubeLink));
			}


			$(postHTML).append(postOptions);

			
			$(postHTML).append(postHTMLGenerator.generateCommentDiv(appendTo, username, postId));
			/*var div = $(postHTMLGenerator.generateCommentSectionDiv()).appendTo("#status" + appendTo);
			$(postHTML).appendTo($(div).children(":first"));*/
			

			console.log(postHTML);


			

			$("#commentBtn" + postId).click(function(e){
				e.preventDefault();
				$("#commentDiv" + postId).toggle();
			});
		}
		
	});
