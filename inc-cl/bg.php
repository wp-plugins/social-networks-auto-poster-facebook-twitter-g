<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger');

if (!class_exists("nxs_snapClassBG")) { class nxs_snapClassBG {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger', 'defNName'=>'diUName', 'tstReq' => true); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;">
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="doShowHideBlocks('<?php echo $ntInfo['code']; ?>');" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1) echo "checked"; ?> /> <?php if ((int)$pbo['catSel'] == 1) { ?>   <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>A" href="#" onclick="doShowHideBlocks2('<?php echo $ntInfo['code'].$indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><?php $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php //## END Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doBG'=>'1', 'bgUName'=>'', 'bgPass'=>'', 'bgBlogID'=>'', 'bgInclTags'=>'1', 'bgMsgFormat'=>'%FULLTEXT% <br/><a href=\'%URL%\'>%TITLE%</a>', 'bgMsgTFormat'=>'%TITLE%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
    <div id="doBG<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew){ ?> clNewNTSets<?php } ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/bg-bg.png);background-position:90% 10%;"> <input type="hidden" name="apDoSBG<?php echo $ii; ?>" value="0" id="apDoSBG<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][apDoBG]" value="1" id="apDoNewBG<?php echo $ii; ?>" /> <?php } ?>
    
    <div style="display: none;"><input name="bg[<?php echo $ii; ?>][apBGPassChr]" id="apBGPassChr" type="password" value="" /></div>
            
            <div id="doBG<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-blogger-social-networks-auto-poster-wordpress/"><?php $nType="Blogger"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="bg[<?php echo $ii; ?>][nName]" id="bgnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('bg', $ii, $options['qTLng']); ?>
            <?php echo nxs_addPostingDelaySel('bg', $ii, $options['nHrs'], $options['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="bg[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSBG<?php echo $ii; ?>" type="radio" name="bg[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_BG<?php echo $ii; ?>" onclick="jQuery('#catSelSBG<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('BG<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_BG<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="bg[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_BG<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
       <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i>
    </div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>Blogger Username/Email:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGUName]" id="apBGUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['bgUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Blogger Password:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGPass]" id="apBGPass" autocomplete="off" type="password" style="width: 30%;" value="<?php if (trim($options['bgPass'])!='') _e(apply_filters('format_to_edit', htmlentities(substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <div style="width:100%;"><strong>Blogger Blog ID:</strong> 
            <p style="font-size: 11px; margin: 0px;"><?php _e('Log to your Blogger management panel and look at the URL of your blog: http://www.blogger.com/blogger.g?blogID=8959085979163812093#allposts. Your Blog ID will be: 8959085979163812093', 'nxs_snap'); ?></p>
            </div><input name="bg[<?php echo $ii; ?>][apBGBlogID]" id="apBGBlogID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['bgBlogID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>
            
   <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apBGTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div> 
              
              <input name="bg[<?php echo $ii; ?>][apBGMsgTFrmt]" id="apBGMsgTFrmt" style="width: 50%;" value="<?php if ($options['bgMsgTFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['bgMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "%TITLE%"; ?>" onfocus="mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apBGTMsgFrmt".$ii); ?><br/>
            
            <div id="altFormat" style="">
   <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apBGMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>) 
   
   <!-- 
   HTML is <?php if(!function_exists('doPostToGooglePlus')) {?> <b>NOT</b> <?php } ?> allowed. <?php if(!function_exists('doPostToGooglePlus')) {?> <i>- Blogger "Free API" limitation. Please get <a href="http://www.nextscripts.com/google-plus-automated-posting/#blogger">NextScripts API</a> to allow HTML</i> <?php } ?>   -->
   </div>  
   
   <textarea cols="150" rows="3" id="bg<?php echo $ii; ?>SNAPformat" name="bg[<?php echo $ii; ?>][apBGMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#bg<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"><?php if ($options['bgMsgFormat']!='') _e(apply_filters('format_to_edit',htmlentities($options['bgMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap');  else echo "%FULLTEXT% <br/><a href='%URL%'>%TITLE%</a>"; ?></textarea>
   
   <?php nxs_doShowHint("apBGMsgFrmt".$ii, __('HTML is allowed', 'nxs_snap'));  ?>
            </div>
            
             <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="bgInclTags" type="checkbox" name="bg[<?php echo $ii; ?>][bgInclTags]"  <?php if ((int)$options['bgInclTags'] == 1) echo "checked"; ?> /> 
              <strong><?php _e('Post with tags', 'nxs_snap'); ?></strong>  <?php _e('Tags from the blogpost will be auto-posted to Blogger/Blogspot', 'nxs_snap'); ?>                                                               
            </p> 
            
            <?php if ($options['bgPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($options['bgOK']) || $options['bgOK']!='1') { ?> <div class="blnkg">=== <?php _e('Submit Test Post to Finish Configuration', 'nxs_snap'); ?> ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('BG', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php } ?>
            
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){// prr($pval);
      if (isset($pval['apBGUName']) && trim($pval['apBGUName'])!='' && isset($pval['apBGPass']) && trim($pval['apBGPass'])!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apDoBG']))   $options[$ii]['doBG'] = $pval['apDoBG']; else $options[$ii]['doBG'] = 0;
                
                if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
                if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apBGUName']))   $options[$ii]['bgUName'] = trim($pval['apBGUName']);
                if (isset($pval['apBGPass']))    $options[$ii]['bgPass'] = 'b4d7s'.nsx_doEncode($pval['apBGPass']); else $options[$ii]['bgPass'] = '';
                if (isset($pval['apBGBlogID']))   $options[$ii]['bgBlogID'] = trim($pval['apBGBlogID']);                
                if (isset($pval['apBGMsgFrmt'])) $options[$ii]['bgMsgFormat'] = trim($pval['apBGMsgFrmt']);                   
                if (isset($pval['apBGMsgTFrmt']))    $options[$ii]['bgMsgTFormat'] = trim($pval['apBGMsgTFrmt']);         
                if (isset($pval['bgInclTags']))    $options[$ii]['bgInclTags'] = $pval['bgInclTags'];  else $options[$ii]['bgInclTags'] = 0;        
                
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
      } //prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options){ $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapBG', true)); if (is_array($pMeta)) $options = $this->adjMetaOpt($options, $pMeta[$ii]); 
       $doBG = $options['doBG'] && (is_array($pMeta) || $options['catSel']!='1'); 
       $isAvailBG =  $options['bgUName']!='' && $options['bgPass']!='';  $bgMsgFormat = htmlentities($options['bgMsgFormat'], ENT_COMPAT, "UTF-8");  $bgMsgTFormat = htmlentities($options['bgMsgTFormat'], ENT_COMPAT, "UTF-8");
      ?>  
      
   <tr><th style="text-align:left;" colspan="2"><?php if ( $options['catSel']=='1' && trim($options['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_BG<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailBG) { ?><input class="nxsGrpDoChb" value="1" id="doBG<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="bg[<?php echo $ii; ?>][doBG]" <?php if ((int)$doBG == 1) echo 'checked="checked" title="def"';  ?> /> <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][doBG]" value="<?php echo $doBG;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);">Blogger - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $options['nName']; ?></i>)</div></th> <td style="min-width: 180px; width: 350px;" ><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailBG) { ?>
                    <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToBG_repostButton" id="rePostToBG_button" value="<?php _e('Repost to Blogger', 'nxs_snap') ?>" />                    
                    <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdBG<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Blogger"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?><?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>                    
                    
                </td></tr>
                <?php if (!$isAvailBG) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b><?php _e('Setup your Blogger Account to AutoPost to Blogger', 'nxs_snap') ?></b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                 <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgTFormat ?>" type="text" name="bg[<?php echo $ii; ?>][SNAPTformat]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apBGTMsgFrmt".$ii, '', '58'); ?></td></tr>
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td>
                <textarea cols="150" rows="1" id="bg<?php echo $ii; ?>SNAPformat" name="bg[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#bg<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"><?php echo $bgMsgFormat; ?></textarea>                
                <?php nxs_doShowHint("apBGMsgFrmt".$ii, '', '58'); ?></td></tr>
                <?php }
    }      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['bgMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPTformat'])) $optMt['bgMsgTFormat'] = $pMeta['SNAPTformat'];      
     if (isset($pMeta['doBG'])) $optMt['doBG'] = $pMeta['doBG'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doBG'] = 0; } 
     if (isset($pMeta['SNAPincludeBG']) && $pMeta['SNAPincludeBG'] == '1' ) $optMt['doBG'] = 1;  
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToBG_ajax")) { function nxs_rePostToBG_ajax() {  check_ajax_referer('rePostToBG');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
      global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
      foreach ($options['bg'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapBG', true); $mpo =  maybe_unserialize($mpo);       
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassBG(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); } 
      $result = nxs_doPublishToBG($postID, $po);  if ($result == 201) { $options['bg'][$ii]['bgOK']=1;  update_option('NS_SNAutoPoster', $options); }
      
      if ($result == 200) die("Successfully sent your post to Blooger."); else die($result);
    }    
  }
}
if (!function_exists('nsBloggerGetAuth')){ function nsBloggerGetAuth($email, $pass) { $pass = urlencode($pass);
    $ch = curl_init("https://www.google.com/accounts/ClientLogin?Email=$email&Passwd=$pass&service=blogger&accountType=GOOGLE");    
    $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; 
    $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us'; 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
    $result = curl_exec($ch); $resultArray = curl_getinfo($ch); 
    curl_close($ch); $arr = explode("=",$result); $token = $arr[3]; if (trim($token)=='') return false; else return $token;
}}
if (!function_exists('nsBloggerNewPost')){ function nsBloggerNewPost($auth, $blogID, $title, $text) {$text = str_ireplace('allowfullscreen','', $text); $title = utf8_decode(strip_tags($title)); 
    $text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', "", $text);  $text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $text); $text = utf8_decode($text); 
    
    $postText = '<entry xmlns="http://www.w3.org/2005/Atom"><title type="text">'.$title.'</title><content type="xhtml">'.$text.'</content></entry>'; //prr($postText);
    $len = strlen($entry); $ch = curl_init("https://www.blogger.com/feeds/$blogID/posts/default"); 
    $headers = array("Content-type: application/atom+xml", "Content-Length: ".strlen($postText), "Authorization: GoogleLogin auth=".$auth, $postText);
    curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);  curl_setopt($ch, CURLOPT_POST, true);  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
    $result = curl_exec($ch); curl_close($ch); 
    if (stripos($result,'tag:blogger.com')!==false) { $postID = CutFromTo($result, " rel='alternate' type='text/html' href='", "'"); return array("code"=>"OK", "post_id"=>$postID); } else { prr($result); return false;}
}}
if (!function_exists("nxs_doPublishToBG")) { //## Second Function to Post to BG
  function nxs_doPublishToBG($postID, $options){ $ntCd = 'BG'; $ntCdL = 'bg'; $ntNm = 'Blogger'; //  $uqID = uniqid('BG_');  
    //$backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), '');  
   // if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToBG',  array($postID, $options));
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();     
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'ACCNAME'=>$options['nName'], 'POSTID'=>$postID));
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10));     
    $logNT = '<span style="color:#F87907">'.$ntNm.'</span> - '.$options['nName']; 
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);   
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
      $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
      }
    }         
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msgT = 'Test Post from '.htmlentities($blogTitle);  $link = home_url(); $msg = 'Test Post from '.$blogTitle. " ".$link; }
    else { $post = get_post($postID); if(!$post) return; $msgFormat = $options['bgMsgFormat'];  $msg = nsFormatMessage($msgFormat, $postID, $addParams);  
        $link = get_permalink($postID); $msgTFormat = $options['bgMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID, $addParams); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    }
    $extInfo = ' | PostID: '.$postID." - ".nxs_doQTrans($post->post_title, $lng);
    //## Actual POST Code
    $email = $options['bgUName'];  $pass = substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass']; $blogID = $options['bgBlogID'];
    //echo "###".$auth."|".$blogID."|".$msgT."|".$msg;
    if ($options['bgInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode('","',$tggs);  $tags = nsTrnc($tags, 195, ',', ''); }
    if (substr($tags, -1)=='"') $tags = substr($tags, 0, -1);   
    
    if (class_exists('DOMDocument')) {$doc = new DOMDocument();  @$doc->loadHTML('<?xml encoding="UTF-8">' .$msg); $doc->encoding = 'UTF-8'; $msg = $doc->saveHTML(); $msg = CutFromTo($msg, '<body>', '</body>'); 
      $msg = preg_replace('/<br(.*?)\/?>/','<br$1/>',$msg);   $msg = preg_replace('/<img(.*?)\/?>/','<img$1/>',$msg);
      require_once ('apis/htmlNumTable.php');  if (is_array($HTML401NamedToNumeric)) { $msg = strtr($msg, $HTML401NamedToNumeric); $msgT = strtr($msgT, $HTML401NamedToNumeric); }
    }  //  prr($text); 
    // prr($msg); echo " =HT= ";
    $msg = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $msg); $msg = preg_replace('/<!--(.*)-->/Uis', "", $msg);  $nxshf = new NXS_HtmlFixer(); $nxshf->debug = false; $msg = $nxshf->getFixedHtml($msg);      
    $msg = str_replace("\r\n","\n", $msg); $msg = str_replace("\n\r","\n", $msg); $msg = str_replace("\r","\n", $msg); $msg = str_replace("\n","<br/>", $msg); //  prr($msg); die();    
    if (function_exists("doConnectToBlogger")) {$auth = doConnectToBlogger($email, $pass); if ($auth!==false) $ret = $auth; else $ret = doPostToBlogger($blogID, $msgT, $msg, $tags);} 
      else {$auth = nsBloggerGetAuth($email, $pass); if ($auth===false) $ret = 'Incorrect Username/Password'; else { $msgT = str_ireplace('&amp;', '&', $msgT); 
        $msgT = utf8_encode(str_ireplace('&', '&amp;', $msgT)); $msg = utf8_encode($msg); $ret = nsBloggerNewPost($auth, $blogID, $msgT, $msg);
      }}
    //## /Actual POST Code    
    if ( (!is_array($ret)) && $ret!='OK') { if ($postID=='0') prr($ret);  nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '. strip_tags(print_r($ret, true)), $extInfo); return  $ret; }
      else { if ($postID=='0') { echo 'OK - Message Posted, please see your '.$ntNm.' Page '; nxs_addToLogN('S', 'Test', $logNT,  'OK - TEST Message Posted '); return 201;} 
        else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); 
          do_action('nxs_actOnBG', array('postID'=>$postID, 'pgID'=>$ret['post_id'], 'ii'=>$ii)); nxs_addToLogN( 'S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);
        } return 200; 
      }
  }
}

?>