<?php
	if (isset($_REQUEST['recipient'])){
		$recipient = $_REQUEST['recipient'];
		$msg = $_REQUEST['msg'];
		//echo $recipient;
		$msg=urlencode($msg);
		//"http://www.mytxtbox.com/smsghapi.ashx/getbalance?api_id=123456&user=kofex&password=kofex";
		if ($recipient == 'all'){
			require_once 'vetsplaceAPI.php';
			$vets_Instance = new vetsplaceAPI();
			$vets_Instance->sendMsgToAll($msg,$url);
		}else{
			$formatRecipient = substr($recipient,1,strlen($recipient));
			$formatRecipient = '233'.$formatRecipient;
		/*$url = "http://site.mytxtbox.com/sms_api?username=kofex&password=blender3D&msg=this+is+a+test&to=233262760003"; 

			//echo $url;
			$ret = file($url);
			var_dump($ret);
			$send = split(":",$ret[0]);
			if ($send[0] == "ID"){
				echo "success";
			}else{
				echo "fail";
			}*/
		}
		}else{
			//echo "fail";
		}
		$url = "http://site.mytxtbox.com/sms_api?username=kofex&password=blender3D&msg=this+is+a+test&to=233262760003"; 
		
		//$ret = file($url);
		//var_dump($ret);
		
		if ($send[0] == "ID"){
				echo "success";
		}else{
			echo "fail";
		}
		
		//$url = "http://www.mytxtbox.com/smsghapi.ashx/sendmsg?api_id=123456&user=kofex&password=blender3D&to=233262760003&text=ThisIsATestMsgVetsPlace&from=VetsPlace";
		//$url = "http://www.mytxtbox.com/smsghapi.ashx/getbalance?api_id=123456&user=kofex&password=blender3D";
?>