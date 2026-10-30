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
<?php $showDeleted = $_SESSION['stype'] !== 'Staff' && isset($_GET['deleted']) && $_GET['deleted'] === '1'; ?>
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
								<?php if ($_SESSION['stype'] !== 'Staff') { ?>
									<div class="mb-3">
										<button type="button" id="bulk-delete-properties" class="btn <?php echo $showDeleted ? 'btn-success' : 'btn-danger'; ?>" disabled>
											<i class="fa <?php echo $showDeleted ? 'fa-undo' : 'fa-trash'; ?>"></i>
											<?php echo $showDeleted ? 'Restore selected' : 'Delete selected'; ?>
										</button>
										<span id="selected-property-count" class="ml-2">0 selected</span>
										<a class="btn btn-outline-secondary ml-2" href="<?php echo $showDeleted ? 'list_properties.php' : 'list_properties.php?deleted=1'; ?>">
											<?php echo $showDeleted ? 'Active properties' : 'Deleted properties'; ?>
										</a>
									</div>
								<?php } ?>
								<div class="table-responsive">
									<table class="display" id="basic-1">
										<thead>
											<tr>
												<?php if ($_SESSION['stype'] !== 'Staff') { ?>
													<th data-orderable="false" class="sorting_disabled"><input type="checkbox" id="select-all-properties" aria-label="Select all properties"></th>
												<?php } ?>
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
											<?php $city = $rstate->query("SELECT tbl_property.*, rl.id AS reel_id, rl.video_path AS reel_video, rl.status AS reel_status, (SELECT GROUP_CONCAT(`title`) from `tbl_facility` WHERE find_in_set(tbl_facility.id,tbl_property.facility)) as facility_select FROM tbl_property LEFT JOIN tbl_reels rl ON rl.prop_id = tbl_property.id WHERE tbl_property.deleted_at IS " . ($showDeleted ? "NOT NULL" : "NULL")); ?>
											<?php $i=0; ?>
											<?php while($row = $city->fetch_assoc()) { $i = $i + 1; ?>
												<tr>
												<?php if ($_SESSION['stype'] !== 'Staff') { ?>
													<td><input type="checkbox" class="property-select" value="<?php echo (int) $row['id']; ?>" aria-label="Select <?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>"></td>
												<?php } ?>
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
													<?php if ($_SESSION['stype'] === 'Staff' && in_array('Update', $property_per)) { ?>
														<td style="white-space: nowrap; width: 15%;">
															<a href="add_properties.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-success" title="Edit property"><i class="fa fa-edit"></i></a>
															<?php if ($row['pbuysell'] != 1 && $row['is_sell'] != 1) { ?>
																<a href="#" data-id="<?php echo (int) $row['id']; ?>" class="drop" data-status="1" data-type="update_status" coll-type="proper_sell"><img src="images/9385054.png" title="Mark property sold" width="30"></a>
															<?php } ?>
														</td>
													<?php } elseif ($_SESSION['stype'] !== 'Staff') { ?>
														<td style="white-space: nowrap; width: 15%;">
															<?php if ($showDeleted) { ?>
																<a href="#" data-id="<?php echo (int) $row['id']; ?>" class="drop btn btn-sm btn-success" data-status="0" data-type="property_restore" coll-type="property" title="Restore as unpublished"><i class="fa fa-undo"></i></a>
															<?php } else { ?>
																<a href="add_properties.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-success" title="Edit property"><i class="fa fa-edit"></i></a>
																<a href="#" data-id="<?php echo (int) $row['id']; ?>" class="drop btn btn-sm btn-danger" data-status="0" data-type="property_delete" coll-type="property" title="Move to deleted properties"><i class="fa fa-trash"></i></a>
																<?php if ($row['pbuysell'] != 1 && $row['is_sell'] != 1) { ?>
																	<a href="#" data-id="<?php echo (int) $row['id']; ?>" class="drop" data-status="1" data-type="update_status" coll-type="proper_sell"><img src="images/9385054.png" title="Mark property sold" width="30"></a>
																<?php } ?>
															<?php } ?>
														</td>
													<?php } ?>
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
    <?php if ($_SESSION['stype'] !== 'Staff') { ?>
    var selectedProperties = new Set();
    var propertyTable = $('#basic-1').DataTable();

    function refreshBulkSelection() {
        propertyTable.rows().nodes().to$().find('.property-select').each(function() {
            $(this).prop('checked', selectedProperties.has(String($(this).val())));
        });
        $('#selected-property-count').text(selectedProperties.size + ' selected');
        $('#bulk-delete-properties').prop('disabled', selectedProperties.size === 0);

        var filteredCheckboxes = propertyTable.rows({ search: 'applied' }).nodes().to$().find('.property-select');
        var allFilteredSelected = filteredCheckboxes.length > 0 && filteredCheckboxes.toArray().every(function(checkbox) {
            return selectedProperties.has(String($(checkbox).val()));
        });
        $('#select-all-properties').prop('checked', allFilteredSelected);
    }

    $('#basic-1').on('change', '.property-select', function() {
        var id = String($(this).val());
        this.checked ? selectedProperties.add(id) : selectedProperties.delete(id);
        refreshBulkSelection();
    });

    $('#select-all-properties').on('click', function(event) {
        event.stopPropagation();
    }).on('change', function() {
        var shouldSelect = this.checked;
        propertyTable.rows({ search: 'applied' }).nodes().to$().find('.property-select').each(function() {
            var id = String($(this).val());
            shouldSelect ? selectedProperties.add(id) : selectedProperties.delete(id);
        });
        refreshBulkSelection();
    });

    propertyTable.on('draw', refreshBulkSelection);

    $('#bulk-delete-properties').on('click', function() {
        var ids = Array.from(selectedProperties);
        var isRestore = <?php echo $showDeleted ? 'true' : 'false'; ?>;
        var actionLabel = isRestore ? 'Restore ' : 'Move ';
        var confirmation = actionLabel + ids.length + ' selected ' + (ids.length === 1 ? 'property' : 'properties') +
            (isRestore ? ' as unpublished?' : ' to deleted properties?');
        if (!ids.length || !confirm(confirmation)) {
            return;
        }

        $(this).prop('disabled', true);
        $.ajax({
            type: 'post',
            url: 'include/property.php',
            data: { type: isRestore ? 'property_bulk_restore' : 'property_bulk_delete', ids: ids },
            success: function(response) {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                alert(result.title);
                if (result.Result === 'true') {
                    window.location.reload();
                } else {
                    refreshBulkSelection();
                }
            },
            error: function() {
                alert('The selected properties could not be updated. Please try again.');
                refreshBulkSelection();
            }
        });
    });
    <?php } ?>

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
