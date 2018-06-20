
function change_captcha(capchaImage) {

	var $capchaItem=jQuery('.'+capchaImage);
	var newSrc=$capchaItem.attr('captcha_link')+'?'+Math.random().toString(36).substring(2,10);;
	$capchaItem.attr('src',newSrc);
	return false;
}

function popupLogin(url) {

	if (!isMobileDevice) {

		var htmlContent = $('#login-dialog.color-box-content').html();
		$.colorbox({html:htmlContent});
		return false;

	} else {

		if (confirm(globalVars.resources.mustLogin)) {

			window.location = url;
		}
	}
}

function popupPayment(url) {

	if (!isMobileDevice) {

		var htmlContent = '<div class="header">'+globalVars.resources.vipTryPayment+'</div>';
		htmlContent = htmlContent + '<p class="mb5">'+globalVars.resources.buyVipMessage+'</p>';
		htmlContent = htmlContent + '<p> - '+globalVars.resources.benefit1+'</p>';
		htmlContent = htmlContent + '<p> - '+globalVars.resources.benefit2+'</p>';
		htmlContent = htmlContent + '<p> - '+globalVars.resources.benefit3+'</p>';
		htmlContent = htmlContent + '<p> - '+globalVars.resources.benefit4+'</p>';
		htmlContent = htmlContent + '<p class="tryVip"><a href="'+url+'" class="buttonStyle">'+globalVars.resources.buyVip+'</a></p>';
		htmlContent = '<div class="smallPopup content popupVip">' + htmlContent + '</div>'
		$.colorbox({html:htmlContent});
		return false;

	} else {

		if (confirm(globalVars.resources.vipTryPayment)) {

			window.location = url;
		}
	}
}


function checkBrowserNotification() 
{
	if (Notification.permission !== "granted" )
	{
		Notification.requestPermission();
	}
	else
	{

		$.get( "/notification/browser", function( data ) {

			if (typeof data.id !== 'undefined') {
			    var notification = new Notification(data.title, {
					icon: data.icon,
					body: data.message,
				});

				/* Remove the notification from Notification Center when clicked.*/
				notification.onclick = function () {
					window.open(data.redirect_url); 
				};

				/* Callback function when the notification is closed. */
				notification.onclose = function () {
					console.log('Notification closed');
				};


			}

		});
	}
}

$(document).ready(function () {

	checkBrowserNotification();
	setInterval(checkBrowserNotification, 600000);

})