<?php    
//## NextScripts deviantART Connection Class
$nxs_snapAvNts[] = array('code'=>'DA', 'lcode'=>'da', 'name'=>'deviantART');

if (!class_exists("nxs_snapClassDA")) { class nxs_snapClassDA {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'DA', 'lcode'=>'da', 'name'=>'deviantART', 'defNName'=>'daUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php if(!function_exists('doConnectToDeviantART')) {?> deviantART doesn't have a built-in API for automated posts yet.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/deviantart-automated-posting">library module</a> to be able to publish your content to deviantART. 
        <?php } else  foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;"> <img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
            <input value="0" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="hidden" />             
            <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <input type="radio" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" id="rbtn<?php echo $ntInfo['lcode'].$indx; ?>" value="1" checked="checked" onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);" /> <?php } else { ?>            
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> />
           <?php } ?>
            <?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <?php if (isset($pbo['rpstOn']) && (int)$pbo['rpstOn'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popReActive');" onmouseover="nxs_showPopUpInfo('popReActive', event);"><?php echo "*[R]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>AG" href="#" onclick="doGetHideNTBlock('<?php echo $ntInfo['code'];?>' , '<?php echo $indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php // $pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($options){ $nto = array('nName'=>'', 'doDA'=>'1', 'daUName'=>'', 'daPageID'=>'', 'daCommID'=>'', 'postType'=>'A', 'daPass'=>''); $nto['ntInfo']= array('lcode'=>'da'); $this->showNTSettings($options, $nto, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
            <div id="doDA<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">     <input type="hidden" value="0" id="apDoS<?php echo $ntU.$ii; ?>" />
            
            <?php if(!function_exists('doConnectToDeviantART')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b><?php _e('deviantART API Library not found', 'nxs_snap'); ?></b>
             <br/><br/> <?php _e('deviantART doesn\'t have a built-in API for automated posts yet.', 'nxs_snap'); ?> <br/><?php _e('<br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/deviantart-automated-posting"><b>API Library Module</b></a> to be able to publish your content to deviantART.', 'nxs_snap'); ?></span></div>
            <?php return; }; ?>             
                               
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/da16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-deviantart-social-networks-auto-poster-wordpress/"><?php $nType="deviantART"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="da[<?php echo $ii; ?>][nName]" id="danName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('da', $ii, $options['qTLng']); ?>
            
            
          <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
      
            
            <div style="width:100%;"><strong>deviantART Username or Email:</strong> </div><input name="da[<?php echo $ii; ?>][uName]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['daUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>deviantART Password:</strong> </div><input name="da[<?php echo $ii; ?>][uPass]" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['daPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['daPass'], 5)):$options['daPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                            
            <br/> 
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="daTitleFormat<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('daTitleFormat<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              <input name="da[<?php echo $ii; ?>][daTitleFormat]" id="daTitleFormat<?php echo $ii; ?>" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($options['daTitleFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>"  onfocus="mxs_showFrmtInfo('daTitleFormat<?php echo $ii; ?>');" /><?php nxs_doShowHint("daTitleFormat".$ii); ?>
            </div><br/> 
                      
            <div id="altFormat" style="margin-left: 0px;">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="daTextFormat<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('daTextFormat<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
              <textarea cols="150" rows="3" id="da<?php echo $ii; ?>SNAPformat" name="da[<?php echo $ii; ?>][daTextFormat]" style="width:51%;max-width: 650px;" onfocus="jQuery('#da<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apDAMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) _e("%FULLTEXT%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($options['daTextFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("daTextFormat".$ii); ?>
            </div><br/>          
            
            
            <?php if ($isNew) { ?> <input type="hidden" name="da[<?php echo $ii; ?>][apDoDA]" value="1" id="apDoNewDA<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['daPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DA', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>              <?php } 
            ?>
            </div>
            
            <?php /* ######################## Advanced Tab ####################### */ ?>
  <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
     <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>  
            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
            </div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = 'DA'; $lcode = 'da'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['uName']) && $pval['uName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['uName']))   $options[$ii]['daUName'] = trim($pval['uName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['uPass']))    $options[$ii]['daPass'] = 'n5g9a'.nsx_doEncode($pval['uPass']); else $options[$ii]['daPass'] = '';  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';

        if (isset($pval['daTitleFormat'])) $options[$ii]['daTitleFormat'] = trim($pval['daTitleFormat']);
        if (isset($pval['daTextFormat'])) $options[$ii]['daTextFormat'] = trim($pval['daTextFormat']);
        
        if (isset($pval['apDoDA']))      $options[$ii]['doDA'] = $pval['apDoDA']; else $options[$ii]['doDA'] = 0; 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'da'; $ntU = 'DA'; 
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDA', true));  if (is_array($pMeta) && !empty($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doDA = $ntOpt['doDA'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailDA =  $ntOpt['daUName']!='' && $ntOpt['daPass']!='';   $daMsgFormat = htmlentities($ntOpt['daTextFormat'], ENT_COMPAT, "UTF-8");      $daMsgTFormat = htmlentities($ntOpt['daTitleFormat'], ENT_COMPAT, "UTF-8");      

      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailDA) { ?><input class="nxsGrpDoChb" value="1" id="doDA<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="da[<?php echo $ii; ?>][doDA]" <?php if ((int)$doDA == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="da[<?php echo $ii; ?>][doDA]" value="<?php echo $doDA;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/da16.png);">deviantART - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDA) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToDA_repostButton" id="rePostToDA_button" value="<?php _e('Repost to deviantART', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && !empty($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdDA<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['postURL']; ?>" target="_blank"><?php $nType="deviantART"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailDA) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your deviantART Account to AutoPost to deviantART</b></td></tr>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>da" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                
       <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
        <td><input value="<?php echo $daMsgTFormat ?>" type="text" name="da[<?php echo $ii; ?>][SNAPformatT]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDAMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDAMsgTFrmt".$ii, '', '58'); ?></td></tr>  
      
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top;  padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th><td>                
                
                <textarea cols="150" rows="1" id="da<?php echo $ii; ?>SNAPformat" name="da[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#da<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDAMsgFrmt<?php echo $ii; ?>');"><?php echo $daMsgFormat ?></textarea>                
                
                </td></tr>
           <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
    if (isset($pMeta['SNAPformat'])) $optMt['daTextFormat'] = $pMeta['SNAPformat'];  if (isset($pMeta['SNAPformatT'])) $optMt['daTitleFormat'] = $pMeta['SNAPformatT'];  
    if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; 
    if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];        
    if (isset($pMeta['doDA'])) $optMt['doDA'] = $pMeta['doDA'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doDA'] = 0; } 
    if (isset($pMeta['SNAPincludeDA']) && $pMeta['SNAPincludeDA'] == '1' ) $optMt['doDA'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToDA_ajax")) {
  function nxs_rePostToDA_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['da'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['daPageID'].$two['daUName']==$_POST['nid']) {  
      $dapo =  get_post_meta($postID, 'snapDA', true); $dapo =  maybe_unserialize($dapo);// prr($dapo);
      if (is_array($dapo) && isset($dapo[$ii]) && is_array($dapo[$ii])){ $ntClInst = new nxs_snapClassDA(); $two = $ntClInst->adjMetaOpt($two, $dapo[$ii]); } 
      $result = nxs_doPublishToDA($postID, $two); if ($result == 200) die("Successfully sent your post to deviantART."); else die($result);        
    }    
  }
}  
if (!function_exists("nxs_doPublishToDA")) { //## Second Function to Post to DA
  function nxs_doPublishToDA($postID, $options){ $ntCd = 'DA'; $ntCdL = 'da'; $ntNm = 'deviantART';  if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));      
      $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));   
      $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();     
      
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#800000">deviantART</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  
           nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }       
      $message = array('message'=>'', 'link'=>'', 'imageURL'=>'', 'videoURL'=>''); 
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $message['description'] = 'Test Post, Description';  $message['title'] = 'Test Post - Title';  $message['url'] = home_url();    
      } else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return; 
        
        $options['daTitleFormat'] = nsFormatMessage($options['daTitleFormat'], $postID, $addParams);  $options['daTextFormat'] = nsFormatMessage($options['daTextFormat'], $postID, $addParams); // prr($options['daTextFormat']); echo $postID;
        $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'');
      }            
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_DA(); $ret = $ntToPost->doPostToNT($options, $message); // echo "~~~"; prr($ret); echo "+++";
      //## Save Session
      if (empty($options['ck'])) $options['ck'] = '';
      if (!empty($ret) && is_array($ret) && !empty($ret['ck']) && !empty($ret['ck']) && serialize($ret['ck'])!=$options['ck']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options; // prr($gOptions['pn']);
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions[$ntCdL][$options['ii']]['ck'] = serialize($ret['ck']);   $gOptions[$ntCdL][$options['ii']]['mh'] = serialize($ret['mh']); update_option('NS_SNAutoPoster', $gOptions);  }        
        else foreach ($gOptions[$ntCdL] as $ii=>$gpn) { $result = array_diff($options, $gpn);
          if (!is_array($result) || count($result)<1) { $gOptions[$ntCdL][$ii]['ck'] = serialize($ret['ck']);  $gOptions[$ntCdL][$options['ii']]['mh'] = serialize($ret['mh']); $plgn_NS_SNAutoPoster->nxs_options = $gOptions; update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
      } 
      //## Process Results
      if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
         if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
      } else {  // ## All Good - log it.
        if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
          else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'postURL'=>$ret['postURL'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      }
      //## Return Result
      if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);      
      
  } 
}  
?>