$(document).on("turbolinks:load",function(){"use strict";$(function(){$(".kit__customScroll").length&&!/Mobi/.test(navigator.userAgent)&&jQuery().perfectScrollbar&&$(".kit__customScroll").perfectScrollbar({theme:"kit"}),$("[data-toggle=tooltip]").tooltip(),$("[data-toggle=popover]").popover()})}),$(document).on("turbolinks:load",function(){"use strict";$(function(){if($("body").find(".juzaweb__menuLeft").length<1)return;for(var e=window.location.href,t=1;t<=10;){var a=$(".juzaweb__menuLeft").find('a[href="'+e+'"]');if(a.length>0){a.addClass("juzaweb__menuLeft__item--active").closest(".juzaweb__menuLeft__submenu").addClass("juzaweb__menuLeft__submenu--toggled").find("> .juzaweb__menuLeft__navigation").show();break}var n=e.split("/");if(!(e=e.replace("/"+n[n.length-1],"")))break;t++}!function(){var e=!1;function t(){e||$("body").addClass("juzaweb__menuLeft--toggled")}$(window).innerWidth()<=992&&(t(),e=!0),$(window).on("resize",function(){$(window).innerWidth()<=992?(t(),e=!0):e=!1})}(),$(".juzaweb__menuLeft__trigger").on("click",function(){$("body").toggleClass("juzaweb__menuLeft--toggled")}),$(".juzaweb__menuLeft__backdrop, .juzaweb__menuLeft__mobileTrigger").on("click",function(){$("body").toggleClass("juzaweb__menuLeft--mobileToggled")});var o=0,s=!1;const i=e=>e.changedTouches?e.changedTouches[0]:e;document.addEventListener("touchstart",e=>{const t=i(e).clientX;o=t,s=t>70},{passive:!1}),document.addEventListener("touchmove",e=>{i(e).clientX-o>50&&!s&&($("body").toggleClass("juzaweb__menuLeft--mobileToggled"),s=!0)},{passive:!1})})}),$(document).on("turbolinks:load",function(){"use strict";$(function(){if($("body").find(".juzaweb__menuTop").length<1)return;var e=window.location.href,t=e.substr(e.lastIndexOf("/")+1),a=$(".juzaweb__menuTop").find('a[href="'+t+'"]');console.log(t),a.addClass("juzaweb__menuTop__item--active").parents(".juzaweb__menuTop__submenu").addClass("juzaweb__menuTop__submenu--toggled").find("> .juzaweb__menuTop__navigation").show(),$(".juzaweb__menuTop__backdrop, .juzaweb__menuTop__mobileTrigger").on("click",function(){$("body").toggleClass("juzaweb__menuTop--mobileToggled")});var n=0,o=!1;const s=e=>e.changedTouches?e.changedTouches[0]:e;document.addEventListener("touchstart",e=>{const t=s(e).clientX;n=t,o=t>70},{passive:!1}),document.addEventListener("touchmove",e=>{s(e).clientX-n>50&&!o&&($("body").toggleClass("juzaweb__menuTop--mobileToggled"),o=!0)},{passive:!1}),$(".juzaweb__menuTop__submenu > .juzaweb__menuTop__item__link").on("click",function(){if($(window).innerWidth()<768){var e=$(this).closest(".juzaweb__menuTop__submenu"),t=$(".juzaweb__menuTop__submenu--toggled");e.hasClass("juzaweb__menuTop__submenu--toggled")||e.parent().closest(".juzaweb__menuTop__submenu").length||t.removeClass("juzaweb__menuTop__submenu--toggled").find("> .juzaweb__menuTop__navigation").slideUp(200),e.toggleClass("juzaweb__menuTop__submenu--toggled");var a=e.find("> .juzaweb__menuTop__navigation");a.is(":visible")?a.slideUp(200):a.slideDown(200)}})})}),$(document).on("turbolinks:load",function(){"use strict";$(function(){function e(e){window.localStorage.setItem("appName",e);var t=$(".juzaweb__menuLeft").length?$(".juzaweb__menuLeft__logo__name"):$(".juzaweb__menuTop__logo__name"),a=$(".juzaweb__menuLeft").length?$(".juzaweb__menuLeft__logo__descr"):$(".juzaweb__menuTop__logo__descr");t.html(e),"Clean UI Pro"!==e?a.hide():a.show()}$(".juzaweb__menuTop").length&&$(".hideIfMenuTop").css({pointerEvents:"none",opacity:.4}),$(".juzaweb__sidebar__actionToggle").on("click",function(){$("body").toggleClass("juzaweb__sidebar--toggled")}),$(".juzaweb__sidebar__actionToggleTheme").on("click",function(){var e=document.querySelector("html").getAttribute("data-kit-theme");"dark"===e&&(document.querySelector("html").setAttribute("data-kit-theme","default"),$("body").removeClass("kit__dark juzaweb__menuLeft--gray juzaweb__menuTop--gray juzaweb__menuLeft--dark juzaweb__menuTop--dark")),"default"===e&&(document.querySelector("html").setAttribute("data-kit-theme","dark"),$("body").removeClass("juzaweb__menuLeft--gray juzaweb__menuTop--gray juzaweb__menuLeft--dark juzaweb__menuTop--dark"),$("body").addClass("juzaweb__menuLeft--dark juzaweb__menuTop--dark"))}),$("#appName").on("keyup",function(t){e(t.target.value)});var t=window.localStorage.getItem("appName");function a(e){function t(e){window.localStorage.setItem("kit.primary",e);var t=`:root { --kit-color-primary: ${e};}`;$("<style />").attr("id","primaryColor").text(t).prependTo("body")}var a=$("#primaryColor");a?(a.remove(),t(e)):t(e)}t&&(e(t),$("#appName").val(t));var n=window.localStorage.getItem("kit.primary");n&&($("#colorPicker").val(n),a(n),$("#resetColor").parent().removeClass("reset")),$("#colorPicker").on("change",function(){a($(this).val()),$("#resetColor").parent().removeClass("reset")}),$("#resetColor").on("click",function(){window.localStorage.removeItem("kit.primary"),$("#primaryColor").remove(),$("#resetColor").parent().addClass("reset")}),$(".juzaweb__sidebar__switch input").on("change",function(){var e=$(this),t=e.is(":checked"),a=e.attr("to"),n=e.attr("setting");t?$(a).addClass(n):$(a).removeClass(n)}),$(".juzaweb__sidebar__switch input").each(function(){var e=$(this),t=e.attr("to"),a=e.attr("setting");$(t).hasClass(a)&&e.attr("checked",!0)}),$(".juzaweb__sidebar__select__item").on("click",function(){var e=$(this),t=e.parent(),a=t.attr("to"),n=e.attr("setting"),o=t.find("> div"),s="";o.each(function(){var e=$(this).attr("setting");e&&(s=s+" "+e)}),o.removeClass("juzaweb__sidebar__select__item--active"),e.addClass("juzaweb__sidebar__select__item--active"),$(a).removeClass(s),$(a).addClass(n)}),$(".juzaweb__sidebar__select__item").each(function(){var e=$(this),t=e.parent(),a=t.attr("to"),n=e.attr("setting"),o=t.find("> div");$(a).hasClass(n)&&(o.removeClass("juzaweb__sidebar__select__item--active"),e.addClass("juzaweb__sidebar__select__item--active"))}),$(".juzaweb__sidebar__type__items input").on("change",function(){var e=$(this),t=e.is(":checked"),a=e.attr("to"),n=e.attr("setting");$("body").removeClass("juzaweb__menu--compact juzaweb__menu--flyout juzaweb__menu--nomenu"),t?$(a).addClass(n):$(a).removeClass(n)}),$(".juzaweb__sidebar__type__items input").each(function(){var e=$(this),t=e.attr("to"),a=e.attr("setting");$(t).hasClass(a)&&e.attr("checked",!0)})})}),$(document).on("turbolinks:load",function(){"use strict";$(function(){$(".kit__chat__actionToggle").on("click",function(){$("body").toggleClass("kit__chat--open")})})}),$(document).on("turbolinks:load",function(){"use strict";$(function(){$(".juzaweb__topbar__actionsDropdown .dropdown-menu").on("click",function(){$(".juzaweb__topbar__actionsDropdown").on("hide.bs.dropdown",function(e){e.preventDefault(),$(".juzaweb__topbar__actionsDropdown .nav-link").on("shown.bs.tab",function(e){$(".juzaweb__topbar__actionsDropdown .dropdown-toggle").dropdown("update")})})}),$(document,".juzaweb__topbar__actionsDropdown .dropdown-toggle").mouseup(function(e){var t=$(".juzaweb__topbar__actionsDropdown"),a=$(".juzaweb__topbar__actionsDropdownMenu");!a.is(e.target)&&0===a.has(e.target).length&&t.hasClass("show")&&(t.removeClass("show"),a.removeClass("show"))});var e=$(".juzaweb__topbar__livesearch"),t=$(".juzaweb__topbar__livesearch__close"),a="juzaweb__topbar__livesearch__visible",n=$("#livesearch__input"),o=$("#livesearch__input__inner");function s(){e.removeClass(a)}n.on("focus",function(){e.addClass(a),setTimeout(function(){o.focus()},200)}),t.on("click",s),document.addEventListener("keydown",function(e){"27"===event.keyCode.toString()&&s()},!1)})});var juzawebFileManager=function(e,t){let a=e.type||"image";var n=e.prefix;"/"!=n[0]&&(n="/"+n);var o=null!=window.screenLeft?window.screenLeft:window.screenX,s=e.width?e.width:800,i=e.height?e.height:500,r=null!=window.screenTop?window.screenTop:window.screenY,l=window.innerWidth?window.innerWidth:document.documentElement.clientWidth?document.documentElement.clientWidth:screen.width,u=window.innerHeight?window.innerHeight:document.documentElement.clientHeight?document.documentElement.clientHeight:screen.height,d=l/window.screen.availWidth,c=(l-s)/2/d+o,m=(u-i)/2/d+r;window.open(n+"?type="+a,"File Manager","scrollbars=yes, width="+s/d+", height="+i/d+", top="+m+", left="+c),window.SetUrl=t};function ajaxRequest(e,t,a=null,n="POST",o="json"){$.ajax({type:n,url:e,dataType:o,data:t}).done(function(e){return a&&a(e),!1}).fail(function(e){return show_message(e),!1})}function show_message(e){if(e.data)return e.data.message&&toastr_message(e.data.message,e.status),!1;if(e.responseJSON)if(e.responseJSON.errors)$.each(e.responseJSON.errors,function(e,t){return toastr_message(t[0],!1),!1});else if(e.responseJSON.message)return toastr_message(e.responseJSON.message,!1),!1;e.message&&toastr_message(e.message.message,!1)}function toastr_message(e,t,a=null){1==t?toastr.success(e,a||juzaweb.lang.successfully+" !!"):toastr.error(e,a||juzaweb.lang.error+" !!")}function replace_template(e,t){return e.replace(/{(\w*)}/g,function(e,a){return t.hasOwnProperty(a)?t[a]:""})}$.fn.filemanager=function(e,t){let a=this,n=juzaweb.adminPrefix+"/file-manager";this.on("click",function(t){juzawebFileManager({type:e,prefix:n},function(e){let t=e[0];a.data("input")&&$("#"+a.data("input")).val(t.path);a.data("preview")&&$("#"+a.data("preview")).html('<img src="'+t.url+'">');a.data("name")&&$("#"+a.data("name")).html(t.name)})})},$(document).on("turbolinks:load",function(){$("body").on("click",".file-manager",function(){var e=$(this).data("type")||"image",t=$(this).data("input"),a=$(this).data("preview"),n=$(this).data("name"),o=juzaweb.adminPrefix+"/file-manager";juzawebFileManager({type:e,prefix:o},function(e){let o=e[0];t&&$("#"+t).val(o.path);a&&$("#"+a).html('<img src="'+o.url+'">');n&&$("#"+n).html(o.name)})}),$("body").on("click",".form-image .icon-choose",function(){var e=$(this).closest(".form-image"),t=e.find(".input-path"),a=e.find(".dropify-render"),n=e.find(".dropify-filename-inner"),o=juzaweb.adminPrefix+"/file-manager";juzawebFileManager({type:"image",prefix:o},function(o){let s=o[0];t.val(s.path),a.html('<img src="'+s.url+'">'),n.html(s.name),e.addClass("previewing"),e.find(".image-hidden").show()})}),$("body").on("click",".form-image .image-clear",function(){var e=$(this).closest(".form-image"),t=e.find(".input-path"),a=e.find(".dropify-render"),n=e.find(".dropify-filename-inner");t.val(""),a.html(""),n.html(""),e.removeClass("previewing"),e.find(".image-hidden").hide()})}),$(document).on("turbolinks:load",function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$(document).ajaxError(function(e,t,a,n){401===t.status&&Turbolinks.visit("/"),419===t.status&&Turbolinks.visit(location.toString())})}),toastr.options.timeOut=3e3,$(document).on("turbolinks:load",function(){$(".select2").select2({allowClear:!0,dropdownAutoWidth:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}}}),$(".select2-default").select2({dropdownAutoWidth:!0,width:"100%",minimumResultsForSearch:1/0}),$(".load-taxonomies").select2({allowClear:!0,dropdownAutoWidth:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}},ajax:{method:"GET",url:juzaweb.adminUrl+"/load-data/loadTaxonomies",dataType:"json",data:function(e){let t=$(this).data("post-type"),a=$(this).data("taxonomy"),n=$(this).data("explodes");return n&&(n=$("."+n).map(function(){return $(this).val()}).get()),{search:$.trim(e.term),page:e.page,explodes:n,post_type:t,taxonomy:a}}}}),$(".load-users").select2({allowClear:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}},ajax:{method:"GET",url:"/admin-cp/load-data/loadUsers",dataType:"json",data:function(e){let t=$(this).data("explodes")?$(this).data("explodes"):null;return t&&(t=$("."+t).map(function(){return $(this).val()}).get()),{search:$.trim(e.term),page:e.page,explodes:t}}}}),$(".load-menu").select2({allowClear:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}},ajax:{method:"GET",url:"/admin-cp/load-data/loadMenu",dataType:"json",data:function(e){let t=$(this).data("explodes")?$(this).data("explodes"):null;return t&&(t=$("."+t).map(function(){return $(this).val()}).get()),{search:$.trim(e.term),page:e.page,explodes:t}}}}),$(".load-slider").select2({allowClear:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}},ajax:{method:"GET",url:"/admin-cp/load-data/loadSliders",dataType:"json",data:function(e){let t=$(this).data("explodes")?$(this).data("explodes"):null;return t&&(t=$("."+t).map(function(){return $(this).val()}).get()),{search:$.trim(e.term),page:e.page,explodes:t}}}}),$(".load-countries-name").select2({allowClear:!0,width:"100%",placeholder:function(e){return{id:null,text:e.placeholder}},ajax:{method:"GET",url:"/admin-cp/load-data/loadCountryName",dataType:"json",data:function(e){let t=$(this).data("explodes")?$(this).data("explodes"):null;return t&&(t=$("."+t).map(function(){return $(this).val()}).get()),{search:$.trim(e.term),page:e.page,explodes:t}}}})});class JuzawebTable{constructor(e){this.url=e.url,this.action_url=e.action_url,this.remove_url=e.remove_url||null,this.status_url=e.status_url||null,this.remove_question=e.remove_question?e.remove_question:juzaweb.lang.remove_question,this.detete_button=e.detete_button?e.detete_button:"#delete-item",this.status_button=e.status_button?e.status_button:".status-button",this.apply_button=e.apply_button?e.apply_button:"#apply-action",this.table=e.table?e.table:".juzaweb-table",this.field_id=e.field_id?e.field_id:"id",this.form_search=e.form_search?e.form_search:"#form-search",this.sort_name=e.sort_name?e.sort_name:"id",this.sort_order=e.sort_order?e.sort_order:"desc",this.page_size=e.page_size?e.page_size:10,this.search=!!e.search&&e.search,this.method=e.method?e.method:"get",this.locale=e.locale?e.locale:"en-US",this.init()}init(){let e=$(this.apply_button),t=$(this.detete_button),a=$(this.status_button);e.prop("disabled",!0),t.prop("disabled",!0),a.prop("disabled",!0);let n=$(this.table),o=this.form_search,s=this.action_url,i=this.remove_question,r=this.url,l=this.field_id,u=this.method,d=this.locale,c=this.status_url,m=this.remove_url;n.bootstrapTable({url:r,idField:l,method:u,locale:d,sidePagination:"server",pagination:!0,sortName:this.sort_name,sortOrder:this.sort_order,toggle:"table",search:this.search,pageSize:this.page_size,queryParams:function(e){let t=$(o).serializeArray();return $.each(t,function(t,a){e[a.name]?e[a.name]+=","+a.value:e[a.name]=a.value}),e}}),$(this.form_search).on("change","select",function(e){return!e.isDefaultPrevented()&&(e.preventDefault(),n.bootstrapTable("refresh"),!1)}),$(this.form_search).on("submit",function(e){return!e.isDefaultPrevented()&&(e.preventDefault(),n.bootstrapTable("refresh"),!1)}),n.on("check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table pre-body.bs.table",()=>{!function(){let o=!n.bootstrapTable("getSelections").length;t.prop("disabled",o),e.prop("disabled",o),a.prop("disabled",o)}()}),e.on("click",function(){let e=$(this);var t=e.html();let a=$("select[name=bulk_actions]").val(),o=$(this).closest("form").find("input[name=_token]").val(),r=$("input[name=btSelectItem]:checked").map(function(){return $(this).val()}).get();return!(!r||!a)&&(e.html(juzaweb.lang.please_wait),e.prop("disabled",!0),"delete"==a?Swal.fire({title:"",text:i,type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:juzaweb.lang.yes+"!",cancelButtonText:juzaweb.lang.cancel+"!"}).then(i=>{i.value&&$.ajax({type:"POST",url:s,dataType:"json",data:{ids:r,action:a,_token:o},success:function(a){return e.prop("disabled",!1),e.html(t),!0===a.status?(show_message(a),n.bootstrapTable("refresh"),$("select[name=bulk_actions]").val(null),!1):(show_message(a),!1)}})}):$.ajax({type:"POST",url:s,dataType:"json",data:{ids:r,action:a,_token:o},success:function(a){return!0===a.status?(show_message(a),a.data.redirect?(setTimeout(function(){Turbolinks.visit(a.data.redirect,{action:"replace"})},1e3),!1):(e.prop("disabled",!1),e.html(t),n.bootstrapTable("refresh"),$("select[name=bulk_actions]").val(null),!1)):(show_message(a),!1)}}),!1)}),a.on("click",function(){let e=$("input[name=btSelectItem]:checked").map(function(){return $(this).val()}).get(),o=$(this).data("status");return!(e.length<=0)&&($.ajax({type:"POST",url:c,dataType:"json",data:{ids:e,status:o},success:function(e){return!0===e.status?(n.bootstrapTable("refresh"),t.prop("disabled",!0),a.prop("disabled",!0),$(".items-checked").prop("disabled",!0),!1):(show_message(e),!1)}}),!1)}),t.on("click",function(){let e=$("input[name=btSelectItem]:checked").map(function(){return $(this).val()}).get();return Swal.fire({title:"",text:i,type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:juzaweb.lang.yes+"!",cancelButtonText:juzaweb.lang.cancel+"!"}).then(o=>{o.value&&$.ajax({type:"POST",url:m,dataType:"json",data:{ids:e},success:function(e){return e.status?(n.bootstrapTable("refresh"),t.prop("disabled",!0),a.prop("disabled",!0),$(".items-checked").prop("disabled",!0),!1):(show_message(e),!1)}})}),!1}),n.on("click",".remove-item",function(){let e=[$(this).data("id")];return!!confirm(i)&&($.ajax({type:"POST",url:m,dataType:"json",data:{ids:e},success:function(e){return e.status,n.bootstrapTable("refresh"),!1}}),!1)})}refresh(e={}){e?$(this.table).bootstrapTable("refreshOptions",e):$(this.table).bootstrapTable("refresh",e)}}$(document).on("turbolinks:load",function(){function validate_isNumberKey(e){var t=e.which?e.which:event.keyCode;return 59==t||46==t||!(t>31&&(t<48||t>57))}function validate_FormatNumber(e){e.value=e.value.replace(/\./gi,""),e.value=e.value.replace(/(\d)(?=(\d{3})+(?!\d))/g,"$1.")}$("body").on("submit",".form-ajax",function(event){if(event.isDefaultPrevented())return!1;event.preventDefault();var form=$(this),formData=new FormData(form[0]),btnsubmit=form.find("button[type=submit]"),currentIcon=btnsubmit.find("i").attr("class"),currentText=btnsubmit.html(),submitSuccess=form.data("success"),reloadAfterSave=form.find("input[name=reload_after_save]").val()||1;btnsubmit.find("i").attr("class","fa fa-spinner fa-spin"),btnsubmit.prop("disabled",!0),btnsubmit.data("loading-text")&&btnsubmit.html('<i class="fa fa-spinner fa-spin"></i> '+btnsubmit.data("loading-text")),$.ajax({type:form.attr("method"),url:form.attr("action"),dataType:"json",data:formData,cache:!1,contentType:!1,processData:!1}).done(function(response){return show_message(response),submitSuccess&&eval(submitSuccess)(form),response.data.redirect?(setTimeout(function(){Turbolinks.visit(response.data.redirect,{action:"replace"})},1e3),!1):(btnsubmit.find("i").attr("class",currentIcon),btnsubmit.prop("disabled",!1),btnsubmit.data("loading-text")&&btnsubmit.html(currentText),!1!==response.status&&(0!==parseInt(reloadAfterSave)&&(setTimeout(function(){Turbolinks.visit(window.location.toString(),{action:"replace"})},1e3),!1)))}).fail(function(e){return btnsubmit.find("i").attr("class",currentIcon),btnsubmit.prop("disabled",!1),btnsubmit.data("loading-text")&&btnsubmit.html(currentText),show_message(e),!1})}),$("body").on("click",".load-modal",function(e){if(e.isDefaultPrevented())return!1;e.preventDefault();var t=$(this).data(),a=$(this),n=a.find("i").attr("class");a.find("i").attr("class","fa fa-spinner fa-spin"),a.prop("disabled",!0),a.addClass("disabled");var o="";$.each(t,function(e,t){"url"!=e&&(o+="&"+e+"="+t)});var s=$(this).data("url");o&&(s=s+"?"+o),$.ajax({type:"GET",url:s,dataType:"json",data:{},cache:!1,contentType:!1,processData:!1}).done(function(e){return a.find("i").attr("class",n),a.prop("disabled",!1),a.removeClass("disabled"),!1!==e.status&&($("#show-modal").html(""),$("#show-modal").html(e.data.source),$("#show-modal .modal").modal(),!1)}).fail(function(e){return a.find("i").attr("class",n),a.prop("disabled",!1),!1})}),$("body").on("keypress",".is-number",function(){return validate_isNumberKey(this)}),$("body").on("keyup",".number-format",function(){return validate_FormatNumber(this)})}),$(document).on("turbolinks:load",function(){$(".form-taxonomy").on("click",".add-new",function(){let e=$(this).closest(".form-taxonomy").find(".form-add");e.is(":visible")?e.hide("slow"):e.show("slow")}),$("body").on("change",".select-tags",function(){let e=$(this),t=e.val(),a=e.data("taxonomy"),n=e.data("type");$.ajax({type:"GET",url:juzaweb.adminUrl+"/"+n+"/"+a+"/component-item",dataType:"json",data:{id:t}}).done(function(t){return!1===t.status?(show_message(t),!1):(e.closest(".form-taxonomy").find(".show-tags").append(t.data.html),e.val(null).trigger("change.select2"),!1)}).fail(function(e){return show_message(e),!1})}),$("body").on("click",".remove-tag-item",function(){$(this).closest(".tag").remove()}),$("body").on("click",".form-add-taxonomy button",function(){let e=$(this),t=e.closest(".form-add"),a=t.find(".taxonomy-name").val(),n=t.find(".taxonomy-parent").val(),o=e.data("type"),s=e.data("taxonomy"),i=e.find("i").attr("class");e.find("i").attr("class","fa fa-spinner fa-spin"),e.prop("disabled",!0),$.ajax({type:"POST",url:juzaweb.adminUrl+"/"+o+"/"+s,dataType:"json",data:{name:a,parent_id:n}}).done(function(a){return e.find("i").attr("class",i),e.prop("disabled",!1),!1===a.status?(show_message(a),!1):(e.closest(".form-taxonomy").find(".show-tags").append(a.data.html),t.find(".taxonomy-name").val(""),n&&t.find(".taxonomy-parent").val(null).trigger("change.select2"),!1)}).fail(function(t){return e.find("i").attr("class",i),e.prop("disabled",!1),show_message(t),!1})})}),$(document).on("turbolinks:load",function(){}),$(document).on("turbolinks:load",function(){});
