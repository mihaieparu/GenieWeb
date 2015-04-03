/*
Mobile Library for Haufe Lexware Romania
All rights reserved Mihai-Alin Eparu 2015.
*/
var mobile = 0;
$(document).ready(function() {
	if ($(window).outerWidth() > 768) {
		mobile = 0;
	}
	else {
		mobile = 1;
	}
	menuCreate();
	langList();
});
$(window).resize(function() {
	if ($(window).outerWidth() > 768) {
		mobile = 0;
	}
	else {
		mobile = 1;
	}
	menuUpdate();
	onResize();
});
function menuCreate() {
	if ($("menu").length) {
		$("#menu").html("");
		$("#menu-black").remove();
		var links = "";
		$("menu").children("element").each(function() {
			links += '<a class="menu-el" id="menu-' + $(this).attr("id") + '" ' + ($(this).attr("data-useajax") == "true" ? 'href="#" data-ajax-go="' + $(this).attr("data-URL") + '"' : 'href="' + $(this).attr("data-URL") + '"') + ' target="' + $(this).attr("data-target") + '" ' + ($(this).attr("data-usetranslr-title") == "true" ? 'data-translr-title="' + $(this).attr("data-title") + '"' : 'title="' + $(this).attr("data-title") + '"') + ($(this).attr("data-usetranslr-value") == "true" ? 'data-translr-value="' + $(this).attr("data-value") + '">' : '>' + $(this).attr("data-value")) + '</a>';
		});
		if (mobile) {
			$("#menu").append('<a href="javascript:showMenu();"><div id="menu-three"><div></div><div></div><div></div></div></a>');
			$("body").prepend('<div id="menu-black" class="full-size"><a id="close" href="javascript:closeMenu();"><span class="fa fa-times"></span></a><div id="menu-black-c" data-center-x="true" data-center-y="true">' + links + '</div></div>');
		}
		else {
			$("#menu").append(links);
		}
		updateTranslr();
	}
}
function menuUpdate() {
	if ($("menu").length) {
		if (mobile && !$("#menu-black").length) {
			menuCreate();
			langList();
			$("#menu-black-c").on("mouseenter", ".menu-el", function() {
				$(this).animate({color: defcolor}, 250);
			});
			$("#menu-black-c").on("mouseleave", ".menu-el", function() {
				$(this).animate({color: "white"}, 250);
			});
		}
		else if (!mobile && $("#menu-black").length) {
			menuCreate();
			langList();
			$("#menu").on("mouseenter", ".menu-el", function() {
				$(this).animate({color: menuhovercolor}, 250);
			});
			$("#menu").on("mouseleave", ".menu-el", function() {
				$(this).animate({color: "white"}, 250);
			});
		}
	}
}
function showMenu() {
	$("#menu-black").fadeIn(500);
}
function closeMenu() {
	$("#menu-black").fadeOut(500);
}
function langList() {
	if ($("langlist").length) {
		var links = '<span id="menu-lang">';
		var i = 0;
		$("langlist").children("lang").each(function() {
			links += '<a class="menu-lang' + ($(this).attr("data-active") == "true" ? ' menu-active' : '') + '" href="javascript:changeLangByID(' + $(this).attr("id").replace("lang-", "") + ');" data-langcode="' + $(this).attr("data-langcode") + '" title="' + $(this).attr("data-langname") + '">' + $(this).attr("data-langshow") + '</a>';
			if (++i < $("langlist").children("lang").length) {
				links += '<div class="divider"></div>';
			}
		});
		links += '</span>';
		$("#menu").prepend(links);
	}
}
function onResize() {
	if (mobile) {
		$("#header").animate({height:($(document).scrollTop() == 0 ? "75px" : "55px"), backgroundColor: ($(document).scrollTop() == 0 ? ($("#header").hasClass("black-contrast") ? navbarcolor : "transparent") : navbarcolor)}, 250);
	}
	else {
		$("#header").animate({height:($(document).scrollTop() == 0 ? "95px" : "65px"), backgroundColor: ($(document).scrollTop() == 0 ? ($("#header").hasClass("black-contrast") ? navbarcolor : "transparent") : navbarcolor)}, 250);
	}
}