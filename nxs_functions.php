<?php
if (!function_exists('prr')){ function prr($str) { echo "<pre>"; print_r($str); echo "</pre>\r\n"; }}        
if (!function_exists('nsx_stripSlashes')){ function nsx_stripSlashes(&$value){$value = stripslashes($value);}}
if (!function_exists('nsx_fixSlashes')){ function nsx_fixSlashes(&$value){ if (strpos($value, "\\'")!==false) $value = str_replace("\\'",'"',$value);
  while (strpos($value, '\\\\')!==false) $value = str_replace('\\\\','\\',$value); if (strpos($value, '\\"')!==false) $value = str_replace('\\"','"',$value);
}}
if (!function_exists('CutFromTo')){ function CutFromTo($string, $from, $to){$fstart = stripos($string, $from); $tmp = substr($string,$fstart+strlen($from)); $flen = stripos($tmp, $to);  return substr($tmp,0, $flen);}}
if (!function_exists('nsx_doEncode')){ function nsx_doEncode($string,$key='NSX') { $key = sha1($key); $strLen = strlen($string);$keyLen = strlen($key); $j = 0; $hash = '';
  for ($i = 0; $i < $strLen; $i++) { $ordStr = ord(substr($string,$i,1)); if ($j == $keyLen) $j = 0; $ordKey = ord(substr($key,$j,1)); $j++; $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));} return $hash;
}}
if (!function_exists('nsx_doDecode')){ function nsx_doDecode($string,$key='NSX') { $key = sha1($key); $strLen = strlen($string); $keyLen = strlen($key); $j = 0; $hash = '';
  for ($i = 0; $i < $strLen; $i+=2) { $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16)); if ($j == $keyLen) $j = 0; $ordKey = ord(substr($key,$j,1)); $j++; $hash .= chr($ordStr - $ordKey);} return $hash;
}}
if (!function_exists('nxs_decodeEntitiesFull')){ function nxs_decodeEntitiesFull($string, $quotes = ENT_COMPAT, $charset = 'utf-8') {
  return html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'nxs_convertEntity', $string), $quotes, $charset); 
}}
if (!function_exists('nxs_convertEntity')){ function nxs_convertEntity($matches, $destroy = true) {
  static $table = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;','psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;','sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;','Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;');
  if (isset($table[$matches[1]])) return $table[$matches[1]];
  // else 
  return $destroy ? '' : $matches[0];
}}
if (!function_exists('nsFindImgsInPost')){function nsFindImgsInPost($post, $advImgFnd=false) { global $ShownAds; if (isset($ShownAds)) $ShownAdsL = $ShownAds;  
  if ($advImgFnd) $postCnt = apply_filters('the_content', $post->post_content); else $postCnt = $post->post_content;   $postImgs = array();
  //$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $postCnt, $matches ); if ($output === false){return false;} prr($matches);
  $postCnt = str_replace("'",'"',$postCnt); $output = preg_match_all( '/src="([^"]*)"/', $postCnt, $matches ); if ($output === false){return false;}// prr($matches);
  foreach ($matches[1] as $match) { if (!preg_match('/^https?:\/\//', $match ) ) $match = site_url( '/' ) . ltrim( $match, '/' ); $postImgs[] = $match;} if (isset($ShownAds)) $ShownAds = $ShownAdsL; return $postImgs;
}}
if (!function_exists('nsFindVidsInPost')){function nsFindVidsInPost($post) {  //## Breaks ob_start() [ref.outcontrol]: Cannot use output buffering in output buffering display handlers - Investigate
  global $ShownAds; if (isset($ShownAds)) $ShownAdsL = $ShownAds; $postCnt = apply_filters('the_content', $post->post_content); $postVids = array();
  $output = preg_match_all( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $postCnt, $matches ); if ($output === false){return false;}
  foreach ($matches[1] as $match) {  $match = trim($match); $postVids[] = $match;} if (isset($ShownAds)) $ShownAds = $ShownAdsL; return $postVids;
}}
if (!function_exists('nsTrnc')){ function nsTrnc($string, $limit, $break=" ", $pad=" ...") { if(strlen($string) <= $limit) return $string; $string = substr($string, 0, $limit-strlen($pad)); 
  $brLoc = strripos($string, $break);  if ($brLoc===false) return $string.$pad; else return substr($string, 0, $brLoc).$pad; 
}}
if (!function_exists('nsSubStrEl')){ function nsSubStrEl($string, $length, $end='...'){ if (strlen($string) > $length){ $length -= strlen($end); $string  = substr($string, 0, $length); $string .= $end; } return $string;}}

if (!function_exists('nxs_snapCleanHTML')){ function nxs_snapCleanHTML($html) { 
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html); $html = preg_replace('/<!--(.*)-->/Uis', "", $html); return $html;
}}
if (!function_exists('nxs_getPostImage')){ function nxs_getPostImage($postID, $size='large', $def='') { $imgURL = '';
  if (function_exists("get_post_thumbnail_id") ){ $imgURL = wp_get_attachment_image_src(get_post_thumbnail_id($postID), $size); $imgURL = $imgURL[0];} 
  if ($imgURL=='') {$post = get_post($postID); $imgsFromPost = nsFindImgsInPost($post);  if (is_array($imgsFromPost) && count($imgsFromPost)>0) $imgURL = $imgsFromPost[0]; } //echo "##".count($imgsFromPost); prr($imgsFromPost);
  if ($imgURL=='' && $def=='') { global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; $imgURL = $options['ogImgDef']; } 
  if ($imgURL=='' && $def!='') $imgURL = $def; 
  return $imgURL;
}}
 
//## CSS && JS
if (!function_exists("jsPostToSNAP")) { function jsPostToSNAP() {  global $nxs_snapAvNts; ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {          
    <?php       
      foreach ($nxs_snapAvNts as $avNt) {?>
        $('input#rePostTo<?php echo $avNt['code']; ?>_button').click(function() { var data = { action: 'rePostTo<?php echo $avNt['code']; ?>', id: $('input#post_ID').val(), nid:$(this).attr('alt'), _wpnonce: $('input#rePostTo<?php echo $avNt['code']; ?>_wpnonce').val()}; callAjSNAP(data, '<?php echo $avNt['name']; ?>'); });
    <?php } ?>
       function callAjSNAP(data, label) {
            var style = "position: fixed; display: none; z-index: 1000; top: 50%; left: 50%; background-color: #E8E8E8; border: 1px solid #555; padding: 15px; width: 350px; min-height: 80px; margin-left: -175px; margin-top: -40px; text-align: center; vertical-align: middle;";
            $('body').append("<div id='test_results' style='" + style + "'></div>");
            $('#test_results').html("<p>Sending update to "+label+"</p>" + "<p><img src='http://gtln.us/img/misc/ajax-loader-med.gif' /></p>");
            $('#test_results').show();            
            jQuery.post(ajaxurl, data, function(response) { if (response=='') response = 'Message Posted';
                $('#test_results').html('<p> ' + response + '</p>' +'<input type="button" class="button" name="results_ok_button" id="results_ok_button" value="OK" />');
                $('#results_ok_button').click(remove_results);
            });
            
        }        
        function remove_results() { jQuery("#results_ok_button").unbind("click");jQuery("#test_results").remove();
            if (typeof document.body.style.maxHeight == "undefined") { jQuery("body","html").css({height: "auto", width: "auto"}); jQuery("html").css("overflow","");}
            document.onkeydown = "";document.onkeyup = "";  return false;
        }
    });
    </script>    
    <?php
  }
}
if (!function_exists("nxs_jsPostToSNAP2")){ function nxs_jsPostToSNAP2() {  global $nxs_snapAvNts, $nxs_snapThisPageUrl, $plgn_NS_SNAutoPoster; 
   if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
?>

 <script type="text/javascript"> if (typeof jQuery == 'undefined') {var script = document.createElement('script'); script.type = "text/javascript"; 
              script.src = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"; document.getElementsByTagName('head')[0].appendChild(script);
            }</script>
            <script type="text/javascript">
            (function(b){b.fn.bPopup=function(n,p){function t(){b.isFunction(a.onOpen)&&a.onOpen.call(c);k=(e.data("bPopup")||0)+1;d="__bPopup"+k;l="auto"!==a.position[1];m="auto"!==a.position[0];i="fixed"===a.positionStyle;j=r(c,a.amsl);f=l?a.position[1]:j[1];g=m?a.position[0]:j[0];q=s();a.modal&&b('<div class="bModal '+d+'"></div>').css({"background-color":a.modalColor,height:"100%",left:0,opacity:0,position:"fixed",top:0,width:"100%","z-index":a.zIndex+k}).each(function(){a.appending&&b(this).appendTo(a.appendTo)}).animate({opacity:a.opacity},a.fadeSpeed);c.data("bPopup",a).data("id",d).css({left:!a.follow[0]&&m||i?g:h.scrollLeft()+g,position:a.positionStyle||"absolute",top:!a.follow[1]&&l||i?f:h.scrollTop()+f,"z-index":a.zIndex+k+1}).each(function(){a.appending&&b(this).appendTo(a.appendTo);if(null!=a.loadUrl)switch(a.contentContainer=b(a.contentContainer||c),a.content){case "iframe":b('<iframe scrolling="no" frameborder="0"></iframe>').attr("src",a.loadUrl).appendTo(a.contentContainer);break;default:a.contentContainer.load(a.loadUrl)}}).fadeIn(a.fadeSpeed,function(){b.isFunction(p)&&p.call(c);u()})}function o(){a.modal&&b(".bModal."+c.data("id")).fadeOut(a.fadeSpeed,function(){b(this).remove()});c.stop().fadeOut(a.fadeSpeed,function(){null!=a.loadUrl&&a.contentContainer.empty()});e.data("bPopup",0<e.data("bPopup")-1?e.data("bPopup")-1:null);a.scrollBar||b("html").css("overflow","auto");b("."+a.closeClass).die("click."+d);b(".bModal."+d).die("click");h.unbind("keydown."+d);e.unbind("."+d);c.data("bPopup",null);b.isFunction(a.onClose)&&setTimeout(function(){a.onClose.call(c)},a.fadeSpeed);return!1}function u(){e.data("bPopup",k);b("."+a.closeClass).live("click."+d,o);a.modalClose&&b(".bModal."+d).live("click",o).css("cursor","pointer");(a.follow[0]||a.follow[1])&&e.bind("scroll."+d,function(){q&&c.stop().animate({left:a.follow[0]&&!i?h.scrollLeft()+g:g,top:a.follow[1]&&!i?h.scrollTop()+f:f},a.followSpeed)}).bind("resize."+d,function(){if(q=s())j=r(c,a.amsl),a.follow[0]&&(g=m?g:j[0]),a.follow[1]&&(f=l?f:j[1]),c.stop().each(function(){i?b(this).css({left:g,top:f},a.followSpeed):b(this).animate({left:m?g:g+h.scrollLeft(),top:l?f:f+h.scrollTop()},a.followSpeed)})});a.escClose&&h.bind("keydown."+d,function(a){27==a.which&&o()})}function r(a,b){var c=(e.width()-a.outerWidth(!0))/2,d=(e.height()-a.outerHeight(!0))/2-b;return[c,20>d?20:d]}function s(){return e.height()>c.outerHeight(!0)+20&&e.width()>c.outerWidth(!0)+20}b.isFunction(n)&&(p=n,n=null);var a=b.extend({},b.fn.bPopup.defaults,n);a.scrollBar||b("html").css("overflow","hidden");var c=this,h=b(document),e=b(window),k,d,q,l,m,i,j,f,g;this.close=function(){a=c.data("bPopup");o()};return this.each(function(){c.data("bPopup")||t()})};b.fn.bPopup.defaults={amsl:50,appending:!0,appendTo:"body",closeClass:"bClose",content:"ajax",contentContainer:null,escClose:!0,fadeSpeed:250,follow:[!0,!0],followSpeed:500,loadUrl:null,modal:!0,modalClose:!0,modalColor:"#000",onClose:null,onOpen:null,opacity:0.7,position:["auto","auto"],positionStyle:"absolute",scrollBar:!0,zIndex:9997}})(jQuery);
            </script>
            <script type="text/javascript">   
            
           // function blinks(hide) { if(hide==1) { jQuery('.blnkg').show(); hide = 0; } else {  jQuery('.blnkg').hide(); hide = 1; } setTimeout("blinks("+hide+")",400);}            
           // jQuery(document).ready(function(){ blinks(1);});
<?php if( $options['exclCats']!='' && $options['exclCats']!='a:0:{}') { ?>

jQuery(function(){
  jQuery("form input:checkbox[name='post_category[]']").click ( function(){ var thVal = jQuery(this).val(); var arr = [<?php $xarr = maybe_unserialize($options['exclCats']); if (is_array($xarr)) echo "'".implode("','", $xarr)."'"; ?>];
     if ( jQuery.inArray(thVal, arr)>-1) jQuery('.nxsGrpDoChb').removeAttr('checked'); else jQuery(".nxsGrpDoChb[title='def']").attr('checked','checked');
     
  });
});

<?php } ?>
      
      
      function showPopShAtt(imid, e){ if (!jQuery('div#popShAtt'+imid).is(":visible")) jQuery('div#popShAtt'+imid).show().css('top', e.pageY+5).css('left', e.pageX+25).appendTo('body'); }
      function hidePopShAtt(imid){ jQuery('div#popShAtt'+imid).hide(); }
      function doSwitchShAtt(att, idNum){
        //if (att==1) { jQuery('#apFBAttch'+idNum).attr('checked', true); jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else {jQuery('#apFBAttch'+idNum).attr('checked', false); jQuery('#apFBAttchShare'+idNum).attr('checked', true);}
        if (att==1) { if (jQuery('#apFBAttch'+idNum).is(":checked")) jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else { if( jQuery('#apFBAttchShare'+idNum).is(":checked")) jQuery('#apFBAttch'+idNum).attr('checked', false);}
      }

            
     (function($) {
        $(function() {
            $('#nxs_snapAddNew').bind('click', function(e) { e.preventDefault(); $('#nxs_spPopup').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [65, 50]}); });
            $('#showLic').bind('click', function(e) { e.preventDefault(); $('#showLicForm').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false]}); });
         });
     })(jQuery);
     
     jQuery(document).ready(function() {
         
          jQuery('#nxsAPIUpd').dblclick(function() { doLic();  });

 //When page loads...
 jQuery(".nsx_tab_content").hide(); //Hide all content
 jQuery("ul.nsx_tabs li:first").addClass("active").show(); //Activate first tab
 jQuery(".nsx_tab_content:first").show(); //Show first tab content

 //On Click Event
 jQuery("ul.nsx_tabs li").click(function() {

  jQuery("ul.nsx_tabs li").removeClass("active"); //Remove any "active" class
  jQuery(this).addClass("active"); //Add "active" class to selected tab
  jQuery(".nsx_tab_content").hide(); //Hide all tab content

  var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
  jQuery(activeTab).fadeIn(); //Fade in the active ID content
  return false;
 });

});
            
                
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
            function seFBA(pgID,fbAppID,fbAppSec){ var data = { pgID: pgID, action: 'nsAuthFBSv', _wpnonce: jQuery('input#nsFB_wpnonce').val()}; 
              jQuery.post(ajaxurl, data, function(response) {  
                window.location = "https://www.facebook.com/dialog/oauth?client_id="+fbAppID+"&client_secret="+fbAppSec+"&scope=publish_stream,offline_access,read_stream,manage_pages&redirect_uri=<?php echo $nxs_snapThisPageUrl;?>";
              });                       
            }
            
            function doLic(){ var lk = jQuery('#eLic').val(); 
                jQuery.post(ajaxurl,{lk:lk, action: 'nxsDoLic', id: 0, _wpnonce: jQuery('input#doLic_wpnonce').val(), ajax: 'true'}, function(j){ 
                    if (j=='OK') window.location = "<?php echo $nxs_snapThisPageUrl; ?>"; else alert('Wrong key, please contact support');
                }, "html")
            }
            
           
            
            function getBoards(u,p,ii){ jQuery("#pnLoadingImg").show();
                
                jQuery.post(ajaxurl,{u:u,p:p,ii:ii, action: 'getBoards', id: 0, _wpnonce: jQuery('input#getBoards_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
                    jQuery("select#apPNBoard").html(j); jQuery("#pnLoadingImg").hide();
                }, "html")

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
        function testPost(nt, nid){ jQuery(".blnkg").hide(); <?php foreach ($nxs_snapAvNts as $avNt) {?>
            if (nt=='<?php echo $avNt['code']; ?>') { 
                var data = { action: 'rePostTo<?php echo $avNt['code']; ?>', id: 0, nid: nid, _wpnonce: jQuery('input#rePostTo<?php echo $avNt['code']; ?>_wpnonce').val()}; callAjSNAP(data, '<?php echo $avNt['name']; ?>'); 
            }<?php } ?>
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
        function nxs_TRSetEnable(ptype, ii){
              if (ptype=='I'){ jQuery('#apTRMsgTFrmt'+ii).attr('disabled', 'disabled'); jQuery('#apTRDefImg'+ii).removeAttr('disabled'); } 
                else { jQuery('#apTRDefImg'+ii).attr('disabled', 'disabled');  jQuery('#apTRMsgTFrmt'+ii).removeAttr('disabled'); }                
        }
        function nxsTRURLVal(ii){ var val = jQuery('#apTRURL'+ii).val(); var srch = val.toLowerCase().indexOf('http://www.tumblr.com/blog/');
        if (srch>-1) { jQuery('#apTRURL'+ii).css({"background-color":"#FFC0C0"}); jQuery('#apTRURLerr'+ii).html('<br/>Incorrect URL: Please note that URL of your Tumblr Blog should be your public URL. (i.e. like http://nextscripts.tumblr.com/, not http://www.tumblr.com/blog/nextscripts'); } else { jQuery('#apTRURL'+ii).css({"background-color":"#ffffff"}); jQuery('#apTRURLerr'+ii).text(''); }

            
        }
        
        
        
        
        </script>
<link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>            
<style type="text/css">
.NXSButton { background-color:#89c403;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809) );
    background:-moz-linear-gradient( center top, #89c403 5%, #77a809 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809');    
    -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; border:1px solid #74b807; display:inline-block; color:#ffffff;
    font-family:Trebuchet MS; font-size:12px; font-weight:bold; padding:4px 5px;  text-decoration:none;  text-shadow:1px 1px 0px #528009;
}.NXSButton:hover {color:#ffffff; background-color:#77a809;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403) );
    background:-moz-linear-gradient( center top, #77a809 5%, #89c403 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77a809', endColorstr='#89c403');    
}.NXSButton:active {color:#ffffff; position:relative; top:1px;}.NXSButton:focus {color:#ffffff; position:relative; top:1px;} .nsBigText{font-size: 14px; color: #585858; font-weight: bold; display: inline;}
.nxspButton:hover { background-color: #1E1E1E;}
.nxspButton { background-color: #2B91AF; color: #FFFFFF; cursor: pointer; display: inline-block; text-align: center; text-decoration: none; border-radius: 6px 6px 6px 6px; box-shadow: none; font: bold 131% sans-serif; padding: 0 6px 2px; position: absolute; right: -7px; top: -7px;}
#nxs_spPopup, #showLicForm{ min-height: 250px; background-color: #FFFFFF; border-radius: 5px 5px 5px 5px;  box-shadow: 0 0 3px 2px #999999; color: #111111; display: none;  min-width: 850px; padding: 25px;}
#nxs_ntType {width: 150px;}
#nsx_addNT {width: 600px;}
.nxsInfoMsg{  margin: 1px auto; padding: 3px 10px 3px 5px; border: 1px solid #ffea90;  background-color: #fdfae4; display: inline; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
.blnkg{text-decoration:blink; font-size: 17px; color: #0CB107; font-weight: bold; display: inline;}

div.popShAtt { display: none; position: absolute; width: 600px; padding: 10px; background: #eeeeee; color: #000000; border: 1px solid #1a1a1a; font-size: 90%; }
.underdash {border-bottom: 1px #21759B dashed; text-decoration:none;}
.underdash a:hover {border-bottom: 1px #21759B dashed}

ul.nsx_tabs {margin: 0;padding: 0;float: left;list-style: none;height: 32px;border-bottom: 1px solid #999;border-left: 1px solid #999;width: 100%;}
ul.nsx_tabs li {float: left;margin: 0;padding: 0;height: 31px;line-height: 31px;border: 1px solid #999;border-left: none;margin-bottom: -1px;overflow: hidden;position: relative;background: #e0e0e0;}
ul.nsx_tabs li a {text-decoration: none;color: #000; display: block; font-size: 1.2em; padding: 0 20px; border: 1px solid #fff; outline: none;}
ul.nsx_tabs li a:hover { background: #ccc;}
html ul.nsx_tabs li.active, html ul.nsx_tabs li.active a:hover  { background: #fff; border-bottom: 1px solid #fff; }
.nsx_tab_container {border: 1px solid #999; border-top: none; overflow: hidden; clear: both; float: left; width: 100%; background: #fff;}
.nsx_tab_content {padding: 10px;}

.nsx_iconedTitle {font-size: 17px; font-weight: bold; margin-bottom: 15px; padding-left: 20px; background-repeat: no-repeat; }
.subDiv{margin-left: 15px;}
.nxs_hili {color:#008000;}
.clNewNTSets{width: 800px;}

.nxs_icon16 { font-size: 14px; line-height: 18px;
    background-position: 3px 50% !important;
    background-repeat: no-repeat !important;
    display: inline-block;
    padding: 1px 0 1px 23px !important;
}

</style>
<?php }}

if (!function_exists('nxs_doShowHint')){ function nxs_doShowHint($t){ ?>
<div id="<?php echo $t; ?>Hint" class="nxs_FRMTHint" style="font-size: 11px; margin: 2px; margin-top: 0px; padding:7px; border: 1px solid #C0C0C0; width: 49%; background: #fff; display: none;"><span class="nxs_hili">%SITENAME%</span> - Inserts the Your Blog/Site Name, <span class="nxs_hili">%TITLE%</span> - Inserts the Title of your post, <span class="nxs_hili">%URL%</span> - Inserts the URL of your post, <span class="nxs_hili">%SURL%</span> - Inserts the <b>Shortened URL</b> of your post, <span class="nxs_hili">%IMG%</span> - Inserts the featured image, <span class="nxs_hili">%TEXT%</span> - Inserts the excerpt of your post, <span class="nxs_hili">%RAWTEXT%</span> - Inserts the body(text) as typed, <span class="nxs_hili">%FULLTEXT%</span> - Inserts the processed body(text) of your post, <span class="nxs_hili">%AUTHORNAME%</span> - Inserts the author's name.</div>
<?php }}
if (!function_exists('getNSXOption')){ function getNSXOption($t){ @eval($t);}} 
if (!function_exists('nxs_doSMAS')){ function nxs_doSMAS($nType, $typeii) { ?><div id="do<?php echo $typeii; ?>Div" class="clNewNTSets" style="margin-left: 10px; display:none; "><div style="font-size: 15px; text-align: center;"><br/><br/>You already have <?php echo $nType; ?> configured.  This plugin supports only one <?php echo $nType; ?> account. <br/><br/> Please consider getting <a target="_blank" href="http://www.nextscripts.com/social-networks-auto-poster-for-wp-multiple-accounts">Multiple Accounts Edition</a> if you would like to add another <?php echo $nType; ?> account for auto-posting.</div></div><?php 
}}
if (!function_exists("nxs_doChAPIU")) { //## Second Function to Post to TW 
  function nxs_doChAPIU($optionsX){
    global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; if ($options=='' || !is_array($options)) return;  
    $options = getRemNSXOption($options); if(is_array($options)) update_option('NS_SNAutoPoster', $options); 
    //nxs_addToLog('API', 'M', '<span style="color:#008000; font-weight:bold;">------=========#### CHECK FOR API UPDATE - '.$options['ukver'].' ####=========------</span>'); // echo "UUU";
  // $myFile = "/home/_shared/deSrc.testFile.txt"; $fh = fopen($myFile, 'w') or die("can't open file");$stringData = "Out 3 \n".print_r($options, true);fwrite($fh, $stringData);fclose($fh); 
  return $options;
}}
if (!function_exists('nxs_snapCleanup')){ function nxs_snapCleanup($options){  global $nxs_snapAvNts; 
    foreach ($nxs_snapAvNts as $avNt) { if (!isset($options[$avNt['lcode']]) || count($options[$avNt['lcode']])>1) { $copt = ''; $t = '';
      if (isset($options[$avNt['lcode']]) && is_array($options[$avNt['lcode']])) $copt = array_values( $options[$avNt['lcode']] );  
      $t = (isset($copt[0]) && is_array($copt[0]) && count($copt[0]>2))?$copt[0]:''; $options[$avNt['lcode']] = array(); if ($t!='') $options[$avNt['lcode']][] = $t;
    }}
    return $options;
}}
if (!function_exists('getRemNSXOption')){ function getRemNSXOption($t){ if (!isset($t['lk']) || $t['lk']=='') return $t;  if (!isset($t['ukver'])) $t['ukver'] = ''; if (!isset($t['uklch'])) $t['uklch'] = ''; 
  $arr = array('method' => 'POST', 'timeout' => 45,'blocking' => true, 'headers' => array(), 'body' => array( 'lk' => $t['lk'], 'ukver' => $t['ukver'], 'ud' => home_url()));// prr($arr);
  $response = wp_remote_post( 'http://www.nextscripts.com/nxs.php', $arr);    
  if ($response['body']!='' && $response['response']['code']=='200') $t['uk'] = $response['body']; $t['uklch'] = time(); //prr($response); prr($t);
  return $t;
}} 
if (!function_exists('nxs_html_to_utf8')){ function nxs_html_to_utf8 ($data){return preg_replace("/\\&\\#([0-9]{3,10})\\;/e", 'nxs__html_to_utf8("\\1")', $data); }}
if (!function_exists('nxs__html_to_utf8')){ function nxs__html_to_utf8 ($data){ if ($data > 127){ $i = 5; while (($i--) > 0){
  if ($data != ($a = $data % ($p = pow(64, $i)))){ 
    $ret = chr(base_convert(str_pad(str_repeat(1, $i + 1), 8, "0"), 2, 10) + (($data - $a) / $p)); for ($i; $i > 0; $i--) $ret .= chr(128 + ((($data % pow(64, $i)) - ($data % ($p = pow(64, $i - 1)))) / $p)); break; }
  }} else $ret = "&#$data;";
  return $ret;
}}
if (!function_exists("nxs_chArrVar")) { function nxs_chArrVar($arr, $varN, $varV){ return (isset($arr) && is_array($arr) && isset($arr[$varN]) && $arr[$varN]==$varV); }}
    
    
if (!function_exists("nxs_metaMarkAsPosted")) { function nxs_metaMarkAsPosted($postID, $nt, $did, $args=''){ $mpo =  get_post_meta($postID, 'snap'.$nt, true); $mpo =  maybe_unserialize($mpo); 
  if (!is_array($mpo)) $mpo = array(); if (!is_array($mpo[$did])) $mpo[$did] = array();
  if ($args=='' || $args['isPosted']==1) $mpo[$did]['isPosted'] = '1';  
  if (is_array($args) && isset($args['isPrePosted']) && $args['isPrePosted']==1) $mpo[$did]['isPrePosted'] = '1';  
  if (is_array($args) && isset($args['pgID'])) $mpo[$did]['pgID'] = $args['pgID'];  
  $mpo = mysql_real_escape_string(serialize($mpo)); delete_post_meta($postID, 'snap'.$nt); add_post_meta($postID, 'snap'.$nt, $mpo);
}}
if (!function_exists('nxs_addToLog')){ function nxs_addToLog ($nt, $type, $msg, $extInfo=''){ global $nxs_tpWMPU; if($nxs_tpWMPU=='S') switch_to_blog(1);  $nxsDBLog = get_option('NS_SNAutoPosterLog'); $nxsDBLog = maybe_unserialize($nxsDBLog); 
  $logItem = array('date'=>date('Y-m-d H:i:s'), 'msg'=>$msg, 'extInfo'=>$extInfo, 'type'=>$type, 'nt'=>$nt); if(!is_array($nxsDBLog)) $nxsDBLog = array();
  $nxsDBLog[] = $logItem; $nxsDBLog = array_slice($nxsDBLog, -150);  update_option('NS_SNAutoPosterLog', serialize($nxsDBLog)); if($nxs_tpWMPU=='S') restore_current_blog();
}}

if (!function_exists('nxsMergeArraysOV')){function nxsMergeArraysOV($Arr1, $Arr2){
  foreach($Arr2 as $key => $Value) { if(array_key_exists($key, $Arr1) && is_array($Value)) $Arr1[$key] = nxsMergeArraysOV($Arr1[$key], $Arr2[$key]); else $Arr1[$key] = $Value;} return $Arr1;
}}

if (!function_exists('nxs_addPostingDelaySel')){function nxs_addPostingDelaySel($nt, $ii, $hrs=0, $min=0){  
  if (function_exists('nxs_doSMAS4')) return nxs_doSMAS4($nt, $ii, $hrs, $min); else return '<br/>';
}}

if (!function_exists("nxs_doQTrans")) { function nxs_doQTrans($txt, $lng=''){
    if (!function_exists(qtrans_split) || strpos($txt, '<!--:')===false ) return $txt; else {
        $tta = qtrans_split($txt); if ($lng!='') return $tta[$lng]; else return reset($tta);
    }
}}

if (!function_exists('nxs_addQTranslSel')){function nxs_addQTranslSel($nt, $ii, $selLng){  
  if (function_exists('nxs_doSMAS5')) return nxs_doSMAS5($nt, $ii, $selLng); else return '<br/>';  
}}

class NXS_HtmlFixer { public $dirtyhtml; public $fixedhtml; public $allowed_styles; private $matrix; public $debug; private $fixedhtmlDisplayCode;
    public function __construct() { $this->dirtyhtml = ""; $this->fixedhtml = ""; $this->debug = false; $this->fixedhtmlDisplayCode = ""; $this->allowed_styles = array();}
    public function getFixedHtml($dirtyhtml) { $c = 0; $this->dirtyhtml = $dirtyhtml; $this->fixedhtml = ""; $this->fixedhtmlDisplayCode = ""; if (is_array($this->matrix)) unset($this->matrix); $errorsFound=0;
      while ($c<10) { if ($c>0) $this->dirtyhtml = $this->fixedxhtml; $errorsFound = $this->charByCharJob(); if (!$errorsFound) $c=10;  $this->fixedxhtml=str_replace('<root>','',$this->fixedxhtml); 
        $this->fixedxhtml=str_replace('</root>','',$this->fixedxhtml); $this->fixedxhtml = $this->removeSpacesAndBadTags($this->fixedxhtml); $c++;
      } return $this->fixedxhtml;
    }
    private function fixStrToLower($m){ $right = strstr($m, '='); $left = str_replace($right,'',$m); return strtolower($left).$right;}
    private function fixQuotes($s){ $q = "\""; if (!stristr($s,"=")) return $s; $out = $s; preg_match_all("|=(.*)|",$s,$o,PREG_PATTERN_ORDER);
      for ($i = 0; $i< count ($o[1]); $i++) { $t = trim ( $o[1][$i] ) ; $lc=""; if ($t!="") { if ($t[strlen($t)-1]==">") { $lc= ($t[strlen($t)-2].$t[strlen($t)-1])=="/>"  ?  "/>"  :  ">" ; $t=substr($t,0,-1);}
        if (($t[0]!="\"")&&($t[0]!="'")) $out = str_replace( $t, "\"".$t,$out); else $q=$t[0]; if (($t[strlen($t)-1]!="\"")&&($t[strlen($t)-1]!="'")) $out = str_replace( $t.$lc, $t.$q.$lc,$out);
      }} return $out;
    }
    private function fixTag($t){ $t = preg_replace ( array( '/borderColor=([^ >])*/i', '/border=([^ >])*/i' ),  array('',''), $t);
        $ar = explode(" ",$t); $nt = ""; for ($i=0;$i<count($ar);$i++) { $ar[$i]=$this->fixStrToLower($ar[$i]); if (stristr($ar[$i],"=")) $ar[$i] = $this->fixQuotes($ar[$i]); $nt.=$ar[$i]." ";   }
        $nt=preg_replace("/<( )*/i","<",$nt); $nt=preg_replace("/( )*>/i",">",$nt); return trim($nt);
    }
    private function extractChars($tag1,$tag2,$tutto) {  if (!stristr($tutto, $tag1)) return ''; $s=stristr($tutto,$tag1); $s=substr( $s,strlen($tag1)); if (!stristr($s,$tag2)) return '';
        $s1=stristr($s,$tag2); return substr($s,0,strlen($s)-strlen($s1));
    }
    private function mergeStyleAttributes($s) { $x = ""; $temp = ""; $c = 0;
        while(stristr($s,"style=\"")) {$temp = $this->extractChars("style=\"","\"",$s); if ($temp=="") { return preg_replace("/(\/)?>/i","\"\\1>",$s);}
            if ($c==0) $s = str_replace("style=\"".$temp."\"","##PUTITHERE##",$s); $s = str_replace("style=\"".$temp."\"","",$s); if (!preg_match("/;$/i",$temp)) $temp.=";"; $x.=$temp; $c++;
        }
        if (count($this->allowed_styles)>0) { $check=explode(';', $x); $x=""; foreach($check as $chk){ foreach($this->allowed_styles as $as) if(stripos($chk, $as) !== False) { $x.=$chk.';'; break; } }}
        if ($c>0) $s = str_replace("##PUTITHERE##","style=\"".$x."\"",$s);return $s;
    }
    private function fixAutoclosingTags($tag,$tipo=""){ if (in_array( $tipo, array ("img","input","br","hr")) ) { if (!stristr($tag,'/>')) $tag = str_replace('>','/>',$tag ); } return $tag;}
    private function getTypeOfTag($tag) { $tag = trim(preg_replace("/[\>\<\/]/i","",$tag)); $a = explode(" ",$tag); return $a[0];}
    private function checkTree() { $errorsCounter = 0; for ($i=1;$i<count($this->matrix);$i++) { $flag=false;
      if ($this->matrix[$i]["tagType"]=="div") { $parentType = $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]; if (in_array($parentType, array("p","b","i","font","u","small","strong","em"))) $flag=true; }
      if (in_array( $this->matrix[$i]["tagType"], array( "b", "strong" )) ) {  $parentType = $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]; if (in_array($parentType, array("b","strong"))) $flag=true; }
      if (in_array( $this->matrix[$i]["tagType"], array ( "i", "em") )) {  $parentType = $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]; if (in_array($parentType, array("i","em"))) $flag=true; }
      if ($this->matrix[$i]["tagType"]=="p") { $parentType = $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]; if (in_array($parentType, array("p","b","i","font","u","small","strong","em"))) $flag=true; }
      if ($this->matrix[$i]["tagType"]=="table") { $parentType = $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]; if (in_array($parentType, array("p","b","i","font","u","small","strong","em","tr","table"))) $flag=true; }
      if ($flag) { $errorsCounter++; if ($this->debug) echo "<div style='color:#ff0000'>Found a <b>".$this->matrix[$i]["tagType"]."</b> tag inside a <b>".htmlspecialchars($parentType)."</b> tag at node $i: MOVED</div>";                
        $swap = $this->matrix[$this->matrix[$i]["parentTag"]]["parentTag"]; if ($this->debug) echo "<div style='color:#ff0000'>Every node that has parent ".$this->matrix[$i]["parentTag"]." will have parent ".$swap."</div>";
        $this->matrix[$this->matrix[$i]["parentTag"]]["tag"]="<!-- T A G \"".$this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]."\" R E M O V E D -->"; $this->matrix[$this->matrix[$i]["parentTag"]]["tagType"]="";
        $hoSpostato=0;for ($j=count($this->matrix)-1;$j>=$i;$j--) { if ($this->matrix[$j]["parentTag"]==$this->matrix[$i]["parentTag"]) { $this->matrix[$j]["parentTag"] = $swap; $hoSpostato=1; }}
      }}return $errorsCounter;
    }
    private function findSonsOf($parentTag) { $out= "";
      for ($i=1;$i<count($this->matrix);$i++) { if ($this->matrix[$i]["parentTag"]==$parentTag) {
          if ($this->matrix[$i]["tag"]!="") { $out.=$this->matrix[$i]["pre"]; $out.=$this->matrix[$i]["tag"]; $out.=$this->matrix[$i]["post"]; } else { $out.=$this->matrix[$i]["pre"]; $out.=$this->matrix[$i]["post"];}
          if ($this->matrix[$i]["tag"]!="") { $out.=$this->findSonsOf($i); if ($this->matrix[$i]["tagType"]!="") { if (!in_array($this->matrix[$i]["tagType"], array ( "br","img","hr","input"))) $out.="</". $this->matrix[$i]["tagType"].">";}}
      }}return $out;
    }
    private function findSonsOfDisplayCode($parentTag) { $out= "";
        for ($i=1;$i<count($this->matrix);$i++) {
            if ($this->matrix[$i]["parentTag"]==$parentTag) { $out.= "<div style=\"padding-left:15\"><span style='float:left;background-color:#FFFF99;color:#000;'>{$i}:</span>";
                if ($this->matrix[$i]["tag"]!="") { if ($this->matrix[$i]["pre"]!="") $out.=htmlspecialchars($this->matrix[$i]["pre"])."<br>";
                    $out.="".htmlspecialchars($this->matrix[$i]["tag"])."<span style='background-color:red; color:white'>{$i} <em>".$this->matrix[$i]["tagType"]."</em></span>";
                    $out.=htmlspecialchars($this->matrix[$i]["post"]);
                } else { if ($this->matrix[$i]["pre"]!="") $out.=htmlspecialchars($this->matrix[$i]["pre"])."<br>"; $out.=htmlspecialchars($this->matrix[$i]["post"]);}
                if ($this->matrix[$i]["tag"]!="") { $out.="<div>".$this->findSonsOfDisplayCode($i)."</div>\n";
                    if ($this->matrix[$i]["tagType"]!="") {
                        if (($this->matrix[$i]["tagType"]!="br") && ($this->matrix[$i]["tagType"]!="img") && ($this->matrix[$i]["tagType"]!="hr")&& ($this->matrix[$i]["tagType"]!="input"))
                            $out.="<div style='color:red'>".htmlspecialchars("</". $this->matrix[$i]["tagType"].">")."{$i} <em>".$this->matrix[$i]["tagType"]."</em></div>";
                    }
                } $out.="</div>\n";
            }
        }return $out;
    }
    private function removeSpacesAndBadTags($s) { $i=0;
      while ($i<10) { $i++; $s = preg_replace (
        array( '/[\r\n]/i', '/  /i', '/<p([^>])*>(&nbsp;)*\s*<\/p>/i', '/<span([^>])*>(&nbsp;)*\s*<\/span>/i', '/<strong([^>])*>(&nbsp;)*\s*<\/strong>/i', '/<em([^>])*>(&nbsp;)*\s*<\/em>/i',
          '/<font([^>])*>(&nbsp;)*\s*<\/font>/i', '/<small([^>])*>(&nbsp;)*\s*<\/small>/i', '/<\?xml:namespace([^>])*><\/\?xml:namespace>/i', '/<\?xml:namespace([^>])*\/>/i', '/class=\"MsoNormal\"/i',
          '/<o:p><\/o:p>/i', '/<!DOCTYPE([^>])*>/i', '/<!--(.|\s)*?-->/', '/<\?(.|\s)*?\?>/'), 
        array(' ', ' ', '', '', '', '', '', '', '', '', '', ' ', '', '' ) , trim($s));
      }return $s;
    }
    private function charByCharJob() { $s = $this->removeSpacesAndBadTags($this->dirtyhtml); if ($s=="") return;
        $s = "<root>".$s."</root>"; $contenuto = ""; $ns = ""; $i=0; $j=0; $indexparentTag=0; $padri=array(); array_push($padri,"0"); $this->matrix[$j]["tagType"]="";
        $this->matrix[$j]["tag"]=""; $this->matrix[$j]["parentTag"]="0"; $this->matrix[$j]["pre"]=""; $this->matrix[$j]["post"]=""; $tags=array();
        while($i<strlen($s)) {
            if ( $s[$i] =="<") { $contenuto = $ns; $ns = ""; $tag=""; while( $i<strlen($s) && $s[$i]!=">" ){ $tag.=$s[$i]; $i++;} $tag.=$s[$i];                
                if($s[$i]==">") { $tag = $this->fixTag($tag); $tagType = $this->getTypeOfTag($tag); $tag = $this->fixAutoclosingTags($tag,$tagType);
                    $tag = $this->mergeStyleAttributes($tag); if (!isset($tags[$tagType])) $tags[$tagType]=0; $tagok=true;
                    if (($tags[$tagType]==0)&&(stristr($tag,'/'.$tagType.'>'))) { $tagok=false; if ($this->debug) echo "<div style='color:#ff0000'>Found a closing tag <b>".htmlspecialchars($tag)."</b> at char $i without open tag: REMOVED</div>";}
                }
                if ($tagok) { $j++; $this->matrix[$j]["pre"]=""; $this->matrix[$j]["post"]=""; $this->matrix[$j]["parentTag"]=""; $this->matrix[$j]["tag"]=""; $this->matrix[$j]["tagType"]="";
                    if (stristr($tag,'/'.$tagType.'>')) { $ind = array_pop($padri); $this->matrix[$j]["post"]=$contenuto; $this->matrix[$j]["parentTag"]=$ind; $tags[$tagType]--;
                    } else { if (@preg_match("/".$tagType."\/>$/i",$tag)||preg_match("/\/>/i",$tag)) { $this->matrix[$j]["tagType"]=$tagType; $this->matrix[$j]["tag"]=$tag;
                      $indexparentTag = array_pop($padri); array_push($padri,$indexparentTag); $this->matrix[$j]["parentTag"]=$indexparentTag; $this->matrix[$j]["pre"]=$contenuto; $this->matrix[$j]["post"]="";
                    } else { $tags[$tagType]++; $this->matrix[$j]["tagType"]=$tagType; $this->matrix[$j]["tag"]=$tag; $indexparentTag = array_pop($padri); array_push($padri,$indexparentTag);
                      array_push($padri,$j); $this->matrix[$j]["parentTag"]=$indexparentTag; $this->matrix[$j]["pre"]=$contenuto; $this->matrix[$j]["post"]=""; }
                    }
                }
            } else { $ns.=$s[$i]; } $i++;
        } for ($eli=$j+1;$eli<count($this->matrix);$eli++) { $this->matrix[$eli]["pre"]=""; $this->matrix[$eli]["post"]=""; $this->matrix[$eli]["parentTag"]=""; $this->matrix[$eli]["tag"]=""; $this->matrix[$eli]["tagType"]="";}
        $errorsCounter = $this->checkTree();  $this->fixedxhtml=$this->findSonsOf(0);return $errorsCounter;
    }
}
?>