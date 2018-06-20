(function($)
{
	$(document).ready(function()
	{
		// Khai bao cac bien
		var upload_info = $(".upload_single_image").find('.upload_info');
		var upload_error = $(".upload_single_image").find('.upload_error');
		var upload_action = $(".upload_single_image").find('.upload_action');
		
		var uploader = new plupload.Uploader({
			runtimes: 'gears,html5,flash,silverlight,browserplus',
			browse_button: 'action_upload',
			max_file_size: '80mb',
			url: "/user/avatar",
			multi_selection: false,
			flash_swf_url: themeUrl + '/pupload/plupload.flash.swf',
			silverlight_xap_url: themeUrl + '/pupload/plupload.silverlight.xap',
			filters: [ {title : "Files", extensions: "gif,jpg,png"} ]
		});
		
		
		// Khi file duoc chon
		uploader.bind('FilesAdded', function(up, files)
		{
			// Hien thi thong tin file
			var params = {
							file_name: files[0].name,
							file_size: plupload.formatSize(files[0].size),
							file_progress: '0'
						};
			upload_info.html(temp_set_value($(".upload_single_image").find('#temp #upload_info').html(), params)).show();
			
			// An thong tin loi
			upload_error.hide();
			
			// An nut upload
			upload_action.hide();
			
			// Reposition Flash/Silverlight
			up.refresh();
		});

		// Khi file duoc chon xong
		uploader.bind('QueueChanged', function(up)
		{
			// Bat dau upload
			uploader.start();
		});

		// Upload progress
		uploader.bind('UploadProgress', function(up, file)
		{
			// Cap nhat progress
			upload_info.find('.progress').css('width', file.percent+'%');
		});
		
		// Upload hoan thanh
		uploader.bind('FileUploaded', function(up, files, object)
		{
			var info = JSON.parse(object.response);
			if(info.result == 1) {
				$(".upload_single_image").find(".upload_complete a").attr("href", info.avatar);
				$(".upload_single_image").find(".upload_complete a img").attr("src", info.avatar);
				// An thong tin loi
				upload_error.hide();
				// Hien thi nut upload
				upload_action.show();
			} else {
				upload_error.html('<div class="error f12">' + info.error + '</div>').show();
			}

			// An thong tin file upload
			upload_info.hide();

		});
		
		// Error
		uploader.bind('Error', function(up, err)
		{
			// Hien thi loi
			var params = {
							file_error: err.message,
							file_name: err.file.name,
							file_size: plupload.formatSize(err.file.size)
						};
			upload_error.html(temp_set_value($(".upload_single_image").find('#temp #upload_error').html(), params)).show();
			
			// An thong tin file
			upload_info.hide();
			
			// Hien thi nut upload
			upload_action.show();

			// Reposition Flash/Silverlight
			up.refresh();
		});
		
		// Khoi dong uploader
		uploader.init();

	});

})(jQuery);