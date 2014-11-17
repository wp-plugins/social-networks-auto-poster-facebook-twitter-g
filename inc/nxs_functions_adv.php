<?php
if (!function_exists("nxs_settings_save")) {function nxs_settings_save($options) { update_option('NS_SNAutoPoster',$options); }}
//## Format Message
if (!function_exists("nsFormatMessage")) { function nsFormatMessage($msg, $postID, $addURLParams='', $lng=''){ global $ShownAds, $plgn_NS_SNAutoPoster, $nxs_urlLen; $post = get_post($postID); $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (!empty($options['nxsHTSpace'])) $htS = $options['nxsHTSpace']; else $htS = '';
  // if ($addURLParams=='' && $options['addURLParams']!='') $addURLParams = $options['addURLParams'];
  $msg = str_replace('%TEXT%','%EXCERPT%',$msg); $msg = str_replace('%RAWEXTEXT%','%RAWEXCERPT%',$msg);
  $msg = stripcslashes($msg); if (isset($ShownAds)) $ShownAdsL = $ShownAds; // $msg = htmlspecialchars(stripcslashes($msg)); 
  $msg = nxs_doSpin($msg);
  if (preg_match('%URL%', $msg)) { $url = get_permalink($postID); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams;  $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%URL%", $url, $msg);}
  if (preg_match('%MYURL%', $msg)) { $url =  get_post_meta($postID, 'snap_MYURL', true); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams;  $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%MYURL%", $url, $msg);}
  if (preg_match('%SURL%', $msg)) { $url = get_permalink($postID); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams; 
    $url = nxs_mkShortURL($url, $postID); $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%SURL%", $url, $msg);
  }
  if (preg_match('%ORID%', $msg)) { $msg = str_ireplace("%ORID%", $postID, $msg); } 
  if (preg_match('%IMG%', $msg)) { $imgURL = nxs_getPostImage($postID); $msg = str_ireplace("%IMG%", $imgURL, $msg); } 
  if (preg_match('%TITLE%', $msg)) { $title = nxs_doQTrans($post->post_title, $lng);  $msg = str_ireplace("%TITLE%", $title, $msg); }                    
  if (preg_match('%FULLTITLE%', $msg)) { $title = apply_filters('the_title', nxs_doQTrans($post->post_title, $lng));  $msg = str_ireplace("%FULLTITLE%", $title, $msg); }                    
  if (preg_match('%STITLE%', $msg)) { $title = nxs_doQTrans($post->post_title, $lng);   $title = substr($title, 0, 115); $msg = str_ireplace("%STITLE%", $title, $msg); }                    
  if (preg_match('%AUTHORNAME%', $msg)) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun );  $msg = str_ireplace("%AUTHORNAME%", $aun, $msg);}                    
  if (preg_match('%ANNOUNCE%', $msg)) { $postContent = nxs_doQTrans($post->post_content, $lng);     
    $postContent = strip_tags(strip_shortcodes(str_ireplace('<!--more-->', '#####!--more--!#####', str_ireplace("&lt;!--more--&gt;", '<!--more-->', $postContent))));
    if (stripos($postContent, '#####!--more--!#####')!==false) { $postContentEx = explode('#####!--more--!#####',$postContent); $postContent = $postContentEx[0]; }    
      else $postContent = nsTrnc($postContent, $options['anounTagLimit']);  $msg = str_ireplace("%ANNOUNCE%", $postContent, $msg);
  }  
  if (preg_match('%EXCERPT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = strip_tags(strip_shortcodes(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)))); 
      else $excerpt= nsTrnc(strip_tags(strip_shortcodes(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)))), 300, " ", "..."); 
    $msg = str_ireplace("%EXCERPT%", $excerpt, $msg);
  }  
  if (preg_match('%RAWEXCERPT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = strip_tags(strip_shortcodes(nxs_doQTrans($post->post_excerpt, $lng))); else $excerpt= nsTrnc(strip_tags(strip_shortcodes(nxs_doQTrans($post->post_content, $lng))), 300, " ", "..."); 
    $msg = str_ireplace("%RAWEXCERPT%", $excerpt, $msg);
  }
  if (preg_match('%RAWEXCERPTHTML%', $msg)) { 
      if ($post->post_excerpt!="") $excerpt = strip_shortcodes(nxs_doQTrans($post->post_excerpt, $lng)); else $excerpt= nsTrnc(strip_tags(strip_shortcodes(nxs_doQTrans($post->post_content, $lng))), 300, " ", "..."); 
       $msg = str_ireplace("%RAWEXCERPTHTML%", $excerpt, $msg);
  }
  if (preg_match('%TAGS%', $msg)) { $t = wp_get_object_terms($postID, 'product_tag'); if ( empty($t) || is_wp_error($pt) || !is_array($t) ) $t = wp_get_post_tags($postID);
    $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(', ',$tggs); $msg = str_ireplace("%TAGS%", $tags, $msg);
  }
  if (preg_match('%CATS%', $msg)) { $t = wp_get_post_categories($postID); $cats = array();  foreach($t as $c){ $cat = get_category($c); $cats[] = str_ireplace('&','&amp;',$cat->name); } 
          $ctts = implode(', ',$cats); $msg = str_ireplace("%CATS%", $ctts, $msg);
  }
  if (preg_match('%HCATS%', $msg)) { $t = wp_get_post_categories($postID); $cats = array();  
    foreach($t as $c){ $cat = get_category($c);  $cats[] = "#".trim(str_replace(' ',$htS, str_replace('  ', ' ', trim(str_ireplace('&','',str_ireplace('&amp;','',$cat->name)))))); } 
    $ctts = implode(', ',$cats); $msg = str_ireplace("%HCATS%", $ctts, $msg);
  }  
  if (preg_match('%HTAGS%', $msg)) { $t = wp_get_object_terms($postID, 'product_tag'); if ( empty($t) || is_wp_error($pt) || !is_array($t) ) $t = wp_get_post_tags($postID);
    $tggs = array(); foreach ($t as $tagA){$tggs[] = "#".trim(str_replace(' ', $htS, preg_replace('/[^a-zA-Z0-9\p{L}\p{N}\s]/u', '', trim(nxs_ucwords(str_ireplace('&','',str_ireplace('&amp;','',$tagA->name)))))));} 
    $tags = implode(', ',$tggs); $msg = str_ireplace("%HTAGS%", $tags, $msg);
  } 
  if (preg_match('%CF-[a-zA-Z0-9_]%', $msg)) { $msgA = explode('%CF', $msg); $mout = '';
    foreach ($msgA as $mms) { 
        if (substr($mms, 0, 1)=='-' && stripos($mms, '%')!==false) { $mGr = CutFromTo($mms, '-', '%'); $cfItem =  get_post_meta($postID, $mGr, true);  $mms = str_ireplace("-".$mGr."%", $cfItem, $mms); } $mout .= $mms; 
    } $msg = $mout; 
  }  
  if (preg_match('%FULLTEXT%', $msg)) { $postContent = apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)); $msg = str_ireplace("%FULLTEXT%", $postContent, $msg);}                    
  if (preg_match('%RAWTEXT%', $msg)) { $postContent = nxs_doQTrans($post->post_content, $lng); $msg = str_ireplace("%RAWTEXT%", $postContent, $msg);}
  if (preg_match('%SITENAME%', $msg)) { $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $msg = str_ireplace("%SITENAME%", $siteTitle, $msg);}      
  if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
  return trim($msg);
}}

if (!function_exists("nxs_mbConvertCaseUTF8var")){ function nxs_mbConvertCaseUTF8var($s) { $arr = preg_split("//u", $s, -1, PREG_SPLIT_NO_EMPTY); $result = ""; $mode = false; 
  foreach ($arr as $char) { $res = preg_match('/\\p{Mn}|\\p{Me}|\\p{Cf}|\\p{Lm}|\\p{Sk}|\\p{Lu}|\\p{Ll}|\\p{Lt}|\\p{Sk}|\\p{Cs}/u', $char) == 1; 
    if ($mode) { if (!$res)$mode = false; } elseif ($res) { $mode = true; $char = mb_convert_case($char, MB_CASE_TITLE, "UTF-8"); } $result .= $char; 
  } return $result; 
}} 
if (!function_exists("nxs_ucwords")){ function nxs_ucwords($str) { if (function_exists("mb_convert_case")) return nxs_mbConvertCaseUTF8var($str); else return ucwords($str); }}

if (!function_exists("nxs_getURL")){ function nxs_getURL($options, $postID, $addURLParams='') { global $plgn_NS_SNAutoPoster; $gOptions = $plgn_NS_SNAutoPoster->nxs_options; 
  if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl =  trim(get_post_meta($postID, 'snap_MYURL', true));
  $ssl = (!empty($gOptions['ht']) && $gOptions['ht'] == ord('h')); if ($myurl!='') $options['urlToUse'] = $myurl;
  if ((isset($options['urlToUse']) && trim($options['urlToUse'])!='') || $ssl) { $options['useFBGURLInfo'] = true; } else $options['urlToUse'] = get_permalink($postID);      
  $options['urlToUse'] = $ssl?$gOptions['useSSLCert']:$options['urlToUse']; // $addURLParams = trim($gOptions['addURLParams']);  
  if($addURLParams!='') $options['urlToUse'] .= (strpos($options['urlToUse'],'?')!==false?'&':'?').$addURLParams; return $options;
}}

if (!function_exists('nxs_showListRow')){function nxs_showListRow($ntParams) { $ntInfo = $ntParams['ntInfo']; $nxs_plurl = $ntParams['nxs_plurl']; $ntOpts = $ntParams['ntOpts'];  ?>
          <div class="nxs_box">
            <div class="nxs_box_header"> 
              <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
              <?php $cbo = count($ntOpts); ?> 
              <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
              </div>
            </div>
            <div class="nxs_box_inside">            
            <?php if(!empty($ntParams['checkFunc']) &&  !function_exists($ntParams['checkFunc']['funcName'])) echo $ntParams['checkFunc']['msg']; else foreach ($ntOpts as $indx=>$pbo){  if (trim($pbo['nName']=='')) $pbo['nName'] = $ntInfo['name']; 
              if (empty($pbo[$ntInfo['lcode'].'OK'])) $pbo[$ntInfo['lcode'].'OK'] = !empty($pbo[$ntParams['chkField']])?'1':''; ?>
              <p style="margin:0px;margin-left:5px;"> <img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
              <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && ((isset($pbo['catSel']) && (int)$pbo['catSel'] == 1)||(!empty($pbo['tagsSel'])))) { ?> <input type="radio" id="rbtn<?php echo $ntInfo['lcode'].$indx; ?>" value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" checked="checked" onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);" /> <?php } else { ?>
                <input value="0" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="hidden" />             
                <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> />             
              <?php } ?>            
              <?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
              <?php if (isset($pbo['rpstOn']) && (int)$pbo['rpstOn'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popReActive');" onmouseover="nxs_showPopUpInfo('popReActive', event);"><?php echo "*[R]*" ?></span><?php } ?>
              <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
              &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?>
              <a id="do<?php echo $ntInfo['code'].$indx; ?>AG" href="#" onclick="doGetHideNTBlock('<?php echo $ntInfo['code'];?>' , '<?php echo $indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;          
              <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
              </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div>
            <?php } ?>
            </div>
          </div> <?php            
        }
}

if (!function_exists("nxs_get_admin_url")) { function nxs_get_admin_url($path=''){ //## Workaround for some buggy 'admin hiding' plugins.
  $admURL = admin_url($path); if (substr($admURL, 0, 4)!='http') { $admURL = admin_url($path, 'https'); $admURL = str_ireplace('https://', 'http://', $admURL);} return $admURL;
}}
//## Process Spin 
if (!function_exists("nxs_spinRecursion")) { function nxs_spinRecursion(&$txt, $startCh) { global $nxs_spin_lCh, $nxs_spin_rCh, $nxs_spin_splCh; $startPos = $startCh;
  while ($startCh++ < strlen($txt)) {
    if (substr($txt, $startCh, strlen($nxs_spin_lCh)) == $nxs_spin_lCh)  $txt = nxs_spinRecursion($txt, $startCh);
    elseif (substr($txt, $startCh, strlen($nxs_spin_rCh)) == $nxs_spin_rCh) {
      $tmpTxt = substr($txt, $startPos+strlen($nxs_spin_lCh), ($startCh - $startPos)-strlen($nxs_spin_rCh));
      $toRepl = nxs_spinReplace($tmpTxt); $txt = str_replace($nxs_spin_lCh.$tmpTxt.$nxs_spin_rCh, $toRepl, $txt);
    }
  } return $txt;
}}
if (!function_exists("nxs_spinReplace")) { function nxs_spinReplace($txt) { global $nxs_spin_splCh; $txt = explode($nxs_spin_splCh, $txt);  $out = $txt[mt_rand(0,count($txt)-1)]; return $out; }}
if (!function_exists("nxs_doSpin")) { function nxs_doSpin($msg){  global $nxs_spin_lCh, $nxs_spin_rCh, $nxs_spin_splCh;
    $nxs_spin_lCh = '{'; $nxs_spin_rCh='}'; $nxs_spin_splCh='|'; $msg = nxs_spinRecursion($msg, -1); return $msg;
}}

if (!function_exists("nxs_getImgfrOpt")) { function nxs_getImgfrOpt($imgOpts, $defSize=''){ if (!is_array($imgOpts)) return $imgOpts;// prr($imgOpts);
   if ($defSize!='' && isset($imgOpts[$defSize]) && trim($imgOpts[$defSize])!='') return $imgOpts[$defSize];
   if (isset($imgOpts['large']) && trim($imgOpts['large'])!='') return $imgOpts['large'];
   if (isset($imgOpts['original']) && trim($imgOpts['original'])!='') return $imgOpts['original'];
   if (isset($imgOpts['thumb']) && trim($imgOpts['thumb'])!='') return $imgOpts['thumb'];
   if (isset($imgOpts['medium']) && trim($imgOpts['medium'])!='') return $imgOpts['medium'];
}}

if (!function_exists("nxs_memCheck")) { function nxs_memCheck() { global $nxs_snapThisPageUrl;  $mLimit = (int) ini_get('memory_limit'); $mLimit = empty($mLimit) ? __('N/A') :$mLimit . __(' MByte');
  $mUsage = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0; $mUsage = empty($mUsage) ? __('N/A') : $mUsage . __(' MByte'); ?>
    <div><strong><?php _e('PHP Version'); ?></strong>: <span><?php echo PHP_VERSION; ?>;&nbsp;</span>
      <strong><?php _e('Memory limit'); ?></strong>: <span><?php echo $mLimit; ?>; &nbsp;</span>
      <strong><?php _e('Memory usage'); ?></strong>: <span><?php echo $mUsage; ?>; &nbsp;</span>
      &nbsp;&nbsp;<a target="_blank" href="<?php echo $nxs_snapThisPageUrl; ?>&do=test">Check HTTPS/SSL</a>
      &nbsp;&nbsp;<a target="_blank" href="<?php echo $nxs_snapThisPageUrl; ?>&do=crtest">Show Cron Test Results</a>
    </div> <?php
}}
//## Check SSL Sec
if (!function_exists("nxsCheckSSLCurl")){function nxsCheckSSLCurl($url){
  $ch = curl_init($url); $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us';  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)"); 
  $content = curl_exec($ch); $err = curl_errno($ch); $errmsg = curl_error($ch); if ($err!=0) return array('errNo'=>$err, 'errMsg'=>$errmsg); else return false;
}}
if (!function_exists("nxs_cron_check")){function nxs_cron_check() { if (stripos($_SERVER["REQUEST_URI"], 'wp-cron.php')!==false) {  
  $cronCheckArray = get_option('NXS_cronCheck'); if (empty($cronCheckArray)) $cronCheckArray = array('cronCheckStartTime'=>time(), 'cronChecks'=>array());    
  if (($cronCheckArray['cronCheckStartTime']+900)>time()) {  ( $offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
    $cronCheckArray['cronChecks'][] = '['.date_i18n('Y-m-d H:i:s', $_SERVER["REQUEST_TIME"]+$offset).'] - WP Cron called from '.$_SERVER["REMOTE_ADDR"].' ('.$_SERVER["HTTP_USER_AGENT"].')';
    //nxs_addToLogN('S', 'Cron Check', '', 'WP Cron called from '.$_SERVER["REMOTE_ADDR"].' ('.$_SERVER["HTTP_USER_AGENT"].')', date_i18n('Y-m-d H:i:s', $_SERVER["REQUEST_TIME"]+$offset));
  } elseif (empty($cronCheckArray['status']) &&  is_array($cronCheckArray['cronChecks'])) $cronCheckArray['status'] = (count($cronCheckArray['cronChecks'])<17 && count($cronCheckArray['cronChecks'])>1)?1:0;
  update_option("NXS_cronCheck", $cronCheckArray);    
}}}

function nxs_toolbar_link_to_mypage( $wp_admin_bar ) {
    $args = array(
        'id'    => 'snap-post',
        'title' => '<span style="font-weight:bold; color:#2ecc2e;">{SNAP} </span> New Post to Social Networks',
        'parent' => 'new-content',
        'href'  => '#',        
        'meta'  => array( 'class' => 'my-toolbar-page', 'onclick'  => 'nxs_showNewPostFrom();return false;' )
    );
    $wp_admin_bar->add_node( $args );
}

if (!function_exists('nxs_remote_request')){function nxs_remote_request($url, $args = array()) { return wp_remote_request($url, $args); }}
if (!function_exists('nxs_remote_get')){function nxs_remote_get($url, $args = array()) { return wp_remote_get($url, $args); }}
if (!function_exists('nxs_remote_post')){function nxs_remote_post($url, $args = array()) { return wp_remote_post($url, $args); }}
if (!function_exists('nxs_remote_head')){function nxs_remote_head($url, $args = array()) { return wp_remote_head($url, $args); }}
if (!function_exists('is_nxs_error')){function is_nxs_error($thing) { return is_wp_error($thing); }}
if (!function_exists('nxs_parse_args')){function nxs_parse_args($args, $defaults='') { return wp_parse_args($args, $defaults); }}

?>