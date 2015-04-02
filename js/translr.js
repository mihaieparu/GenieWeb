/*
Translr Library for Haufe Lexware Romania
This is a special designed library and was not optimized for use on other websites. Please do not reuse this library.
All rights reserved Mihai-Alin Eparu 2015.
*/
var lang = "";
$(document).ready(function() {
	if (getCookie("language") != "") {
		lang = getCookie("language");
	}
	else {
		lang = "en";
	}
});
function changeLangByID(id) {
	if ($("lang[id='lang-" + id + "']").length) {
		changeLanguage($("lang[id='lang-" + id + "']").attr("data-langcode"));
	}
}
function changeLanguage(langcode) {
	if ($("lang[data-langcode='" + langcode + "']").length) {
		lang = langcode;
		var d = new Date();
		d.setDate(d.getFullYear() + 1);
		setCookie("language", langcode, d, "/");
		$("lang").attr("data-active", "false");
		$("lang[data-langcode='" + langcode + "']").attr("data-active", "true");
		$(".menu-lang").removeClass("menu-active");
		$(".menu-lang[data-langcode='" + langcode + "']").addClass("menu-active");
		updateTranslr();
	}
}
function updateTranslr() {
	var definitions;
	$.ajax({
		type: "POST",
		url: ign_pathname + "translr.php",
		data: "lang=" + lang,
		success: function(result) {
			if (result.indexOf("ERROR_") == -1) {
				definitions = JSON.parse(result);
			}
			else {
				if ($("#jumbotron").length) { $("#jumbotron").animate({opacity: 0}, 300, function() { $("#jumbotron").remove(); }); }
				if ($("#content").length) { $("#content").animate({opacity: 0, height: 0}, 300, function() { $("#content").html(""); onResize(); }); }
				if ($("#scrollspy").length) { $("#scrollspy").animate({opacity: 0}, 300, function() { $("#scrollspy").remove(); }); }
				if ($("#header").hasClass("black-contrast")) { $("#header").animate({backgroundColor:"transparent"}, 300, function() { $("#header").removeClass("black-contrast"); }); }
				console.error("Translr Error " + result.replace("ERROR_", ""));
				showError(100);
			}
		},
		error: function() {
			if ($("#jumbotron").length) { $("#jumbotron").animate({opacity: 0}, 300, function() { $("#jumbotron").remove(); }); }
			if ($("#content").length) { $("#content").animate({opacity: 0, height: 0}, 300, function() { $("#content").html(""); onResize(); }); }
			if ($("#scrollspy").length) { $("#scrollspy").animate({opacity: 0}, 300, function() { $("#scrollspy").remove(); }); }
			if ($("#header").hasClass("black-contrast")) { $("#header").animate({backgroundColor:"transparent"}, 300, function() { $("#header").removeClass("black-contrast"); }); }
			console.error("Translr Error 500");
			showError(100);
		}
	}).done(function() {
		$("*[data-translr-value]").each(function() {
			if (definitions[$(this).attr("data-translr-value")]) {
				$(this).html(B64.decode(definitions[$(this).attr("data-translr-value")]));
			}
			else {
				$(this).html('<span class="fa fa-times-circle-o" title="Translr key not found!"></span>');
			}
		});
		$("*[data-translr-title]").each(function() {
			if (definitions[$(this).attr("data-translr-title")]) {
				$(this).attr("title", B64.decode(definitions[$(this).attr("data-translr-title")]));
			}
		});
		$("*[data-translr-placeholder]").each(function() {
			if (definitions[$(this).attr("data-translr-placeholder")]) {
				$(this).attr("placeholder", B64.decode(definitions[$(this).attr("data-translr-placeholder")]));
			}
		});
		$("img[data-translr-img]").each(function() {
			if (definitions[$(this).attr("data-translr-img")]) {
				$(this).attr("src", B64.decode(definitions[$(this).attr("data-translr-img")]));
			}
		});
		$("*[data-translr-check]").each(function() {
			var patt = /@translr-(\w+\/\w+\/\w+|\w+\/\w+|\w+)/;
			while (patt.test($(this).html())) {
				$(this).html($(this).html().replace(patt, '<span data-translr-value="@$1"></span>'));
			}
			$(this).children("*[data-translr-value]").each(function() {
				if (definitions[$(this).attr("data-translr-value")]) {
					$(this).html(B64.decode(definitions[$(this).attr("data-translr-value")]));
				}
				else {
					$(this).html('<span class="fa fa-times-circle-o" title="Translr key not found!"></span>');
				}
			});
		});
		if ($("title").attr("data-translr-ptitle")) {
			if (definitions[$("title").attr("data-translr-ptitle")]) {
				$("title").html(B64.decode(definitions[$("title").attr("data-translr-ptitle")]) + " - HAUFE Lexware Romania");
			}
		}
	});
}