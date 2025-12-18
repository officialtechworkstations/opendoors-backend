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
	if ($_GET['doc_id'] && $_GET['action']) {
		$doc_id = $_GET['doc_id'];
		$action = $_GET['action'];
		$sql = "";

		if ($action == 'disable') {
			$sql = "UPDATE `tbl_required_documents` SET `status` = 'inactive' WHERE `id` = $doc_id LIMIT 1";
		} elseif ($action == 'enable') {
			$sql = "UPDATE `tbl_required_documents` SET `status` = 'active' WHERE `id` = $doc_id LIMIT 1";
		} elseif ($action == 'delete') {
			$sql = "UPDATE `tbl_required_documents` SET `deleted_at` = '".date("Y-m-d H:i:s")."' WHERE `id` = $doc_id LIMIT 1";
		}

		$completed = $rstate->query($sql);
		
		header('Location: required-documents.php');
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
								<?php $rates = $rstate->query("SELECT * FROM `tbl_required_documents` WHERE `deleted_at` IS NULL ORDER BY `created_at` DESC"); ?>

								<div class="form-group mb-3 col-12">
									<div class="d-flex justify-content-between">
										<h5 class="h5_set"><i class="fa fa-list" aria-hidden="true"></i> List</h5>

										<div class="">
											<a href="<?php echo "upsert-required-document.php"; ?>" class="btn btn-primary">
												Add Document
											</a>
										</div>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table table-light table-hover table-borderless">
										<thead class="thead-dark">
											<tr>
												<th>S/N</th>
												<th>User Type</th>
												<th>Name</th>
												<th>Description</th>
												<th>Upload Type</th>
												<th>Accepted File Types</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($rates->num_rows) { $counter = 0; $row = $rates->fetch_assoc(); ?>
												<?php do { ?>
													<tr>
														<td><?php echo ++$counter; ?></td>
														<td><?php echo ucwords($row['user_type'], 2); ?></td>
														<td><?php echo $row['name']; ?></td>
														<td><?php echo $row['description']; ?></td>
														<td><?php echo ucwords($row['upload_type']); ?></td>
														<td><?php echo ucwords($row['accpetable_file_types']); ?></td>
														<td>
															<a href="<?php echo 'upsert-required-document.php?doc_id='.$row['id'] ?>" class="btn-xs btn btn-info">
																Edit
															</a>
															<?php if ($row['status'] == 'active') { ?>
																<a href="javascript:void(0);" data-href="<?php echo "required-documents.php?doc_id=".$row['id']."&action=disable"; ?>" class="btn-xs btn btn-warning msg-toggler" warning-msg="Are you sure you want to disable this required document?">
																	Disable
																</a>
															<?php } else { ?>
																<a href="javascript:void(0);" data-href="<?php echo "required-documents.php?doc_id=".$row['id']."&action=enable"; ?>" class="btn-xs btn btn-success msg-toggler" warning-msg="Are you sure you want to activate this required document?">
																	Enable
																</a>
															<?php } ?>
															<a href="javascript:void(0);" data-href="<?php echo "required-documents.php?doc_id=".$row['id']."&action=delete"; ?>" class="btn-xs btn btn-danger msg-toggler" warning-msg="Are you sure you want to delete this required document?">
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