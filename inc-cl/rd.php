<?php    
//## NextScripts Reddit Connection Class
if(function_exists('doConnectToRD')) { $nxs_snapAvNts[] = array('code'=>'RD', 'lcode'=>'rd', 'name'=>'Reddit'); }

if (!class_exists("nxs_snapClassRD")) { class nxs_snapClassRD {  
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'RD', 'lcode'=>'rd', 'name'=>'Reddit', 'defNName'=>'rdUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php if(!function_exists('doConnectToRD')) {?> Reddit doesn't have a built-in API for automated posts yet.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/reddit-automated-posting">library module</a> to be able to publish your content to Reddit. 
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
          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php //$pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mrdo){ $rdo = array('nName'=>'', 'doRD'=>'1', 'rdUName'=>'', 'rdPageID'=>'', 'rdCommID'=>'', 'postType'=>'A', 'rdPass'=>''); $rdo['ntInfo']= array('lcode'=>'rd'); $this->showNTSettings($mrdo, $rdo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['rdSubReddit'])) $options['rdSubReddit'] = ''; ?>
            <div id="doRD<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">     <input type="hidden" name="apDoSRD<?php echo $ii; ?>" value="0" id="apDoSRD<?php echo $ii; ?>" />      
            
            <?php if(!function_exists('doConnectToRD')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b><?php _e('Reddit API Library not found', 'nxs_snap'); ?></b>
             <br/><br/> <?php _e('Reddit doesn\'t have a built-in API for automated posts yet.', 'nxs_snap'); ?> <br/><?php _e('<br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/reddit-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Reddit.', 'nxs_snap'); ?></span></div>
            <?php return; }; ?>  
            
                               
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/rd16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-reddit-social-networks-auto-poster-wordpress/"><?php $nType="Reddit"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="rd[<?php echo $ii; ?>][nName]" id="rdnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('rd', $ii, $options['qTLng']); ?>
            
            
          <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
      
            
            <div style="width:100%;"><strong>Reddit Username:</strong> </div><input name="rd[<?php echo $ii; ?>][uName]" id="apRDUName<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['rdUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Reddit Password:</strong> </div><input name="rd[<?php echo $ii; ?>][uPass]" id="apRDPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['rdPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['rdPass'], 5)):$options['rdPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <div style="width:100%;"><strong>Subreddit ID:</strong>                         
            Please <a href="#" onclick="nxs_getBrdsOrCats(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apRDUName<?php echo $ii; ?>').val(),jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apRDPass<?php echo $ii; ?>').val(), 'rd' , '<?php echo $ii; ?>', 'rdSubReddit'); return false;">click here to retrieve your subreddits</a>
            </div>
            <img id="rdLoadingImg<?php echo $ii; ?>" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' /> 
            <select name="rd[<?php echo $ii; ?>][rdSubReddit]" id="rdSubReddit<?php echo $ii; ?>">
            <?php if ($options['rdSubRedditsList']!=''){ $gBoards = $options['rdSubRedditsList']; if ( base64_encode(base64_decode($gBoards)) === $gBoards) $gBoards = base64_decode($gBoards); 
              if ($options['rdSubReddit']!='') $gBoards = str_replace($options['rdSubReddit'].'"', $options['rdSubReddit'].'" selected="selected"', $gBoards);  echo $gBoards;} else { ?>
              <option value="0">None(Click above to retrieve your subreddits)</option>
            <?php } ?>
            </select>
            
            <br/><br/>              
            <?php /* <input name="rd[<?php echo $ii; ?>][rdSubReddit]" id="apRDPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['rdSubReddit'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  */ ?>
            <i style="color: #580000;">Please do not try to post to subredits that you do not own. Reddit is very serious about it's policy that prohibits sharing your own links. You will loose posting privileges and you account will be <b>banned</b> if you post to public subreddits. </i>
            <br/>  <br/>  
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="rdTitleFormat<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('rdTitleFormat<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              <input name="rd[<?php echo $ii; ?>][rdTitleFormat]" id="rdTitleFormat<?php echo $ii; ?>" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($options['rdTitleFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>"  onfocus="mxs_showFrmtInfo('rdTitleFormat<?php echo $ii; ?>');" /><?php nxs_doShowHint("rdTitleFormat".$ii); ?>
            </div><br/> 
            
     <div style="width:100%;"><strong id="altFormatText">Post Type:</strong></div>                      
      <div style="margin-left: 10px;">
        <input type="radio" name="rd[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($options['postType']) || $options['postType'] == '' || $options['postType'] == 'A') echo 'checked="checked"'; ?> /> <?php _e('Link Post', 'nxs_snap'); ?>
        <br/>
        <input type="radio" name="rd[<?php echo $ii; ?>][postType]" value="T" <?php if ($options['postType'] == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap'); ?> - <i><?php _e('set the text format below', 'nxs_snap'); ?></i>
     </div><br/>
                      
            <div id="altFormat" style="margin-left: 20px;">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="rdTextFormat<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('rdTextFormat<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
              <textarea cols="150" rows="3" id="rd<?php echo $ii; ?>SNAPformat" name="rd[<?php echo $ii; ?>][rdTextFormat]" style="width:51%;max-width: 650px;" onfocus="jQuery('#rd<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apRDMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) _e("New post (%TITLE%) has been published on %SITENAME%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($options['rdTextFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("rdTextFormat".$ii); ?>
            </div><br/>          
            
            
            <?php if ($isNew) { ?> <input type="hidden" name="rd[<?php echo $ii; ?>][apDoRD]" value="1" id="apDoNewRD<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['rdPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('RD', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>              <?php } 
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
  function setNTSettings($post, $options){ $code = 'RD'; $lcode = 'rd'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['uName']) && $pval['uName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['uName']))   $options[$ii]['rdUName'] = trim($pval['uName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['uPass']))    $options[$ii]['rdPass'] = 'n5g9a'.nsx_doEncode($pval['uPass']); else $options[$ii]['rdPass'] = '';  
        
        if (empty($options[$ii]['rdSubRedditsList'])) { $pass = substr($options[$ii]['rdPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options[$ii]['rdPass'], 5)):$options[$ii]['rdPass'];
           $loginInfo = doConnectToRD($options[$ii]['rdUName'], $pass); if (is_array($loginInfo))  { 
               $options[$ii]['rdSubRedditsList'] = doGetSubredditsFromRD();
           }  
        }
        
        if (isset($pval['rdSubReddit'])) $options[$ii]['rdSubReddit'] = trim($pval['rdSubReddit']);          
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                      
        if (isset($pval['postType']))   $options[$ii]['postType'] = $pval['postType'];         
        if (isset($pval['rdTitleFormat'])) $options[$ii]['rdTitleFormat'] = trim($pval['rdTitleFormat']);
        if (isset($pval['rdTextFormat'])) $options[$ii]['rdTextFormat'] = trim($pval['rdTextFormat']);
        
        if (isset($pval['apDoRD']))      $options[$ii]['doRD'] = $pval['apDoRD']; else $options[$ii]['doRD'] = 0; 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){global $nxs_plurl; $post_id = $post->ID; $nt = 'rd'; $ntU = 'RD'; 
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapRD', true));  if (!empty($pMeta) && is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doRD = $ntOpt['doRD'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailRD =  $ntOpt['rdUName']!='' && $ntOpt['rdPass']!='';   $rdMsgFormat = htmlentities($ntOpt['rdTextFormat'], ENT_COMPAT, "UTF-8");      $rdMsgTFormat = htmlentities($ntOpt['rdTitleFormat'], ENT_COMPAT, "UTF-8");      
        $rdPostType = $ntOpt['postType'];
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailRD) { ?><input class="nxsGrpDoChb" value="1" id="doRD<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="rd[<?php echo $ii; ?>][doRD]" <?php if ((int)$doRD == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="rd[<?php echo $ii; ?>][doRD]" value="<?php echo $doRD;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/rd16.png);">Reddit - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailRD) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToRD_repostButton" id="rePostToRD_button" value="<?php _e('Repost to Reddit', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdRD<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Reddit"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailRD) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Reddit Account to AutoPost to Reddit</b></td></tr>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>rd" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                
       <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
        <td><input value="<?php echo $rdMsgTFormat ?>" type="text" name="rd[<?php echo $ii; ?>][SNAPformatT]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apRDMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apRDMsgTFrmt".$ii, '', '58'); ?></td></tr>  
                
       <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> <?php _e('Post Type:', 'nxs_snap') ?> </th><td>             
        <input type="radio" name="rd[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($rdPostType) || $rdPostType == '' || $rdPostType == 'A') echo 'checked="checked"'; ?> /><?php _e('Link Post', 'nxs_snap') ?>
        <br/>
      <input type="radio" name="rd[<?php echo $ii; ?>][postType]" value="T" <?php if ($rdPostType == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap') ?><br/>               
     </td></tr>
     
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top;  padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th><td>                
                
                <textarea cols="150" rows="1" id="rd<?php echo $ii; ?>SNAPformat" name="rd[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#rd<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apRDMsgFrmt<?php echo $ii; ?>');"><?php echo $rdMsgFormat ?></textarea>                
                
                </td></tr>
           <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
    if (isset($pMeta['SNAPformat'])) $optMt['rdTextFormat'] = $pMeta['SNAPformat'];  if (isset($pMeta['SNAPformatT'])) $optMt['rdTitleFormat'] = $pMeta['SNAPformatT'];  
    if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; 
    if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
    if (isset($pMeta['postType'])) $optMt['postType'] = $pMeta['postType'];
    if (isset($pMeta['doRD'])) $optMt['doRD'] = $pMeta['doRD'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doRD'] = 0; } 
    if (isset($pMeta['SNAPincludeRD']) && $pMeta['SNAPincludeRD'] == '1' ) $optMt['doRD'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToRD_ajax")) {
  function nxs_rePostToRD_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['rd'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['rdPageID'].$two['rdUName']==$_POST['nid']) {  
      $rdpo =  get_post_meta($postID, 'snapRD', true); $rdpo =  maybe_unserialize($rdpo);// prr($rdpo);
      if (is_array($rdpo) && isset($rdpo[$ii]) && is_array($rdpo[$ii])){ $ntClInst = new nxs_snapClassRD(); $two = $ntClInst->adjMetaOpt($two, $rdpo[$ii]); } 
      $result = nxs_doPublishToRD($postID, $two); if ($result == 200) die("Successfully sent your post to Reddit."); else die($result);        
    }    
  }
}  
if (!function_exists("nxs_doPublishToRD")) { //## Second Function to Post to RD
  function nxs_doPublishToRD($postID, $options){ $ntCd = 'RD'; $ntCdL = 'rd'; $ntNm = 'Reddit'; if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));       
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#800000">Reddit</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  
           nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }       
      $message = array('message'=>'', 'link'=>'', 'imageURL'=>'', 'videoURL'=>''); 
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $message['description'] = 'Test Post, Description';  $message['title'] = 'Test Post - Title';  $message['url'] = home_url();    
      } else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return; 
        $rdPostType = $options['postType']; 
        $options['rdTitleFormat'] = nsFormatMessage($options['rdTitleFormat'], $postID);  $options['rdTextFormat'] = nsFormatMessage($options['rdTextFormat'], $postID); // prr($msg); echo $postID;
        $extInfo = ' | PostID: '.$postID." - ".$post->post_title;
        $message = array('message'=>$options['rdTextFormat'], 'url'=>get_permalink($postID), 'title'=>$options['rdTitleFormat']);
      }            
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_RD(); $ret = $ntToPost->doPostToNT($options, $message); // echo "~~~"; prr($ret); echo "+++";
      //## Process Results
      if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
         if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
      } else {  // ## All Good - log it.
        if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
          else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); 
          $extInfo .= ' | <a href="'.$ret['postURL'].'" target="_blank">Post Link</a>'; nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      }
      //## Return Result
      if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);      
      
  } 
}  
?>