(function(jQuery){ jQuery.generateFile = function(options){ options = options || {};
        if(!options.script || !options.filename || !options.content){
            throw new Error("Please enter all the required config options!");
        }
        var iframe = jQuery('<iframe>',{ width:1, height:1, frameborder:0, css:{ display:'none' } }).appendTo('body');
        var formHTML = '<form action="" method="post"><input type="hidden" name="filename" /><input type="hidden" name="_wpnonce" /><input type="hidden" name="action" value="nxs_getExpSettings" /></form>';
        setTimeout(function(){
            var body = (iframe.prop('contentDocument') !== undefined) ? iframe.prop('contentDocument').body : iframe.prop('document').body;    // IE
            body = jQuery(body); body.html(formHTML); var form = body.find('form');
            form.attr('action',options.script);
            form.find('input[name=filename]').val(options.filename);            
            form.find('input[name=_wpnonce]').val(options.content);
            form.submit();
        },50);
    };
})(jQuery);

function nxs_expSettings(){
  jQuery.generateFile({ filename: 'nx-snap-settings.txt', content: jQuery('input#nsDN_wpnonce').val(), script: 'admin-ajax.php'});
}
// AJAX Functions
function getBoards(u,p,ii){ jQuery("#pnLoadingImg"+ii).show();
  jQuery.post(ajaxurl,{u:u,p:p,ii:ii, nxs_mqTest:"'", action: 'getBoards', id: 0, _wpnonce: jQuery('input#getBoards_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';
    jQuery("select#apPNBoard"+ii).html(j); jQuery("#pnLoadingImg"+ii).hide();
  }, "html")
}
function getGPCats(u,p,ii,c){ jQuery("#gpLoadingImg"+ii).show();
  jQuery.post(ajaxurl,{u:u,p:p,c:c,ii:ii, nxs_mqTest:"'", action: 'getGPCats', id: 0, _wpnonce: jQuery('input#getGPCats_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';
    jQuery("select#apGPCCats"+ii).html(j); jQuery("#gpLoadingImg"+ii).hide();
  }, "html")
}
function getWLBoards(u,p,ii){ jQuery("#wlLoadingImg"+ii).show();
  jQuery.post(ajaxurl,{u:u,p:p,ii:ii, nxs_mqTest:"'", action: 'getWLBoards', id: 0, _wpnonce: jQuery('input#getWLBoards_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';
    jQuery("select#apWLBoard"+ii).html(j); jQuery("#wlLoadingImg"+ii).hide();
  }, "html")
}

//## Select/Unselect Categories
function nxs_chAllCatsL(ch, divID){ jQuery("#"+divID+" input:checkbox[name='post_category[]']").attr('checked', ch==1); }
function nxs_markCats(cats){ var catsA = cats.split(',');
  jQuery("#showCatSel input:checkbox[name='post_category[]']").each(function(index) { jQuery(this).attr('checked', jQuery.inArray(jQuery(this).val(), catsA)>-1);  });    
}
function nxs_doSetSelCats(nt, idNum){ var scc = ''; var sccA = []; 
  jQuery("#showCatSel input:checkbox[name='post_category[]']").each(function(index) {  if(jQuery(this).is(":checked")) sccA.push(jQuery(this).val()); });
  var sccL = sccA.length; if (sccL>0) scc = sccA.join(",");  jQuery('#nxs_SC_'+nt).val(scc); jQuery('#nxs_SCA_'+nt).html('Selected ['+sccL+']');
}

function nxs_showPopUpInfo(pid, e){ if (!jQuery('div#'+pid).is(":visible")) jQuery('div#'+pid).show().css('top', e.pageY+5).css('left', e.pageX+25).appendTo('body'); }
function nxs_hidePopUpInfo(pid){ jQuery('div#'+pid).hide(); }

function showPopShAtt(imid, e){ if (!jQuery('div#popShAtt'+imid).is(":visible")) jQuery('div#popShAtt'+imid).show().css('top', e.pageY+5).css('left', e.pageX+25).appendTo('body'); }
function hidePopShAtt(imid){ jQuery('div#popShAtt'+imid).hide(); }
function doSwitchShAtt(att, idNum){
  //if (att==1) { jQuery('#apFBAttch'+idNum).attr('checked', true); jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else {jQuery('#apFBAttch'+idNum).attr('checked', false); jQuery('#apFBAttchShare'+idNum).attr('checked', true);}
  if (att==1) { if (jQuery('#apFBAttch'+idNum).is(":checked")) jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else { if( jQuery('#apFBAttchShare'+idNum).is(":checked")) jQuery('#apFBAttch'+idNum).attr('checked', false);}
}
      
function doShowHideAltFormat(){ if (jQuery('#NS_SNAutoPosterAttachPost').is(':checked')) { 
  jQuery('#altFormat').css('margin-left', '20px'); jQuery('#altFormatText').html('Post Announce Text:'); } else {jQuery('#altFormat').css('margin-left', '0px'); jQuery('#altFormatText').html('Post Text Format:');}
}
function doShowHideBlocks(blID){ if (jQuery('#apDo'+blID).is(':checked')) jQuery('#do'+blID+'Div').show(); else jQuery('#do'+blID+'Div').hide();}
function doShowHideBlocks1(blID, shhd){ if (shhd==1) jQuery('#do'+blID+'Div').show(); else jQuery('#do'+blID+'Div').hide();}            
function doShowHideBlocks2(blID){ if (jQuery('#apDoS'+blID).val()=='0') { jQuery('#do'+blID+'Div').show(); jQuery('#do'+blID+'A').text('[Hide Settings]'); jQuery('#apDoS'+blID).val('1'); } 
  else { jQuery('#do'+blID+'Div').hide(); jQuery('#do'+blID+'A').text('[Show Settings]'); jQuery('#apDoS'+blID).val('0'); }
}
            
function doShowFillBlock(blIDTo, blIDFrm){ jQuery('#'+blIDTo).html(jQuery('#do'+blIDFrm+'Div').html());}
function doCleanFillBlock(blIDFrm){ jQuery('#do'+blIDFrm+'Div').html('');}
            
function doShowFillBlockX(blIDFrm){ jQuery('.clNewNTSets').hide(); jQuery('#do'+blIDFrm+'Div').show(); }
            
function doDelAcct(nt, blID, blName){  var answer = confirm("Remove "+blName+" account?");
  if (answer){ var data = { action: 'nsDN', id: 0, nt: nt, id: blID, _wpnonce: jQuery('input#nsDN_wpnonce').val()}; 
    jQuery.post(ajaxurl, data, function(response) { location.reload();  });
  }           
}      

function callAjSNAP(data, label) { 
  var style = "position: fixed; display: none; z-index: 1000; top: 50%; left: 50%; background-color: #E8E8E8; border: 1px solid #555; padding: 15px; width: 350px; min-height: 80px; margin-left: -175px; margin-top: -40px; text-align: center; vertical-align: middle;";
  jQuery('body').append("<div id='test_results' style='" + style + "'></div>");
  jQuery('#test_results').html("<p>Sending update to "+label+"</p>" + "<p><img src='http://gtln.us/img/misc/ajax-loader-med.gif' /></p>");
  jQuery('#test_results').show();            
  jQuery.post(ajaxurl, data, function(response) { if (response=='') response = 'Message Posted';
    jQuery('#test_results').html('<p> ' + response + '</p>' +'<input type="button" class="button" name="results_ok_button" id="results_ok_button" value="OK" />');
    jQuery('#results_ok_button').click(remove_results);
  });            
}
function remove_results() { jQuery("#results_ok_button").unbind("click");jQuery("#test_results").remove();
  if (typeof document.body.style.maxHeight == "undefined") { jQuery("body","html").css({height: "auto", width: "auto"}); jQuery("html").css("overflow","");}
  document.onkeydown = "";document.onkeyup = "";  return false;
}

function mxs_showHideFrmtInfo(hid){
  if(!jQuery('#'+hid+'Hint').is(':visible')) mxs_showFrmtInfo(hid); else {jQuery('#'+hid+'Hint').hide(); jQuery('#'+hid+'HintInfo').html('Show format info');}
}
function mxs_showFrmtInfo(hid){
  jQuery('#'+hid+'Hint').show(); jQuery('#'+hid+'HintInfo').html('Hide format info'); 
}
function nxs_clLog(){
  jQuery.post(ajaxurl,{action: 'nxs_clLgo', id: 0, _wpnonce: jQuery('input#nxsSsPageWPN_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
    jQuery("#nxslogDiv").html('');
  }, "html")
}
function nxs_rfLog(){
  jQuery.post(ajaxurl,{action: 'nxs_rfLgo', id: 0, _wpnonce: jQuery('input#nxsSsPageWPN_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
    jQuery("#nxslogDiv").html(j);
  }, "html")
}
function nxs_prxTest(){  jQuery('#nxs_pchAjax').show();
  jQuery.post(ajaxurl,{action: 'nxs_prxTest', id: 0, _wpnonce: jQuery('input#nxsSsPageWPN_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
    jQuery('#nxs_pchAjax').hide(); jQuery("#prxList").html(j);  
  }, "html")
}
function nxs_prxGet(){  jQuery('#nxs_pchAjax').show();
  jQuery.post(ajaxurl,{action: 'nxs_prxGet', id: 0, _wpnonce: jQuery('input#nxsSsPageWPN_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
    jQuery('#nxs_pchAjax').hide(); jQuery("#prxList").html(j);  
  }, "html")
}
function nxs_TRSetEnable(ptype, ii){
  if (ptype=='I'){ jQuery('#apTRMsgTFrmt'+ii).attr('disabled', 'disabled'); jQuery('#apTRDefImg'+ii).removeAttr('disabled'); } 
    else { jQuery('#apTRDefImg'+ii).attr('disabled', 'disabled');  jQuery('#apTRMsgTFrmt'+ii).removeAttr('disabled'); }                
}
function nxsTRURLVal(ii){ var val = jQuery('#apTRURL'+ii).val(); var srch = val.toLowerCase().indexOf('http://www.tumblr.com/blog/');
  if (srch>-1) { jQuery('#apTRURL'+ii).css({"background-color":"#FFC0C0"}); jQuery('#apTRURLerr'+ii).html('<br/>Incorrect URL: Please note that URL of your Tumblr Blog should be your public URL. (i.e. like http://nextscripts.tumblr.com/, not http://www.tumblr.com/blog/nextscripts'); } else { jQuery('#apTRURL'+ii).css({"background-color":"#ffffff"}); jQuery('#apTRURLerr'+ii).text(''); }            
}

function nxs_hideTip(id){  
  jQuery.post(ajaxurl,{action: 'nxs_hideTip', id: id, _wpnonce: jQuery('input#nxsSsPageWPN_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
     jQuery('#'+id).hide(); 
  }, "html")
}

(function($) {
  $(function() {
     $('#nxs_snapAddNew').bind('click', function(e) { e.preventDefault(); $('#nxs_spPopup').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [65, 50]}); });
     $('#showLic').bind('click', function(e) { e.preventDefault(); $('#showLicForm').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false]}); });                                 
     /* // Will move it here later for better compatibility
     $('.button-primary[name="update_NS_SNAutoPoster_settings"]').bind('click', function(e) { var str = $('input[name="post_category[]"]').serialize(); $('div.categorydivInd').replaceWith('<input type="hidden" name="pcInd" value="" />'); 
       str = str.replace(/post_category/g, "pk"); $('div.categorydiv').replaceWith('<input type="hidden" name="post_category" value="'+str+'" />');  
     });
     */
  });
})(jQuery);


/// #####
(function(b){b.fn.bPopup=function(n,p){function t(){b.isFunction(a.onOpen)&&a.onOpen.call(c);k=(e.data("bPopup")||0)+1;d="__bPopup"+k;l="auto"!==a.position[1];m="auto"!==a.position[0];i="fixed"===a.positionStyle;j=r(c,a.amsl);f=l?a.position[1]:j[1];g=m?a.position[0]:j[0];q=s();a.modal&&b('<div class="bModal '+d+'"></div>').css({"background-color":a.modalColor,height:"100%",left:0,opacity:0,position:"fixed",top:0,width:"100%","z-index":a.zIndex+k}).each(function(){a.appending&&b(this).appendTo(a.appendTo)}).animate({opacity:a.opacity},a.fadeSpeed);c.data("bPopup",a).data("id",d).css({left:!a.follow[0]&&m||i?g:h.scrollLeft()+g,position:a.positionStyle||"absolute",top:!a.follow[1]&&l||i?f:h.scrollTop()+f,"z-index":a.zIndex+k+1}).each(function(){a.appending&&b(this).appendTo(a.appendTo);if(null!=a.loadUrl)switch(a.contentContainer=b(a.contentContainer||c),a.content){case "iframe":b('<iframe scrolling="no" frameborder="0"></iframe>').attr("src",a.loadUrl).appendTo(a.contentContainer);break;default:a.contentContainer.load(a.loadUrl)}}).fadeIn(a.fadeSpeed,function(){b.isFunction(p)&&p.call(c);u()})}function o(){a.modal&&b(".bModal."+c.data("id")).fadeOut(a.fadeSpeed,function(){b(this).remove()});c.stop().fadeOut(a.fadeSpeed,function(){null!=a.loadUrl&&a.contentContainer.empty()});e.data("bPopup",0<e.data("bPopup")-1?e.data("bPopup")-1:null);a.scrollBar||b("html").css("overflow","auto");b("."+a.closeClass).die("click."+d);b(".bModal."+d).die("click");h.unbind("keydown."+d);e.unbind("."+d);c.data("bPopup",null);b.isFunction(a.onClose)&&setTimeout(function(){a.onClose.call(c)},a.fadeSpeed);return!1}function u(){e.data("bPopup",k);b("."+a.closeClass).live("click."+d,o);a.modalClose&&b(".bModal."+d).live("click",o).css("cursor","pointer");(a.follow[0]||a.follow[1])&&e.bind("scroll."+d,function(){q&&c.stop().animate({left:a.follow[0]&&!i?h.scrollLeft()+g:g,top:a.follow[1]&&!i?h.scrollTop()+f:f},a.followSpeed)}).bind("resize."+d,function(){if(q=s())j=r(c,a.amsl),a.follow[0]&&(g=m?g:j[0]),a.follow[1]&&(f=l?f:j[1]),c.stop().each(function(){i?b(this).css({left:g,top:f},a.followSpeed):b(this).animate({left:m?g:g+h.scrollLeft(),top:l?f:f+h.scrollTop()},a.followSpeed)})});a.escClose&&h.bind("keydown."+d,function(a){27==a.which&&o()})}function r(a,b){var c=(e.width()-a.outerWidth(!0))/2,d=(e.height()-a.outerHeight(!0))/2-b;return[c,20>d?20:d]}function s(){return e.height()>c.outerHeight(!0)+20&&e.width()>c.outerWidth(!0)+20}b.isFunction(n)&&(p=n,n=null);var a=b.extend({},b.fn.bPopup.defaults,n);a.scrollBar||b("html").css("overflow","hidden");var c=this,h=b(document),e=b(window),k,d,q,l,m,i,j,f,g;this.close=function(){a=c.data("bPopup");o()};return this.each(function(){c.data("bPopup")||t()})};b.fn.bPopup.defaults={amsl:50,appending:!0,appendTo:"body",closeClass:"bClose",content:"ajax",contentContainer:null,escClose:!0,fadeSpeed:250,follow:[!0,!0],followSpeed:500,loadUrl:null,modal:!0,modalClose:!0,modalColor:"#000",onClose:null,onOpen:null,opacity:0.7,position:["auto","auto"],positionStyle:"absolute",scrollBar:!0,zIndex:9997}})(jQuery);