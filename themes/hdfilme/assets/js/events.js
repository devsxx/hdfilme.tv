$(document).ready(function() {

	$('body').append('<div id="go_to_top"></div>');

	$('#go_to_top').hide();

	$(window).on('load scroll',function(e){
		if ($(window).scrollTop() > 500) {
			$('#go_to_top').fadeIn();
		} else {
			$('#go_to_top').fadeOut();
		}
	});

	$('#go_to_top').click(function() {

		$('html, body').animate({
			scrollTop: 0
		}, 500);

	});

	$('.hide_me').click(function() {
		$(this).fadeOut();
	});

	$('#navbar-collapse .btn-opacity').click(function() {
		$('[data-target="#navbar-collapse"]').trigger('click');
	});

	$('.btn-s').click(function () {
		$('.form-search').toggleClass('active')
	});
	
	$('.btn-search').click(function () {
		$('.form-search').toggleClass('active')
	});
	
	$(document).on('click', '.color-box-button', function(){
		if ($(window).width()>767 || !$(this).data('color-box-desktop-only')) {
			
			var htmlContent = $(this).parent().find('.color-box-content').html();
			if (!htmlContent) {
				target = $(this).attr('href');
				htmlContent = $(target).html();

			}

			if ($(this).attr('name') === 'movie_request')
				$.colorbox({inline:true, href: '#movie_request'})
			else
				$.colorbox({html:htmlContent});

			return false;
		}
	});

	$(document).on('click', '#form-ordering .order-up, #form-ordering .order-down', function(){
		$(this).addClass('active').siblings('a').removeClass('active');
		$(this).siblings('input[name="order_d"]').val($(this).attr('value'));
	});

	function updateQueryStringParameter(uri, key, value) {
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		var separator = uri.indexOf('?') !== -1 ? "&" : "?";
		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		}
		else {
			return uri + separator + key + "=" + value;
		}
	}

	$(document).on('click', '.pagination-append-url a[data-page]', function(){
		var targetPage = $(this).data('page');
		var urlCurrent = window.location.href;
		var hashPosition = urlCurrent.indexOf('#');
		var hashBackup = '';
		if (hashPosition > 0) {
			hashBackup = urlCurrent.substr(hashPosition,urlCurrent.length - hashPosition);
			urlCurrent = urlCurrent.substr(0,hashPosition);
		}
		var pageName = $(this).data("page-name");
		pageName = typeof pageName != "undefined" && pageName != "" ? pageName : "page";
		var targetUrl = updateQueryStringParameter(urlCurrent, pageName, targetPage);
		window.location = targetUrl + hashBackup;
	});

	$(document).on('click', '.toggle-parent', function(){
		$(this).parent().toggleClass('active');
		return false;
	});

	$(document).on( "mouseup touchend", function(e){
		var container = $('.toggle-parent').parent();
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.removeClass('active');
		}
	});


	$(document).on( "mouseup touchend", function(e){
		var container = $('.click-outside-remove-active');
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.removeClass('active open');
		}
	});

	$(document).on('click', '.navbar-menu .avatar .username', function(){
		$(this).closest('.avatar').toggleClass('active');
		return false;
	});


	//tab

	$(document).on('click', '.tab-wrapper [data-tab-button]', function(){
		var $this = $(this);
		var $tabWrapper = $this.closest('.tab-wrapper');
		var targetSelector = $this.attr('href');
		$tabWrapper.find('[data-tab-button]').removeClass('active');
		$tabWrapper.find('[data-tab-content]').removeClass('active');
		$this.addClass('active');
		$tabWrapper.find(targetSelector).addClass('active');
		return false;
	});

	$(document).on('submit','form.require-one-field',function(){
		var isFormValid = false;

		$(this).find("input[type=text]").each(function(){
			if ($.trim($(this).val()).length != 0){
				isFormValid = true;
			}
		});
		$(this).find("input[type=checkbox]:checked, input[type=radio]:checked").each(function(){
			isFormValid = true;
		});
		if (!isFormValid) {
			$(this).addClass('formTotalError');
		};
		return isFormValid;
	});

	$('[data-prevent-rate=true]').on('beforerated', function (e, value) {
		e.preventDefault();
	});

	
});