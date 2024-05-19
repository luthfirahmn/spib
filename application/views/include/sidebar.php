<?php
$menu = $this->session->userdata('menu');
$submenu = $this->session->userdata('submenu');

if(isset($controller)){
	$map=$controller;
}else{
	$map='';
}

?>
<nav class="pc-sidebar <?= ($map=='Maps')?'pc-sidebar-hide':''?>">
	<div class="navbar-wrapper">
		<div class="m-header">
			<a href="index-2.html" class="b-brand">
				<img src="https://berrydashboard.io/bootstrap/default/assets/images/logo-dark.svg" alt="" class="logo logo-lg" />
			</a>
		</div>
		<div class="navbar-content">
			<ul class="pc-navbar">
				<?php 
					foreach($menu as $menu){
						if($menu->child == '1'){
				?>
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon">
										<i class="<?= $menu->icon ?>"></i>
									</span>
									<span class="pc-mtext"><?= $menu->menu_name ?></span>
									<span class="pc-arrow">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
											<polyline points="9 18 15 12 9 6"></polyline>
										</svg>
									</span>
								</a>
								<ul class="pc-submenu" style="display: none;">
									<?php 
										foreach($submenu as $sub){
											if($sub->parent==$menu->id){
									?>
									<li class="pc-item">
										<a class="pc-link" href="<?= base_url().$sub->controller?>"><?= $sub->menu_name ?></a>
									</li>
									<?php 
										}
									} 
									?>
								</ul>
							</li>
				<?php
						}else{
				?>
							<li class="pc-item">
							<a href="<?= base_url().$menu->controller?>" class="pc-link">
								<span class="pc-micon">
									<i class="<?= $menu->icon ?>"></i>
								</span>
								<span class="pc-mtext"><?= $menu->menu_name ?></span>
							</a>
						</li>
				<?php	
						}
					}
				?>
			</ul>
		</div>
	</div>
</nav>