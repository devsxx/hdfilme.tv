//####################################################
//####################################################
(function($)
{$(document).ready(function()
{$('body').append('<div id="go_to_top"></div>');$(window).scroll(function(){if($(window).scrollTop()!=0){$('#go_to_top').fadeIn();}else{$('#go_to_top').fadeOut();}});$('#go_to_top').click(function(){$('html, body').animate({scrollTop:0},500);});$(".hideit").click(function()
{$(this).fadeOut();});$('.lightbox').nstUI({method:'lightbox'});$('.form_action').each(function()
{$(this).nstUI({method:'formAction',formAction:{field_load:$(this).attr('_field_load')}});});$('.verify_action').nstUI({method:'verifyAction'});$('[_tooltip]').nstUI({method:'tooltip'});$('[_dropdown]').nstUI({method:'dropdown'});$('input[placeholder]').nstUI({method:'placeholder'});$('.accordion').each(function()
{var _t=$(this);_t.nstUI({method:'accordion',accordion:{type:_t.attr('_accordion_type')}});});$('.auto_check_pages').each(function()
{auto_check_pages($(this));});$('.format_currency').formatCurrency({roundToDecimalPlace:0,symbol:''});$('.format_currency').blur(function()
{$(this).formatCurrency({roundToDecimalPlace:0,symbol:''});});$('.tags').each(function()
{var _t=$(this);var setting={'width':'100%','defaultText':_t.attr('_tags_text')};var ac_url=_t.attr('_tags_ac');if(ac_url)
{setting.autocomplete_url=ac_url}
_t.tagsInput(setting);});var cache={},lastXhr;$('.autocomplete').each(function()
{var url_search=$(this).attr('_url');$(this).autocomplete({minLength:2,source:function(request,response)
{var term=request.term;if(term in cache)
{response(cache[term]);return;}
lastXhr=$.getJSON(url_search,request,function(data,status,xhr)
{cache[term]=data;if(xhr===lastXhr)
{response(data);}});}});});$('.toggle_next').click(function()
{$(this).next().slideToggle();return false;});$('.toggle_next2').click(function()
{$(this).hide();$(this).next().slideToggle();return false;});var uri=window.location.href.split('#uri=');if(uri[1])
{$.colorbox({href:admin_url+uri[1],opacity:0.75});}
$('.btn-login-mobile').click(function()
{var _t=$(this);var url=_t.attr('href')+'?return='+ window.location.href;window.location.href=url;return false;});});})(jQuery);//####################################################
//####################################################
function load_ajax(_t)
{var field=jQuery(_t).attr('_field');var url=jQuery(_t).attr('_url');jQuery(_t).nstUI({method:"loadAjax",loadAjax:{url:url,field:{load:field+'_load',show:field+'_show'}}});return false;}
function temp_set_value(html,params)
{jQuery.each(params,function(param,value)
{var regex=new RegExp('{'+param+'}',"igm");html=html.replace(regex,value);});return html;}
function copy_value(from,to)
{jQuery(this).nstUI({method:'copyValue',copyValue:{from:from,to:to}});}
function lightbox(t)
{jQuery(t).nstUI({method:'lightbox'});}
function auto_check_pages(t)
{if(t.find('a')[0]==undefined)
{t.remove();}}
function load_account_panel()
{jQuery(this).nstUI({method:"loadAjax",loadAjax:{url:site_url+'user/account_panel',field:{load:'_',show:'account_panel'}}});}
function change_captcha(field)
{var t=jQuery('#'+field);var url=t.attr('_captcha')+'?id='+Math.random();t.attr('src',url);return false;}//####################################################
//####################################################
function doc_widget_call(widget)
{jQuery('#doc_'+widget+' .doc_widget_load a').click(function()
{var url=jQuery(this).attr('href');doc_widget_load(widget,url);return false;});}
function doc_widget_load(widget,url)
{jQuery(this).nstUI({method:"loadAjax",loadAjax:{url:url,field:{load:'',show:'doc_'+widget},event_complete:function(data,setting)
{jQuery.scrollTo('#'+setting.field.show,800);}}});}