<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'LJ', 'lcode'=>'lj', 'name'=>'LiveJournal');

if (!class_exists("nxs_snapClassLJ")) { class nxs_snapClassLJ {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'LJ', 'lcode'=>'lj', 'name'=>'LiveJournal', 'defNName'=>'', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('/xmlrpc.php','', str_ireplace('http://','', str_ireplace('https://','', $pbo['ljURL']))); ?>
          <p style="margin:0px;margin-left:5px;">
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="doShowHideBlocks('<?php echo $ntInfo['code']; ?>');" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1) echo "checked"; ?> /> <?php if ((int)$pbo['catSel'] == 1) { ?>   <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>A" href="#" onclick="doShowHideBlocks2('<?php echo $ntInfo['code'].$indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><?php $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }   
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doLJ'=>'1', 'ljUName'=>'', 'ljPageID'=>'', 'inclTags'=>'1', 'ljAttch'=>'', 'ljPass'=>'', 'ljURL'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){ global $nxs_plurl; ?>
            <div id="doLJ<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/lj-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSLJ<?php echo $ii; ?>" value="0" id="apDoSLJ<?php echo $ii; ?>" />
            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/lj16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-livejournal-social-networks-auto-poster-for-wordpress/"><?php $nType="LiveJournal"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>            
            <?php if ($isNew){ ?> <br/><?php _e('You can setup LiveJournal blog.', 'nxs_snap'); ?><br/><br/> <?php } ?> 
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="lj[<?php echo $ii; ?>][nName]" id="ljnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('lj', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('lj', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="lj[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSLJ<?php echo $ii; ?>" type="radio" name="lj[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_LJ<?php echo $ii; ?>" onclick="jQuery('#catSelSLJ<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('LJ<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_LJ<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="lj[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_LJ<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div>     
    <?php } ?>
            <div style="width:100%;"><br/><strong>LiveJournal Username:</strong> </div><input name="lj[<?php echo $ii; ?>][apLJUName]" id="apLJUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['ljUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>LiveJournal Password:</strong> </div><input name="lj[<?php echo $ii; ?>][apLJPass]" id="apLJPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['ljPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['ljPass'], 5)):$gpo['ljPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <div style="width:100%;"><br/><strong>Blog/Community URL or ID:</strong> Please specify the Blog or Community URL or ID. <i>Use this only if you are posting NOT to your own journal. </i></div> 
            <input name="lj[<?php echo $ii; ?>][commID]" id="commID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['commID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            
            <div style="width:100%;"><br/><strong>Website:</strong> Please select your website. <i>SNAP could also post to other LJ Engine Based sites like DreamWidth.org </i></div> 
            
            <select id="lj1delayHrs" name="lj[<?php echo $ii; ?>][ljSrv]"><option  <?php if ( !isset($gpo['ljSrv']) || $gpo['ljSrv']=='' || $gpo['ljSrv']=='LJ') {?> selected="selected" <?php } ?> value="LJ">LiveJournal.com</option>
      <option <?php if ( isset($gpo['ljSrv']) && $gpo['ljSrv']=='DW') {?> selected="selected" <?php } ?> value="DW">DreamWidth.org</option>
      </select>            
            
            <br/>
            
            <?php if ($isNew) { ?> <input type="hidden" name="lj[<?php echo $ii; ?>][apDoLJ]" value="1" id="apDoNewLJ<?php echo $ii; ?>" /> <?php } ?>
            
            <br/><strong id="altFormatText"><?php _e('Post Title and Post Text Formats', 'nxs_snap'); ?></strong>               
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="apLJMsgTFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apLJMsgTFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)               
              </div><input name="lj[<?php echo $ii; ?>][apLJMsgTFrmt]" id="apLJMsgTFrmt<?php echo $ii; ?>" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['ljMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>"  onfocus="mxs_showFrmtInfo('apLJMsgTFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apLJMsgTFrmt".$ii); ?>
            </div>            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apLJMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apLJMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)               
              </div>
              
               <textarea cols="150" rows="3" id="lj<?php echo $ii; ?>SNAPformat" name="lj[<?php echo $ii; ?>][apLJMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#lj<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apLJMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%FULLTEXT%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['ljMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("apLJMsgFrmt".$ii); ?>
            </div>
            <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="ljInclTags<?php echo $ii; ?>" type="checkbox" name="lj[<?php echo $ii; ?>][inclTags]"  <?php if ((int)$gpo['inclTags'] == 1) echo "checked"; ?> /> 
              <strong><?php _e('Post with tags.', 'nxs_snap') ?></strong> <?php _e('Tags from the blogpost will be auto posted to LiveJournal', 'nxs_snap') ?>                                            
            </p><br/>                
            <?php if ($gpo['ljPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToLJ', 'rePostToLJ_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('LJ', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>           <?php } ?>
      <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'LJ'; $lcode = 'lj'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apLJUName']) && $pval['apLJUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();        
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);  
        if (isset($pval['ljSrv']))   $options[$ii]['ljSrv'] = trim($pval['ljSrv']); if ($options[$ii]['ljSrv']=='DW') $server = 'dreamwidth.org'; else $server = 'livejournal.com';      
        if (isset($pval['apLJUName']))   $options[$ii]['ljUName'] = trim($pval['apLJUName']);  $options[$ii]['ljURL'] = 'http://'.$options[$ii]['ljUName'].$server;
        if (isset($pval['apLJPass']))    $options[$ii]['ljPass'] = 'n5g9a'.nsx_doEncode($pval['apLJPass']); else $options[$ii]['ljPass'] = '';  
        if (isset($pval['apLJMsgFrmt'])) $options[$ii]['ljMsgFormat'] = trim($pval['apLJMsgFrmt']);                                                  
        if (isset($pval['apLJMsgTFrmt'])) $options[$ii]['ljMsgTFormat'] = trim($pval['apLJMsgTFrmt']);               
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if (isset($pval['inclTags'])) $options[$ii]['inclTags'] = $pval['inclTags']; else $options[$ii]['inclTags'] = 0;
        
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['commID']))  {          
          if (stripos($pval['commID'], '.')!==false) $pval['commID'] = CutFromTo($pval['commID'], '://', '.');                                 
          $options[$ii]['commID'] = trim($pval['commID']);
        }                                           
        if (isset($pval['apDoLJ']))      $options[$ii]['doLJ'] = $pval['apDoLJ']; else $options[$ii]['doLJ'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapLJ', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doLJ = $ntOpt['doLJ'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailLJ =  $ntOpt['ljUName']!='' && $ntOpt['ljPass']!=''; $ljMsgFormat = htmlentities($ntOpt['ljMsgFormat'], ENT_COMPAT, "UTF-8"); $ljMsgTFormat = htmlentities($ntOpt['ljMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_LJ<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailLJ) { ?><input class="nxsGrpDoChb" value="1" id="doLJ<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="lj[<?php echo $ii; ?>][doLJ]" <?php if ((int)$doLJ == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="lj[<?php echo $ii; ?>][doLJ]" value="<?php echo $doLJ;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/lj16.png);">LiveJournal - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailLJ) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="shoLJopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToLJ_repostButton" id="rePostToLJ_button" value="<?php _e('Repost to LiveJournal', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToLJ', 'rePostToLJ_wpnonce' ); } ?>
                    
                     <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdLJ<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="LiveJournal"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailLJ) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your LiveJournal Account to AutoPost to LiveJournal</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
                <td><input value="<?php echo $ljMsgTFormat ?>" type="text" name="lj[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLJTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apLJTMsgFrmt".$ii); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="lj<?php echo $ii; ?>SNAPformat" name="lj[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#lj<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLJMsgFrmt<?php echo $ii; ?>');"><?php echo $ljMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apLJMsgFrmt".$ii); ?></td></tr>
  
  <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
    if (isset($pMeta['SNAPformat'])) $optMt['ljMsgFormat'] = $pMeta['SNAPformat']; 
    if (isset($pMeta['SNAPformatT'])) $optMt['ljMsgTFormat'] = $pMeta['SNAPformatT'];  
    if (isset($pMeta['doLJ'])) $optMt['doLJ'] = $pMeta['doLJ'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doLJ'] = 0; } 
    if (isset($pMeta['SNAPincludeLJ']) && $pMeta['SNAPincludeLJ'] == '1' ) $optMt['doLJ'] = 1;  
    return $optMt;
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
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToLJ',  array($postID, $options));        
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#2097EE">LJ</span> - '.$options['nName'];
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      } 
      $imgURL = nxs_getPostImage($postID);
      $email = $options['ljUName'];  $pass = substr($options['ljPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ljPass'], 5)):$options['ljPass'];      
      if ($postID=='0') { echo "Testing ... <br/><br/>";  $link = home_url(); $msgT = 'Test Link from '.$link; $msg = 'Test post please ignore'; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID); 
        $msgFormat = $options['ljMsgFormat']; $msg = nsFormatMessage($msgFormat, $postID); $msgTFormat = $options['ljMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);      
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      } //prr($msg); prr($msgFormat);
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
      //## Post   
      require_once ('apis/xmlrpc-client.php'); if ($options['ljSrv']=='DW') $server = 'dreamwidth.org'; else $server = 'livejournal.com';      
      $nxsToLJclient = new NXS_XMLRPC_Client('http://www.'.$server.'/interface/xmlrpc'); $nxsToLJclient->debug = false;             
      
      $date = time(); $year = date("Y", $date); $mon = date("m", $date); $day = date("d", $date); $hour = date("G", $date); $min = date("i", $date);
      $nxsToLJContent = array( "username" => $options['ljUName'], "password" => $pass, "event" => $msg, "subject" => $msgT, "lineendings" => "unix", "year" => $year, "mon" => $mon, "day" => $day, "hour" => $hour, "min" => $min, "ver" => 2);      
      if ($options['commID']!='') $nxsToLJContent["usejournal"] = $options['commID'];    
      
      if ($options['inclTags']=='1'){ $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',', $tggs); $nxsToLJContent['props'] = array('taglist' => $tags); }        
      
        
      if (!$nxsToLJclient->query('LJ.XMLRPC.postevent', $nxsToLJContent)) { $ret = 'Something went wrong - '.$nxsToLJclient->getErrorCode().' : '.$nxsToLJclient->getErrorMessage();} else $ret = 'OK';      
      $pid = $nxsToLJclient->getResponse(); if (is_array($pid)) $pid = $pid['url']; else { $ret = 'Something went wrong - NO PID '.print_r($pid, true);}
      
      if ($ret!='OK') { if ($postID=='0') echo $ret; 
        nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);
      } else { if ($postID=='0') { echo 'OK - Message Posted, please see your LiveJournal'; nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); } else 
        { nxs_metaMarkAsPosted($postID, 'LJ', $options['ii'], array('isPosted'=>'1', 'pgID'=>$pid, 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);} }
      if ($ret == 'OK') return 200; else return $ret;
  }
}  
?>