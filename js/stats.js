/*
Statistics Library for Haufe Lexware Romania
All rights reserved Mihai-Alin Eparu 2015.
*/
var visid = "";
function createVisit(page) {
	if (page) {
		$.ajax({
			url: "stats.php",
			type: "POST",
			data: "create=visit&page=" + page,
			success: function(result) {
				visid = result;
			}
		});
	}
}
function closeVisit() {
	$.ajax({
		url: "stats.php",
		type: "POST",
		data: "close=true" + (visid.length ? "&visid=" + visid : ""),
		async: false
	});
}
