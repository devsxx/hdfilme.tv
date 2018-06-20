var m_default = {
    method:	'',
    tableRowCss: {even:'even', odd:'odd'},
    lightbox: {opacity: 0.75},
    loadAjax: {url:'', data:'', field:{load:'', show:''}, datatype:'html', event_complete:'', event_error:''},
    formAction: {action:'', field_load:'', submit:false, event_submit:'', event_error:'', event_complete:'', loading:false},
    verifyAction: {field:'', event_complete:''},
    placeholder: {},
    toggleTab: {field:'', effect:'', duration:0},
    tooltip: {},
    dropdown: {},
    copyValue: {from:'', to:''},
    accordion: {type:''},
    tabs: {effect:'', duration:0}
};
function deleteAvatar(element) {
    $.ajax({
        url: '/user/avatar',
        type: 'DELETE',
        data: {id: $(element).attr("avatar-id")},
        success: function(result) {
            // Do something with the result
            $(".upload_single_image .upload_complete a").attr("href", result.avatar);
            $(".upload_single_image .upload_complete a img").attr("src", result.avatar);
        }
    });
}
function favorite(element, type) {
    type = typeof type == "undefined" ? 0 : 1;
    var movieID = $(element).attr("movie-id");
    $.ajax({
        url: '/movie/favorite/' + movieID,
        type: 'POST',
        dataType: 'JSON',
        data: { elem: $(element).attr("id"), type: type},
        success: function(result) {
            // Do something with the result
            if(result.result == 1) {
                var $element = $("#" + result.elem);
                var $addButtons = $('.btn-favorite[movie-id="'+movieID+'"]');
                if(type == 0) {
                    if (result.active) {
                        $addButtons.addClass('active');
                    } else {
                        $addButtons.removeClass('active');
                    }
                } else {
                    $('.favourite-movies-total').text(
                        parseInt($('.favourite-movies-total').text()) - 1
                    )
                    $element.parents(".box-product").remove();
                }
            }
        }
    });
}
function watchLater(element, type) {
    type = typeof type == "undefined" ? 0 : 1;
    var movieID = $(element).attr("movie-id");
    $.ajax({
        url: '/movie/watch-later/' + movieID,
        type: 'POST',
        dataType: 'JSON',
        data: { elem: $(element).attr("id"), type: type},
        success: function(result) {
            // Do something with the result
            if(result.result == 1) {
                var $element = $("#" + result.elem);
                var $addButtons = $('.btn-watchlater[movie-id="'+movieID+'"]');
                if(type == 0) {
                    if (result.active) {
                        $addButtons.addClass('active');
                    } else {
                        $addButtons.removeClass('active');
                    }
                } else {
                    $element.removeClass('active').parents(".box-product").remove();
                }
            }
        }
    });
}
function lightbox(elem)
{
    var _t = $(elem);
    var lightbox_setting = new Array();

    var url = _t.attr('href');
    var url_arr = url.split('?lightbox&');
    if (url_arr[1])
    {
        var settings = url_arr[1].split('&');
        for (var i = 0; i < settings.length; i++)
        {
            var key_value = settings[i].split('=');
            if (key_value[1])
            {
                if (key_value[1] == 'true') key_value[1] = true;
                else if(key_value[1] == 'false') key_value[1] = false;

                lightbox_setting[key_value[0]] = key_value[1];
            }
        }
    }
    lightbox_setting['href'] = url_arr[0];

    settings = $.extend({}, m_default.lightbox, lightbox_setting);
    _t.colorbox(settings);

    return false;
}
function temp_set_value(html, params)
{
    jQuery.each(params, function(param, value)
    {
        var regex = new RegExp('{'+param+'}', "igm");
        html = html.replace(regex, value);
    });

    return html;
}