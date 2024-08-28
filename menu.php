	<div id='imfBanner'>
		<div id='imfBannerContent'>
			<h1>
			Assurance - ENIQ Events EC Tracker
			</h1>
		</div>
		<div id='imfBannerLogin'>
			<ul>
				<li>
					<!--<a id='helpLink' href='#' onclick='javascript:helpLink(); return false;'>Help</a>-->
					<a id='helplink' href='http://atrclin2.athtem.eei.ericsson.se/wiki/index.php/EC_tracker_Instruction_Videos' target='_blank'>Help</a>
					
				</li>
				<li>
				<?php
				if(isset($_SESSION['user_signum'])) {
					echo "<a id='loginLink' href='logout.php' onclick='return logout();'>Logout</a> <span id='user'>" . (string)$_SESSION['user_signum'] . "</span>";
				} else {
					echo "<a id='loginLink' href='login.php'>Log In</a>";
				}
				?>
				</li>
			</ul>
		</div>
	</div>
	
	<div id='imfMenu'>
		<div id='imfMenuContent'>		
			<ul id='nav-one' class='nav'>
				<li><a href='index.php'>EC TRACKER</a></li>
				
				<li><a href='ecSubmit.php'>EC SUBMIT</a></li>
					
								
				<?php
				if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='admin') {
				echo "	
				<li><a href='#'>ADMIN</a>
					<ul class='itemGroup'>
						<li><a href='user.php' class='daddy'>User Management</a></li>
					</ul>
				</li>";
				}
				?>
				
			</ul>
		</div>
	</div>
	
	<script type='text/javascript'>
		$(document).ready(function(){
			$('#nav-one li').hover(
				function(){ $('ul', this).fadeIn('fast'); }, 
				function() { } 
			);
			if (document.all) {
				$('#nav-one li').hoverClass ('sfHover');
			}
		});
	  
		$.fn.hoverClass = function(c) {
			return this.each(function(){
				$(this).hover( 
					function() { $(this).addClass(c);  },
					function() { $(this).removeClass(c); }
				);
			});
		};
		
		function logout() {
			var logout = confirm ("Do you really want to logout ?")
			if (logout) {
				return true;
			} else {
				return false;
			}
		}
	</script>
	<script type='text/javascript' src='js/help.js'></script>