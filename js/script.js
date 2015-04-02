/*
Script Library for Haufe Lexware Romania
All rights reserved Mihai-Alin Eparu 2015.
*/
function setCookie (c_name, value, exdate, path) {
	var c_value = escape(value) + "; expires=" + exdate.toUTCString() + (path != null ? "; path=" + escape(path) : "");
	document.cookie = c_name + "=" + c_value;
}
function getCookie (c_name) {
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)
	  {
	  c_start = c_value.indexOf(c_name + "=");
	  }
	if (c_start == -1)
	  {
	  c_value = null;
	  }
	else
	  {
	  c_start = c_value.indexOf("=", c_start) + 1;
	  var c_end = c_value.indexOf(";", c_start);
	  if (c_end == -1)
	  {
	c_end = c_value.length;
	}
	c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}
function centerX (elid) {
	var element = $("#" + elid);
	if (element.parents().length > 0 && element.outerWidth() > 0 && element.parent().outerWidth() > 0) {
		if (element.css("position") == null || element.css("position") == "static") { element.css("position", "relative"); }
		element.css("margin-left", "0");
		element.css("left", Math.round((element.parent().outerWidth() - element.outerWidth()) / 2));
	}
}
function centerY (elid) {
	var element = $("#" + elid);
	if (element.parents().length > 0 && element.outerHeight() > 0 && element.parent().outerHeight() > 0) {
		if (element.css("position") == null || element.css("position") == "static") { element.css("position", "relative"); }
		element.css("margin-top", "0");
		element.css("top", Math.round((element.parent().outerHeight() - element.outerHeight()) / 2));
	}
}
setInterval(function() {
	$("*[data-center-x='true']").each(function() {
		centerX($(this).attr("id"));
	});
	$("*[data-center-y='true']").each(function() {
		centerY($(this).attr("id"));
	});
}, 1);