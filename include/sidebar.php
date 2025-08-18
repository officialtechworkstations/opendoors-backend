<?php 
if(isset($_SESSION['restatename']))
{
	
}
else 
{
	?>
	<script>
	window.location.href="/";
	</script>
	<?php 
}
?>
<?php 
	if($_SESSION["stype"] == 'Admin')
	{
		?>
<div class="sidebar-wrapper">
          <div>
            <div class="logo-wrapper"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-dark.png" alt=""></a>
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-left"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-icon-dark.png" alt=""></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-icon-dark.png" alt=""></a>
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  
                  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="dashboard.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g> 
                            <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Dashboard</span></a></li>
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5103 10.7105C14.5103 9.3292 13.391 8.20996 12.0097 8.20996C10.6295 8.20996 9.51025 9.3292 9.51025 10.7105C9.51025 12.0907 10.6295 13.21 12.0097 13.21C13.391 13.21 14.5103 12.0907 14.5103 10.7105Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9995 21C9.10148 21 4.5 15.9587 4.5 10.5986C4.5 6.40246 7.8571 3 11.9995 3C16.1419 3 19.5 6.40246 19.5 10.5986C19.5 15.9587 14.8985 21 11.9995 21Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Country</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_country.php">Add Country</a></li>
                      <li><a href="list_country.php">List Country</a></li>
					  <li><a href="d_country.php">Set Default Country</a></li>
                    </ul>
                  </li>
				  
                  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g> 
                            <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Category</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_category.php">Add Category</a></li>
                      <li><a href="list_category.php">List Category</a></li>
                    </ul>
                  </li>
				  
				  
				  
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 5.063 5.06324 2.75 12.0002 2.75C18.9372 2.75 21.2502 5.063 21.2502 12C21.2502 18.937 18.9372 21.25 12.0002 21.25C5.06324 21.25 2.75024 18.937 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.42993 14.5697L14.5699 9.42969" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.4955 14.5H14.5045" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.4955 9.5H9.5045" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Coupon</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_coupon.php">Add Coupon</a></li>
                      <li><a href="list_coupon.php">List Coupon</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M21.0802 8.58003V15.42C21.0802 16.54 20.4802 17.58 19.5102 18.15L13.5702 21.58C12.6002 22.14 11.4002 22.14 10.4202 21.58L4.48016 18.15C3.51016 17.59 2.91016 16.55 2.91016 15.42V8.58003C2.91016 7.46003 3.51016 6.41999 4.48016 5.84999L10.4202 2.42C11.3902 1.86 12.5902 1.86 13.5702 2.42L19.5102 5.84999C20.4802 6.41999 21.0802 7.45003 21.0802 8.58003Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M11.9999 11.0001C13.2867 11.0001 14.3299 9.95687 14.3299 8.67004C14.3299 7.38322 13.2867 6.34009 11.9999 6.34009C10.7131 6.34009 9.66992 7.38322 9.66992 8.67004C9.66992 9.95687 10.7131 11.0001 11.9999 11.0001Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 16.6601C16 14.8601 14.21 13.4001 12 13.4001C9.79 13.4001 8 14.8601 8 16.6601" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<span >Staff</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_staff.php">Add Staff</a></li>
                      <li><a href="list_staff.php">List Staff</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8 12.2002H15" stroke="#130F26" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 16.2002H12.38" stroke="#130F26" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 6H14C16 6 16 5 16 4C16 2 15 2 14 2H10C9 2 8 2 8 4C8 6 9 6 10 6Z" stroke="#130F26" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 4.02002C19.33 4.20002 21 5.43002 21 10V16C21 20 20 22 15 22H9C4 22 3 20 3 16V10C3 5.44002 4.67 4.20002 8 4.02002" stroke="#130F26" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<span >Reports</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="daily.php">Daily Reports</a></li>
                      <li><a href="book.php">Book Reports</a></li>
					  <li><a href="sales.php">Sales Reports</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="paymentlist.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M21.1712 14.6755H17.2845C15.8693 14.6755 14.7217 13.5279 14.7217 12.1117C14.7217 10.6964 15.8693 9.54883 17.2845 9.54883H21.1407" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.7219 12.0531H17.4248" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.6062 8.14367H11.6662" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.71411 12.2532C2.71411 5.8484 5.03887 3.71411 12.0151 3.71411C18.9903 3.71411 21.3151 5.8484 21.3151 12.2532C21.3151 18.657 18.9903 20.7922 12.0151 20.7922C5.03887 20.7922 2.71411 18.657 2.71411 12.2532Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Payment Gateway</span></a></li>
					  
					  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="list_enquiry.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Enquiry List</span></a></li>
					  
					    <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="list_payout.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Payout List</span></a></li>
                  
                  
                  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 5.063 5.06324 2.75 12.0002 2.75C18.9372 2.75 21.2502 5.063 21.2502 12C21.2502 18.937 18.9372 21.25 12.0002 21.25C5.06324 21.25 2.75024 18.937 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.2045 13.8999H15.2135" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.2045 9.8999H12.2135" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.19557 13.8999H9.20457" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Properties                </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_properties.php">Add Properties</a></li>
                      <li><a href="list_properties.php">List Properties</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.07056 16.4588C6.07056 16.4588 6.88256 14.8218 8.06456 14.8218C9.24656 14.8218 9.85056 16.1968 11.1606 16.1968C12.4696 16.1968 13.9386 12.7488 15.4226 12.7488C16.9046 12.7488 17.9706 15.1398 17.9706 15.1398" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.1393 9.10487C10.1393 9.96487 9.44229 10.6629 8.58129 10.6629C7.72129 10.6629 7.02429 9.96487 7.02429 9.10487C7.02429 8.24487 7.72129 7.54688 8.58129 7.54688C9.44229 7.54788 10.1393 8.24487 10.1393 9.10487Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 18.937 5.06324 21.25 12.0002 21.25C18.9372 21.25 21.2502 18.937 21.2502 12C21.2502 5.063 18.9372 2.75 12.0002 2.75C5.06324 2.75 2.75024 5.063 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Extra Image                </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_extra.php">Add Extra Image</a></li>
                      <li><a href="list_extra.php">List Extra Image</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M11.0791 13.8496H7.4314" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.4774 12.1712H15.3752" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.2081 15.5833H17.1059" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8.51392 2.21606C8.5206 2.93015 9.1058 3.50295 9.81989 3.49626H10.828C11.9306 3.48767 12.8328 4.37169 12.8481 5.47432V6.48148" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.8121 13.8953C21.8121 8.33539 19.4255 6.48145 12.2646 6.48145C5.10271 6.48145 2.71606 8.33539 2.71606 13.8953C2.71606 19.4562 5.10271 21.3092 12.2646 21.3092C19.4255 21.3092 21.8121 19.4562 21.8121 13.8953Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Facility                </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_facility.php">Add Facility</a></li>
                      <li><a href="list_facility.php">List Facility</a></li>
                    </ul>
                  </li>
				  
				 
				  
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g>
                            <path d="M14.3053 15.45H8.90527" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.2604 11.4387H8.90442" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.1598 8.3L14.4898 2.9C13.7598 2.8 12.9398 2.75 12.0398 2.75C5.74978 2.75 3.64978 5.07 3.64978 12C3.64978 18.94 5.74978 21.25 12.0398 21.25C18.3398 21.25 20.4398 18.94 20.4398 12C20.4398 10.58 20.3498 9.35 20.1598 8.3Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M13.9342 2.83276V5.49376C13.9342 7.35176 15.4402 8.85676 17.2982 8.85676H20.2492" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Gallery Category               </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="ad_gal_cat.php">Add Gallery Category</a></li>
                      <li><a href="list_gal_cat.php">List Gallery Category</a></li>
                    </ul>
                  </li>
				  
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.07056 16.4588C6.07056 16.4588 6.88256 14.8218 8.06456 14.8218C9.24656 14.8218 9.85056 16.1968 11.1606 16.1968C12.4696 16.1968 13.9386 12.7488 15.4226 12.7488C16.9046 12.7488 17.9706 15.1398 17.9706 15.1398" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.1393 9.10487C10.1393 9.96487 9.44229 10.6629 8.58129 10.6629C7.72129 10.6629 7.02429 9.96487 7.02429 9.10487C7.02429 8.24487 7.72129 7.54688 8.58129 7.54688C9.44229 7.54788 10.1393 8.24487 10.1393 9.10487Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 18.937 5.06324 21.25 12.0002 21.25C18.9372 21.25 21.2502 18.937 21.2502 12C21.2502 5.063 18.9372 2.75 12.0002 2.75C5.06324 2.75 2.75024 5.063 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Gallery                </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_gal.php">Add Gallery</a></li>
                      <li><a href="list_gal.php">List Gallery</a></li>
                    </ul>
                  </li>
				  
				   <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Package               </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_package.php">Add Package</a></li>
                      <li><a href="list_package.php">List Package</a></li>
                    </ul>
                  </li>
				   
					  
                  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.74976 12.7755C2.74976 5.81947 5.06876 3.50146 12.0238 3.50146C18.9798 3.50146 21.2988 5.81947 21.2988 12.7755C21.2988 19.7315 18.9798 22.0495 12.0238 22.0495C5.06876 22.0495 2.74976 19.7315 2.74976 12.7755Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M3.02515 9.32397H21.0331" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.4284 13.261H16.4374" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0289 13.261H12.0379" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.62148 13.261H7.63048" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.4284 17.113H16.4374" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0289 17.113H12.0379" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.62148 17.113H7.63048" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">                   </path>
                          </g>
                        </g>
                      </svg><span>Booking </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="pending.php">Pending Booking</a></li>
                      <li><a href="approved.php">Approved Booking</a></li>
					   <li><a href="check_in.php">Check In Booking</a></li>
					  <li><a href="completed.php">Completed Booking</a></li>
					  <li><a href="cancelled.php">Cancelled Booking</a></li>
					  
                    </ul>
                  </li>
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.1601 8.3L14.4901 2.9C13.7601 2.8 12.9401 2.75 12.0401 2.75C5.75015 2.75 3.65015 5.07 3.65015 12C3.65015 18.94 5.75015 21.25 12.0401 21.25C18.3401 21.25 20.4401 18.94 20.4401 12C20.4401 10.58 20.3501 9.35 20.1601 8.3Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M13.9341 2.83276V5.49376C13.9341 7.35176 15.4401 8.85676 17.2981 8.85676H20.2491" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.3125 12.9807H9.41248" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.8633 15.4308V10.5308" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Page</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_page.php">Add Page</a></li>
                      <li><a href="list_page.php">List Page</a></li>
                    </ul>
                  </li>
          
           <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M8.44019 12L10.8142 14.373L15.5602 9.62695" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.74976 12C2.74976 18.937 5.06276 21.25 11.9998 21.25C18.9368 21.25 21.2498 18.937 21.2498 12C21.2498 5.063 18.9368 2.75 11.9998 2.75C5.06276 2.75 2.74976 5.063 2.74976 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >FAQ</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="add_faq.php">Add FAQ</a></li>
                      <li><a href="list_faq.php">List FAQ</a></li>
                    </ul>
                  </li>
				  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="userlist.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g> 
                      <g> 
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55851 21.4562C5.88651 21.4562 2.74951 20.9012 2.74951 18.6772C2.74951 16.4532 5.86651 14.4492 9.55851 14.4492C13.2305 14.4492 16.3665 16.4342 16.3665 18.6572C16.3665 20.8802 13.2505 21.4562 9.55851 21.4562Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55849 11.2776C11.9685 11.2776 13.9225 9.32356 13.9225 6.91356C13.9225 4.50356 11.9685 2.54956 9.55849 2.54956C7.14849 2.54956 5.19449 4.50356 5.19449 6.91356C5.18549 9.31556 7.12649 11.2696 9.52749 11.2776H9.55849Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M16.8013 10.0789C18.2043 9.70388 19.2383 8.42488 19.2383 6.90288C19.2393 5.31488 18.1123 3.98888 16.6143 3.68188" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4608 13.6536C19.4488 13.6536 21.1468 15.0016 21.1468 16.2046C21.1468 16.9136 20.5618 17.6416 19.6718 17.8506" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      </g>
                    </g>
                  </svg><span>User List</span></a></li>
                    <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="profile.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.92234 21.8083C6.10834 21.8083 2.85034 21.2313 2.85034 18.9213C2.85034 16.6113 6.08734 14.5103 9.92234 14.5103C13.7363 14.5103 16.9943 16.5913 16.9943 18.9003C16.9943 21.2093 13.7573 21.8083 9.92234 21.8083Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.92231 11.2159C12.4253 11.2159 14.4553 9.1859 14.4553 6.6829C14.4553 4.1789 12.4253 2.1499 9.92231 2.1499C7.41931 2.1499 5.38931 4.1789 5.38931 6.6829C5.38031 9.1769 7.39631 11.2069 9.89031 11.2159H9.92231Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Account</span></a></li>
                    <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="setting.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M11.1437 17.8829H4.67114" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.205 17.8839C15.205 19.9257 15.8859 20.6057 17.9267 20.6057C19.9676 20.6057 20.6485 19.9257 20.6485 17.8839C20.6485 15.8421 19.9676 15.1621 17.9267 15.1621C15.8859 15.1621 15.205 15.8421 15.205 17.8839Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.1765 7.39439H20.6481" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.1153 7.39293C10.1153 5.35204 9.43436 4.67114 7.39346 4.67114C5.35167 4.67114 4.67078 5.35204 4.67078 7.39293C4.67078 9.43472 5.35167 10.1147 7.39346 10.1147C9.43436 10.1147 10.1153 9.43472 10.1153 7.39293Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Setting</span></a></li>
                     <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="logout.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="logout" style="transform: rotate(90deg);">
                        <g> 
                          <g> 
                            <path d="M11.879 14.791V2.75" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.795 11.8643L11.879 14.7923L8.96301 11.8643" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.3702 7.25879C19.9492 7.58879 21.2502 8.92879 21.2502 14.2588C21.2502 21.3588 18.9392 21.3588 12.0002 21.3588C5.05924 21.3588 2.75024 21.3588 2.75024 14.2588C2.75024 8.92879 4.05024 7.58879 7.63024 7.25879" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Log out</span></a></li>
                </ul>
                
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
		
		<?php } else { ?>
		
		<div class="sidebar-wrapper">
          <div>
            <div class="logo-wrapper"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-dark.png" alt=""></a>
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-left"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-icon-dark.png" alt=""></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn"><a href="dashboard.php"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo-icon-dark.png" alt=""></a>
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  
                  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="dashboard.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g> 
                            <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Dashboard</span></a></li>
					  
					   <?php 
					 if($country_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5103 10.7105C14.5103 9.3292 13.391 8.20996 12.0097 8.20996C10.6295 8.20996 9.51025 9.3292 9.51025 10.7105C9.51025 12.0907 10.6295 13.21 12.0097 13.21C13.391 13.21 14.5103 12.0907 14.5103 10.7105Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9995 21C9.10148 21 4.5 15.9587 4.5 10.5986C4.5 6.40246 7.8571 3 11.9995 3C16.1419 3 19.5 6.40246 19.5 10.5986C19.5 15.9587 14.8985 21 11.9995 21Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Country</span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $country_per)) {
            ?>
                      <li><a href="add_country.php">Add Country</a></li>
					  <li><a href="d_country.php">Set Default Country</a></li>
					  <?php } 
        if (in_array('Read', $country_per)) {
            ?>
                      <li><a href="list_country.php">List Country</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				  
					 <?php } ?>
				  
				  
				   <?php 
					 if($category_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
                  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g> 
                            <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Category</span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $category_per)) {
            ?>
                      <li><a href="add_category.php">Add Category</a></li>
					  <?php } 
        if (in_array('Read', $category_per)) {
            ?>
                      <li><a href="list_category.php">List Category</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				  
				   <?php } ?>
				  
				  
				   <?php 
					 if($coupon_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 5.063 5.06324 2.75 12.0002 2.75C18.9372 2.75 21.2502 5.063 21.2502 12C21.2502 18.937 18.9372 21.25 12.0002 21.25C5.06324 21.25 2.75024 18.937 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.42993 14.5697L14.5699 9.42969" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.4955 14.5H14.5045" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.4955 9.5H9.5045" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Coupon</span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $coupon_per)) {
            ?>
                      <li><a href="add_coupon.php">Add Coupon</a></li>
					  <?php } 
        if (in_array('Read', $coupon_per)) {
            ?>
                      <li><a href="list_coupon.php">List Coupon</a></li>
					  
					  <?php } ?>
                    </ul>
                  </li>
				  
				  <?php } ?>
				  
				  <?php  
					 
					 if($payment_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 <?php 
        if (in_array('Read', $payment_per)) {
            ?>
			
				  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="paymentlist.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M21.1712 14.6755H17.2845C15.8693 14.6755 14.7217 13.5279 14.7217 12.1117C14.7217 10.6964 15.8693 9.54883 17.2845 9.54883H21.1407" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.7219 12.0531H17.4248" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.6062 8.14367H11.6662" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.71411 12.2532C2.71411 5.8484 5.03887 3.71411 12.0151 3.71411C18.9903 3.71411 21.3151 5.8484 21.3151 12.2532C21.3151 18.657 18.9903 20.7922 12.0151 20.7922C5.03887 20.7922 2.71411 18.657 2.71411 12.2532Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Payment Gateway</span></a></li>
					  
					  <?php } } ?>
					  
					  
					  <?php  
					 
					 if($enquiry_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 <?php 
        if (in_array('Read', $enquiry_per)) {
            ?>
			
					  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="list_enquiry.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Enquiry List</span></a></li>
					  
					  <?php } } ?>
					  
					  
					  <?php  
					 
					 if($payout_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 <?php 
        if (in_array('Read', $payout_per)) {
            ?>
			
					    <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="list_payout.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Payout List</span></a></li>
                  
				   <?php } } ?>
				   
				   
                  
				  <?php 
					 if($property_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
                  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 5.063 5.06324 2.75 12.0002 2.75C18.9372 2.75 21.2502 5.063 21.2502 12C21.2502 18.937 18.9372 21.25 12.0002 21.25C5.06324 21.25 2.75024 18.937 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.2045 13.8999H15.2135" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.2045 9.8999H12.2135" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.19557 13.8999H9.20457" stroke="#130F26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Properties                </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $property_per)) {
            ?>
                      <li><a href="add_properties.php">Add Properties</a></li>
					   <?php } 
        if (in_array('Read', $property_per)) {
            ?>
                      <li><a href="list_properties.php">List Properties</a></li>
					   <?php } ?>
                    </ul>
                  </li>
				  
				  <?php } ?>
				  
				  <?php 
					 if($eimg_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.07056 16.4588C6.07056 16.4588 6.88256 14.8218 8.06456 14.8218C9.24656 14.8218 9.85056 16.1968 11.1606 16.1968C12.4696 16.1968 13.9386 12.7488 15.4226 12.7488C16.9046 12.7488 17.9706 15.1398 17.9706 15.1398" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.1393 9.10487C10.1393 9.96487 9.44229 10.6629 8.58129 10.6629C7.72129 10.6629 7.02429 9.96487 7.02429 9.10487C7.02429 8.24487 7.72129 7.54688 8.58129 7.54688C9.44229 7.54788 10.1393 8.24487 10.1393 9.10487Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 18.937 5.06324 21.25 12.0002 21.25C18.9372 21.25 21.2502 18.937 21.2502 12C21.2502 5.063 18.9372 2.75 12.0002 2.75C5.06324 2.75 2.75024 5.063 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Extra Image                </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $eimg_per)) {
            ?>
                      <li><a href="add_extra.php">Add Extra Image</a></li>
					  <?php } 
        if (in_array('Read', $eimg_per)) {
            ?>
                      <li><a href="list_extra.php">List Extra Image</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				   <?php } ?>
				   
				   
				   <?php 
					 if($facility_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M11.0791 13.8496H7.4314" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.4774 12.1712H15.3752" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.2081 15.5833H17.1059" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8.51392 2.21606C8.5206 2.93015 9.1058 3.50295 9.81989 3.49626H10.828C11.9306 3.48767 12.8328 4.37169 12.8481 5.47432V6.48148" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.8121 13.8953C21.8121 8.33539 19.4255 6.48145 12.2646 6.48145C5.10271 6.48145 2.71606 8.33539 2.71606 13.8953C2.71606 19.4562 5.10271 21.3092 12.2646 21.3092C19.4255 21.3092 21.8121 19.4562 21.8121 13.8953Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Facility                </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $facility_per)) {
            ?>
                      <li><a href="add_facility.php">Add Facility</a></li>
					  <?php } 
        if (in_array('Read', $facility_per)) {
            ?>
                      <li><a href="list_facility.php">List Facility</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				  <?php } ?>
				 
				  
				  <?php 
					 if($gcat_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                          <g>
                            <path d="M14.3053 15.45H8.90527" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.2604 11.4387H8.90442" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.1598 8.3L14.4898 2.9C13.7598 2.8 12.9398 2.75 12.0398 2.75C5.74978 2.75 3.64978 5.07 3.64978 12C3.64978 18.94 5.74978 21.25 12.0398 21.25C18.3398 21.25 20.4398 18.94 20.4398 12C20.4398 10.58 20.3498 9.35 20.1598 8.3Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M13.9342 2.83276V5.49376C13.9342 7.35176 15.4402 8.85676 17.2982 8.85676H20.2492" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Gallery Category               </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $gcat_per)) {
            ?>
                      <li><a href="ad_gal_cat.php">Add Gallery Category</a></li>
					  <?php } 
        if (in_array('Read', $gcat_per)) {
            ?>
                      <li><a href="list_gal_cat.php">List Gallery Category</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				  
				  <?php } ?>
				  
				   <?php 
					 if($gal_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
				  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.07056 16.4588C6.07056 16.4588 6.88256 14.8218 8.06456 14.8218C9.24656 14.8218 9.85056 16.1968 11.1606 16.1968C12.4696 16.1968 13.9386 12.7488 15.4226 12.7488C16.9046 12.7488 17.9706 15.1398 17.9706 15.1398" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.1393 9.10487C10.1393 9.96487 9.44229 10.6629 8.58129 10.6629C7.72129 10.6629 7.02429 9.96487 7.02429 9.10487C7.02429 8.24487 7.72129 7.54688 8.58129 7.54688C9.44229 7.54788 10.1393 8.24487 10.1393 9.10487Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75024 12C2.75024 18.937 5.06324 21.25 12.0002 21.25C18.9372 21.25 21.2502 18.937 21.2502 12C21.2502 5.063 18.9372 2.75 12.0002 2.75C5.06324 2.75 2.75024 5.063 2.75024 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Gallery                </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $gal_per)) {
            ?>
                      <li><a href="add_gal.php">Add Gallery</a></li>
					  <?php } 
        if (in_array('Read', $gal_per)) {
            ?>
                      <li><a href="list_gal.php">List Gallery</a></li>
					   <?php } ?>
                    </ul>
                  </li>
				  
				   <?php } ?>
				   
				   
				   <?php 
					 if($package_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
				   <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M6.91699 14.854L9.90999 10.965L13.324 13.645L16.253 9.86499" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6671 2.3501C20.7291 2.3501 21.5891 3.2101 21.5891 4.2721C21.5891 5.3331 20.7291 6.1941 19.6671 6.1941C18.6051 6.1941 17.7451 5.3331 17.7451 4.2721C17.7451 3.2101 18.6051 2.3501 19.6671 2.3501Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.7555 9.26898C20.8885 10.164 20.9495 11.172 20.9495 12.303C20.9495 19.241 18.6375 21.553 11.6995 21.553C4.76246 21.553 2.44946 19.241 2.44946 12.303C2.44946 5.36598 4.76246 3.05298 11.6995 3.05298C12.8095 3.05298 13.8005 3.11198 14.6825 3.23998" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Package               </span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $package_per)) {
            ?>
                      <li><a href="add_package.php">Add Package</a></li>
					  <?php } 
        if (in_array('Read', $package_per)) {
            ?>
                      <li><a href="list_package.php">List Package</a></li>
					   <?php } ?>
                    </ul>
                  </li>
				  
				  <?php } ?>
				   
					  
					  <?php  
					 
					 if($booking_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 <?php 
        if (in_array('Read', $booking_per)) {
            ?>
                  <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.74976 12.7755C2.74976 5.81947 5.06876 3.50146 12.0238 3.50146C18.9798 3.50146 21.2988 5.81947 21.2988 12.7755C21.2988 19.7315 18.9798 22.0495 12.0238 22.0495C5.06876 22.0495 2.74976 19.7315 2.74976 12.7755Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M3.02515 9.32397H21.0331" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.4284 13.261H16.4374" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0289 13.261H12.0379" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.62148 13.261H7.63048" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.4284 17.113H16.4374" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0289 17.113H12.0379" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7.62148 17.113H7.63048" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">                   </path>
                          </g>
                        </g>
                      </svg><span>Booking </span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="pending.php">Pending Booking</a></li>
                      <li><a href="approved.php">Approved Booking</a></li>
					   <li><a href="check_in.php">Check In Booking</a></li>
					  <li><a href="completed.php">Completed Booking</a></li>
					  <li><a href="cancelled.php">Cancelled Booking</a></li>
					  
                    </ul>
                  </li>
				  
					 <?php } }?>
		
		 <?php 
					 if($page_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
				  <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.1601 8.3L14.4901 2.9C13.7601 2.8 12.9401 2.75 12.0401 2.75C5.75015 2.75 3.65015 5.07 3.65015 12C3.65015 18.94 5.75015 21.25 12.0401 21.25C18.3401 21.25 20.4401 18.94 20.4401 12C20.4401 10.58 20.3501 9.35 20.1601 8.3Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M13.9341 2.83276V5.49376C13.9341 7.35176 15.4401 8.85676 17.2981 8.85676H20.2491" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.3125 12.9807H9.41248" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.8633 15.4308V10.5308" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >Page</span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $page_per)) {
            ?>
                      <li><a href="add_page.php">Add Page</a></li>
					   <?php } 
        if (in_array('Read', $page_per)) {
            ?>
                      <li><a href="list_page.php">List Page</a></li>
					  <?php } ?>
                    </ul>
                  </li>
          <?php } ?>
		  
		   <?php 
					 if($faq_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
           <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g> 
                          <g> 
                            <path d="M8.44019 12L10.8142 14.373L15.5602 9.62695" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.74976 12C2.74976 18.937 5.06276 21.25 11.9998 21.25C18.9368 21.25 21.2498 18.937 21.2498 12C21.2498 5.063 18.9368 2.75 11.9998 2.75C5.06276 2.75 2.74976 5.063 2.74976 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span >FAQ</span></a>
                    <ul class="sidebar-submenu">
					<?php 
        if (in_array('Write', $faq_per)) {
            ?>
                      <li><a href="add_faq.php">Add FAQ</a></li>
					  <?php } 
        if (in_array('Read', $faq_per)) {
            ?>
                      <li><a href="list_faq.php">List FAQ</a></li>
					  <?php } ?>
                    </ul>
                  </li>
				  <?php } ?>
				  
				  
				  <?php  
					 
					 if($ulist_per[0]== "")
					 {
						 
					 }
					 else 
					 {
						 
					 ?>
					 <?php 
        if (in_array('Read', $ulist_per)) {
            ?>
			
				  <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="userlist.php"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g> 
                      <g> 
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55851 21.4562C5.88651 21.4562 2.74951 20.9012 2.74951 18.6772C2.74951 16.4532 5.86651 14.4492 9.55851 14.4492C13.2305 14.4492 16.3665 16.4342 16.3665 18.6572C16.3665 20.8802 13.2505 21.4562 9.55851 21.4562Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55849 11.2776C11.9685 11.2776 13.9225 9.32356 13.9225 6.91356C13.9225 4.50356 11.9685 2.54956 9.55849 2.54956C7.14849 2.54956 5.19449 4.50356 5.19449 6.91356C5.18549 9.31556 7.12649 11.2696 9.52749 11.2776H9.55849Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M16.8013 10.0789C18.2043 9.70388 19.2383 8.42488 19.2383 6.90288C19.2393 5.31488 18.1123 3.98888 16.6143 3.68188" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4608 13.6536C19.4488 13.6536 21.1468 15.0016 21.1468 16.2046C21.1468 16.9136 20.5618 17.6416 19.6718 17.8506" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      </g>
                    </g>
                  </svg><span>User List</span></a></li>
				  
					 <?php } } ?>
                   
                     <li class="sidebar-list">  <a class="sidebar-link sidebar-title link-nav" href="logout.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="logout" style="transform: rotate(90deg);">
                        <g> 
                          <g> 
                            <path d="M11.879 14.791V2.75" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14.795 11.8643L11.879 14.7923L8.96301 11.8643" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.3702 7.25879C19.9492 7.58879 21.2502 8.92879 21.2502 14.2588C21.2502 21.3588 18.9392 21.3588 12.0002 21.3588C5.05924 21.3588 2.75024 21.3588 2.75024 14.2588C2.75024 8.92879 4.05024 7.58879 7.63024 7.25879" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </g>
                        </g>
                      </svg><span>Log out</span></a></li>
                </ul>
                
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
		
		<?php } ?>