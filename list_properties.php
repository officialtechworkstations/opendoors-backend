<?php require 'include/main_head.php'; ?>
<?php if ($_SESSION['stype'] == 'Staff' && !in_array('Read', $property_per)) { ?>
	<?php header('HTTP/1.1 401 Unauthorized'); ?>
	<style>
		.loader-wrapper {
			display: none;
		}
	</style>
	<?php require 'auth.php'; exit(); ?>
<?php } ?>
<!-- Loader ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
	<!-- Page Header Start-->
	<?php require 'include/inside_top.php'; ?>
	<!-- Page Header Ends -->
	<!-- Page Body Start-->
	<div class="page-body-wrapper">
		<!-- Page Sidebar Start-->
		<?php require 'include/sidebar.php'; ?>
		<!-- Page Sidebar Ends-->
		<div class="page-body">
			<div class="container-fluid">
				<div class="page-title">
					<div class="row">
						<div class="col-6">
							<h3>
								Property List Management
							</h3>
						</div>
						<div class="col-6">
						</div>
					</div>
				</div>
			</div>
			<!-- Container-fluid starts-->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-body">
								<div class="table-responsive">
									<table class="display" id="basic-1">
										<thead>
											<tr>
												<th>Sr No.</th>
												<th>Property Title</th>
												<th>Property Type</th>
												<th>Property Image</th>
												<th>Is Featured</th>
												<th>Party Allowed</th>
												<th>Property Price(/Night)</th>
												<th>Total Beds</th>
												<th>Total Bathrooms</th>
												<th>Total SQFT.</th>
												<th>Property Facility</th>
												<th>Property Rent or Buy?</th>
												<th>Person Limit?</th>
												<th>Property Status</th>
												<th>Reel</th>
												<?php if($_SESSION['stype'] == 'Staff') { ?>
													<?php if (in_array('Update', $property_per)) { ?>
														<th>Action</th>
													<?php } ?>
												<?php } else {?>
													<th>Action</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php $city = $rstate->query("SELECT tbl_property.*, rl.id AS reel_id, rl.video_path AS reel_video, rl.status AS reel_status, (SELECT GROUP_CONCAT(`title`) from `tbl_facility` WHERE find_in_set(tbl_facility.id,tbl_property.facility)) as facility_select FROM tbl_property LEFT JOIN tbl_reels rl ON rl.prop_id = tbl_property.id"); ?>
											<?php $i=0; ?>
											<?php while($row = $city->fetch_assoc()) { $i = $i + 1; ?>
												<tr>
													<td>
														<?php echo $i; ?>
													</td>
													<td class="align-middle">
														<?php echo $row['title'];?>
													</td>
													<td class="align-middle">
														<?php $type = $rstate->query("select * from tbl_category where id=".$row['ptype']."")->fetch_assoc(); echo $type['title'];?>
													</td>
													<td class="align-middle">
														<img src="<?php echo $row['image']; ?>" width="70" height="80"/>
													</td>
													<td class="align-middle">
														<?php echo $row['is_featured'] ? 'Yes' : 'No' ?>
													</td>
													<td class="align-middle">
														<?php echo $row['party_allowed'] ? 'Yes' : 'No' ?>
														<?php if ($row['party_allowed']) { ?>
															<div>
																<small><?php echo number_format($row['party_cost']).$set['currency']; ?></small>
															</div>
														<?php } ?>
													</td>
													<td class="align-middle">
														<?php echo number_format($row['price']).$set['currency'];?>
													</td>
													<td class="align-middle">
														<?php echo $row['beds'];?>
													</td>
													<td class="align-middle">
														<?php echo $row['bathroom'];?>
													</td>
													<td class="align-middle">
														<?php echo $row['sqrft'];?>
													</td>
													<td class="align-middle">
														<?php echo '<span class="badge badge-dark tag-pills-sm-mb">'.str_replace(',','</span><span class="badge badge-dark tag-pills-sm-mb">',$row['facility_select']);?>
													</td>
													<?php if($row['pbuysell'] == 1) { ?>
													<td><span class="badge badge-success">Rent</span></td>
													<?php } else { ?>
													<td>
														<span class="badge badge-danger">Buy</span>
														<?php if($row['is_sell'] == 1)
															{?><span class="badge badge-info">Property Selled</span>
														<?php }
															?>
													</td>
													<?php } ?>
													<td class="align-middle">
														<?php echo $row['plimit'];?>
													</td>
													<?php if($row['status'] == 1) { ?>
													<td><span class="badge badge-success">Publish</span></td>
													<?php } else { ?>
													<td>
														<span class="badge badge-danger">Unpublish</span>
													</td>
													<?php } ?>
													<td class="align-middle">
														<?php if (!empty($row['reel_id'])) { ?>
															<a href="#" class="btn btn-sm btn-primary view-reel-btn mb-1" data-video="<?php echo $row['reel_video']; ?>">View</a><br>
															<a href="#" data-id="<?php echo $row['reel_id']; ?>" class="drop badge badge-danger cursor-pointer p-2" data-status="0" data-type="update_status" coll-type="delete_reel">Remove</a>
														<?php } else { ?>
															N/A
														<?php } ?>
													</td>
													<?php 
														if($_SESSION['stype'] == 'Staff')
														{
														if (in_array('Update', $property_per)) {
														?>
													<td style="white-space: nowrap; width: 15%;">
														<div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
															<div class="btn-group btn-group-sm" style="float: none;">
																<a href="add_properties.php?id=<?php echo $row['id'];?>" data-toggle="tooltip" title="edit property" class="tabledit-edit-button" style="float: none; margin: 5px;">
																	<?xml version="1.0" encoding="UTF-8"?>
																	<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<rect width="30" height="30" rx="15" fill="#79F9B4"/>
																		<path d="M22.5168 9.34109L20.6589 7.48324C20.0011 6.83703 18.951 6.837 18.2933 7.49476L16.7355 9.06416L20.9359 13.2645L22.5052 11.7067C23.163 11.0489 23.163 9.99885 22.5168 9.34109ZM15.5123 10.2873L8 17.8342V22H12.1658L19.7127 14.4877L15.5123 10.2873Z" fill="#25314C"/>
																	</svg>
																</a>
																<?php if($row['pbuysell'] != 1) { 
																	if($row['is_sell'] != 1)
																	{
																	?>
																<a href="#"  data-id="<?php echo $row['id'];?>" class="drop" data-status="1" data-type="update_status" coll-type="proper_sell" style="float: none; margin: 5px;"><img src="images/9385054.png" data-toggle="tooltip" title="sell property done" width="30px"/></a>
																<?php } else {} }?>
															</div>
														</div>
													</td>
													<?php 
														}			
														}
														else 
														{
														?>
													<td style="white-space: nowrap; width: 15%;">
														<div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
															<div class="btn-group btn-group-sm" style="float: none;">
																<a href="add_properties.php?id=<?php echo $row['id'];?>" data-toggle="tooltip" title="edit property" class="tabledit-edit-button" style="float: none; margin: 5px;">
																	<?xml version="1.0" encoding="UTF-8"?>
																	<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<rect width="30" height="30" rx="15" fill="#79F9B4"/>
																		<path d="M22.5168 9.34109L20.6589 7.48324C20.0011 6.83703 18.951 6.837 18.2933 7.49476L16.7355 9.06416L20.9359 13.2645L22.5052 11.7067C23.163 11.0489 23.163 9.99885 22.5168 9.34109ZM15.5123 10.2873L8 17.8342V22H12.1658L19.7127 14.4877L15.5123 10.2873Z" fill="#25314C"/>
																	</svg>
																</a>
																<?php if($row['pbuysell'] != 1) { 
																	if($row['is_sell'] != 1)
																	{
																	?>
																<a href="#"  data-id="<?php echo $row['id'];?>" class="drop" data-status="1" data-type="update_status" coll-type="proper_sell" style="float: none; margin: 5px;"><img src="images/9385054.png" data-toggle="tooltip" title="sell property done" width="30px"/></a>
																<?php } else {} }?>
															</div>
														</div>
													</td>
													<?php 
														}
														?>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Container-fluid Ends-->
		</div>
		<!-- footer start-->
	</div>
</div>
<!-- latest jquery-->
<?php 
	require 'include/footer.php';
	?>
<!-- Reel Modal -->
<div class="modal fade" id="reelModal" tabindex="-1" role="dialog" aria-labelledby="reelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reelModalLabel">Property Reel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <video id="reelVideoPlayer" width="100%" style="max-height: 70vh;" controls controlsList="nodownload">
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('.view-reel-btn').on('click', function(e) {
        e.preventDefault();
        var videoUrl = $(this).data('video');
        $('#reelVideoPlayer source').attr('src', videoUrl);
        $('#reelVideoPlayer')[0].load();
        var playPromise = $('#reelVideoPlayer')[0].play();
        if (playPromise !== undefined) {
            playPromise.catch(function(error) {
                // Auto-play was prevented
            });
        }
        $('#reelModal').modal('show');
    });

    $('#reelModal').on('hidden.bs.modal', function () {
        $('#reelVideoPlayer')[0].pause();
        $('#reelVideoPlayer source').attr('src', '');
    });
});
</script>
</body>
</html>