<?	session_start();
	if (!(isset($_SESSION['USER'])))
		header("location:login.php?error=You have to login first!");
	else if (!(isset($_GET['access_level']))){
		header("location:login.php?error=No need to edit the URL!");
	}
?>
<?php include("view/header.php")?>
<title>Home &middot; The Vets Place Management System</title>
	<script type="text/javascript" src="js/indexScript.js"></script>
	</head>
	
	<body class = "metrouicss" style="background-color:#ccccff" ><!--background="images/background.jpg"-->
			
			<div class="grid" >
				<div class="row">
					<div class="span6">
						<h2 class="fg-color-green"><img src="images/logo.png" width = "30" height="33">The Vets Place</h2>
						<div id='vets' class="fg-color-green" ><span style="padding:16px"></span>Management System</div>
					</div>
					<div class="span 6 place-right">
						<button data-role = "dropdown" class="bg-color-blue fg-color-white">
							<span id ="access_level" class="label">
								<?echo $_GET['access_level'];?>
							</span>
							
							<span id="dispUsername">
								<?echo $_SESSION['USER'];?>
							</span>
							
							<span class="icon">
							<i class="icon-user"></i>
							</span>
				
							<ul align="left" class="dropdown-menu" style="display:none;">
								<!--li><a><i class="icon-user"></i>Modify Profile</a></li>
								<li><a><i class="icon-cog"></i>Settings</a></li>
								<li class="divider"></li-->
								<li><a href="logout.php"><i class="icon-reply-2"></i>Logout</a></li>
							</ul>
						</button>
					</div>
				</div>
			</div>
		
		
		
		
		<div class="page secondary with-sidebar">
			<div class="page-header">
				<div class="page-header-content">
					<h1 id="globalHeader" class="fg-color-white">Overview<small>.</small></h1>
				</div>
			</div>
			
			<div class="grid">
				<div class="row">
					<div class="span4"> <!--Sidebar-->
						<div class="page-sidebar">
							<ul style="overflow: visible">
								<li id="li_Overview"><a id="overview">
									<i class="icon-info-2"></i>
										Overview
									</a>
								</li>
								<li id="li_ShowAll">
									<a id="showAll">
										<i class="icon-list"></i>
										Show All
									</a>
									<!--ul class="sub-menu light" >
										<li><a id="showAllClients">Clients</a></li>
										<li><a id="showAllAnimals">Animals</a></li>
									</ul-->
								</li>
								<li id="li_AddNew" >
									<a id="addNew">
										<i class="icon-plus-2"></i>
										Add-New
									</a>
								
									<!--ul class="sub-menu light" >
										<li><a id ="addNewClient">Clients</a></li>
									</ul-->
								</li>
								
								<li id="li_Inventory">
									<a id="inventory">
										<i class="icon-cabinet"></i>
										Inventory
									</a>
								
									<!--ul class="sub-menu light" >
										<li><a id="lstShowAllItems">Show All Items</a></li>
										<li><a id="lstShowItems">Show stocked information</a></li>
										<li><a id="lstShowNewItems">Show New Items</a></li>
										<li><a id="lstAddNewItem">Add New Item</a></li>
										<li><a id="lstTakeStock">Take Stock</a></li>
									</ul-->
								</li>
								
								<!--li id="li_Pharmacy" class="dropdown" data-role="dropdown">
									<a id="pharmacy">
										<i class="icon-lab"></i>
										Pharmacy(WIP)
									</a>
								
									<!--ul class="sub-menu light sidebar-dropdown-menu keep-opened open" >
										<!--li><a id="lstShowDrugs">Show Drugs</a></li>
										<li><a id="lstAddNewDrug">Add Drugs</a></li>
									</ul>
								</li-->
								
								<li id="li_Messaging" class="dropdown" data-role="dropdown">
									<a id="messaging">
										<i class="icon-comments-2"></i>
										Messaging
									</a>
									<!--ul class="sub-menu light sidebar-dropdown-menu keep-opened open" >
										<li><a id="lstSMSClient">SMS Client(s)</a></li>
										<li><a id="lstScheduleSMS">Schedule SMS to Client(s)</a></li>
									</ul-->
								</li>
								
								<li id="li_ManageDB" class="dropdown" data-role="dropdown">
									<a id="manageDB">
										<i class="icon-wrench"></i>
										Manage Database
									</a>
								<!--Switch owner ship
									Deleting client
									request for the excel file-->
									<!--ul class="sub-menu light sidebar-dropdown-menu keep-opened open" >
										<li><a href="">Clients</a></li>
									</ul-->
								</li>
							</ul>
						</div>
					</div>
						<!--
						<div>Calender<i class="icon-calendar"></i></div>
							<div class="calendar" style="width:255px" data-param-lang="en" data-role="calendar"></div>-->
					<div class="page-region">
						<div class="page-region-content">
							<div id="divOverview" style="display:none"> <!--HomePage(Overview)-->
								<?php include ("view/overview.php")?>
							</div>
							
							<div id="divShowAll" style="display:none">
								<?php include ("view/showAll.php")?>
							</div>
							
							<div id="divShowAllClients" style="display:none">
								<?php include ("view/showAllClients.php")?>
							</div>
							
							<div id="divShowAllPets" style="display:none">
								<?php include ("view/showAllPets.php")?>
							</div>
							
							<div id="divAddNew" style="display:none">
								<?php include ("view/addNew.php")?>
							</div>
							<div id="divAddnewClient" style="display:none">
								<?php include ("view/addNewClient.php")?>
							</div>
							
							<div id ="divAddClientPet" style="display:none">
								<?php include ("view/addClientPet.php")?>
							</div>
							
							<div id="divInventory" style="display:none">
								<?php include("view/inventory.php")
								
								//in the inventory homepage, display a summary of current available things and their quantities ?>
							</div>
							
							<div id="divShowAllItems" style="display:none">
								<?php include("view/showAllItems.php")?>
							</div>						
							
							<div id="divShowItems" style="display:none">
								<?php include ("view/showInventory.php")?>
							</div>
							
							<div id="divShowNewItems" style="display:none">
								<?php include ("view/showNewItems.php")?>
							</div>
							
							<div id="divAddNewItem" style="display:none">
								<?php include ("view/addNewItem.php")?>
							</div>
							
							<div id="divTakeStock" style="display:none">
								<?php include("view/takeStock.php")?>
								
							</div>
							
							<div id="divPharmacy" style="display:none">
								<?php include("view/pharmacy.php")?>
							</div>
							
							<div id="divMessaging" style="display:none">
								<?php include ("view/showMessaging.php")?>
							</div>
							
							<div id="divsms2Client" style="display:none">
								<?php include("view/sms2client.php")?>
							</div>
							
							<div id="divManageDBAuth" style="display:none;">
								<?php include("view/manageDatabaseAuth.php")?>
							</div>
							
							<div id="divManageDB" style="display:none;">
								<?php include("view/manageDatabase.php")?>
							</div>
							
					</div>
				</div>	
			</div>
		</div>	
		
		
			<div class="message-dialog" id ="errMsgDiv" style="z-index:3;display:none;">
					<div style='text-align:center;'> <!--I want to center the error message!!-->
						<h2 id="confirmation" class="fg-color-white"></h2>
						<p id="msg" ></p>
						<button id="closemsg" class="place-right">OK</button>
					</div>
			</div>
			
			<div id="errBackGrnd" class="bg-color-darken" style="width:100%;height:100%;top:0px;opacity:0.5;z-index:2;display:none;position:fixed;">
			</div>
			
			<!--div id="newInfo" class="bg-color-blueLight fg-color-darken" style="position:fixed;bottom:100%;z-index:1000;margin-top: 20px; padding: 0.7em;display:block;">
				<p id='infoMsg'></p>
			</div-->
	</body>
</html>