/*function ajaxFunction(url,ajxcommand){
			var xmlHttp;
			//alert("ajax function args "+url+" "+ajxcommand);
			 
			try{
			// Firefox, Opera 8.0+, Safari
				xmlHttp=new XMLHttpRequest();
				
			}catch (e){
				// Internet Explorer
				try{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}catch (e){
					try{
						xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e){
						alert("Your browser does not support AJAX!");
						return false;
					}
				}
			}
			
			xmlHttp.onreadystatechange=function(){
				if (xmlHttp.readyState==1){
					switch(ajxcommand){
						case 3:
						$("#newCli_Info").html("<img src = \"images/preloader-w8-cycle-black.gif\" width=\"25\" height=\"25\"/>");
						break;
					}
				}
				else if(xmlHttp.readyState==4){
					 var reply = xmlHttp.responseText;
					 var replyJSON = JSON.parse(reply);
					 var bufferedList_str;
					 var bufferClients="";
					 var bufferClientInfo;
					 var bufferPetInfo="";
					 var bufferStockItems="";
					 
					switch(ajxcommand){
						
						case 3://add a client to the database
							bufferClientInfo = "<div class=\"tile double bg-color-green\">"+"<div class=\"tile-content\">"+
							"<img src=\"images/clientImgs/defaultPic.jpg\" class=\"place-left\" style=\"width:60px;height:60px;\">"
							+"<h3 style=\"margin-botton:5px;\">"+replyJSON.cliName+"</h3>"+"<p>Address: "+replyJSON.address+"</p><p>Email: "+replyJSON.email+"</p>"+
							"</div>"+
							"<div class=\"brand\">"+
							"<span class=\"name\">The Vets Place Folder ID: "+replyJSON.folderNum+"</span></div></div>";
							cur_Client_Id = replyJSON.cliId;
							$("#newCli_Info").html(bufferClientInfo);
						break;	
					 }
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
	}*/