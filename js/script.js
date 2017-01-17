$(document).ready(function() {
	var url = "setup.php";

	api_called = "<strong>Url</strong><br>\"setup.php\"<br>";
	$(".api-called-content").html(api_called);

	$.get(url, function(data) {
		$(".api-data-content").html(data);
	});
});

var url = "store.php";
var api_called_base = "<strong>Url</strong><br>\"store.php\"<br><br><strong>Post Request Parameters</strong><br>";

function login(event) {
	event.preventDefault();

	var username = $("#username").val();
	var password = $("#password").val();

	api_called = api_called_base + "method: login<br>username: " + username + "<br>password: " + password;
	$(".api-called-content").html(api_called);
	
	$.post(url, {method: "login", username: username, password: password}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}

function add(event) {
	event.preventDefault();
	var code = $("#code1").val();
	var name = $("#name1").val();
	var prod_code = $("#prod_code1").val();
	var quantity = $("#quantity1").val();
	var category = ($("#category1").val() == "") ? null : $("#category1").val();
	var description = ($("#description1").val() == "") ? null : $("#description1").val();

	api_called = api_called_base + "method: add<br>code: " + code + "<br>name: " + name + "<br>quantity: " + quantity + "<br>product_code: " + prod_code + "<br>category: " + category + "<br>description: " + description;
	$(".api-called-content").html(api_called);

	$.post(url, {method: "add", code: code, name: name, quantity: quantity, product_code: prod_code, category: category, description: description}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}

function del(event) {
	event.preventDefault();
	var code = $("#code2").val();
	var prod_code = $("#prod_code2").val();

	api_called = api_called_base + "method: delete<br>code: " + code + "<br>product_code: " + prod_code;
	$(".api-called-content").html(api_called);

	$.post(url, {method: "delete", code: code, product_code: prod_code}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}

function modify(event) {
	event.preventDefault();
	var code = $("#code3").val();
	var name = ($("#name3").val() == "") ? null : $("#name3").val();
	var prod_code = $("#prod_code3").val();
	var quantity = ($("#quantity3").val() == "") ? null : $("#quantity3").val();
	var category = ($("#category3").val() == "") ? null : $("#category3").val();
	var description = ($("#description3").val() == "") ? null : $("#description3").val();

	api_called = api_called_base + "method: modify<br>code: " + code + "<br>name: " + name + "<br>quantity: " + quantity + "<br>product_code: " + prod_code + "<br>category: " + category + "<br>description: " + description;
	$(".api-called-content").html(api_called);

	$.post(url, {method: "modify", code: code, name: name, quantity: quantity, product_code: prod_code, category: category, description: description}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}

function search(event) {
	event.preventDefault();
	var code = $("#code4").val();
	var name = ($("#name4").val() == "") ? null : $("#name4").val();
	var prod_code = ($("#prod_code4").val() == "") ? null : $("#prod_code4").val();
	var quantity = ($("#quantity4").val() == "") ? null : $("#quantity4").val();
	var category = ($("#category4").val() == "") ? null : $("#category4").val();

	api_called = api_called_base + "method: search<br>code: " + code + "<br>name: " + name + "<br>quantity: " + quantity + "<br>product_code: " + prod_code + "<br>category: " + category;
	$(".api-called-content").html(api_called);

	$.post(url, {method: "search", code: code, name: name, quantity: quantity, product_code: prod_code, category: category}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}

function list_all(event) {
	event.preventDefault();
	var code = $("#code5").val();

	api_called = api_called_base + "method: display_all<br>code: " + code;
	$(".api-called-content").html(api_called);

	$.post(url, {method: "display_all", code: code}, function(data) {
		var jsonPretty = JSON.stringify(JSON.parse(data), null, 2);  
		$(".api-data-content").html(jsonPretty);
	});
}