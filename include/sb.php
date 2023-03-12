			<div class="indicator <?php echo CommonFunc::getIndicatorModule($module); ?>"></div>
			<div id="module-nav">
				<ul>
				  <li class="li-all"><a <?php if ($module == 'all') echo 'class="active"';?> href="index.php">All</a></li>
				  <li class="li-mark"><a <?php if ($module == 'mark') echo 'class="active"';?> href="index.php?module=mark">Mark</a></li>
				  <li class="li-auto"><a <?php if ($module == 'auto') echo 'class="active"';?> href="index.php?module=auto">Auto</a></li>
				</ul>
			</div>
			<?php 
				if ($module == 'all') {
					echo '<div id="paging">';
						require_once("include/paging.php");
					echo '</div>';
				}
			?>

			<div id="search">
				<form action="search.php" method="get" target="_blank">
					<input type="text" name="pair-search" class="search-text" placeholder="Search pair">
			    	<button type="submit" class="cbutton search-button">
			        	Search
			    	</button>
		    	</form>
			</div>
			
			<div id="calculator">
				<div class="cal-unit-price" id="cal-fst">
					<label>1st price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-sec">
					<label>2nd price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-rate">
					<div id="cal-rate-line"></div>
					<label>Rate price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-man">
					<div class="cbutton" id="cal-man-rev">Revert</div>
					<div class="cbutton" id="cal-man-cal">Cal</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div id="other">
				<div class="cbutton" id="reset-mark" onclick="onResetMark()">Reset mark</div>
			</div>