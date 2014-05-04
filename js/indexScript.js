var currentDiv; //remember to highlight the current selected option
var currentSubDiv;
var currentLi;//the current list item selected
var cur_Client_Id;//the current Client selected at any point in time

var stickerClass = "sticker sticker-color-pink";

	$(document).ready(function(){	
		$("#divOverview").fadeIn(500);
		currentDiv = "#divOverview";
		$("#li_Overview").addClass(stickerClass);
		currentLi = "#li_Overview";
		
		$("#overview").click(function(){
			$("#globalHeader").html("Overview<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_Overview").addClass(stickerClass);
			currentLi = "#li_Overview";
			
			$(currentDiv).fadeOut(200,function(){
				$("#divOverview").fadeIn(500);
				currentDiv = "#divOverview";
			});
		});
		

		$("#showAll").click(function(){
			$("#globalHeader").html("Show All<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_ShowAll").addClass(stickerClass);
			currentLi = "#li_ShowAll";
			
			$(currentDiv).fadeOut(200,function(){
				$("#divShowAll").fadeIn(500);
				currentDiv = "#divShowAll";
			});
		});
		//you can do sticker change here l8tr;
	
		$("#addNew").click(function(){
			$("#globalHeader").html("Add New<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_AddNew").addClass(stickerClass);
			currentLi = "#li_AddNew";
			
			$(currentDiv).fadeOut(200,function(){
				$("#divAddNew").fadeIn(500);
				currentDiv = "#divAddNew";
			});
		});
		
		$("#addNewClient").click(function(){ //chosen from the list
			$(currentDiv).fadeOut(200,function(){
				$("#divAddnewClient").fadeIn(500);
				currentDiv = "#divAddnewClient";
			});
		});
		
		$("#btnAddNew").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divAddnewClient").fadeIn(500);
				currentDiv = "#divAddnewClient";
			});
		});
		
		
		
		
		
		//****** INVENTORY **************
		$("#inventory").click(function(){
			$("#globalHeader").html("Inventory<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_Inventory").addClass(stickerClass);
			currentLi = "#li_Inventory";
			
			$(currentDiv).fadeOut(200,function(){
				$("#divInventory").fadeIn(500);
				currentDiv = "#divInventory";
			});
		});
		
		/*$("#pharmacy").click(function(){
			$("#globalHeader").html("Pharmacy<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_Pharmacy").addClass(stickerClass);
			currentLi = "#li_Pharmacy";
			
			$(currentDiv).hide(500,function(){
				$("#divPharmacy").show(500);
				currentDiv = "#divPharmacy";
			});
		})*/
		
		
		
		//***** MESSAGING ***************
		$("#messaging").click(function(){
			$("#globalHeader").html("Messaging<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_Messaging").addClass(stickerClass);
			currentLi = "#li_Messaging";
			
			$(currentDiv).fadeOut(200,function(){
				$("#divMessaging").fadeIn(500);
				currentDiv = "#divMessaging";
			});
		});
		
		$("#lstSMSClient").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divsms2Client").fadeIn(500);
				currentDiv = "#divsms2Client";
			});
		});
		
		//***** END OF MESSAGING ***************
		
		
		$("#manageDB").click(function(){
			$("#globalHeader").html("Manage Database<small>.</small>");
			$(currentLi).removeClass(stickerClass);
			$("#li_ManageDB").addClass(stickerClass);
			currentLi = "#li_ManageDB";
		});
		
		$("#vets").click(function(){
			//$("#errMsgDiv").css('top','0px');
			//$("#errMsgDiv").css('left','500px');
			//$("#errMsgDiv")
			
			displayError("Yaay: Vets Place Client and Pet Management System version 1 beta.","Nice ");
			//console.log("clicked");
			
			//showNewInfo("this is the message");
			
			
		});
		
		$("#closemsg").click(function(){
			$("#errMsgDiv").removeClass("bg-color-green bg-color-red fg-color-white");
			$("#errBackGrnd").fadeOut();
			$("#errMsgDiv").fadeOut();
		});
		
		
			
		
		
	});
	
	function showNewInfo(message){
		$("#newInfo").fadeIn(function(){
			$("#infoMsg").html(message);
			//setTimeout("console.log(\"WORKINGGG\")",3000);
		});
	}
	
	function displayNotice(msg,msgType){
		$("#errMsgDiv").css('box-shadow','10px 10px 5px #888888');
		document.getElementById('errMsgDiv').style.position = 'fixed';
		
		$("#errMsgDiv").addClass("bg-color-green fg-color-white");
		
		$("#confirmation").html(msgType);
		$("#msg").html(msg);
		$("#errBackGrnd").fadeIn(100);
		$("#errMsgDiv").fadeIn(200);
	}
	
	function displayError(msg,msgType){
		$("#errMsgDiv").css('box-shadow','10px 10px 5px #888888');
		document.getElementById('errMsgDiv').style.position = 'fixed';
		
		$("#errMsgDiv").addClass("bg-color-red fg-color-white");
		
		$("#confirmation").html(msgType);
		$("#msg").html(msg);
		$("#errBackGrnd").fadeIn(100);
		$("#errMsgDiv").fadeIn(200);
	}
	
	function showLoggedInUser(){
		//case 1 .. do the other functions for order!!!
	}
	
	
	
			