(function($) {
	if (!Session.getLoggedUser()) {
		alert("You're not signed in! Please sign in first!");
		$.open('./home.html', '_self');
	}
})(window);
