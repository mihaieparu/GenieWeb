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
function logout() {
	if (confirm("Are you sure you want to log off?")) {
		location.href="logout.php";
	}
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
function moveUp(id) {
	var el = $("#row-" + id).clone();
	var appto = $("#row-" + id).prev();
	if (appto.length) {
		$("#row-" + id).remove();
		appto.before(el);
	}
	if ($("#update-order").attr("data-hidden") == "true") {
		$("#update-order").show().attr("data-hidden", "false");
	}
}
function moveDown(id) {
	var el = $("#row-" + id).clone();
	var appto = $("#row-" + id).next();
	if (appto.length) {
		$("#row-" + id).remove();
		appto.after(el);
	}
	if ($("#update-order").attr("data-hidden") == "true") {
		$("#update-order").show().attr("data-hidden", "false");
	}
}
function updateOrder() {
	var el = {};
	var i = 1;
	$("tr").each(function() {
		if ($(this).attr("id")) {
			el[$(this).attr("id")] = i;
			i++;
		}
	});
	location.href = "edit.php?what=menu&save=true&order=" + encodeURIComponent(JSON.stringify(el))
}
function triggerTranslrModal(id_el, append) {
	$.ajax({
		url: "translr.php",
		type: "POST",
		data: "action=retrieve",
		success: function(result) {
			if (result) {
				var obj = JSON.parse(result);
				$("#translr-keys").html('<option value="">Select...</option>');
				for (var i = 0; i < obj.keys.length; i++) {
					$("#translr-keys").append('<option value="' + encodeURIComponent(obj.keys[i]) + '">' + obj.keys[i] + '</option>');
				}
				var html = "";
				html = '<table style="width:100%"><tr><td><label for="key">Key</label></td><td><input type="text" name="Key" class="form-control" id="key" placeholder="Key (e.g. @key/example)" required /></td></tr>';
				for (var i = 0; i < obj.langs.length; i++) {
					html += '<tr><td><label for="value-' + obj.langs[i] + '"><img src="http://l10n.xwiki.org/xwiki/bin/download/L10N/Flags/' + obj.langs[i] + '.png" /></label></td><td><input type="text" name="value-' + obj.langs[i] + '" id="value-' + obj.langs[i] + '" class="form-control" placeholder="String for this language" /></td></tr>';
				}
				$("#add-key-form").html(html);
				$("#add-key-form").append('</table><center><button class="btn btn-primary" style="margin-top:10px" type="submit"><span class="fa fa-fw fa-plus"></span> Add key</button></center>');
				$(".modal").attr("data-for", id_el);
				if (append) { $(".modal").attr("data-append", "true"); } else { $(".modal").removeAttr("data-append"); }
				$(".modal").modal();
			}
		}
	});
}
function addKey(elid) {
	var append = ($(".modal").attr("data-append") == "true" ? 1 : 0);
	var element = $("#" + elid);
	if (element.attr("id") == "add-key-form") {
		var data = "action=create";
		if (!(element.find("input[name='Key']").val().indexOf("@") == 0)) {
			element.find("input[name='Key']").val("@" + element.find("input[name='Key']").val())
		}
		element.find("input").each(function() {
			data += "&" + encodeURIComponent($(this).attr("name")) + "=" + encodeURIComponent($(this).val());
		});
		$.ajax({
			url: "translr.php",
			type: "POST",
			data: data,
			success: function(result) {
				if ($(".modal").attr("data-for")) {
					if (!append) { $("*[data-translr-await-id='" + $(".modal").attr("data-for") + "'").val(element.find("input[name='Key']").val()); }
					else { $("*[data-translr-await-id='" + $(".modal").attr("data-for") + "'").append(element.find("input[name='Key']").val()); }
				}
				$(".modal").modal('hide');
			}
		});
	}
	else {
		if (element.children("#translr-keys").val()) {
			if ($(".modal").attr("data-for")) {
				if (!append) { $("*[data-translr-await-id='" + $(".modal").attr("data-for") + "'").val(decodeURIComponent(element.children("#translr-keys").val())); }
				else { $("*[data-translr-await-id='" + $(".modal").attr("data-for") + "'").append(decodeURIComponent(element.children("#translr-keys").val())); }
			}
			$(".modal").modal('hide');
		}
	}
}