YUI.add("moodle-quizaccess_offlinemode-download",function(e,t){M.quizaccess_offlinemode=M.quizaccess_offlinemode||{},M.quizaccess_offlinemode.download={SELECTORS:{QUIZ_FORM:"#responseform",LINK_CONTAINER:"#mod_quiz_navblock .othernav"},filename:null,form:null,link:null,init:function(t){this.filename=t,this.form=e.one(this.SELECTORS.QUIZ_FORM);if(!this.form)return;var n=e.one(this.SELECTORS.LINK_CONTAINER);if(!n)return;this.link=n.appendChild('<a download="'+t+'" href="#">Emergency response export</a>'),this.link.on("click",this.download_clicked,this)},download_clicked:function(){typeof tinyMCE!="undefined"&&tinyMCE.triggerSave(),this.link.set("download",this.link.get("download").replace(/-d\d+\.attemptdata/,"-d"+this.get_current_datestamp()+".attemptdata")),this.link.set("href","data:application/octet-stream,"+e.LZString.compressToBase64(e.IO.stringify(this.form)))},get_current_datestamp:function(){function t(e){return e<10?"0"+e:e}var e=new Date;return""+e.getUTCFullYear()+t(e.getUTCMonth()+1)+t(e.getUTCDate())+t(e.getUTCHours())+t(e.getUTCMinutes())}}},"@VERSION@",{requires:["base","node","event","node-event-delegate","io-form","moodle-quizaccess_offlinemode-lzstring"]});
