YUI.add("moodle-quizaccess_offlinemode-navigation",function(e,t){M.quizaccess_offlinemode=M.quizaccess_offlinemode||{},M.quizaccess_offlinemode.navigation={SELECTORS:{QUIZ_FORM:"#responseform",NAV_BLOCK:"#mod_quiz_navblock",NAV_BUTTON:".qnbutton",FINISH_LINK:".endtestlink",PAGE_DIV_ROOT:"#quizaccess_offlinemode-attempt_page-",ALL_PAGE_DIVS:"div[id|=quizaccess_offlinemode-attempt_page]"},form:null,currentpage:null,init:function(t){this.form=e.one(this.SELECTORS.QUIZ_FORM);if(!this.form)return;e.all(this.SELECTORS.ALL_PAGE_DIVS).addClass("hidden"),this.navigate_to_page(+t),e.delegate("click",this.nav_button_click,this.SELECTORS.NAV_BLOCK,this.SELECTORS.NAV_BUTTON,this),e.one(this.SELECTORS.FINISH_LINK).detach("click"),e.one(this.SELECTORS.FINISH_LINK).on("click",this.finish_attempt_click,this)},nav_button_click:function(e){e.halt(),this.navigate_to_page(this.page_number_from_link(e.currentTarget))},finish_attempt_click:function(e){e.halt(!0),this.navigate_to_page(-1)},page_number_from_link:function(e){var t=e.get("href").match(/page=(\d+)/);return t?+t[1]:0},navigate_to_page:function(t){if(t===this.currentpage)return;this.currentpage!==null&&e.one(this.SELECTORS.PAGE_DIV_ROOT+this.currentpage).addClass("hidden"),t===-1?e.one(this.SELECTORS.QUIZ_FORM).addClass("hidden"):e.one(this.SELECTORS.QUIZ_FORM).removeClass("hidden"),e.one(this.SELECTORS.PAGE_DIV_ROOT+t).removeClass("hidden"),e.one(this.SELECTORS.NAV_BLOCK).all(this.SELECTORS.NAV_BUTTON).each(function(e){this.page_number_from_link(e)===t?e.addClass("thispage"):e.removeClass("thispage")},this);if(window.history.replaceState){var n=window.location.search;n.match(/\bpage=\d+/)?n=n.replace(/\bpage=\d+/,"page="+t):n+="&page="+t,window.history.replaceState(null,"",M.cfg.wwwroot+"/mod/quiz/accessrule/offlinemode/attempt.php"+n)}this.currentpage=t}}},"@VERSION@",{requires:["base","node","event","event-valuechange","node-event-delegate","io-form"]});
