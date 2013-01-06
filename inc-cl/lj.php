<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'LJ', 'lcode'=>'lj', 'name'=>'LiveJournal');

if (!class_exists("nxs_snapClassLJ")) { class nxs_snapClassLJ {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl, $nxsOne; $code = 'LJ'; $lcode = 'lj'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?> 
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">LiveJournal Blog Settings:           
            <?php $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts)); $nxsOne .= "&g=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Blog account<?php if ($cgpo!=1){ ?>s<?php } ?>  </div></div> 
              <?php  //if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Google+', 'GP'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$gpo){ if (trim($gpo['nName']=='')) $gpo['nName'] = str_ireplace('/xmlrpc.php','', str_ireplace('http://','', str_ireplace('https://','', $gpo['ljURL']))); ?>
                <p style="margin: 0px; margin-left: 5px;">
                  <input value="1" id="apDoLJ" name="lj[<?php echo $indx; ?>][apDoLJ]"type="checkbox" <?php if ((int)$gpo['doLJ'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your Blog <i style="color: #005800;"><?php if($gpo['nName']!='') echo "(".$gpo['nName'].")"; ?></i></strong> 
                  &nbsp;&nbsp;<a id="doLJ<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('LJ<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('lj','<?php echo $indx; ?>', '<?php echo $gpo['ljUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $gpo);             
              } ?>            
            <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doLJ'=>'1', 'ljUName'=>'', 'ljPageID'=>'', 'ljAttch'=>'', 'ljPass'=>'', 'ljURL'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){ global $nxs_plurl; ?>
            <div id="doLJ<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/lj-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSLJ<?php echo $ii; ?>" value="0" id="apDoSLJ<?php echo $ii; ?>" />
            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/lj16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-lj-based-social-networks-auto-poster-livejournal/">Detailed LiveJournal Installation/Configuration Instructions</a></div>
            
            <?php if ($isNew){ ?> <br/>You can setup LiveJournal blog.<br/><br/> <?php } ?> 
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="lj[<?php echo $ii; ?>][nName]" id="ljnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('lj', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('lj', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong>Auto-Post Categories:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="lj[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSLJ<?php echo $ii; ?>" type="radio" name="lj[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_LJ<?php echo $ii; ?>" onclick="jQuery('#catSelSLJ<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('LJ<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_LJ<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="lj[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_LJ<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    </div>     
    <?php } ?>
            <div style="width:100%;"><br/><strong>LiveJournal Username:</strong> </div><input name="lj[<?php echo $ii; ?>][apLJUName]" id="apLJUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['ljUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>LiveJournal Password:</strong> </div><input name="lj[<?php echo $ii; ?>][apLJPass]" id="apLJPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['ljPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['ljPass'], 5)):$gpo['ljPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="lj[<?php echo $ii; ?>][apDoLJ]" value="1" id="apDoNewLJ<?php echo $ii; ?>" /> <?php } ?>
            
            <br/><strong id="altFormatText">Post Title and Post Text Formats</strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Post Title Format</strong>               
              </div><input name="lj[<?php echo $ii; ?>][apLJMsgTFrmt]" id="apLJMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['ljMsgTFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>" />
            </div>            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Post Text Format</strong>               
              </div><input name="lj[<?php echo $ii; ?>][apLJMsgFrmt]" id="apLJMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%FULLTEXT%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['ljMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>" />
            </div><br/>    
            
            <?php if ($gpo['ljPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToLJ', 'rePostToLJ_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('LJ', '<?php echo $ii; ?>'); return false;">Submit Test Post to LiveJournal</a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'LJ'; $lcode = 'lj'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apLJUName']) && $pval['apLJUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();        
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);  
        if (isset($pval['apLJUName']))   $options[$ii]['ljUName'] = trim($pval['apLJUName']);  $options[$ii]['ljURL'] = 'http://'.$options[$ii]['ljUName'].'.livejournal.com';
        if (isset($pval['apLJPass']))    $options[$ii]['ljPass'] = 'n5g9a'.nsx_doEncode($pval['apLJPass']); else $options[$ii]['ljPass'] = '';  
        if (isset($pval['apLJMsgFrmt'])) $options[$ii]['ljMsgFormat'] = trim($pval['apLJMsgFrmt']);                                                  
        if (isset($pval['apLJMsgTFrmt'])) $options[$ii]['ljMsgTFormat'] = trim($pval['apLJMsgTFrmt']);               
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                                           
        if (isset($pval['apDoLJ']))      $options[$ii]['doLJ'] = $pval['apDoLJ']; else $options[$ii]['doLJ'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapLJ', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doLJ = $ntOpt['doLJ'] && $ntOpt['catSel']!='1';   
        $isAvailLJ =  $ntOpt['ljUName']!='' && $ntOpt['ljPass']!=''; $ljMsgFormat = htmlentities($ntOpt['ljMsgFormat'], ENT_COMPAT, "UTF-8"); $ljMsgTFormat = htmlentities($ntOpt['ljMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_LJ<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailLJ) { ?><input class="nxsGrpDoChb" value="1" id="doLJ<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="lj[<?php echo $ii; ?>][SNAPincludeLJ]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doLJ == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/lj16.png);">LiveJournal - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailLJ) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="shoLJopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToLJ_repostButton" id="rePostToLJ_button" value="<?php _e('Repost to LiveJournal', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToLJ', 'rePostToLJ_wpnonce' ); } ?>
                    
                     <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdLJ<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank">Posted on LiveJournal <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailLJ) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your LiveJournal Account to AutoPost to LiveJournal</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $ljMsgTFormat ?>" type="text" name="lj[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLJTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apLJTMsgFrmt".$ii); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $ljMsgFormat ?>" type="text" name="lj[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLJMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apLJMsgFrmt".$ii); ?></td></tr>
  
  <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
    if (isset($pMeta['SNAPformat'])) $optMt['ljMsgFormat'] = $pMeta['SNAPformat']; 
    if (isset($pMeta['SNAPformatT'])) $optMt['ljMsgTFormat'] = $pMeta['SNAPformatT'];  
    if (isset($pMeta['SNAPincludeLJ'])) $optMt['doLJ'] = $pMeta['SNAPincludeLJ'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doLJ'] = 0; } return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToLJ_ajax")) {
  function nxs_rePostToLJ_ajax() { check_ajax_referer('rePostToLJ');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['lj'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii;  $two['pType'] = 'aj';//if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapLJ', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassLJ(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToLJ($postID, $two); if ($result == 200) die("Successfully sent your post to LiveJournal."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToLJ")) { //## Second Function to Post to LJ
  function nxs_doPublishToLJ($postID, $options){ $ntCd = 'LJ'; $ntCdL = 'lj'; $ntNm = 'LJ Based Blog';
      
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    } 
      $imgURL = nxs_getPostImage($postID);
      $email = $options['ljUName'];  $pass = substr($options['ljPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ljPass'], 5)):$options['ljPass'];      
      if ($postID=='0') { echo "Testing ... <br/><br/>";  $link = home_url(); $msgT = 'Test Link from '.$link; $msg = 'Test post please ignore'; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID); 
        $msgFormat = $options['ljMsgFormat']; $msg = nsFormatMessage($msgFormat, $postID); $msgTFormat = $options['ljMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);      
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      } //prr($msg); prr($msgFormat);
      //## Post   
      require_once ('apis/xmlrpc-client.php'); $nxsToLJclient = new NXS_XMLRPC_Client('http://www.livejournal.com/interface/xmlrpc'); $nxsToLJclient->debug = false;
      $date = time(); $year = date("Y", $date); $mon = date("m", $date); $day = date("d", $date); $hour = date("G", $date); $min = date("i", $date);
      $nxsToLJContent = array( "username" => $options['ljUName'], "password" => $pass, "event" => $msg, "subject" => $msgT, "lineendings" => "unix", "year" => $year, "mon" => $mon, "day" => $day, "hour" => $hour, "min" => $min, "ver" => 2);
      if (!$nxsToLJclient->query('LJ.XMLRPC.postevent', $nxsToLJContent)) { $ret = 'Something went wrong - '.$nxsToLJclient->getErrorCode().' : '.$nxsToLJclient->getErrorMessage();} else $ret = 'OK';
      $pid = $nxsToLJclient->getResponse(); if (is_array($pid)) $pid = $pid['url']; else { $ret = 'Something went wrong - '.print_r($pid, true);}
      
      if ($ret!='OK') { if ($postID=='0') echo $ret; 
        nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo);
      } else { if ($postID=='0') { echo 'OK - Message Posted, please see your LiveJournal'; nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); } else 
        { nxs_metaMarkAsPosted($postID, 'LJ', $options['ii'], array('isPosted'=>'1', 'pgID'=>$pid, 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);} }
      if ($ret == 'OK') return 200; else return $ret;
  }
}  
?>