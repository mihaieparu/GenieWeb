/*
Animations Library for Haufe Lexware Romania
All rights reserved Mihai-Alin Eparu 2015.
*/
var navbarcolor = "black", defcolor = "rgb(77,157,219)", contrast = "black", menuhovercolor = "rgba(255,255,255,0.5)", linkhovercolor = defcolor, buttoncontrast1color = "white", buttoncontrast2color = defcolor, defaultimg = "http://www.bani.md/wp-content/uploads/2015/02/Fotolia_57325018_Subscription_Monthly_XL.jpg";
$(document).scroll(function() {
	if ($(document).scrollTop() > 0) {
		if ($("#header").height() == 95 || $("#header").height() == 75) { $("#header").animate({height:(!mobile ? "65px" : "55px"), backgroundColor:navbarcolor}, 500); }
	}
	else {
		if ($("#header").height() == 65 || $("#header").height() == 55) { $("#header").animate({height:(!mobile ? "95px" : "75px"), backgroundColor:($("#header").hasClass("black-contrast") ? navbarcolor : "transparent")}, 500); }
	}
});
function scrollToEl(elid) {
	$("html, body").animate({scrollTop: ($(elid).offset().top  > (mobile ? 55 : 65) ? $(elid).offset().top - (mobile ? 55 : 65) : 0) + "px"}, 1000);
}
function scrollToY(height) {
	$("html, body").animate({scrollTop: (height > (mobile ? 55 : 65) ? height - (mobile ? 55 : 65) : 0) + "px"}, 1000);
}
function animateAll() {
	$("#header").animate({top:0}, 500);
}
function animateButtons() {
	$("#content").find(".btn").each(function() {
		if ($(this).parents(".contrast").length || $(this).hasClass("contrast"))  {
			$(this).css("border", "1px solid " + buttoncontrast1color);
			$(this).css("color", buttoncontrast1color);
		}
		else if ($(this).attr("data-hover-to")) {
			$(this).css("border", "1px solid " + $(this).attr("data-hover-to"));
			$(this).css("color", $(this).attr("data-hover-to"));
		}
		else {
			$(this).css("border", "1px solid " + buttoncontrast2color);
			$(this).css("color", buttoncontrast2color);
		}
	});
}
$(document).ready(function() {
	$("#menu").on("mouseenter", ".menu-el", function() {
		$(this).animate({color: menuhovercolor}, 250);
	});
	$("#menu").on("mouseleave", ".menu-el", function() {
		$(this).animate({color: "white"}, 250);
	});
	$("body").on("mouseenter", "#jumbotron .jumbotron-container .btn", function() {
		$(this).animate({color:"black", backgroundColor:"white"}, 250);
		$(this).children(".glyphicon, .fa").animate({color:"black"}, 250);
	});
	$("body").on("mouseleave", "#jumbotron .jumbotron-container .btn", function() {
		$(this).animate({color:"white", backgroundColor:"transparent"}, 250);
		$(this).children(".glyphicon, .fa").animate({color:"white"}, 250);
	});
	$("#content").on("mouseenter", "* .btn", function() {
		if ($(this).attr("data-hover-to")) {
			$(this).animate({color:($(this).attr("data-hover-into") ? $(this).attr("data-hover-into") : "white"), backgroundColor:$(this).attr("data-hover-to")}, 250);
			$(this).children(".glyphicon, .fa").animate({color:($(this).attr("data-hover-into") ? $(this).attr("data-hover-into") : "white")}, 250);
		}
		else if ($(this).parents(".contrast").length || $(this).hasClass("contrast"))  {
			$(this).animate({color:buttoncontrast2color, backgroundColor:buttoncontrast1color}, 250);
			$(this).children(".glyphicon, .fa").animate({color:buttoncontrast2color}, 250);
		}
		else {
			$(this).animate({color:buttoncontrast1color, backgroundColor:buttoncontrast2color}, 250);
			$(this).children(".glyphicon, .fa").animate({color:buttoncontrast1color}, 250);
		}
	});
	$("#content").on("mouseleave", "* .btn", function() {
		if ($(this).attr("data-hover-to")) {
			$(this).animate({color:$(this).attr("data-hover-to"), backgroundColor:"transparent"}, 250);
			$(this).children(".glyphicon, .fa").animate({color:$(this).attr("data-hover-to")}, 250);
		}
		else if ($(this).parents(".contrast").length || $(this).hasClass("contrast"))  {
			$(this).animate({color:buttoncontrast1color, backgroundColor:"transparent"}, 250);
			$(this).children(".glyphicon, .fa").animate({color:buttoncontrast1color}, 250);
		}
		else {
			$(this).animate({color:buttoncontrast2color, backgroundColor:"transparent"}, 250);
			$(this).children(".glyphicon, .fa").animate({color:buttoncontrast2color}, 250);
		}
	});
	$("#content").on("mouseenter", "* #follow a span", function() {
		if ($(this).hasClass("fa-facebook")) { $(this).animate({color: "rgb(59,89,152)"}, 250); }
		else if ($(this).hasClass("fa-twitter")) { $(this).animate({color: "rgb(64,153,255)"}, 250); }
		else if ($(this).hasClass("fa-linkedin")) { $(this).animate({color: "rgb(9,118,180)"}, 250); }
	});
	$("#content").on("mouseleave", "* #follow a span", function() {
		$(this).animate({color: "white"}, 250);
	});
});
function animateJumbotron() {
	$("#jumbotron").children(".jumbotron-container").animate({opacity:1}, 500);
}
function animateContent() {
	$("#content").animate({top: $("#jumbotron").outerHeight(), opacity: 1}, 500);
}
function animateTimeline() {
	var timeout = 200;
	$(".timeline-el").each(function() {
		$(this).animate({opacity:1}, timeout);
		timeout+=100;
	});
}