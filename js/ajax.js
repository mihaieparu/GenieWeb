/*
AJAX Library for Haufe Lexware Romania
All rights reserved Mihai-Alin Eparu 2015.
*/
$(document).ready(function() {
	loaderImg();
	setInterval(function() {
		linkAJAXGo();
	}, 500);
	setInterval(function() {
		if (!(location.href.indexOf(".php") != -1)) {
			if (location.pathname.replace(ign_pathname, "").split("/")[0] != curr_route) {
				var attr = "";
				for (var i = 1; i < location.pathname.replace(ign_pathname, "").split("/").length; i++) {
					attr += location.pathname.replace(ign_pathname, "").split("/")[i] + (i < location.pathname.replace(ign_pathname, "").split("/").length - 1 ? "/" : "");
				}
				routeTo(location.pathname.replace(ign_pathname, "").split("/")[0], attr);
			}
		}
	}, 500);
});
var elementcount = 0;
function loaderImg() {
	if ($("imglist").length) {
		var c = parseInt($("imglist").attr("count"));
		if (c > 1) {
			var r = Math.floor(Math.random() * c);
			while ($("#loader").css('background-image').indexOf($("#img-" + r).attr("src")) != -1) {
				r = Math.floor(Math.random() * c);
			}
			$("#loader").css('background-image', 'url("' + $("#img-" + r).attr("src") + '")');
		}
		else if (c == 1) {
			$("#loader").css('background-image', 'url("' + $("#img-0").attr("src") + '")');
		}
	}
}
function linkAJAXGo() {
	$("*[data-ajax-go]").each(function () {
		$(this).unbind('click');
		$(this).click(function() {
			if ($(this).attr("data-ajax-go").indexOf("/") == -1) {
				routeTo($(this).attr("data-ajax-go"));
			}
			else {
				routeTo($(this).attr("data-ajax-go").substr(0, $(this).attr("data-ajax-go").indexOf("/")), $(this).attr("data-ajax-go").substr($(this).attr("data-ajax-go").indexOf("/") + 1));
			}
			if (mobile) { closeMenu(); }
		});
		$(this).removeAttr("href").css("cursor", "pointer").css("cursor", "hand");
	});
}
function routeTo(route, attributes) {
	if (route != "") {
		getRoute(route, attributes);
	}
}
function getRoute(route, attributes) {
	$.ajax({
		type: "POST",
		url: ign_pathname + "route.php",
		cache: false,
		data: "type=route&route=" + route,
		success: function(result) {
			routeAction(route, attributes, result);
		},
		error: function() {
			routeAction(route, attributes, "ERROR_500");
		}
	});
}
function routeAction(route, attributes, callbackresult) {
	if (callbackresult && callbackresult.indexOf("ERROR_") == -1) {
		switch (callbackresult) {
			case curr_routeto:
				$.ajax({
					type: "POST",
					url: ign_pathname + "route.php",
					data: "type=attr&route=" + route,
					cache: false,
					success: function(result) {
						if (result.indexOf("ERROR_") == -1) {
							var res = JSON.parse(result);
							if (res.routetoattributes.indexOf("#") == 0) {
								scrollToEl(res.routetoattributes);
							}
							else {
								scrollToY(0);
							}
						}
						else {
							alert("We could not connect to the server. Please check your internet connection and then try again.");
							console.error("AJAX Error " + parseInt(result.replace("ERROR_", "")));
						}
					},
					error: function() {
						alert("We could not connect to the server. Please check your internet connection and then try again.");
						console.error("AJAX Error 500");
					}
				});
				break;
			default:
				loaderImg();
				scrollToY(0);
				if ($("#jumbotron").length) { $("#jumbotron").animate({opacity: 0}, 300, function() { $("#jumbotron").remove(); }); }
				if ($("#content").length) { $("#content").animate({opacity: 0, height: 0}, 300, function() { $("#content").html(""); $("#content").removeAttr("data-scrollspy"); onResize(); }); }
				if ($("#scrollspy").length) { $("#scrollspy").animate({opacity: 0}, 300, function() { $("#scrollspy").remove(); }); }
				if ($("#header").hasClass("black-contrast")) { $("#header").animate({backgroundColor:"transparent"}, 300, function() { $("#header").removeClass("black-contrast"); }); }
				$("#loader").show().animate({opacity:1}, 500);
				var rtattr = {}; var attrchecked = 0;
				setTimeout(function() {
					$.ajax({
						type: "POST",
						url: ign_pathname + "route.php",
						data: "type=attr&route=" + route,
						cache: false,
						success: function(result) {
							if (result.indexOf("ERROR_") == -1) {
								if (result) {
									var res = JSON.parse(result);
									if (res.routetoattributes.indexOf("#") == 0) {
										rtattr["jumpTo"] = res.routetoattributes;
									}
									else if (attributes) {
										var rattr = attributes.split("/");
										var cattr = res.attributes.split("/");
										var crtattr = res.routetoattributes.split("/");
										if (rattr.length <= cattr.length) {
											for (var i = 0; i < rattr.length; i++) {
												rtattr[crtattr[i].split("=")[0]] = rattr[i];
											}
										}
									}
									attrchecked = 1;
								}
							}
							else {
								showError(parseInt(result.replace("ERROR_", "")));
								console.error("AJAX Error " + parseInt(result.replace("ERROR_", "")));
							}
						},
						error: function() {
							showError(500);
							console.error("AJAX Error 500");
						}
					}).done(function() {
						if (attrchecked && callbackresult.indexOf(".php") == -1) {
							$.ajax({
								type: "POST",
								url: ign_pathname + "page-renderer.php",
								data: "route=" + route + "&attr=" + encodeURIComponent(JSON.stringify(rtattr)),
								cache: false,
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										JSONRender(result, rtattr["jumpTo"]);
									}
									else {
										showError(parseInt(result.replace("ERROR_", "")));
										console.error("AJAX Error " + parseInt(result.replace("ERROR_", "")));
									}
								},
								error: function() {
									showError(500);
									console.error("AJAX Error 500");
								}
							})
						}
						else if (attrchecked && callbackresult.indexOf(".php") != -1) {
							$.ajax({
								type: "POST",
								url: ign_pathname + callbackresult,
								data: "route=" + route + "&attr=" + encodeURIComponent(JSON.stringify(rtattr)),
								cache: false,
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										PHPRender(result);
									}
									else {
										showError(parseInt(result.replace("ERROR_", "")));
										console.error("AJAX Error " + parseInt(result.replace("ERROR_", "")));
									}
								},
								error: function() {
									showError(500);
									console.error("AJAX Error 500");
								}
							});
						}
						setTimeout(function() { updateTranslr(); animateButtons(); }, 500);
					});
				}, 300);
				break;
		}
	}
	else {
		loaderImg();
		if ($("#jumbotron").length) { $("#jumbotron").animate({opacity: 0}, 300, function() { $("#jumbotron").remove(); }); }
		if ($("#content").length) { $("#content").animate({opacity: 0, height: 0}, 300, function() { $("#content").html(""); $("#content").removeAttr("data-scrollspy"); onResize(); }); }
		if ($("#scrollspy").length) { $("#scrollspy").animate({opacity: 0}, 300, function() { $("#scrollspy").remove(); }); }
		if ($("#header").hasClass("black-contrast")) { $("#header").animate({backgroundColor:"transparent"}, 300, function() { $("#header").removeClass("black-contrast"); }); }
		$("#loader").show().animate({opacity:1}, 500);
		showError((callbackresult.indexOf("ERROR_") == -1 ? 500 : parseInt(callbackresult.replace("ERROR_", ""))), 500);
		console.error("AJAX Error " + (callbackresult.indexOf("ERROR_") == -1 ? 500 : parseInt(callbackresult.replace("ERROR_", ""))));
	}
	setTimeout(function () { updateTranslr(); }, 500);
	curr_route = route;
	curr_routeto = callbackresult;
	curr_attr = attributes;
	window.history.pushState("", document.title, ign_pathname + curr_route + (curr_attr ? "/" + curr_attr : ""));
	closeVisit();
	createVisit(curr_route + (curr_attr ? "/" + curr_attr : ""));
}
function JSONRender(string, jumpTo) {
	if (string) {
		elementcount = 0;
		var jhtml = "", chtml = "", scripts = "";
		var obj = JSON.parse(string);
		$("title").removeAttr("data-translr-ptitle");
		if (obj.Title.indexOf("@") == 0) {
			$("title").attr("data-translr-ptitle", obj.Title);
		}
		else {
			$("title").html((obj.Title ? obj.Title + " - " : "") + "HAUFE Lexware Romania");
		}
		if (obj.PageContent.jumbotron) {
			jhtml += '<div id="jumbotron" class="full-size"';
			switch (obj.PageContent.jumbotron.type) {
				case "image":
					jhtml += ' style="' + (obj.PageContent.jumbotron.size == "small" ? "height:400px; " : "") + 'background-image:url(' + (obj.PageContent.jumbotron.src.length ? obj.PageContent.jumbotron.src : defaultimg) + ');">';
					break;
				case "video":
					jhtml += (obj.PageContent.jumbotron.size == "small" ? ' style="height:400px"' : '') + '><video class="full-size video-full" preload autoplay loop><source src="' + obj.PageContent.jumbotron.src + '"></source><embed src="' + obj.PageContent.jumbotron.src + '" class="video-full" autoplay loop></embed>Background video</video>';
					break;
			}
			jhtml += '<div class="blackfix">&nbsp;</div>';
			jhtml += '<div id="jumbo-text" class="jumbotron-container" data-center-x="true" data-center-y="true"><h1>' + obj.PageContent.jumbotron.h1 + '</h1>' + (obj.PageContent.jumbotron.divider == "true" ? '<div class="h-divider"></div>' : '') + (obj.PageContent.jumbotron.h2 ? '<h2>' + obj.PageContent.jumbotron.h2 + '</h2>' : '');
			if (obj.PageContent.jumbotron.buttons) {
				jhtml += '<br />';
				for (var i = 0; i < obj.PageContent.jumbotron.buttons.length; i++) {
					switch (obj.PageContent.jumbotron.buttons[i].type) {
						case "firststop":
							jhtml += '<button onclick="javascript:firstStop();" class="btn btn-jumbotron btn-round"><span id="btn-fs-glyph" class="fa fa-angle-down" data-center-x="true" data-center-y="true"></span></button>';
							break; 
						case "primary":
							jhtml += '<a href="#" data-ajax-go="' + obj.PageContent.jumbotron.buttons[i].href + '"><button class="btn btn-jumbotron">' + obj.PageContent.jumbotron.buttons[i].title + '</button></a> ';
							break;
					}
				}
			}
			jhtml += '</div></div>';
			$("body").prepend(jhtml);
			if (obj.PageContent.content) {
				elementcount = obj.PageContent.content.length;
				for (var i = 0; i < elementcount; i++) {
					var currel = obj.PageContent.content[i];
					var currhtml = "";
					switch (currel.type) {
						case "div":
							currhtml = '<div ' + (obj.PageContent.scrollspy ? 'data-scrollspy-id="content-' + i + '" ' : '') + (currel.id ? 'id="' + currel.id + '"' : '') + ' class="content-container-div ' + (currel.classes ? currel.classes + '"' : '"') + ' ' + (currel.custom_css ? 'style="' + currel.custom_css + '"' : '') + ' ' + (currel.title ? 'data-title="' + currel.title + '"' : '') + '>' + (currel.title ? '<h1' + (currel.title.indexOf("@") == 0 ? ' data-translr-value="' + currel.title + '">' : '>' + currel.title) + '</h1><div class="h-divider"></div>' : '') + '<div class="content-div"' + (B64.decode(currel.content).indexOf("@") == 0 ? ' data-translr-value="' + B64.decode(currel.content) + '">' : ' data-translr-check="true">' + B64.decode(currel.content)) + '</div></div>';
							if (currel.custom_scripts) {
								scripts += (currel.custom_scripts.indexOf('<script>') == -1 ? '<script>' : '') + currel.custom_scripts + (currel.custom_scripts.indexOf('</script>') == -1 ? '</script>' : '');
							}
							break;
						case "jobs":
							$.ajax({
								type: "POST",
								async: false,
								url: ign_pathname + "jobs.php",
								data: "scrollspy=" + (obj.PageContent.scrollspy ? 'true' : 'false') + "&id=" + i,
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										currhtml = result;
									}
								}
							});
							break;
						case "newsletter":
							$.ajax({
								type: "POST",
								async: false,
								url: ign_pathname + "newsletter.php",
								data: "scrollspy=" + (obj.PageContent.scrollspy ? 'true' : 'false') + "&id=" + i,
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										currhtml = result;
									}
								}
							});
							break;
						case "timeline":
							$.ajax({
								type: "POST",
								async: false,
								url: ign_pathname + "timeline.php",
								data: "scrollspy=" + (obj.PageContent.scrollspy ? 'true' : 'false') + "&id=" + i,
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										currhtml = result;
									}
								}
							});
							break;
						case "contact":
							$.ajax({
								type: "POST",
								async: false,
								url: ign_pathname + "contact.php",
								data: "scrollspy=" + (obj.PageContent.scrollspy ? 'true' : 'false') + "&id=" + i + (currel.email ? '&email=' + currel.email : ''),
								success: function(result) {
									if (result.indexOf("ERROR_") == -1) {
										currhtml = result;
									}
								}
							});
							break;
					}
					chtml += currhtml;
				}
				$("#content").prepend(scripts);
				$("#content").append(chtml);
				if (obj.PageContent.scrollspy) { $("#content").attr("data-scrollspy", "true"); createScrollspy(); }
			}
			$("#loader").animate({opacity:0}, 300, function() { $("#loader").hide(); });
			$("#jumbotron").show().animate({opacity:1}, 500);
			if ($("#jumbotron").length) { animateJumbotron(); }
			if ($("#content").children("*").length) { animateContent(); }
			if (jumpTo) { scrollToEl(jumpTo); }
		}
		else {
			showError(404);
			console.log("AJAX Error 404");
		}
	}
}
function PHPRender(string) {
	$("title").removeAttr("data-translr-ptitle");
	$("title").html("HAUFE Lexware Romania");
	$("#content").append(string);
	$("#content").css("top", 95);
	$("#loader").animate({opacity:0}, 300, function() { $("#loader").hide(); $("#header").animate({backgroundColor:navbarcolor}, 300, function() { $("#header").addClass("black-contrast"); }); });
	if ($("#content").children("*").length) { animateContent(); }
}
function showError(err_code, timeout) {
	setTimeout(function() {
		if (err_code.toString().substr(0, 1) == "5") {
			$("body").prepend('<div id="jumbotron" class="full-size"><div id="error-details" class="jumbotron-container" data-center-x="true" data-center-y="true"><h1>Server error</h1><div class="h-divider"></div><h2>We encountered a server error and could not load the page you requested. Perhaps you should check your internet connection and then try again.</h2></div></div>');
		}
		else if (err_code == 100) {
			$("body").prepend('<div id="jumbotron" class="full-size"><div id="error-details" class="jumbotron-container" data-center-x="true" data-center-y="true"><h1>Translr error</h1><div class="h-divider"></div><h2>We encountered a plugin error and could not load the page you requested. Please come back later.</h2></div></div>');
		}
		else {
			$("body").prepend('<div id="jumbotron" class="full-size"><div id="error-details" class="jumbotron-container" data-center-x="true" data-center-y="true"><h1 data-translr-value="@errors/' + err_code + '-title"></h1><div class="h-divider"></div><h2 data-translr-value="@errors/' + err_code + '-description"></h2></div></div>');
		}
		$("#loader").animate({opacity:0}, 300, function() { $("#loader").hide(); });
		$("#jumbotron").show().animate({opacity:1}, 500);
		if ($("#jumbotron").length) { animateJumbotron(); }
		if ($("#content").children("*").length) { animateContent(); }
	}, timeout);
}
function sendAJAXForm(fid) {
	if ($("#form-" + fid).length && $("#form-" + fid).attr("data-type")) {
		var formData = new FormData();
		switch ($("#form-" + fid).attr("data-type")) {
			case "contact":
				formData.append("email_to", $("#form-" + fid).attr("data-email"));
				formData.append("name", $("#f-" + fid + "-name").val());
				formData.append("email", $("#f-" + fid + "-email").val());
				formData.append("subject", $("#f-" + fid + "-subject").val());
				if ($("#form-" + fid).attr("data-file-upload") == "true") { formData.append("file", document.getElementById("f-" + fid + "-file").files[0]); }
				formData.append("message", $("#f-" + fid + "-message").val());
				break;
			case "newsletter":
				formData.append("email", $("#f-" + fid + "-email").val());
				break;
		}
		$.ajax({
			url: ign_pathname + $("#form-" + fid).attr("data-action"),
			type: 'POST',
			data: formData,
			success: function(result) {
				switch ($("#form-" + fid).attr("data-type")) {
					case "contact":
						$("#form-" + fid).animate({opacity:0}, function() { $(this).parent().append('<span data-translr-value="@contact/success"></span>'); updateTranslr(); $(this).remove(); });
						break;
					case "newsletter":
						$("#form-" + fid).animate({opacity:0}, function() { $(this).parent().append('<span data-translr-value="@newsletter/success"></span>'); updateTranslr(); $(this).remove(); });
						var d = new Date();
						d.setDate(d.getFullYear() + 1);
						setCookie("newsletter", "true", d, "/");
						break;
				}
			},
			error: function() {
				switch ($("#form-" + fid).attr("data-type")) {
					case "contact":
						$("#form-" + fid).before('<span data-translr-value="@contact/try-again"></span><br />');
						break;
					case "newsletter":
						$("#form-" + fid).before('<span data-translr-value="@newsletter/try-again"></span><br />');
						break;
				}
				updateTranslr();
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}
	else {
		console.error("Bad AJAX Form submit!");
	}
}