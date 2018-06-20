
$(document).ready(function() {

	$(".box-product").popover({
		title: function () {
			var pop_dest = $(this).attr("data-popover");
			return $("#"+pop_dest + " .popover-title").html();
		},
		content: function () {
			var pop_dest = $(this).attr("data-popover");
			return $("#"+pop_dest + " .popover-content").html();
		},
		placement: 'left auto ',
		trigger: 'click',
		container: 'body',
		html: true
	});
	var timeOutPopOver;
	var currentCarousel = '';
	$(".box-product").hover(function() {
		currentCarousel = $(this).closest('.carousel').attr('id');
		clearTimeout(timeOutPopOver);
		$this = $(this);
		$(".box-product").not(this).popover('hide');
		if (!$this.attr('aria-describedby')) {
			$this.popover('show');
		}
	}, function() {
		var $this = $(this);
		timeOutPopOver = setTimeout(function(){
			$this.popover('hide');
			$('#'+currentCarousel).carousel('cycle');
		}, 300);
	});

	$(document).on({
		mouseenter: function () {
			clearTimeout(timeOutPopOver);
			$('#'+currentCarousel).carousel('pause');
		},
		mouseleave: function () {
			var $this = $(this);
			timeOutPopOver = setTimeout(function(){
				$this.popover('hide');
				$('#'+currentCarousel).carousel('cycle');
			}, 300);
		}
	}, '.popover');

	//hide all pop over of box-product on click outside
	$(document).mouseup(function (e){
		var container = $('.popover');
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.popover('hide');
			$('#'+currentCarousel).carousel('cycle');
		}
	});
	var $slimScrollItem = $('.widget-product .slimScroll');
	var heightSlimScroll = parseInt($slimScrollItem.css('max-height'));
	$slimScrollItem.each(function() {
		var $this = $(this);
		if ($this.outerHeight() < heightSlimScroll) {
			$this.removeClass('slimScroll')
		}
	});
	//query new slimScrollItem and apply slimScroll
	var $slimScrollItem = $('.widget-product .slimScroll');
	$slimScrollItem.slimScroll({
		height: heightSlimScroll,
		color:'#68c939',
		wheelStep: 5,
		alwaysVisible: true
	});
})