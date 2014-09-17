<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PK', 'lcode'=>'pk', 'name'=>'Plurk');

if (!class_exists("nxs_snapClassPK")) { class nxs_snapClassPK { var $ntInfo = array('code'=>'PK', 'lcode'=>'pk', 'name'=>'Plurk', 'defNName'=>'', 'tstReq' => true); 
    
  function pkCats() { return '<option value="">:freestyle(None)</option><option value="loves">loves</option><option value="likes">likes</option><option value="shares">shares</option><option value="gives">gives</option><option value="hates">hates</option><option value="wants">wants</option><option value="wishes">wishes</option><option value="needs">needs</option><option value="will">will</option><option value="hopes">hopes</option><option value="asks">asks</option><option value="has">has</option><option value="was">was</option><option value="wonders">wonders</option><option value="feels on">feels</option><option value="thinks">thinks</option><option value="says">says</option><option value="is">is</option>';}  
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapSetPgURL, $nxs_plurl, $nxs_gOptions;  $ntInfo = $this->ntInfo;  
   if ( isset($_GET['auth']) && $_GET['auth']=='pk'){ require_once('apis/plurkOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
              $callback_url = $nxs_snapSetPgURL."&auth=pka&acc=".$_GET['acc'];
             
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret); //prr($tum_oauth);
              $request_token = $tum_oauth->getReqToken($callback_url); 
              $options['pkOAuthToken'] = $request_token['oauth_token'];
              $options['pkOAuthTokenSecret'] = $request_token['oauth_token_secret'];// prr($tum_oauth ); die();

              //prr($tum_oauth); prr($options); die();
              
              switch ($tum_oauth->http_code) { case 200: $url = 'http://www.plurk.com/OAuth/authorize?oauth_token='.$options['pkOAuthToken'];                 
                if (function_exists('get_option')) $nxs_gOptions = get_option('NS_SNAutoPoster'); if(!empty($nxs_gOptions)) { $nxs_gOptions['pk'][$_GET['acc']] = $options; nxs_settings_save($nxs_gOptions); }
                echo '<br/><br/>All good?! Redirecting ..... <script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to Plurk. Refresh the page or try again later.</b>'; die();
              }
              die();
            }
   if ( isset($_GET['auth']) && $_GET['auth']=='pka'){ require_once('apis/plurkOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
            
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkOAuthToken'], $options['pkOAuthTokenSecret']); //prr($tum_oauth);
              $access_token = $tum_oauth->getAccToken($_GET['oauth_verifier']); prr($access_token);
              $options['pkAccessTocken'] = $access_token['oauth_token'];  $options['pkAccessTockenSec'] = $access_token['oauth_token_secret'];
              if (function_exists('get_option')) $nxs_gOptions = get_option('NS_SNAutoPoster'); if(!empty($nxs_gOptions)) { $nxs_gOptions['pk'][$_GET['acc']] = $options; nxs_settings_save($nxs_gOptions); }
              
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkAccessTocken'], $options['pkAccessTockenSec']); 
              $uinfo = $tum_oauth->makeReq('http://www.plurk.com/APP/Profile/getOwnProfile', $params); 
              if (is_array($uinfo) && isset($uinfo['user_info'])) $userinfo = $uinfo['user_info']['display_name'];
              if (empty($userinfo) && is_array($uinfo) && isset($uinfo['user_info'])) $userinfo = $uinfo['user_info']['nick_name'];  $options['pkPgID'] = $userinfo; 
              if (function_exists('get_option')) $nxs_gOptions = get_option('NS_SNAutoPoster'); if(!empty($nxs_gOptions)) { $nxs_gOptions['pk'][$_GET['acc']] = $options; nxs_settings_save($nxs_gOptions); }
              if ($options['pkPgID']!='') {  
                  $gGet = $_GET; unset($gGet['auth']); unset($gGet['acc']); unset($gGet['oauth_token']);  unset($gGet['oauth_verifier']); unset($gGet['post_type']);
                  $sturl = explode('?',$nxs_snapSetPgURL); $nxs_snapSetPgURL = $sturl[0].((!empty($gGet))?'?'.http_build_query($gGet):'');
                  echo '<br/><br/>All good?! Redirecting ..... <script type="text/javascript">window.location = "'.$nxs_snapSetPgURL.'"</script>'; break;  die();
              }
                else die("<span style='color:red;'>ERROR: Authorization Error: <span style='color:darkred; font-weight: bold;'>".$options['pkPgID']."</span></span>");              
            }
    global $nxs_plurl; ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['pkURL']));         
        if (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='') $pbo[$ntInfo['lcode'].'OK'] = (isset($pbo['pkOAuthTokenSecret']) && $pbo['pkOAuthTokenSecret']!='')?'1':''; ?>
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
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doPK'=>'1', 'pkURL'=>'', 'pkPgID'=>'', 'pkConsKey'=>'', 'pkInclTags'=>'1', 'cImgURL'=>'R', 'pkConsSec'=>'', 'pkPostType'=>'T', 'pkDefImg'=>'', 'pkOAuthTokenSecret'=>'', 'pkAccessTocken'=>'', 'pkMsgFormat'=>'%TITLE% - %URL%'); $po['ntInfo']= array('lcode'=>'pk'); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl,$nxs_snapSetPgURL; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  if (!isset($options['attchImg'])) $options['attchImg'] = ''; ?>
    <div id="doPK<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">   <input type="hidden" name="apDoSPK<?php echo $ii; ?>" value="0" id="apDoSPK<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="pk[<?php echo $ii; ?>][apDoPK]" value="1" id="apDoNewPK<?php echo $ii; ?>" /> <?php } ?>
    
    <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/pk16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-plurk-social-networks-auto-poster-wordpress/"><?php $nType="Plurk"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
    
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="pk[<?php echo $ii; ?>][nName]" id="pknName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('pk', $ii, $options['qTLng']); ?>
            
              <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
            
            <div style="width:100%;"><strong>Your Plurk URL:</strong> </div><input onchange="nxsPKURLVal(<?php echo $ii; ?>);" name="pk[<?php echo $ii; ?>][apPKURL]" id="apPKURL<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><span style="color: #F00000;" id="apPKURLerr<?php echo $ii; ?>"></span>
            <div style="width:100%;"><strong>Your Plurk App Key:</strong> </div><input name="pk[<?php echo $ii; ?>][apPKConsKey]" id="apPKConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkConsKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />             
            <div style="width:100%;"><strong>Your Plurk App Secret:</strong> </div><input name="pk[<?php echo $ii; ?>][apPKConsSec]" id="apPKConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkConsSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
            <br/><br/>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Plurk prefix:</strong> </div>
  
            <select name="pk[<?php echo $ii; ?>][Cat]" id="pkCat<?php echo $ii; ?>">
            <?php  $pkCats = $this->pkCats(); 
              if (isset($options['pkCat']) && $options['pkCat']!='') $pkCats = str_replace($options['pkCat'].'"', $options['pkCat'].'" selected="selected"', $pkCats);  echo $pkCats; 
            ?>
            </select>            
            </div>  
            <br/>
    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="pk[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$options['attchImg'] == 1) echo "checked"; ?> /> <strong>Attach Image to Plurk Post</strong></p>
    <br/>
            
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apPKMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apPKMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>) </div>
              
               <textarea cols="150" rows="3" id="pk<?php echo $ii; ?>SNAPformat" name="pk[<?php echo $ii; ?>][apPKMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#pk<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apPKMsgFrmt<?php echo $ii; ?>');"><?php if ($options['pkMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['pkMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo htmlentities("%TITLE% - %URL%"); ?></textarea>
              
              <br/>
               <?php nxs_doShowHint("apPKMsgFrmt".$ii); ?>
              <br/>
              <?php 
            if($options['pkConsSec']=='') { ?>
            <b>Authorize Your Plurk Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if (!empty($options['pkAccessTocken']) && $options['pkAccessTockenSec']!=='') { ?>
            Your Plurk Account has been authorized. Your display name: <?php _e(apply_filters('format_to_edit', htmlentities($options['pkPgID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>. 
            You can Re- <?php } ?>            
            <a href="<?php echo $nxs_snapSetPgURL.(stripos($nxs_snapSetPgURL, '?')!==false?'&':'?');?>auth=pk&acc=<?php echo $ii; ?>">Authorize Your Plurk Account</a> 
              <?php if (!isset($options['pkOAuthTokenSecret']) || $options['pkOAuthTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>            
            <?php }  ?>            
            
            
            <?php if( isset($options['pkOAuthTokenSecret']) && $options['pkOAuthTokenSecret']!='') { ?>
            
            <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('PK', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>  <br/><br/>
            <?php }?>
            </div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
   <?php if (!$isNew) { ?> <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
     <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>  <?php } ?><?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = $this->ntInfo['code'];
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apPKConsKey']) && $pval['apPKConsSec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apPKURL']))  {   $options[$ii]['pkURL'] = trim($pval['apPKURL']);  if ( substr($options[$ii]['pkURL'], 0, 4)!='http' )  $options[$ii]['pkURL'] = 'http://'.$options[$ii]['pkURL'];
                  $pkPgID = $options[$ii]['pkURL']; if (substr($pkPgID, -1)=='/') $pkPgID = substr($pkPgID, 0, -1);  $pkPgID = substr(strrchr($pkPgID, "/"), 1);
                  $options[$ii]['pkPgID'] = $pkPgID; //echo $fbPgID;
                }
                if (isset($pval['apDoPK']))         $options[$ii]['doPK'] = $pval['apDoPK']; else $options[$ii]['doPK'] = 0;
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apPKConsKey']))    $options[$ii]['pkConsKey'] = trim($pval['apPKConsKey']);
                if (isset($pval['apPKConsSec']))    $options[$ii]['pkConsSec'] = trim($pval['apPKConsSec']);
                
                if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
                if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                                                
                if (isset($pval['apPKMsgFrmt']))    $options[$ii]['pkMsgFormat'] = trim($pval['apPKMsgFrmt']);                                
                if (isset($pval['Cat']))      $options[$ii]['pkCat'] = $pval['Cat']; else $options[$ii]['pkCat'] = "";
                if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                
                
                $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
                if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }  elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'pk'; $ntU = 'PK';
    foreach($ntOpts as $ii=>$ntOpt)  {$pMeta = maybe_unserialize(get_post_meta($post_id, 'snapPK', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
       $doPK = $ntOpt['doPK'] && (is_array($pMeta) || $ntOpt['catSel']!='1');// prr($ntOpt);
       $isAvailPK =  (!empty($ntOpt['pkAccessTocken'])  && !empty($ntOpt['pkOAuthTokenSecret']));
       $pkMsgFormat = htmlentities($ntOpt['pkMsgFormat'], ENT_COMPAT, "UTF-8"); 
      ?>  
      
<tr><th style="text-align:left;" colspan="2">
<?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailPK) { ?><input class="nxsGrpDoChb" value="1" id="doPK<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="pk[<?php echo $ii; ?>][doPK]" <?php if ((int)$doPK == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="pk[<?php echo $ii; ?>][doPK]" value="<?php echo $doPK;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/pk16.png);">Plurk - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>) </div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPK) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToPK_repostButton" id="rePostToPK_button" value="<?php _e('Repost to Plurk', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdPK<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://www.plurk.com/p/<?php echo base_convert($pMeta[$ii]['pgID'], 10, 36); ?>" target="_blank"><?php $nType="Plurk"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>
                <?php if (!$isAvailPK) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and authorize your Plurk Account to AutoPost to Plurk</b>
                <?php }else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>pk" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                         
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">
                Prefix:
                
                </th>
                <td><select name="pk[<?php echo $ii; ?>][Cat]" id="apPKCat<?php echo $ii; ?>">
            <?php  $pkCats = $this->pkCats(); 
              if ($ntOpt['pkCat']!='') $pkCats = str_replace($ntOpt['pkCat'].'"', $ntOpt['pkCat'].'" selected="selected"', $pkCats);  echo $pkCats; 
            
             ?>
            </select></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="pk<?php echo $ii; ?>SNAPformat" name="pk[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#pk<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apPKMsgFrmt<?php echo $ii; ?>');"><?php echo $pkMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apPKMsgFrmt".$ii); ?></td></tr>
                               
   <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['pkMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['Cat'])) $optMt['pkCat'] = $pMeta['Cat'];      
     if (isset($pMeta['doPK'])) $optMt['doPK'] = $pMeta['doPK'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doPK'] = 0; }
     if (isset($pMeta['SNAPincludePK']) && $pMeta['SNAPincludePK'] == '1' ) $optMt['doPK'] = 1;  
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToPK_ajax")) { function nxs_rePostToPK_ajax() {  check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['pk'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapPK', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassPK(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); }
      $result = nxs_doPublishToPK($postID, $po); if ($result == 200 || $result == 201) die("Your post has been successfully sent to Plurk."); else { echo $result; die(); }
    }    
  }
}

if (!function_exists("nxs_doPublishToPK")) { //## Second Function to Post to TR
  function nxs_doPublishToPK($postID, $options){ $ntCd = 'PK'; $ntCdL = 'pk'; $ntNm = 'Plurk'; if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToPK',  array($postID, $options));          
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));
    if (empty($options['imgToUse'])) $options['imgToUse'] = ''; if (empty($options['imgSize'])) $options['imgSize'] = '';
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#014A76">Plurk</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    }  
    //## Format
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle;  $msgT = 'Test Post from '.$blogTitle;}
      else { $post = get_post($postID); if(!$post) return; $twMsgFormat = $options['pkMsgFormat']; nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $twLim = 180;
        $extInfo = ' | PostID: '.$postID." - ".$post->post_title;
      
        if (stripos($twMsgFormat, '%URL%')!==false || stripos($twMsgFormat, '%SURL%')!==false) $twLim = $twLim - 5; 
        if (stripos($twMsgFormat, '%AUTHORNAME%')!==false) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun ); $twLim = $twLim - strlen($aun); } 
        
        $noRepl = str_ireplace("%TITLE%", "", $twMsgFormat); $noRepl = str_ireplace("%SITENAME%", "", $noRepl); $noRepl = str_ireplace("%URL%", "", $noRepl);$noRepl = str_ireplace("%RAWEXCERPT%", "", $noRepl);
        $noRepl = str_ireplace("%SURL%", "", $noRepl);$noRepl = str_ireplace("%TEXT%", "", $noRepl);$noRepl = str_ireplace("%FULLTEXT%", "", $noRepl);$noRepl = str_ireplace("%EXCERPT%", "", $noRepl);
        $noRepl = str_ireplace("%ANNOUNCE%", "", $noRepl); $noRepl = str_ireplace("%AUTHORNAME%", "", $noRepl); $twLim = $twLim - strlen($noRepl); 
        
        $pTitle = $title = $post->post_title;
        if ($post->post_excerpt!="") $pText = apply_filters('the_content', $post->post_excerpt); else $pText= apply_filters('the_content', $post->post_content);
        $pFullText = apply_filters('the_content', $post->post_content);  $pRawText = $post->post_content;        
               
        if (stripos($twMsgFormat, '%TITLE%')!==false) { $pTitle = nsTrnc($pTitle, $twLim); $twMsgFormat = str_ireplace("%TITLE%", $pTitle, $twMsgFormat); $twLim = $twLim - strlen($pTitle); } 
        if (stripos($twMsgFormat, '%SITENAME%')!==false) {
          $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $siteTitle = nsTrnc($siteTitle, $twLim); $twMsgFormat = str_ireplace("%SITENAME%", $siteTitle, $twMsgFormat); $twLim = $twLim - strlen($siteTitle);
        }        
        if (stripos($twMsgFormat, '%EXCERPT%')!==false) { $pText = nsTrnc(strip_tags(strip_shortcodes($pText)), 300, " ", "...");
          $pText = nsTrnc($pText, $twLim); $twMsgFormat = str_ireplace("%EXCERPT%", $pText, $twMsgFormat); $twLim = $twLim - strlen($pText);
        }
        if (stripos($twMsgFormat, '%FULLTEXT%')!==false) { $pFullText = nsTrnc(strip_tags($pFullText), $twLim); $twMsgFormat = str_ireplace("%FULLTEXT%", $pFullText, $twMsgFormat); $twLim = $twLim - strlen($pFullText); }          
        if (stripos($twMsgFormat, '%RAWTEXT%')!==false) { $pRawText = nsTrnc(strip_tags($pRawText), $twLim); $twMsgFormat = str_ireplace("%FULLTEXT%", $pRawText, $twMsgFormat); $twLim = $twLim - strlen($pRawText); }          
        $options['pkMsgFormat'] = nsFormatMessage($twMsgFormat, $postID, $addParams);         
    } 
    if (isset($options['attchImg']) && $options['attchImg']=='1' ) if (!empty($options['imgToUse'])) $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'full'); else $imgURL='';
    if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = ''; 
    //## Post             
    $message = array('siteName'=>$blogTitle, 'tags'=>'', 'imageURL'=>$imgURL);// prr($message);
    //## Actual Post
    $ntToPost = new nxs_class_SNAP_PK(); $ret = $ntToPost->doPostToNT($options, $message);
    //## Process Results
    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
      if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
    } else {  // ## All Good - log it.
      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
        else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
    }
    //## Return Result
    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);     
  }
}

?>