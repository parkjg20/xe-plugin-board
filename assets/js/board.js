!function(t){function e(n){if(a[n])return a[n].exports;var o=a[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,e),o.l=!0,o.exports}var a={};e.m=t,e.c=a,e.d=function(t,a,n){e.o(t,a)||Object.defineProperty(t,a,{configurable:!1,enumerable:!0,get:n})},e.n=function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,"a",a),a},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=7)}({7:function(t,e,a){"use strict";window.AssentVirtualGrid=function(t,e){var a,n,o;return{getTemplate:function(){return["\x3c!--[D] 링크가 아닌 경우 div 로 교체 --\x3e",'<a href="{{profilePage}}" class="list-inner-item">',"\x3c!--[D] 실제 이미지 사이즈는 모바일 대응 위해 일대일 비율로 96*96 이상--\x3e",'<div class="img-thumbnail"><img src="{{profileImage}}" width="48" height="48" alt="{{displayName}}" /></div>','<div class="list-text">',"<p>{{displayName}}</p>","</div>","</a>"].join("\n")},init:function(){return a=AssentVirtualGrid,e(".xe-list-group").css("height","365px"),n=0,o=10,t.DynamicLoadManager.jsLoad("/assets/core/xe-ui-component/js/xe-infinite.js",function(){window.XeInfinite.init({wrapper:".xe-list-group",template:a.getTemplate(),loadRowCount:3,rowHeight:80,onGetRows:a.onGetRows})}),a},onGetRows:function(){window.XeInfinite.setPrevent(!0);var t={limit:o};0!==n&&(t.startId=n),window.XE.ajax({url:e(".xe-list-group").data("url"),type:"get",dataType:"json",data:t,success:function(t){0===t.nextStartId?window.XeInfinite.setPrevent(!0):window.XeInfinite.setPrevent(!1),n=t.nextStartId;for(var e=0,a=t.list.length;e<a;e+=1)window.XeInfinite.addItems(t.list[e])}})}}}(window.XE,window.jQuery),window.jQuery(function(t){t(".__xe-bd-favorite").on("click",function(e){e.preventDefault();var a=t(e.target),n=a.closest("a"),o=n.data("id"),i=n.data("url");window.XE.ajax({url:i,type:"post",dataType:"json",data:{id:o}}).done(function(t){!0===t.favorite?n.addClass("on"):n.removeClass("on")})}),t(".__xe-forms .__xe-dropdown-form input").on("change",function(e){var a=t(e.target),n=t(".__xe_search");n.find('[name="'+a.attr("name")+'"]').val(a.val()),n.submit()}),t(".__xe-period .__xe-dropdown-form input").on("change",function(e){var a=t(e.target),n=a.val(),o="",i=window.XE.moment().format("YYYY-MM-DD"),r=t(e.target).closest(".__xe-period").find('[name="start_created_at"]'),d=t(e.target).closest(".__xe-period").find('[name="end_created_at"]');switch(n){case"1week":o=window.XE.moment().add(-1,"weeks").format("YYYY-MM-DD");break;case"2week":o=window.XE.moment().add(-2,"weeks").format("YYYY-MM-DD");break;case"1month":o=window.XE.moment().add(-1,"months").format("YYYY-MM-DD");break;case"3month":o=window.XE.moment().add(-3,"months").format("YYYY-MM-DD");break;case"6month":o=window.XE.moment().add(-6,"months").format("YYYY-MM-DD");break;case"1year":o=window.XE.moment().add(-1,"years").format("YYYY-MM-DD")}r.val(o),d.val(i)}),t(".__xe-bd-mobile-sorting").on("click",function(e){e.preventDefault();var a=t(".__xe-forms");a.hasClass("xe-hidden-xs")?(a.removeClass("xe-hidden-xs"),t(".board .bd_dimmed").show()):(a.addClass("xe-hidden-xs"),t(".board .bd_dimmed").hide())}),t(".__xe-bd-manage").on("click",function(){t(".bd_manage_detail").toggle()}),t(".__xe-bd-search").on("click",function(e){e.preventDefault(),t(this).toggleClass("on"),t(this).hasClass("on")?(t(".bd_search_area").show(),t(".bd_search_input").focus()):t(".bd_search_area").hide()}),t(".bd_btn_detail").on("click",function(e){t(this).toggleClass("on"),t(this).hasClass("on")?t(".bd_search_detail").show():t(".bd_search_detail").hide()}),t(".__xe_simple_search").on("submit",function(e){e.preventDefault();var a=t(".__xe_search");a.find('[name="title_pure_content"]').val(t(this).find('[name="title_pure_content"]').val()),a.submit()}),t(".bd_btn_cancel").on("click touchstart",function(e){e.preventDefault(),t(e.target).closest("form").find(".bd_search_detail").hide()}),t(".bd_btn_search").on("click touchstart",function(e){e.preventDefault(),t(e.target).closest("form").submit()}),t(".bd_btn_manage_check_all").on("click touchstart",function(e){t(".bd_manage_check").prop("checked",t(e.target).prop("checked"))}),t(".bd_btn_file").on("click touchstart",function(e){e.preventDefault(),t(e.target).closest("a").toggleClass("on")}),t(".bd_like").on("click touchstart",function(e){e.preventDefault();var a=t(e.target).closest("a"),n=a.data("url");window.XE.ajax({url:n,type:"post",dataType:"json"}).done(function(e){a.toggleClass("voted"),t(".bd_like_num").text(e.counts.assent)})}),t(".bd_delete").on("click touchstart",function(e){if(e.preventDefault(),confirm(window.XE.Lang.trans("board::msgDeleteConfirm"))){var a=t(this).data("url"),n=t("<form>",{action:a,method:"post"}).append(t("<input>",{type:"hidden",name:"_token",value:window.XE.Request.options.headers["X-CSRF-TOKEN"]})).append(t("<input>",{type:"hidden",name:"_method",value:"delete"}));t("body").append(n),n.submit()}}),t(".bd_like_num").on("click touchstart",function(e){if(e.preventDefault(),0!=parseInt(t(e.target).text())){var a=t(e.target).closest("a"),n=a.data("url");window.XE.page(n,"#bd_like_more"+a.data("id"),{},function(){t("#bd_like_more"+a.data("id")).show()})}}),t(".bd_like_more_text a").on("click touchstart",function(e){if(e.preventDefault(),0!=parseInt(t(e.target).text())){var a=t(e.target).closest("a"),n=a.prop("href");window.XE.pageModal(n)}}),t(".bd_share").on("click touchstart",function(e){e.preventDefault(),t(e.target).closest("a").toggleClass("on")});var e=function(t,e,a,n,o){if(a>o){var i=o/a;t.css("height",o),t.css("width",e*i),e*=i,a*=i}};t(".board_list .thumb_area img").each(function(){var a=t(this);if(void 0===a.data("resize")){var n=(a.prop("clientWidth"),a.prop("naturalWidth")),o=a.prop("clientHeight"),i=a.prop("naturalHeight");0!=n&&0!=o&&(a.data("resize","1"),e(a,n,i,0,o))}}),t(".board_list .thumb_area img").bind("load",function(){var a=t(this);if(void 0===a.data("resize")){a.data("resize","2");var n=(a.prop("clientWidth"),a.prop("naturalWidth")),o=parseInt(a.css("max-height").replace("px","")),i=a.prop("naturalHeight");e(a,n,i,0,o)}})}),window.jQuery(function(t){t(".__board_form").on("click",".__xe_btn_preview",function(e){var a=t(this).parents("form"),n=a.attr("action"),o=a.attr("target");a.attr("action",a.data("url-preview")),a.attr("target","_blank"),a.submit(),a.attr("action",n),a.attr("target",void 0===o?"":o)})}),window.jQuery(function(t){t(".__xe_copy .__xe_btn_submit").on("click",function(n){if(n.preventDefault(),!1!==e()){var o=a(),i=t(".__xe_copy").find('[name="copyTo"]').val();if(""==i)return void window.XE.toast("warning",window.XE.Lang.trans("board::selectBoard"));window.XE.ajax({type:"post",dataType:"json",data:{id:o,instance_id:i},url:t(n.target).data("url"),success:function(t){document.location.reload()}})}}),t(".__xe_move .__xe_btn_submit").on("click",function(n){if(!1!==e()){n.preventDefault();var o=a(),i=t(".__xe_move").find('[name="moveTo"]').val();if(""==i)return void window.XE.toast("warning",window.XE.Lang.trans("board::selectBoard"));window.XE.ajax({type:"post",dataType:"json",data:{id:o,instance_id:i},url:t(n.target).data("url"),success:function(t){document.location.reload()}})}}),t(".__xe_to_trash").on("click","a:first",function(n){if(n.preventDefault(),!1!==e()){var o=a();window.XE.ajax({type:"post",dataType:"json",data:{id:o},url:t(n.target).data("url"),success:function(t){document.location.reload()}})}}),t(".__xe_delete").on("click","a:first",function(n){if(n.preventDefault(),!1!==e()){var o=a();window.XE.ajax({type:"post",dataType:"json",data:{id:o},url:t(n.target).data("url"),success:function(t){document.location.reload()}})}});var e=function(){return 0!=t(".bd_manage_check:checked").length||(window.XE.toast("warning",window.XE.Lang.trans("board::selectPost")),!1)},a=function(){var e=[];return t(".bd_manage_check:checked").each(function(){e.push(t(this).val())}),e}})}});