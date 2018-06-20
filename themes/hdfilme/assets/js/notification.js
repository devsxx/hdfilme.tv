$(document).ready(function () {

	var $webNotif = $('#notifyMessage');
	var $webNotifCount = $webNotif.find('.haveNotifications');
	var $webNotifList = $webNotif.find('.tn-dropdown-txt');
	var newNotif = false;

	function checkWebNotification() {
		$.ajax({
			url: globalVars.urls.webNotifCount,
			method: "GET",
			contentType: "application/json",
			dataType: "json",
		}).done(function(content) {
			if (content.count > 0) {
				newNotif = true;
				$webNotifCount.removeClass('hidden')
				$webNotifCount.html(content.count);
			} else {
				$webNotifCount.addClass('hidden')
				$webNotifCount.html('0');
				newNotif = false;
			}
		});
	}

	function getListNotification() {
		$.ajax({
			url: globalVars.urls.webNotifList,
			method: "GET",
			contentType: "application/json",
			dataType: "json",
		}).done(function(content) {
			var notifListHtml = "";
			var listCount = content.length;
			if (listCount > 0) {
				$webNotifCount.text(0);
				for (var i = 0; i < listCount; i++) {
					notifListHtml = notifListHtml + '<li><a target="_blank" href="'+content[i].redirect_url+'"><h5>'+content[i].title+'</h5>'+content[i].content+'<br><span class="createTime">'+(content[i].created_at ? content[i].created_at : '')+'</span></a></li>';
				}
				$webNotifList.html(notifListHtml);
				newNotif = false;
			}
		});
	}

	if (isMember) {
		checkWebNotification();
		setInterval(checkWebNotification, 600000);
		var firstLoadNotif = true;
		$webNotif.find('[data-toggle="dropdown"]').click(function() {
			if ($(this).attr('aria-expanded') != "true") {
				checkWebNotification();
				if (newNotif || firstLoadNotif) {
					getListNotification();
					firstLoadNotif = false;
				}
			}
		});
	}

	
	checkBrowserNotification();
	setInterval(checkBrowserNotification, 600000);
})