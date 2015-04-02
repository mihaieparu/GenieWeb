/*
Scrollspy Library for Haufe Lexware Romania
This is a special designed library and was not optimized for use on other websites. Please do not reuse this library.
All rights reserved Mihai-Alin Eparu 2015.
*/
var scrollspy = {};
var len = 0;
function firstStop() {
	var fstoph = 0;
	for (e in scrollspy) { if (e != "jumbotron") { fstoph = scrollspy[e]["top"]; break; } }
	scrollToY(fstoph);
}
$(document).scroll(function() {
	scrollSpy();
});
function scrollSpy() {
	var tops = {};
	var i = 0;
	var imin = "jumbotron";
	for (e in scrollspy) {
		tops[i] = {"top": scrollspy[e]["top"], "equiv": e}
		i++;
	}
	for (var j = 0; j < i; j++) {
		if (($(document).scrollTop() + $(window).outerHeight()) > (tops[j]["top"] + ($("#" + tops[j]["equiv"]).outerHeight() / 2))) {
			imin = tops[j]["equiv"];
		}
	}
	if ($("#" + imin).hasClass("contrast") || imin == "jumbotron") {
		$(".scrollspy").removeClass("black");
	}
	else {
		$(".scrollspy").addClass("black");
	}
	if (!($(".scrollspy").attr("data-animations") == "progress")) {
		var animations = 0;
		$(".scrollspy-element").each(function() {
			if (!$(this).hasClass("on") && $(this).attr("data-for") != imin) {
				animations = 1;
				$(this).animate({backgroundColor:"transparent"}, 250);
			}
		});
		if ($(".scrollspy-element.on").attr("data-for") != imin) {
			animations = 1;
			$(".scrollspy-element.on").animate({backgroundColor:"transparent"}, 250, function() { $(this).removeClass("on"); });
		}
		if (!$("#scrollspy-" + imin).hasClass("on")) { animations = 1; $("#scrollspy-" + imin).animate({backgroundColor: ($(".scrollspy").hasClass("black") ? 'black' : 'white')}, 250, function() { $(this).addClass("on"); }); }
		if (animations) { $(".scrollspy").attr("data-animations", "progress"); setTimeout(function() { $(".scrollspy").removeAttr("data-animations"); }, 250); }
	}
}
function createScrollspy() {
	if ($("#content").attr("data-scrollspy") == "true") {
		if ($("#jumbotron").length) {
			scrollspy["jumbotron"] = {"top": 0, "title": "Intro"};
		}
		$(".content-container-div").each(function() {
			if ($(this).attr("data-scrollspy-id")) {
				scrollspy[$(this).attr("data-scrollspy-id")] = {"top": $("#content").position().top + $(this).position().top, "title": $(this).attr("data-title")};
			}
		});
		for (e in scrollspy) { len++ }
		if (len) {
			var scrollspyobj = '<div class="scrollspy" data-center-y="true" id="scrollspy" data-element-count="' + len + '">';
			for (var e in scrollspy) {
				scrollspyobj += '<div class="scrollspy-element" ' + (scrollspy[e]["title"].indexOf("@") == 0 ? 'data-translr-title="' + scrollspy[e]["title"] : 'title="' + scrollspy[e]["title"]) + '" id="scrollspy-' + e + '" data-for="' + e + '"></div>';
			}
			scrollspyobj += '</div>';
			$("body").prepend(scrollspyobj);
			for (e in scrollspy) {
				$("#scrollspy-" + e).mouseenter(function() {
					if (!$(this).hasClass("on")) {
						$(this).animate({backgroundColor: $(this).css("border-color")}, 250);
					}
				});
				$("#scrollspy-" + e).mouseleave(function() {
					if (!$(this).hasClass("on")) {
						$(this).animate({backgroundColor: "transparent"}, 250);
					}
				});
				$("#scrollspy-" + e).click(function() {
					scrollToY(scrollspy[$(this).attr("data-for")]["top"]);
				});
			}
			$(".scrollspy-element").first().addClass("on");
		}
	}
}
function updateScrollspy() {
	var ch = 0;
	if ($("#jumbotron").length) {
		if ($("#content").position().top != $("#jumbotron").outerHeight() && !$("#content").attr("data-on-moving")) {
			$("#content").attr("data-on-moving", "true").animate({top: $("#jumbotron").outerHeight()}, 150, function() { $("#content").removeAttr("data-on-moving"); });
		}
	}
	else {
		if ($("#content").position().top != (mobile ? 75 : 95) && !$("#content").attr("data-on-moving")) {
			$("#content").attr("data-on-moving", "true").animate({top: (mobile ? 75 : 95)}, 150, function() { $("#content").removeAttr("data-on-moving"); });
		}
	}
	$(".content-container-div").each(function() {
		if ($(this).position().top != ch && !$(this).attr("data-on-moving")) {
			$(this).attr("data-on-moving", "true").animate({top: ch}, 150, function() { $(this).removeAttr("data-on-moving"); });
		}
		if ($("#content").attr("data-scrollspy") == "true" && len) {
			scrollspy[$(this).attr("data-scrollspy-id")]["top"] = $("#content").position().top + ch;
		}
		ch += $(this).outerHeight();
	});
	$("#content").outerHeight(ch);
}
$(document).ready(function() {
	setInterval(function() {
		updateScrollspy();
	}, 100);
});