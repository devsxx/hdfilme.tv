$(document).ready(function () {

	$('.rateit').rateit({step: 1,max:5});
	$(".rateit").bind('rated', function (event, value) {
		$.ajax({
					async: false,
					type: "POST",
				url: $(this).attr('data-url'),
				data: {'score':value},
				success: function(data)
				{
								alert(data.msg);
				}
				});
			
		$('[name=rate]').val(value);
	});
	
})