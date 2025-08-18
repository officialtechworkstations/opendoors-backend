<div class="page-header">
        <div class="header-wrapper row m-0">
          <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group w-100">
              <div class="Typeahead Typeahead--twitterUsers">
                <div class="u-posRelative">
                  <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Koho .." name="q" title="" autofocus>
                  <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
                </div>
                <div class="Typeahead-menu"></div>
              </div>
            </div>
          </form>
          <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="assets/images/logo/logo.png" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-dark.png" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
          </div>
          <div class="left-header col horizontal-wrapper ps-0">
           
          </div>
          <div class="nav-right col-6 pull-right right-header p-0">
            <ul class="nav-menus">   
              
              
			  
			  <li>
                
				
				<?php 
				if($set['show_dark'] == 0)
				{
				?>
				<div class="mode drop" data-id="1" data-status="1"  data-type="update_status" coll-type="dark_mode"><i data-feather="moon" ></i></div>
				
                 
				<?php } else { ?>
				
                 <div class="mode drop" data-id="1" data-status="0"  data-type="update_status" coll-type="dark_mode"><i data-feather="sun" ></i></div>
				<?php } ?>
              </li>
              
              
             
              <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
              <li class="profile-nav onhover-dropdown p-0 me-0">
                <div class="d-flex profile-media"><img class="b-r-50" src="assets/images/dashboard/profile.png" alt="">
                  <div class="flex-grow-1"><span><?php echo $set['webname'];?></span>
                    <p class="mb-0 font-roboto">Admin <i class="middle fa fa-angle-down"></i></p>
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="profile.php"><i data-feather="user"></i><span>Account </span></a></li>
                  <li><a href="setting.php"><i data-feather="file-text"></i><span>Setting</span></a></li>
                  <li><a href="logout.php"><i data-feather="log-in"> </i><span>Log out</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
          
        </div>
      </div>