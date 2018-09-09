
(() => {


	const addFriendLink = document.querySelector('#add_friend') || null;

	if(!addFriendLink)
		return;

	addFriendLink.addEventListener('click', addFriend);


	function addFriend(e) {
		let usernameToAdd = document.querySelector('input[name="username_to"]').value;

		e.preventDefault();

		sendFriendRequest(usernameToAdd);
	}

	function sendFriendRequest(data) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'includes/friendships.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', false);

		xhr.onreadystatechange = function() {
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
        		document.querySelector('#friendship').innerHTML = xhr.responseText;
    		}
		}

		xhr.send(`data=${data}`);
	}

	

})();