

// CHECK IF DOM IS READY

function newMessageFormCreator(e) {

	const msgContainer = document.querySelector("#msgContainer");
	//console.log(msgContainer);
	const form = document.createElement('form');
	msgContainer.appendChild(form);

	e.preventDefault();
}

function clearMsgContainer() {

	const msgContainer = document.querySelector("#msgContainer");

	while (msgContainer.firstChild) {
	  msgContainer.removeChild(msgContainer.firstChild);
	}
}

function inboxLoader(e) {
	console.log(e.target);

	e.preventDefault();
}

function outboxLoader(e) {
	console.log(e.target);

	e.preventDefault();
}

function detachListener(id) {
	document.querySelector("#" + id).removeEventListener('click');
}

function attachListeners() {
	document.querySelector("#newMessage").addEventListener('click', newMessageFormCreator);
	document.querySelector("#inbox").addEventListener('click', inboxLoader);
	document.querySelector("#outbox").addEventListener('click', outboxLoader);
}

document.addEventListener("DOMContentLoaded", () => {

	//clearMsgContainer()
	//attachListeners()
	

});