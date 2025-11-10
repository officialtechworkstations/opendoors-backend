<?php 
require 'include/main_head.php';
if($_SESSION['stype'] == 'Staff') { ?>
    <?php header('HTTP/1.1 401 Unauthorized'); ?>
    <style>
        .loader-wrapper {
            display:none;
        }
    </style>
    <?php require 'auth.php';  exit(); ?>
<?php } ?>
<?php 
	if ($_GET['comm_id'] && $_GET['action']) {
		$comm_id = $_GET['comm_id'];
		$action = $_GET['action'];
		$sql = "";

		if ($action == 'disable') {
			$sql = "UPDATE `tbl_booking_commission` SET `is_active` = 0 WHERE `id` = $comm_id LIMIT 1";
		} elseif ($action == 'enable') {
			$sql = "UPDATE `tbl_booking_commission` SET `is_active` = 1 WHERE `id` = $comm_id LIMIT 1";
		} elseif ($action == 'delete') {
			$sql = "DELETE FROM `tbl_booking_commission` WHERE `id` = $comm_id LIMIT 1";
		}

		$completed = $rstate->query($sql);
		
		header('Location: booking-commission.php');
		exit;
	}
?>
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
								Booking Commission
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
								<?php $rates = $rstate->query("SELECT * FROM `tbl_booking_commission` WHERE `user_type` IN ('user', 'host') ORDER BY `created_at` DESC"); ?>

								<div class="form-group mb-3 col-12">
									<div class="d-flex justify-content-between">
										<h5 class="h5_set"><i class="fa fa-list" aria-hidden="true"></i> List</h5>

										<div class="">
											<a href="<?php echo "upsert-booking-commission.php"; ?>" class="btn btn-primary">
												Set Booking Commission
											</a>
										</div>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table table-light table-hover table-borderless">
										<thead class="thead-dark">
											<tr>
												<th>S/N</th>
												<th>Type</th>
												<th>Amount</th>
												<th>Type</th>
												<th>Max Amount</th>
												<th>Range From</th>
												<th>Range To</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($rates->num_rows) { $counter = 0; $row = $rates->fetch_assoc(); ?>
												<?php do { ?>
													<tr>
														<td><?php echo ++$counter; ?></td>
														<td><?php echo ucwords($row['user_type'], 2); ?></td>
														<td><?php echo number_format($row['amount'], 2); ?></td>
														<td><?php echo $row['amount_type']; ?></td>
														<td><?php echo number_format($row['max_amount'], 2); ?></td>
														<td><?php echo number_format($row['range_from'], 2); ?></td>
														<td><?php echo number_format($row['range_to'], 2); ?></td>
														<td>
															<a href="<?php echo 'upsert-booking-commission.php?comm_id='.$row['id'] ?>" class="btn-xs btn btn-info">
																Edit
															</a>
															<?php if ($row['is_active']) { ?>
																<a href="javascript:void(0);" data-href="<?php echo "booking-commission.php?comm_id=".$row['id']."&action=disable"; ?>" class="btn-xs btn btn-warning msg-toggler" warning-msg="Are you sure you want to disable this booking commission?">
																	Disable
																</a>
															<?php } else { ?>
																<a href="javascript:void(0);" data-href="<?php echo "booking-commission.php?comm_id=".$row['id']."&action=enable"; ?>" class="btn-xs btn btn-success msg-toggler" warning-msg="Are you sure you want to activate this booking commission?">
																	Enable
																</a>
															<?php } ?>
															<a href="javascript:void(0);" data-href="<?php echo "booking-commission.php?comm_id=".$row['id']."&action=delete"; ?>" class="btn-xs btn btn-danger msg-toggler" warning-msg="Are you sure you want to delete this booking commission?">
																Delete
															</a>
														</td>
													</tr>
												<?php } while ($row = $rates->fetch_assoc()) ?>
											<?php } else { ?>
												<tr>
													<td colspan="9">
														<div class="text-center">
															<p><i class="fa fa-exclamation-triangle fa-2x text-warning"></i></p>
															<p>No record found</p>
														</div>
													</td>
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
<?php require 'include/footer.php'; ?>
<script>
	$(function() {
		$(`body`).on(`click`, `.msg-toggler`, function(e) {
			e.preventDefault();

			if (confirm($(this).attr(`warning-msg`))) {
				window.location.href = $(this).data(`href`);
			}
		})
	});
</script>
</body>
</html>