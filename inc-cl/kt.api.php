<?php    
//## NextScripts Kippt Connection Class 
$nxs_snapAPINts[] = array('code'=>'KT', 'lcode'=>'kt', 'name'=>'Kippt');

if (!class_exists("nxs_class_SNAP_KT")) { class nxs_class_SNAP_KT {
    
    var $ntCode = 'KT';
    var $ntLCode = 'kt';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getKTHeaders($ref, $uname, $pass, $post=false){ $hdrsArr = array(); 
      $hdrsArr['X-Requested-With']='XMLHttpRequest'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
      $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.22 Safari/537.11';
      if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
      $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; 
      //$hdrsArr['Authorization']= 'Basic '.base64_encode($uname.':'.$pass);
      $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); 
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['uName']) || trim($options['uPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }      
      $email = $options['uName'];  $pass = substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'];  
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['msgFormat'], $message);       
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['msgTFrmt'], $message);
      //######  ===============
      
      $hdrsArr = $this->nxs_getKTHeaders('https://kippt.com/api/clips/', $email, $pass, true); $hdrsArr['X-Kippt-Username'] = $email; $hdrsArr['X-Kippt-API-Token'] = $pass;            
      $cnt = wp_remote_get( 'https://kippt.com/api/lists/', array( 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr)); $lists = json_decode($cnt['body'], true); 
      foreach ($lists['objects'] as $list) if ($list['slug'] == $options['list']) $listID = $list['resource_uri']; if (empty($listID))  $listID = '';      
      $flds = array();  $flds['url']=$message['url']; $flds['notes']=$msg; $flds['title']=$msgT; $flds['list']=$listID; $flds = json_encode($flds); // prr($flds);      
      $cnt = wp_remote_post( 'https://kippt.com/api/clips/', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds));     
      if (is_wp_error($cnt) || empty($cnt['body']) || $cnt['response']['code']!='201') return "ERROR: ".print_r($cnt, true);
      
      //prr($cnt['body']);
         
      //## Return      
      if (stripos($cnt['body'],'"resource_uri": "')!==false) { 
         $pid = CutFromTo($cnt['body'], '"resource_uri": "', '"'); $purl = 'https://kippt.com'.CutFromTo($cnt['body'], '"app_url": "', '"');
         return array('postID'=>$pid, 'isPosted'=>1, 'postURL'=>$purl, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= print_r($cnt, true); 
        return $badOut;
      }
    }
}}
?>