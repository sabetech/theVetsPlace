<?php include("header.php")?>
	<title>Home &middot; The Vets Place Management System</title>
	</head>
	
	<body class = "metrouicss" background="images/background.jpg">
		
			<div class="grid" >
				<div class="row">
					<div class="span6">
						<h1 class="fg-color-green"><img src="images/logo.png" width = "50" height="53">The Vets Place</h1>
						<div class="fg-color-green" ><span style="padding:28px"></span>Client Management System</div>
					</div>
					<div class="span 6 place-right">
						<button data-role = "dropdown" class="bg-color-blue fg-color-white">
							<span class="label">
								Admin <!--Will have to get this value from DAtabase L8tr-->
							</span>
							
							<span>
								Kwesi Alavayo
							</span>
							
							<span class="icon">
							<i class="icon-user"></i>
							</span>
				
							<ul align="left" class="dropdown-menu" style="display:none;">
								<li><a><i class="icon-user"></i>Modify Profile</a></li>
								<li><a><i class="icon-cog"></i>Settings</a></li>
								<li class="divider"></li>
								<li><a><i class="icon-reply-2"></i>Logout</a></li>
							</ul>
						</button>
					</div>
				</div>
			</div>
			
		<div class="page secondary with-sidebar">
			<div class="page-header">
				<div class="page-header-content">
					<h1 class="fg-color-blue">Overview<small>.</small></h1>
				</div>
			</div>
			
			<div class="page-sidebar">
				<ul style="overflow: visible">
					<li><a>
						<i class="icon-info-2"></i>
							Overview
						</a>
						</li>
					<li class="dropdown" data-role="dropdown">
						<a>
							<i class="icon-list"></i>
							Show All
						</a>
						<ul class="sub-menu light sidebar-dropdown-menu open" style="display:none">
							<li><a href="">Clients</a></li>
							<li><a href="">Animals</a></li>
						</ul>
					</li>
					<li class="dropdown" data-role="dropdown">
						<a>
							<i class="icon-plus-2"></i>
							Add-New
						</a>
					
						<ul class="sub-menu light sidebar-dropdown-menu open" style="display:none">
							<li><a href="">Clients</a></li>
						</ul>
					</li>
					
					<li class="dropdown" data-role="dropdown">
						<a>
							<i class="icon-cabinet"></i>
							Inventory
						</a>
					
						<ul class="sub-menu light sidebar-dropdown-menu open" style="display:none">
							<li><a href="">Show Available Drugs</a></li>
							<li><a href="">Show fixed Assets</a></li>
							<li><a href="">Add New Drugs</a></li>
							<li><a href="">Add New Asset</a></li>
						</ul>
					</li>
					
					<li class="dropdown" data-role="dropdown">
						<a>
							<i class="icon-comments-2"></i>
							Messaging
						</a>
						<ul class="sub-menu light sidebar-dropdown-menu open" style="display:none">
							<li><a href="">Clients</a></li>
							<li><a href="">Animals</a></li>
						</ul>
					</li>
					
					<li class="dropdown" data-role="dropdown">
						<a>
							<i class="icon-wrench"></i>
							Manage Database
						</a>
					<!--Switch owner ship
						Deleting client
						request for the excel file-->
						<ul class="sub-menu light sidebar-dropdown-menu open" style="display:none">
							<li><a href="">Clients</a></li>
						</ul>
					</li>
					<li class="divider"></li>
					<li class="divider"></li>
				
					<li>
					<i class="icon-calendar" data-param-lang="en"></i>Calender</li>
				 <div class="calendar" data-role="calendar"></div>
				</ul>
			</div>
		
		
		
	
			<div class="page-region">
				<div class="page-region-content">
					<div class="grid">
						<div class="row">
							<div class="span4 offset1">
								<h2>Recent Activities</h2>
								<ul class="listview">
									<li class = "bg-color-blueLight">
										<div class="icon">
											<img src="images/chrome.png">
										</div>
										<div class="data">
											<h4>24/03/12</h4>
											<p>
												You deleted two dogs belonging to
												Yaw Perbi because they died.
											</p>
										</div>
									</li>
									
									<li class = "bg-color-blueLight">
										<div class="icon">
											<img src="images/chrome.png">
										</div>
										<div class="data">
											<h4>24/03/12</h4>
											<p>
												You deleted two Horses belonging to
												Ama Mensah because they transfered
											</p>
										</div>
									</li>
								</ul>
							</div>
							<div class="span4">
								<h2>Summary</h2>
								<ul class="listview fluid">
									
									<li class="bg-color-white">
										<div class="icon">
											<img src="images/5.jpg">
										</div>
										<div class="data">
											<h4>Information on Dogs</h4>
											<p>
												Population: 249 <br />
												Male: 51 <br />
												Female: 148 <br />
												Different Breeds: 26 <br />
												Castrated: 5
											</p>
										</div>
									</li>
									
									<li class="bg-color-white">
										<div class="icon">
											<img src="images/5.jpg">
										</div>
										<div class="data">
											<h4>Information on Dogs</h4>
											<p>
												Population: 249 <br />
												Male: 51 <br />
												Female: 148 <br />
												Different Breeds: 26 <br />
												Castrated: 5
											</p>
										</div>
									</li>
									
									<li class="bg-color-white">
										<div class="icon">
											<img src="images/5.jpg">
										</div>
										<div class="data">
											<h4>Information on Dogs</h4>
											<p>
												Population: 249 <br />
												Male: 51 <br />
												Female: 148 <br />
												Different Breeds: 26 <br />
												Castrated: 5
											</p>
										</div>
									</li>
									
									<li class="bg-color-white">
										<div class="icon">
											<img src="images/5.jpg">
										</div>
										<div class="data">
											<h4>Information on Dogs</h4>
											<p>
												Population: 249 <br />
												Male: 51 <br />
												Female: 148 <br />
												Different Breeds: 26 <br />
												Castrated: 5
											</p>
										</div>
									</li>
									
								</ul>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
		
	</body>
</html>