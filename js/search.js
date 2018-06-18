(() => {

	let searchInput = document.querySelector("#searchText");
	searchInput.addEventListener("keyup", typingHandler);

	searchInput.addEventListener("blur", () => {
		searchInput.value = "";

	});

	let searchString = "";

	const resultsContainer = document.querySelector(".search-results > .container");
	const resultRow = document.querySelector(".s-result");

	function typingHandler(event) {
		
		searchString = searchInput.value;

		if(searchString) {
			
			$(document.querySelector(".search-results")).show();

			fetchSearchResults(searchString)

			.then((response) => {

				const results = JSON.parse(response);
				displaySearchResults(results);
			})
			.catch((error) => {
				console.error("Greska");
			});

		}
	}

	function fetchSearchResults(searchString) {

		let searchSubstrings = searchString.split(" ").reduce((acc, curr, i) => {
			if(curr)
				acc['s' + i] = curr;
			return acc;
		}, {});

		let searchPromise = new Promise((resolve, reject) => {

			let data = "search=" + JSON.stringify(searchSubstrings);

			const xhr = new XMLHttpRequest();
			xhr.open("POST", 'includes/wall.php', true);
			xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
			xhr.setRequestHeader('Accept','application/json; charset=utf-8');

			xhr.onreadystatechange = () => {
				if(xhr.readyState === 4 && xhr.status === 200) {
					resolve(xhr.responseText);
				}

				if(xhr.readyState === 4 && xhr.status !== 200) {
					reject(xhr.responseText);
				}
			};

			xhr.send(data);


		});

		return searchPromise;
	}

	function displaySearchResults(results) {

		clearResults();

		for(let result of results) {

			let row = resultRow.cloneNode(true);

			let profileImage = row.children[0].children[0]; // podesiti src

			let fullName = row.children[1].children[0]; // zpodesiti href i inner text

			profileImage.src = "images/uploads/profile_images/" + result.profile_image;

			fullName.href = "index.php?account=" + result.username;
			fullName.innerText = result.fname + " " + result.lname;

			resultsContainer.appendChild(row);

		}

		

	}

	function clearResults() {
		
		let rowsToClear = document.querySelectorAll(".s-result");
		
		for(let row of rowsToClear) 
			row.remove();
	}



})();