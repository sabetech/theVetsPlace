$(document).ready(function(){
		
			$(window).resize(function(){
				$('.fitCenter').css({
					position:'absolute',
					left: ($(window).width() - $('.fitCenter').outerWidth())/2
					//top: ($(window).height() - $('.fitCenter').outerHeight())/2
				});
			});
			// To initially run the function:
			$(window).resize();	
		});
		/*
		document.captureEvents(Events.KEYPRESS);
		document.captureEvents(Events.KEYUP);
		
		document.onkeypress = enterPressed();
		function enterPressed(e){
			if (e.keycode == 13){
				alert("somphin");
				document.getElementById("enter").click();
			}
		}
		*/
		/*
		document.getElementById('password').onkeypress=function(e){
			if(e.keyCode==13){
				document.getElementById('enter').click();
			}
		}
		*/
		function ajaxFunction(username,pwd){
			var xmlHttp;
			var loginStatus = document.getElementById("loginStatus");
			loginStatus.innerText = "";
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
				var loading = document.getElementById("loading");;
				if (xmlHttp.readyState==1){
					loading.innerHTML = "<img src = \"images/preloader-w8-cycle-black.gif\" width=\"25\" height=\"25\"/>";
				}
				else if(xmlHttp.readyState==4){
					loading.innerHTML = "";
					var loginStatus = document.getElementById("loginStatus");
					loginStatus.innerText = xmlHttp.responseText;
					loginStatus.className = "fg-color-green";
					
					if (loginStatus.innerText == "login successful"){
						window.location.replace("home3.php");
					}
				}
			}
			url = "vets_Oauth.php?username="+username+"&"+"pwd="+pwd;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
			
			
		}