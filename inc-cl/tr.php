<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'TR', 'lcode'=>'tr', 'name'=>'Tumblr');

if (!class_exists("nxs_snapClassTR")) { class nxs_snapClassTR { var $ntInfo = array('code'=>'TR', 'lcode'=>'tr', 'name'=>'Tumblr', 'defNName'=>'', 'tstReq' => true);
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapSetPgURL, $nxs_plurl, $nxs_gOptions;  $ntInfo = $this->ntInfo; 
   if ( isset($_GET['auth']) && $_GET['auth']=='tr'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']];
     $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
              $callback_url = $nxs_snapSetPgURL."&auth=tra&acc=".$_GET['acc'];
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret);prr($tum_oauth );
              $request_token = $tum_oauth->getRequestToken($callback_url); echo "####"; prr($request_token);
              $options['trOAuthToken'] = $request_token['oauth_token'];
              $options['trOAuthTokenSecret'] = $request_token['oauth_token_secret'];// prr($tum_oauth ); die();
              switch ($tum_oauth->http_code) { case 200: $url = $tum_oauth->getAuthorizeURL($options['trOAuthToken']); 
              if (function_exists('get_option')) $nxs_gOptions = get_option('NS_SNAutoPoster'); if(!empty($nxs_gOptions)) { $nxs_gOptions['tr'][$_GET['acc']] = $options; nxs_settings_save($nxs_gOptions); }
                echo '<script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to Tumblr. Refresh the page or try again later.</b>'; die();
              }
              die();
            }
   if ( isset($_GET['auth']) && $_GET['auth']=='tra'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']]; prr($options);
     
              $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];  
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trOAuthToken'], $options['trOAuthTokenSecret']);
              $options['trAccessTocken'] = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']); // prr($_GET);  prr($_REQUEST);   prr($options['trAccessTocken']);         
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']);               
              if (function_exists('get_option')) $nxs_gOptions = get_option('NS_SNAutoPoster'); if(!empty($nxs_gOptions)) { $nxs_gOptions['tr'][$_GET['acc']] = $options; nxs_settings_save($nxs_gOptions); }
              $userinfo = $tum_oauth->get('http://api.tumblr.com/v2/user/info'); prr($userinfo); prr($tum_oauth);// prr($url); die();
              if (is_array($userinfo->response->user->blogs)) {
                foreach ($userinfo->response->user->blogs as $blog){
                  if (stripos($blog->url, $options['trPgID'])!==false) {  
                     $gGet = $_GET; unset($gGet['auth']); unset($gGet['acc']); unset($gGet['oauth_token']);  unset($gGet['oauth_verifier']); unset($gGet['post_type']);
                     $sturl = explode('?',$nxs_snapSetPgURL); $nxs_snapSetPgURL = $sturl[0].((!empty($gGet))?'?'.http_build_query($gGet):'');  
                     echo '<script type="text/javascript">window.location = "'.$nxs_snapSetPgURL.'"</script>'; die();
                  }
                } prr($userinfo);
                die("<span style='color:red;'>ERROR: Authorized USER don't have access to the specified blog: <span style='color:darkred; font-weight: bold;'>".$options['trPgID']."</span></span>");
              }
   }
    ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['trURL'])); 
        if (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='') $pbo[$ntInfo['lcode'].'OK'] = (isset($pbo['trOAuthTokenSecret']) && $pbo['trOAuthTokenSecret']!='')?'1':''; ?>
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
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doTR'=>'1', 'trURL'=>'', 'trPgID'=>'', 'trConsKey'=>'', 'trInclTags'=>'1', 'fillSrcURL'=>'1', 'useOrDate'=>'1', 'trInclCats'=>'0', 'cImgURL'=>'R', 'trConsSec'=>'', 'trPostType'=>'T', 'trDefImg'=>'', 'trOAuthTokenSecret'=>'', 'trAccessTocken'=>'', 'trMsgFormat'=>'<p>New Post has been published on %URL%</p><blockquote><p><strong>%TITLE%</strong></p><p><img src=\'%IMG%\'/></p><p>%FULLTEXT%</p></blockquote>', 'trMsgTFormat'=>'New Post has been published on %SITENAME%' );
  $po['ntInfo']= array('lcode'=>'tr'); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl,$nxs_snapSetPgURL; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = '';  if (!isset($options['fillSrcURL'])) $options['fillSrcURL'] = '0'; if (!isset($options['useOrDate'])) $options['useOrDate'] = '1';
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['trMsgTFormat'])) $options['trMsgTFormat'] = '';  ?>
    <div id="doTR<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/tr-bg.png);  background-position:90% 10%;">   <input type="hidden" name="apDoSTR<?php echo $ii; ?>" value="0" id="apDoSTR<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="tr[<?php echo $ii; ?>][apDoTR]" value="1" id="apDoNewTR<?php echo $ii; ?>" /> <?php } ?>
    
    <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/tr16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-tumblr-social-networks-auto-poster-wordpress/"><?php $nType="Tumblr"; printf( __( 'Detailed %s Installation/Configuration Instructions' , 'nxs_snap'), $nType); ?></a></div>
    
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="tr[<?php echo $ii; ?>][nName]" id="trnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('tr', $ii, $options['qTLng']); ?>
            
             <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
            
            <div style="width:100%;"><strong>Tumblr URL:</strong> <i>It should be your public URL. (i.e. like <b>http://nextscripts.tumblr.com/</b>, not http://www.tumblr.com/blog/nextscripts</i> </div><input onchange="nxsTRURLVal(<?php echo $ii; ?>);" name="tr[<?php echo $ii; ?>][apTRURL]" id="apTRURL<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><span style="color: #F00000;" id="apTRURLerr<?php echo $ii; ?>"></span>
            <div style="width:100%;"><strong>Tumblr OAuth Consumer Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsKey]" id="apTRConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trConsKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />             
            <div style="width:100%;"><strong>Tumblr Secret Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsSec]" id="apTRConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trConsSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
            <br/>
            
<div style="width:100%;"><strong id="altFormatText">Default Post Type:</strong></div>                      
<div style="margin-left: 10px;">
    
    <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="T" <?php if ($options['trPostType'] != 'I') echo 'checked="checked"'; ?> onchange="nxs_TRSetEnable('T','<?php echo $ii; ?>');" /> Text Post<br/>            

    <div style="width:100%; margin-left: 15px;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apTRTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apTRTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)  </div><div onblur="jQuery('#apTRMsgFrmt<?php echo $ii; ?>Hint').hide();">
              <input name="tr[<?php echo $ii; ?>][apTRMsgTFrmt]" id="apTRMsgTFrmt<?php echo $ii; ?>" style="margin-left: 15px; width: 50%;" value="<?php if ($options['trMsgTFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['trMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "New Post has been published on %SITENAME%"; ?>"  onfocus="jQuery('#apTRTMsgFrmt<?php echo $ii; ?>Hint').show();"  <?php if ($options['trPostType'] == 'I') echo 'disabled="disabled"'; ?>  /><br/>
              <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?>
            </div>
            
<input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="I" <?php if ($options['trPostType'] == 'I') echo 'checked="checked"'; ?> onchange="nxs_TRSetEnable('I','<?php echo $ii; ?>');"/> Image Post
<i>Don't forget to change default "Post Text Format" to prevent duplicate images.</i><br/>

<div style="width:100%; margin-left: 15px;">

<strong>Clickthrough URL:</strong> 
<p style="margin-bottom: 20px;margin-top: 5px;">
<input type="radio" name="tr[<?php echo $ii; ?>][cImgURL]" value="R" <?php if ( !isset($options['cImgURL']) || $options['cImgURL'] == '' || $options['cImgURL'] == 'R') echo 'checked="checked"'; ?> /> Regular Post URL&nbsp;&nbsp;
<input type="radio" name="tr[<?php echo $ii; ?>][cImgURL]" value="S" <?php if ($options['cImgURL'] == 'S') echo 'checked="checked"'; ?> /> Shortened Post URL&nbsp;&nbsp;
<input type="radio" name="tr[<?php echo $ii; ?>][cImgURL]" value="N" <?php if ($options['cImgURL'] == 'N') echo 'checked="checked"'; ?> /> No Clickthrough URL&nbsp;&nbsp;
</p><strong>Defailt Image to Post:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post is missing "Featured Image" and doesn't have any images in the text body this will be used instead.</p>
            </div><input name="tr[<?php echo $ii; ?>][apTRDefImg]" id="apTRDefImg<?php echo $ii; ?>" style=" margin-left: 15px; width: 30%;" <?php if ($options['trPostType'] != 'I') echo 'disabled="disabled"'; ?> value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trDefImg'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
<br/>            
<input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="U" <?php if ($options['trPostType'] == 'U') echo 'checked="checked"'; ?> /> Audio Post<br/>
<input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="V" <?php if ($options['trPostType'] == 'V') echo 'checked="checked"'; ?> /> Video Post<br/>            
<i style="">Tip: Your post must contain link to Audio or Video file if you select "Audio Post" or "Video Post" , otherwise it will reverted to the "Text Post"</i>
            <br/><br/>

</div>            
            
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apTRMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apTRMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>) </div>
              
              
              <textarea cols="150" rows="3" id="tr<?php echo $ii; ?>SNAPformat" name="tr[<?php echo $ii; ?>][apTRMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#tr<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apTRMsgFrmt<?php echo $ii; ?>');"><?php if ($options['trMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['trMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo htmlentities("<p>New Post has been published on %URL%</p>\r\n<blockquote><p><strong>%TITLE%</strong></p>\r\n<p><img src=\"%IMG%\"/></p><p>%FULLTEXT%</p></blockquote>"); ?></textarea>
              
              <br/>
               <?php nxs_doShowHint("apTRMsgFrmt".$ii); ?>
               
              
              <p style="margin-bottom: 20px;margin-top: 5px;">                                 
              
              <input value="1" type="checkbox" name="tr[<?php echo $ii; ?>][fillSrcURL]"  <?php if ((int)$options['fillSrcURL'] == 1) echo "checked"; ?> /> 
              <strong>Fill "Source URL"</strong> Will fill Tumblr's "Source URL" with post URL or defined URL.
              
              <br/><input value="1" type="checkbox" name="tr[<?php echo $ii; ?>][useOrDate]"  <?php if ((int)$options['useOrDate'] == 1) echo "checked"; ?> /> 
              <strong>Keep Original Post Date</strong> Will post to Tumblr with original date of the post 
              
              <br/><input value="1" type="checkbox" name="tr[<?php echo $ii; ?>][trInclTags]"  <?php if ((int)$options['trInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags.</strong> Tags from the blogpost will be auto posted to Tumblr                                
              
              <br/><input value="1" type="checkbox" name="tr[<?php echo $ii; ?>][trInclCats]"  <?php if ((int)$options['trInclCats'] == 1) echo "checked"; ?> /> 
              <strong>Post categories as tags.</strong> Categories from the blogpost will be auto posted to Tumblr as tags                                
            </p>
              
              <?php 
            if($options['trConsSec']=='') { ?>
            <b>Authorize Your Tumblr Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['trAccessTocken']) && isset($options['trAccessTocken']['oauth_token_secret']) && $options['trAccessTocken']['oauth_token_secret']!=='') { ?>
            Your Tumblr Account has been authorized. Blog ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['trPgID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>. 
            You can Re- <?php } ?>            
            <a href="<?php echo $nxs_snapSetPgURL.(stripos($nxs_snapSetPgURL, '?')!==false?'&':'?');?>auth=tr&acc=<?php echo $ii; ?>">Authorize Your Tumblr Account</a> 
              <?php if (!isset($options['trOAuthTokenSecret']) || $options['trOAuthTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>            
            <?php }  ?>            
            
            
            <?php if( isset($options['trOAuthTokenSecret']) && $options['trOAuthTokenSecret']!='') { ?>
            
            <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('TR', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s' , 'nxs_snap'), $nType); ?></a>  <br/><br/>
            <?php }?>
            
            </div>
      <?php /* ######################## Advanced Tab ####################### */ ?>
    <?php if (!$isNew) { ?> <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
    <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){  $code = $this->ntInfo['code'];
    foreach ($post as $ii => $pval){
      if (isset($pval['apTRConsKey']) && $pval['apTRConsSec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apTRURL']))  {   $options[$ii]['trURL'] = trim($pval['apTRURL']);  if ( substr($options[$ii]['trURL'], 0, 4)!='http' )  $options[$ii]['trURL'] = 'http://'.$options[$ii]['trURL'];
                  $trPgID = $options[$ii]['trURL']; if (substr($trPgID, -1)=='/') $trPgID = substr($trPgID, 0, -1);  $trPgID = substr(strrchr($trPgID, "/"), 1);
                  $options[$ii]['trPgID'] = $trPgID; //echo $fbPgID;
                }
                if (substr($options[$ii]['trURL'], -1)!='/') $options[$ii]['trURL'] .= '/';;
                if (isset($pval['apDoTR']))         $options[$ii]['doTR'] = $pval['apDoTR']; else $options[$ii]['doTR'] = 0;
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apTRConsKey']))    $options[$ii]['trConsKey'] = trim($pval['apTRConsKey']);
                if (isset($pval['apTRConsSec']))    $options[$ii]['trConsSec'] = trim($pval['apTRConsSec']);                                
                if (isset($pval['apTRMsgFrmt']))    $options[$ii]['trMsgFormat'] = trim($pval['apTRMsgFrmt']);                                
                if (isset($pval['apTRMsgTFrmt']))   $options[$ii]['trMsgTFormat'] = trim($pval['apTRMsgTFrmt']);   
                if (isset($pval['trInclTags']))     $options[$ii]['trInclTags'] = $pval['trInclTags']; else $options[$ii]['trInclTags'] = 0;
                if (isset($pval['fillSrcURL']))     $options[$ii]['fillSrcURL'] = $pval['fillSrcURL']; else $options[$ii]['fillSrcURL'] = 0;               
                if (isset($pval['useOrDate']))      $options[$ii]['useOrDate'] = $pval['useOrDate']; else $options[$ii]['useOrDate'] = 0;               
                
                if (isset($pval['trInclCats']))     $options[$ii]['trInclCats'] = $pval['trInclCats']; else $options[$ii]['trInclCats'] = 0;
                if (isset($pval['apTRPostType']))   $options[$ii]['trPostType'] = trim($pval['apTRPostType']);   
                if (isset($pval['cImgURL']))        $options[$ii]['cImgURL'] = trim($pval['cImgURL']);   
                
                if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
                if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';                
                
                if (isset($pval['apTRDefImg']))     $options[$ii]['trDefImg'] = trim($pval['apTRDefImg']);   
                
                $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
                if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }  elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'tr'; $ntU = 'TR';
    foreach($ntOpts as $ii=>$ntOpt)  {$pMeta = maybe_unserialize(get_post_meta($post_id, 'snapTR', true)); // prr($ntOpts); echo "~~~~~~~~~~~~~~~~"; prr($pMeta); echo "#######";
       if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]);
       if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';  if (empty($ntOpt['trMsgTFormat'])) $ntOpt['trMsgTFormat'] = '';
       $doTR = $ntOpt['doTR'] && (is_array($pMeta) || (is_array($pMeta) || $ntOpt['catSel']!='1'));  $imgToUse = $ntOpt['imgToUse'];  $urlToUse = $ntOpt['urlToUse']; 
       $isAvailTR =  isset($ntOpt['trAccessTocken']) && isset($ntOpt['trAccessTocken']['oauth_token_secret']) && $ntOpt['trAccessTocken']['oauth_token_secret']!=='';          
       $trMsgFormat = htmlentities($ntOpt['trMsgFormat'], ENT_COMPAT, "UTF-8");  $trMsgTFormat = htmlentities($ntOpt['trMsgTFormat'], ENT_COMPAT, "UTF-8");
      ?>  
      
 <tr><th style="text-align:left;" colspan="2">
 <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailTR) { ?><input class="nxsGrpDoChb" value="1" id="doTR<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="tr[<?php echo $ii; ?>][doTR]" <?php if ((int)$doTR == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="tr[<?php echo $ii; ?>][doTR]" value="<?php echo $doTR;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/tr16.png);">Tumblr - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>) </div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailTR) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;"Z type="button" class="button" name="rePostToTR_repostButton" id="rePostToTR_button" value="<?php _e('Repost to Tumblr', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdTR<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $ntOpt['trURL']; ?>post/<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Tumblr"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>
                <?php if (!$isAvailTR) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and authorize your Tumblr Account to AutoPost to Tumblr</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>tr" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                         
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">
                <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="T" <?php if ($ntOpt['trPostType'] != 'I') echo 'checked="checked"'; ?>  /> <br/>                
                </th>
                <td><b><?php _e('Text Post. Title Format:', 'nxs_snap') ?></b>&nbsp;<input value="<?php echo $trMsgTFormat ?>" type="text" name="tr[<?php echo $ii; ?>][SNAPTformat]" style="width:270px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTRTMsgFrmt<?php echo $ii; ?>');"/>
                 <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?> </td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">
                <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="I" <?php if ($ntOpt['trPostType'] == 'I') echo 'checked="checked"'; ?>  />  <br/>                
                </th>
                <td><b>Image Post</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="V" <?php if ($ntOpt['trPostType'] == 'V') echo 'checked="checked"'; ?>  /> <b>Video Post</b> <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="U" <?php if ($ntOpt['trPostType'] == 'U') echo 'checked="checked"'; ?>  /> <b>Audio Post</b> <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?> 
                </td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="tr<?php echo $ii; ?>SNAPformat" name="tr[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#tr<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTRMsgFrmt<?php echo $ii; ?>');"><?php echo $trMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apTRMsgFrmt".$ii); ?></td></tr>
                
                <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); nxs_showURLToUseDlg($nt, $ii, $urlToUse); ?>  
                               
   <?php } 
    }
      
  }

  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['trMsgFormat'] = $pMeta['SNAPformat'];  if (isset($pMeta['SNAPTformat'])) $optMt['trMsgTFormat'] = $pMeta['SNAPTformat']; 
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; if (isset($pMeta['urlToUse'])) $optMt['urlToUse'] = $pMeta['urlToUse']; 
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['apTRPostType'])) $optMt['trPostType'] = $pMeta['apTRPostType']; 
     if (isset($pMeta['AttachPost'])) $optMt['trAttch'] = $pMeta['AttachPost'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['trAttch'] = 0; }
     if (isset($pMeta['doTR'])) $optMt['doTR'] = $pMeta['doTR'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doTR'] = 0; }
     if (isset($pMeta['SNAPincludeTR']) && $pMeta['SNAPincludeTR'] == '1' ) $optMt['doTR'] = 1;  
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTR_ajax")) { function nxs_rePostToTR_ajax() {  check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; 
    $options = get_option('NS_SNAutoPoster');  foreach ($options['tr'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj'; 
      $mpo =  get_post_meta($postID, 'snapTR', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassTR();  $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]);  } 
      $result = nxs_doPublishToTR($postID, $po); if ($result == 200 || $result == 201) die("Your post has been successfully sent to Tumblr."); else { echo $result; die(); }
    }    
  }
}

if (!function_exists("nxs_doPublishToTR")) { //## Second Function to Post to TR
  function nxs_doPublishToTR($postID, $options){ $ntCd = 'TR'; $ntCdL = 'tr'; $ntNm = 'Tumblr';   global $plgn_NS_SNAutoPoster; $ytUrl = ''; $imgURL = ''; 
    if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToTR',  array($postID, $options));    
    if (empty($options['imgToUse'])) $options['imgToUse'] = ''; if (empty($options['urlToUse'])) $options['urlToUse'] = '';  
    if (empty($options['trMsgTFormat'])) $options['trMsgTFormat'] = '';   if (empty($options['imgSize'])) $options['imgSize'] = '';
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));
    
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();     
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#014A76">Tumblr</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$options['pType']); return;
        }
    }  
    //## Format
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $options['trMsgFormat'] = 'Test Post from '.$blogTitle;  $msgT = 'Test Post from '.$blogTitle; $options['trPostType']='T'; 
      $postDate = gmdate("Y-m-d H:i:s")." GMT"; $tags = ''; $urlToGo = ''; 
    } else{ $post = get_post($postID); if(!$post) return;  $options['trMsgFormat'] = nsFormatMessage($options['trMsgFormat'], $postID, $addParams); 
      $options['trMsgTFormat'] = nsFormatMessage($options['trMsgTFormat'], $postID, $addParams);  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));   
      
      $tggs = array();   
      if ($options['trInclTags']=='1'){ $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',', $tggs); }        
      if ($options['trInclCats']=='1'){ $t = wp_get_post_categories($postID); foreach($t as $c){ $cat = get_category($c); $tggs[] = $cat->name; }  $tags = implode(',', $tggs); }    
      $postDate = (($options['useOrDate']=='1' && $post->post_date_gmt!='0000-00-00 00:00:00')?$post->post_date_gmt:gmdate("Y-m-d H:i:s", strtotime($post->post_date)))." GMT";  //## Adds date to Tumblr post. Thanks to Kenneth Lecky
    
      if($options['trPostType']=='V') { $vids = nsFindVidsInPost($post); if (count($vids)>0) $ytUrl = $vids[0]; if (trim($ytUrl)=='') $options['trPostType']='T'; }
      if($options['trPostType']=='U') { $aud = nsFindAudioInPost($post); if (count($aud)>0) $aUrl = $aud[0]; if (trim($aUrl)=='') $options['trPostType']='T'; }
      if($options['trPostType']=='I') {  if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'large', $options['trDefImg']);  
        if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = '';  if (trim($imgURL)=='') $options['trPostType']='T'; 
      }
      //## MyURL - URLToGo code
      $options = nxs_getURL($options, $postID, $addParams); $urlToGo = $options['urlToUse'];       
    }   
    $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'').' |'.$options['pType'];        
    //## Post             
    $message = array('siteName'=>$blogTitle, 'imageURL'=>$imgURL, 'tags'=>$tags, 'url'=>$urlToGo, 'postDate'=>$postDate, 'videoURL'=>$ytUrl); // prr($message); prr($options); die();
    //## Actual Post
    $ntToPost = new nxs_class_SNAP_TR(); $ret = $ntToPost->doPostToNT($options, $message);     
    //## Process Results
    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
      if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
    } else {  // ## All Good - log it.
      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
        else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'postURL'=>$ret['postURL'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
    } //prr($ret);
    //## Return Result
    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true); 
  }
}

?>