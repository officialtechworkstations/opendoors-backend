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
	$rate = [];

	if ($_GET['comm_id']) {
		$rates = $rstate->query("SELECT * FROM `tbl_booking_commission` WHERE `id` = '{$_GET['comm_id']}'");

		if (!$rates->num_rows) {
			header('Location: booking-commission.php');
			exit;
		}

		$rate = $rates->fetch_assoc();
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
								Setting Management
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
								<form method="post" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="type" value="save_commission" />
									<input type="hidden" name="comm_id" value="<?php echo $rate['id'] ?>"/>

									<div class="row">
										<div class="form-group mb-3 col-12">
											<div class="d-flex justify-content-between">
												<h5 class="h5_set"><i class="fa fa-dollar" aria-hidden="true"></i> Save Commission Rate</h5>

												<div class="">
													<a href="<?php echo "booking-commission.php"; ?>" class="btn btn-danger btn-sm">
														Back
													</a>
												</div>
											</div>
										</div>

										<?php if ($_GET['comm_id']) { ?>
											<h5 class="fe-bold"><?php echo ucwords($rate['user_type']); ?></h5>

											<hr />
										<?php } else { ?>
											<div class="form-group mb-3 col-6">
												<label><span class="text-danger">*</span> User Type</label>
												<select name="user_type" id="" class="form-control" required>
													<option value="" disabled selected>--Choose--</option>
													<option value="host" <?php echo ($rate['user_type'] == 'host') ? 'selected' : '' ?>>Host</option>
													<option value="user" <?php echo ($rate['user_type'] == 'user') ? 'selected' : '' ?>>User</option>
												</select>
											</div>
										<?php } ?>

                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Amount</label>
                                            <input type="number" class="form-control" placeholder="Enter an amount" step="0.01" value="<?php echo $rate['amount'];?>" name="amount" required="">
                                        </div>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Type</label>
                                            <select name="amount_type" id="" class="form-control" required>
                                                <option value="" disabled selected>--Choose--</option>
                                                <option value="flat" <?php echo ($rate['amount_type'] == 'flat') ? 'selected' : '' ?>>Flat</option>
                                                <option value="percentage" <?php echo ($rate['amount_type'] == 'percentage') ? 'selected' : '' ?>>Percentage</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Max Amount</label>
                                            <input type="number" class="form-control" placeholder="Enter a max amount" step="0.01" value="<?php echo $rate['max_amount'];?>" name="max_amount">
                                        </div>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Range From</label>
                                            <input type="number" class="form-control" placeholder="Enter a max amount" step="0.01" value="<?php echo $rate['range_from'];?>" name="range_from">
                                        </div>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Range To</label>
                                            <input type="number" class="form-control" placeholder="Enter a max amount" step="0.01" value="<?php echo $rate['range_to'];?>" name="range_to">
                                        </div>
                                        <div class="form-group mb-3 col-6 d-flex align-items-center">
                                            <div class="">
                                                <input type="checkbox" class="form-check-input" id="<?php echo 'is-active-'.$user_type ?>" name="is_active" <?php echo $rate['is_active'] ? 'checked' : ''; ?> value="1" />
                                                <label class="form-check-label" for="<?php echo 'is-active-'.$user_type ?>">Active</label>
                                            </div>
                                        </div>

										<div class="col-12">
											<button type="submit" name="save_commission" class="btn btn-primary mb-2">Save Changes</button>
										</div>
									</div>
								</form>
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
</body>
</html>