<?php    
//## NextScripts Twitter Connection Class
$nxs_snapAvNts[] = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter');

if (!class_exists("nxs_snapClassTW")) { class nxs_snapClassTW {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter', 'defNName'=>'dlUName', 'tstReq' => true); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['twURL'])); ?>
          <p style="margin:0px;margin-left:5px;">
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="doShowHideBlocks('<?php echo $ntInfo['code']; ?>');" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1) echo "checked"; ?> /> 
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>A" href="#" onclick="doShowHideBlocks2('<?php echo $ntInfo['code'].$indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><?php $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mtwo){ $two = array('nName'=>'', 'doTW'=>'1', 'twURL'=>'', 'twConsKey'=>'',  'twConsSec'=>'', 'twAccToken'=>'', 'twAccTokenSec'=>'', 'attchImg'=>0, 'twAttch'=>'', 'twAccTokenSec'=>''); $this->showNTSettings($mtwo, $two, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $two, $isNew=false){ global $nxs_plurl; ?>
    <div id="doTW<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/tw-bg.png);  background-position:90% 10%;">   <input type="hidden" name="apDoSTW<?php echo $ii; ?>" value="0" id="apDoSTW<?php echo $ii; ?>" />      
    
     <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-twitter-social-networks-auto-poster-wordpress/"><?php $nType="Twitter"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
    
    <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="tw[<?php echo $ii; ?>][nName]" id="twnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
    <?php echo nxs_addQTranslSel('tw', $ii, $two['qTLng']); ?><?php echo nxs_addPostingDelaySel('tw', $ii, $two['nHrs'], $two['nMin']); ?>
    
     <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="tw[<?php echo $ii; ?>][catSel]" <?php if ((int)$two['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSTW<?php echo $ii; ?>" type="radio" name="tw[<?php echo $ii; ?>][catSel]" <?php if ((int)$two['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_TW<?php echo $ii; ?>" onclick="jQuery('#catSelSTW<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('TW<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_TW<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($two['catSelEd']!='') echo "[".(substr_count($two['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="tw[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_TW<?php echo $ii; ?>" value="<?php echo $two['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
    
    <div style="width:100%;"><strong>Your Twitter URL:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWURL]" id="apTWURL" style="width: 40%;border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
    <div style="width:100%;"><strong>Your Twitter Consumer Key:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsKey]" id="apTWConsKey" style="width: 40%; border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twConsKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  
    <div style="width:100%;"><strong>Your Twitter Consumer Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsSec]" id="apTWConsSec" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twConsSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
    <div style="width:100%;"><strong>Your Access Token:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccToken]" id="apTWAccToken" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twAccToken'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
    <div style="width:100%;"><strong>Your Access Token Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccTokenSec]" id="apTWAccTokenSec" style="width: 40%;" value="<?php  _e(apply_filters('format_to_edit', htmlentities($two['twAccTokenSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
    <?php if ($isNew) { ?> <input type="hidden" name="tw[<?php echo $ii; ?>][apDoTW]" value="1" id="apDoNewTW<?php echo $ii; ?>" /> <?php } ?>
    <br/><br/>
    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$two['attchImg'] == 1) echo "checked"; ?> /> <strong>Attach Image to Twitter Post</strong></p>
    <br/>
    <strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong>
    <div style="width:100%;">
      <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp; %TAGS% - Inserts the post tags as hashtags. &nbsp; %CATS% - Inserts the post categories as hashtags. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name. <i>Please remember that Twitter takes only 140 characters.</i></p>
    </div>
    <input name="tw[<?php echo $ii; ?>][apTWMsgFrmt]" id="apTWMsgFrmt" style="width: 50%;" value="<?php if (!$isNew) _e(apply_filters('format_to_edit', htmlentities($two['twMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "%TITLE% - %URL%"; ?>" />
               
    <?php if($two['twAccTokenSec']!='') { ?> <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); ?>
      <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($two['twOK']) || $two['twOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('TW', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s' , 'nxs_snap'), $nType); ?></a> <br/>
      <?php }?>
      <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
    </div>
    <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'TW'; $lcode = 'tw'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apTWURL']) && $pval['apTWURL']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoTW']))         $options[$ii]['doTW'] = $pval['apDoTW']; else $options[$ii]['doTW'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apTWURL']))        $options[$ii]['twURL'] = trim($pval['apTWURL']);  if ( substr($options[$ii]['twURL'], 0, 4)!='http' )  $options[$ii]['twURL'] = 'http://'.$options[$ii]['twURL'];
        if (isset($pval['apTWConsKey']))    $options[$ii]['twConsKey'] = trim($pval['apTWConsKey']);
        if (isset($pval['apTWConsSec']))    $options[$ii]['twConsSec'] = trim($pval['apTWConsSec']);                                
        if (isset($pval['apTWAccToken']))   $options[$ii]['twAccToken'] = trim($pval['apTWAccToken']);                
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apTWAccTokenSec']))$options[$ii]['twAccTokenSec'] = trim($pval['apTWAccTokenSec']);                                
        if (isset($pval['apTWMsgFrmt']))    $options[$ii]['twMsgFormat'] = trim($pval['apTWMsgFrmt']);
        if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }    
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapTW', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
         $doTW = $ntOpt['doTW'] && (is_array($pMeta) || $ntOpt['catSel']!='1');  
         $isAvailTW =  $ntOpt['twURL']!='' && $ntOpt['twConsKey']!='' && $ntOpt['twConsSec']!='' && $ntOpt['twAccToken']!=''; $twMsgFormat = htmlentities($ntOpt['twMsgFormat'], ENT_COMPAT, "UTF-8");   $isAttchImg = $ntOpt['attchImg'];    
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_TW<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailTW) { ?><input class="nxsGrpDoChb" value="1" id="doTW<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="tw[<?php echo $ii; ?>][doTW]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doTW == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);">Twitter - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"
      if ($post->post_status == "publish" && $isAvailTW) { ?>
                    <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToTW_repostButton" id="rePostToTW_button" value="<?php _e('Repost to Twitter', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); } ?>
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID'])) { ?> <span style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a id="pstdTW<?php echo $ii; ?>" style="font-size: 10px;" href="<?php echo $ntOpt['twURL'].'/status/'.$pMeta[$ii]['pgID'];  ?>" target="_blank"><?php $nType="Twitter"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                </td></tr>
                <?php if (!$isAvailTW) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Twitter Account to AutoPost to Twitter</b>
                <?php }elseif ($post->post_status != "puZblish") { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>
                <td><input value="<?php echo $twMsgFormat ?>" type="text" name="tw[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTWMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apTWMsgFrmt".$ii); ?></td></tr>
                
<tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                 <input value="0"  type="hidden" name="tw[<?php echo $ii; ?>][attchImg]"/>
                 <input value="1" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$isAttchImg == 1) echo "checked"; ?> /> </th><td><strong>Attach Image to Twitter Post</strong></td> </tr>                  
       <?php } 
    } 
  }
  //#### 
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['twMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['attchImg'])) $optMt['attchImg'] = $pMeta['attchImg'] == 1?1:0; else { if (isset($pMeta['attchImg'])) $optMt['attchImg'] = 0; } 
     if (isset($pMeta['doTW'])) $optMt['doTW'] = $pMeta['doTW'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doTW'] = 0; } return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTW_ajax")) {
  function nxs_rePostToTW_ajax() { check_ajax_referer('rePostToTW');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['tw'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii;  $two['pType'] = 'aj';
      $twpo =  get_post_meta($postID, 'snapTW', true); $twpo =  maybe_unserialize($twpo);
      if (is_array($twpo) && isset($twpo[$ii]) && is_array($twpo[$ii]) && isset($twpo[$ii]['SNAPformat']) ) { $ntClInst = new nxs_snapClassTW(); $two = $ntClInst->adjMetaOpt($two, $twpo[$ii]);} 
      $result = nxs_doPublishToTW($postID, $two); if ($result == 201) {$options['tw'][$ii]['twOK']=1;  update_option('NS_SNAutoPoster', $options); } if ($result == 200) die("Successfully sent your post to Twitter."); else die($result);
    }
  }
} 

if (!function_exists("nxs_doPublishToTW")) { //## Second Function to Post to TW 
  function nxs_doPublishToTW($postID, $options){ $ntCd = 'TW'; $ntCdL = 'tw'; $ntNm = 'Twitter'; $img = ''; $imgURL = '';
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $uln = 0;
    
    if ($options['attchImg']=='1') { $imgURL = nxs_getPostImage($postID); if(trim($imgURL)=='') $options['attchImg'] = 0; else {  
      if( ini_get('allow_url_fopen') ) { if (@getimagesize($imgURL)!==false) { $img = wp_remote_get($imgURL); if(is_wp_error($img)) $options['attchImg'] = 0; else $img = $img['body']; } else $options['attchImg'] = 0; } 
        else {  $img = wp_remote_get($imgURL); if(is_wp_error($img)) $options['attchImg'] = 0; elseif (isset($img['body'])&& trim($img['body'])!='') $img = $img['body'];  else $options['attchImg'] = 0; }   
    }}  
    if ($options['attchImg']=='1' && $img!='') $twLim = 119; else $twLim = 140;
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle." - ".rand(1, 155); $uln = strlen($msg);}  
    else{ $post = get_post($postID); if(!$post) return; $twMsgFormat = $options['twMsgFormat'];  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));        
        if (stripos($twMsgFormat, '%URL%')!==false || stripos($twMsgFormat, '%SURL%')!==false) $twLim = $twLim - 20; 
        if (stripos($twMsgFormat, '%AUTHORNAME%')!==false) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun ); $twLim = $twLim - strlen($aun); } 
        
        $noRepl = str_ireplace("%TITLE%", "", $twMsgFormat); $noRepl = str_ireplace("%SITENAME%", "", $noRepl); $noRepl = str_ireplace("%URL%", "", $noRepl);
        $noRepl = str_ireplace("%SURL%", "", $noRepl);$noRepl = str_ireplace("%TEXT%", "", $noRepl);$noRepl = str_ireplace("%FULLTEXT%", "", $noRepl);
        $noRepl = str_ireplace("%AUTHORNAME%", "", $noRepl); $twLim = $twLim - strlen($noRepl); 
        
        $pTitle = $title = $post->post_title;
        if ($post->post_excerpt!="") $pText = apply_filters('the_content', $post->post_excerpt); else $pText= apply_filters('the_content', $post->post_content);
        $pFullText = apply_filters('the_content', $post->post_content); 
        $pRawText = $post->post_content;
        
        if (stripos($twMsgFormat, '%TAGS%')!==false || stripos($twMsgFormat, '%HTAGS%')!==false) {
          $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) { $frmTag =  trim(str_replace(' ','_', str_replace('  ', ' ', trim($tagA->name))));
              if (stripos($pTitle, $tagA->name)!==false) $pTitle = str_ireplace($tagA->name, '#'.$frmTag, $pTitle); elseif (stripos($pTitle, $frmTag)!==false) $pTitle = str_ireplace($frmTag, '#'.$frmTag, $pTitle); 
              if (stripos($pText, $tagA->name)!==false) $pText = str_ireplace($tagA->name, '#'.$frmTag, $pText); elseif (stripos($pText, $frmTag)!==false) $pText = str_ireplace($frmTag, '#'.$frmTag, $pText); 
              if (stripos($pFullText, $tagA->name)!==false) $pFullText = str_ireplace($tagA->name, '#'.$frmTag, $pFullText); elseif (stripos($pFullText, $frmTag)!==false) $pFullText = str_ireplace($frmTag, '#'.$frmTag, $pFullText); 
              if (stripos($pRawText, $tagA->name)!==false) $pRawText = str_ireplace($tagA->name, '#'.$frmTag, $pRawText); elseif (stripos($pRawText, $frmTag)!==false) $pRawText = str_ireplace($frmTag, '#'.$frmTag, $pRawText); 
              if ( ((stripos($twMsgFormat, '%TITLE%')!==false) && (stripos($pTitle, $tagA->name)!==false || stripos($pTitle, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%TEXT%')!==false) && (stripos($pText, $tagA->name)!==false || stripos($pText, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%FULLTEXT%')!==false) && (stripos($pFullText, $tagA->name)!==false || stripos($pFullText, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%RAWTEXT%')!==false) && (stripos($pRawText, $tagA->name)!==false || stripos($pRawText, $frmTag)!==false)) ) {} else $tggs[] = '#'.$frmTag;
          } $tags = implode(' ',$tggs); while(strlen($tags)>($twLim-10)) {array_pop($tggs); $tags = implode(' ',$tggs);} $twMsgFormat = str_ireplace("%TAGS%", $tags, $twMsgFormat);  $twMsgFormat = str_ireplace("%HTAGS%", $tags, $twMsgFormat);
          $twLim = $twLim - strlen($tags);
        }
        if (stripos($twMsgFormat, '%CATS%')!==false || stripos($twMsgFormat, '%HCATS%')!==false) {
          $t = wp_get_post_categories($postID); $cats = array();  foreach($t as $c){ $cat = get_category($c); $frmTag =  trim(str_replace(' ','_', str_replace('  ',' ',str_ireplace('&','&amp;',trim($cat->name)))));
          if (stripos($pTitle, $cat->name)!==false) $pTitle = str_ireplace($cat->name, '#'.$frmTag, $pTitle); elseif (stripos($pTitle, $frmTag)!==false) $pTitle = str_ireplace($frmTag, '#'.$frmTag, $pTitle); 
              if (stripos($pText, $cat->name)!==false) $pText = str_ireplace($cat->name, '#'.$frmTag, $pText); elseif (stripos($pText, $frmTag)!==false) $pText = str_ireplace($frmTag, '#'.$frmTag, $pText); 
              if (stripos($pFullText, $cat->name)!==false) $pFullText = str_ireplace($cat->name, '#'.$frmTag, $pFullText); elseif (stripos($pFullText, $frmTag)!==false) $pFullText = str_ireplace($frmTag, '#'.$frmTag, $pFullText); 
              if (stripos($pRawText, $cat->name)!==false) $pRawText = str_ireplace($cat->name, '#'.$frmTag, $pRawText); elseif (stripos($pRawText, $frmTag)!==false) $pRawText = str_ireplace($frmTag, '#'.$frmTag, $pRawText); 
              if ( ((stripos($twMsgFormat, '%TITLE%')!==false) && (stripos($pTitle, $cat->name)!==false || stripos($pTitle, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%TEXT%')!==false) && (stripos($pText, $cat->name)!==false || stripos($pText, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%FULLTEXT%')!==false) && (stripos($pFullText, $cat->name)!==false || stripos($pFullText, $frmTag)!==false)) ||
                   ((stripos($twMsgFormat, '%RAWTEXT%')!==false) && (stripos($pRawText, $cat->name)!==false || stripos($pRawText, $frmTag)!==false)) ) {} else $cats[] = '#'.$frmTag; 
          } $ctts = implode(' ',$cats); while(strlen($cats)>($twLim-10)) {array_pop($ctts); $cats = implode(' ',$ctts);} $twMsgFormat = str_ireplace("%CATS%", $ctts, $twMsgFormat);  $twMsgFormat = str_ireplace("%HCATS%", $ctts, $twMsgFormat);
          $twLim = $twLim - strlen($ctts);
        }
        if (stripos($twMsgFormat, '%TITLE%')!==false) { if (stripos($pTitle, '.co.uk')!==false) $twLim = $twLim - 14;
           if (stripos($pTitle, '.com')!==false) $twLim = $twLim - 16; if (stripos($pTitle, '.net')!==false) $twLim = $twLim - 16; if (stripos($pTitle, '.org')!==false) $twLim = $twLim - 16;
           $pTitle = html_entity_decode(strip_tags($pTitle), ENT_NOQUOTES, 'UTF-8');
           $pTitle = nsTrnc($pTitle, $twLim); $twMsgFormat = str_ireplace("%TITLE%", $pTitle, $twMsgFormat); $twLim = $twLim - strlen($pTitle);
        } 
        if (stripos($twMsgFormat, '%SITENAME%')!==false) {
          $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $siteTitle = nsTrnc($siteTitle, $twLim); $twMsgFormat = str_ireplace("%SITENAME%", $siteTitle, $twMsgFormat); $twLim = $twLim - strlen($siteTitle);
        }        
        if (stripos($twMsgFormat, '%TEXT%')!==false) {          
          $pText = nsTrnc(strip_tags(strip_shortcodes($pText)), 300, " ", "...");
          $pText = nsTrnc($pText, $twLim); $twMsgFormat = str_ireplace("%TEXT%", $pText, $twMsgFormat); $twLim = $twLim - strlen($pText);
        }
        if (stripos($twMsgFormat, '%FULLTEXT%')!==false) {
           $pFullText = nsTrnc(strip_tags($pFullText), $twLim); $twMsgFormat = str_ireplace("%FULLTEXT%", $pFullText, $twMsgFormat); $twLim = $twLim - strlen($pFullText);
        }          
        if (stripos($twMsgFormat, '%RAWTEXT%')!==false) {
           $pRawText = nsTrnc(strip_tags($pRawText), $twLim); $twMsgFormat = str_ireplace("%RAWTEXT%", $pRawText, $twMsgFormat); $twLim = $twLim - strlen($pRawText);
        }               
        $msg = nsFormatMessage($twMsgFormat, $postID);       
    } //prr($msg);
    
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#00FFFF">Twitter</span> - '.$options['nName']; 
    require_once ('apis/tmhOAuth.php'); require_once ('apis/tmhUtilities.php'); if ($uln>0) $msg = nsTrnc($msg, 140+$uln); else { $url = get_permalink($postID); $msg = nsTrnc($msg, 120+strlen($url)); }
    $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));      
    if ($options['attchImg']=='1' && $img!='') $code = $tmhOAuth -> request('POST', 'http://upload.twitter.com/1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $msg), true, true);    
      else $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' =>$msg)); //prr($code); echo "YYY";
    if ($code == 200){if ($postID=='0'){ nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Twitter Page'; /*NXS_tmhUtilities::pr(json_decode($tmhOAuth->response['response'])); */ return 201;}
      else { $twResp = json_decode($tmhOAuth->response['response'], true); if (is_array($twResp) && isset($twResp['id_str'])) $twNewPostID = $twResp['id_str'];  
        $args = array('pgID'=>$twNewPostID, 'isPosted'=>1, 'pDate'=>date('Y-m-d H:i:s')); nxs_metaMarkAsPosted($postID, 'TW', $options['ii'], $args); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);
      } 
    } else{ if ($postID=='0') {NXS_tmhUtilities::pr($tmhOAuth->response['response']); prr($msg); } nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($tmhOAuth->response['response'], true)." MSG:".print_r($msg, true), $extInfo); 
      $code .= " | ".$tmhOAuth->response['response']." | ".print_r($msg, true);
    }
    return $code;
  }
}

?>