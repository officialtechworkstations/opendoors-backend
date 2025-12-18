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
								Required Documents
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
								<?php $rates = $rstate->query("SELECT DISTINCT u.*, rd.user_type FROM tbl_user u
                                                                JOIN tbl_user_documents ud ON ud.user_id = u.id
                                                                JOIN tbl_required_documents rd ON rd.id = ud.document_id
                                                                ORDER BY ud.created_at DESC "); ?>

								<div class="table-responsive">
									<table class="table table-light table-hover table-borderless">
										<thead class="thead-dark">
											<tr>
												<th>S/N</th>
												<th>User</th>
												<th>Type</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($rates->num_rows) { $counter = 0; $row = $rates->fetch_assoc(); ?>
												<?php do { ?>
													<tr>
														<td><?php echo ++$counter; ?></td>
														<td><?php echo $row['name']; ?></td>
														<td><?php echo ucwords($row['user_type'], 2); ?></td>
														<td>
															<?php if ($row['verification_status'] == 'rejected') { ?>
																<span class="badge bg-danger">Rejected</span>
															<?php } elseif ($row['verification_status'] == 'approved') { ?>
																<span class="badge bg-success">Approved</span>
															<?php } else { ?>
																<span class="badge bg-warning">Pending</span>
															<?php }  ?>
														</td>
														<td>
															<a href="<?php echo 'review-user-documents.php?user='.$row['id'] ?>&type=<?php echo $row['user_type'] ?>" class="btn-xs btn btn-info">
																View
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