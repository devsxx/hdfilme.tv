/*
 * This is a sample JavaScript file used by {{ name }}
 *
 * You can delete this file if you want
 */



$(document).ready(function () {


	$('.btn-get-film-info').on('click', function () {
	    $(this).before('<div class="loading-indicator size-small"><span></span></div>')

	    $(this).request('onGetFilmInfo', {
	        data: {
	            link_filmstart: $('#link_filmstart').val()
	        },
	        success: function (response) {
	            var result = JSON.parse(response.result)

	            if (result.error)
	        		$('#link-error').text(result.error)
	        	else
	        		$('#link-error').text('')

	            var seoOptions = { 
	                'translitarate': true, 
	                'uppercase': false,
	                "lowercase": true, 
	                "divider": '-' 
	            }

	            result.friendly_url = result.name.seoURL(seoOptions)

	            // set value to other fields
	            for (let key in result) {
	                var input = $('input[name="Movie[' + key + ']"]')

	                if (!input.length)
	                    input = $('textarea[name="Movie[' + key + ']"]')

	                input.val(result[key])
	            }

	            processArrayToSelect2(result.genre, 'categories', true)
	            processArrayToSelect2(result.producer, 'producers', true)
	            processArrayToSelect2(result.director, 'directors', true)
	            processArrayToSelect2(result.actor, 'actors', true)
	            processArrayToSelect2(result.country ? result.country : 'USA', 'country_id')

	            enableShowHideOriginFilmInfo(result.image)

	        },
	        error: function (err) {
	        	$('#link-error').text(err.responseJSON.error)
	        },
	        complete: function () {
	            $('.film-start .loading-indicator').remove()
	        }
	    })
	})


	function processArrayToSelect2 (value, name, multi)
	{

		if (!value)
			value = ''

		var arr = value.split(',')

		var select = $('select[name="Movie[' + name + ']' + (multi ? '[]' : '') + '"]')

		select.find('option').remove()

		arr.forEach(function(item) {
			select.append('<option value="' + item + '">' + item + '</option>')
		})

		select.val(arr).trigger('change')
	}


	function enableShowHideOriginFilmInfo (image) {

	    //show button Hiện thông tin phim gốc
	    $('#origin_poster').attr('src', image).parent().css('display', 'inline')
	    
	    $('#show_hide_film_info').css('display', 'inline').on('click', function () {

	        if ($(this).hasClass('show-film-info')) {
	            $(this).text('Hiện thông tin phim gốc').addClass('show-film-info')
	        } else {
	            $(this).text('Ẩn thông tin phim gốc').removeClass('show-film-info')
	        }

	        $('#origin_film_info').toggle()
	    })
	}
})
