$(document).ready(function() {
	var url = "setup.php";
	$.get(url, function(data) {
		console.log(data);
	});
});

function login(event) {
	event.preventDefault();
	var username = $("#username").val();
	var password = $("#password").val();
	
	var url = "store.php?method=login&username="+username+"&password="+password;
	$.post(url, {method: "login", username: username, password: password}, function(data) {
		console.log(data);
	})
}