
var currentKeyword="";
var searching = false; 

function doSearch() {

	if (isMobileDevice){
		return;
	}

	if(searching){

		return;
	}

	keyword = $('#headerSearch #search_t').val();
	if(keyword && keyword != currentKeyword){
		currentKeyword = keyword;


		$('#headerSearch').addClass('active');
		var linkShowMore ='/movie-search',
			searching = true;
		$.ajax({
			url: linkShowMore,
			data: {key: keyword, getInfo: 1},
			method: "POST",
			dataType: "json",
		}).done(function(content) {
			var suggestionHtml = "";
			$('#headerSearchSuggestion').html('');
			for (var key in content) {
				if (content.hasOwnProperty(key)&& content[key].length>0) {
					suggestionHtml = suggestionHtml + '<li class="searchTitle"></li>';
					var endLoop = content[key].length > 5? 5:content[key].length;
					for (var i = 0; i < endLoop; i++) {
						var miniThumb = content[key][i].image == ''?globalVars.urls.miniThumb : content[key][i].image;
						suggestionHtml = suggestionHtml + '<li><a href="'+content[key][i].link+'">'+'<img class="miniThumb" src="'+miniThumb+'" onerror="this.onerror=null;this.src=\''+globalVars.urls.miniThumb+'\';">'+content[key][i].name+'</a></li>';
					}
				}
			}
			var suggestionWrapper = '<ul class="suggestList">'+suggestionHtml+'</ul>';
			linkShowMore += '?key=' + keyword;
			suggestionWrapper = suggestionWrapper + '<p class="linkShowMore"><a href="'+linkShowMore+'">'+globalVars.resources.showMore+'</a></p>'
			$(suggestionWrapper).appendTo($('#headerSearchSuggestion'));

			searching=false;
		});

	}

}

$(document).ready(function () {

	var timer = null;

	$('#headerSearch #search_t').on('keyup', function () {
		clearTimeout(timer)
		timer = setTimeout(doSearch, 600)
	}).on('keydown', function () {
		clearTimeout(timer)
	})


	// handle search sugestion
	
	if ($().slimScroll) {
		$('.slimScrollGlobal').slimScroll({
			height: '205px',
			color:'#68c939',
			wheelStep: 10,
			alwaysVisible: true
		});
	};
});