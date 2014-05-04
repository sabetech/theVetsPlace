<div class="span8">
<h2>Messaging</h2>
	<div class="hero-unit span8">
		<div class="span7 bg-color-purple fg-color-white">
			<h3 class="fg-color-white padding5">Description<img src="images/simple.png" class="place-right" style="margin:10px"/></h3>
			<p class="padding20">	This enables you to send TXT messages to your valued clients concerning updates about their animals.</p>
		</div>
		
		<div id="smsClient" class="tile  bg-color-pinkDark" style="margin-top:1em;">
			<div class="tile-content">
				<img src="images/mail128.png" style="width:60px;height:60px;margin-left:20px;margin-top:2em;"/>
			</div>
			<div class="brand"><span class="name">SMS Client</span></div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#smsClient").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divsms2Client").fadeIn(500);
				currentDiv = "#divsms2Client";
			});
		});
	});
</script>