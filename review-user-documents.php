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
    $redirect_back = true;
    if ($_GET['type'] && $_GET['user']) {
        $user_id = $_GET['user'];
        $user_type = $_GET['type'];

        $sql = "SELECT ud.document_id, rd.name, ud.file_path, ud.status 
				FROM tbl_user_documents ud
                JOIN tbl_required_documents rd ON ud.document_id = rd.id
                WHERE ud.user_id = '{$user_id}'
                    AND rd.user_type = '{$user_type}'
                    AND ud.deleted_at IS NULL
                    AND rd.deleted_at IS NULL
                GROUP BY ud.document_id
				ORDER BY ud.created_at DESC";

        $result = $rstate->query($sql);

        if ($result->num_rows) {
			if ($_GET['action']) {
				$doc_status = 'pending';
				$document_id = $_GET['document'];

				if ($_GET['action'] == 'approve') {
					$doc_status = 'approved';
					$additional_sql = "";
				} elseif ($_GET['action'] == 'reject') {
					$doc_status = 'rejected';
					$additional_sql = ", reason = '{$_GET['reason']}'";
				}

				$updateSql = "UPDATE tbl_user_documents 
							SET `status` = '{$doc_status}'
							{$additional_sql}
							WHERE user_id = '{$user_id}'
								AND document_id = '{$document_id}'
								AND deleted_at IS NULL";
				$update_result = $rstate->query($updateSql);

				if (mysqli_affected_rows($rstate) > 0) {
					$verificationSql = '';
					if ($doc_status == 'rejected') {
						$verificationSql = "UPDATE tbl_user SET verification_status = 'rejected' WHERE id = '{$user_id}' LIMIT 1";
					} elseif ($doc_status == 'approved') {
						$approvedSql = "SELECT ud.id FROM tbl_user_documents ud
										JOIN tbl_required_documents rd ON rd.id = ud.document_id
										WHERE rd.user_type = '{$user_type}'
											AND ud.user_id = '{$user_id}'
											AND ud.status IN ('pending', 'rejected')
											AND ud.deleted_at IS NULL
											AND rd.deleted_at IS NULL";

						$approved_result = $rstate->query($approvedSql);

						if (!$approved_result->num_rows) {
							$verificationSql = "UPDATE tbl_user SET verification_status = 'approved' WHERE id = '{$user_id}' LIMIT 1";
						}
					}

					if ($verificationSql) {
						$rstate->query($verificationSql);

						if (mysqli_affected_rows($rstate) > 0) {
							header('Location: user-documents.php');
							exit;
						}
					}

					header('Location: review-user-documents.php?user='.$user_id.'&type='.$user_type);
					exit;
				}
			}
            $redirect_back = false;
            $document = $result->fetch_assoc();
        }
    }

    if ($redirect_back) {
        header('Location: user-documents.php');
        exit;
    }
?>
<style>
	.btn-custom {
		padding: 0.45rem;
		font-size: 10px;
	}
</style>
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
								User Document Uploads
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
									<table class="table table-light table-hover table-borderless">
										<thead class="thead-dark">
											<tr>
												<th>S/N</th>
												<th>Name</th>
												<th width="30%">Files</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($result->num_rows) { $counter = 0; ?>
												<?php do { ?>
													<?php
														$file_sql = "SELECT * FROM
																	tbl_user_documents ud
																	WHERE ud.deleted_at IS NULL
																		AND ud.document_id = '{$document['document_id']}'
																		AND ud.user_id = '{$user_id}'
																		AND EXISTS (
																			SELECT id FROM 
																			tbl_required_documents rd 
																			WHERE rd.id = ud.document_id
																				AND rd.deleted_at IS NULL
																		)";

														$file_result = $rstate->query($file_sql);
														$file = $file_result->fetch_assoc();
														$file_counter = 0;
													?>
													<tr>
														<td><?php echo ++$counter; ?></td>
														<td class="document-name"><?php echo $document['name']; ?></td>
														<td>
															<?php do { $file_counter++; ?>
																<a href="<?php echo getConfig('BASE_URL').'uploads/'.$file['file_path'] ?>" class="btn btn-info btn-custom m-1" target="_blank">
																	<?php echo $document['name'].' - '.$file_counter; ?>
																</a>
															<?php } while ($file = $file_result->fetch_assoc()); ?>
														</td>
														<td>
															<?php if ($document['status'] == 'rejected') { ?>
																<span class="badge bg-danger">Rejected</span>
															<?php } elseif ($document['status'] == 'approved') { ?>
																<span class="badge bg-success">Approved</span>
															<?php } else { ?>
																<span class="badge bg-warning">Pending</span>
															<?php }  ?>
														</td>
														<td>
															<?php if ($document['status'] == 'pending') { ?>
																<a href="javascript:void(0);" data-href="<?php echo getConfig('BASE_URL').'review-user-documents.php?user='.$user_id.'&type='.$user_type.'&document='.$document['document_id'].'&action=approve'; ?>" class="btn btn-custom btn-success approve-document">
																	<i class="fa fa-check-circle"></i> Approve
																</a>

																<a href="javascript:void(0);" data-href="<?php echo getConfig('BASE_URL').'review-user-documents.php?user='.$user_id.'&type='.$user_type.'&document='.$document['document_id'].'&action=reject'; ?>" class="btn btn-custom btn-danger reject-document" data-bs-toggle="modal" data-bs-target="#exampleModal">
																	<i class="fa fa-times-circle"></i> Reject
																</a>
															<?php } ?>
														</td>
													</tr>
												<?php } while ($document = $result->fetch_assoc()) ?>
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

				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Reject Document</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="" method="get" class="reject-document-form">
									<div class="form-group mb-2">
										<label for="">File:</label>
										<input type="text" name="filename" id="form-filename" class="form-control" readonly />
									</div>

									<div class="form-group mb-2">
										<label for="reason">Reason:</label>
										<textarea class="form-control border-dark" name="reason" id="form-reason" cols="30" rows="8" required></textarea>
									</div>

									<div class="mb-2">
										<button type="submit" class="btn btn-info btn-sm">
											Reject
										</button>
									</div>
								</form>
							</div>
							<!-- <div class="modal-footer">
								<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
							</div> -->
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
		});

		$(`body`).on(`click`, `.approve-document`, function(e) {
			if (confirm(`Are you sure you want to approve this document?`)) {
				$(this)
					.attr(`disabled`, true)
					.html(`Approving...<i class="fa fa-spin fa-spinner></i>`);

				window.location.href = $(this).data(`href`);
			}
		});

		$(`body`).on(`click`, `.reject-document`, function(e) {
			let document_name = $(this).closest(`tr`).find(`.document-name`).text();
			$(`#form-filename`).val(document_name);
			$(`.reject-document-form`).attr(`action`, $(this).data(`href`));
		});

		$(`body`).on(`submit`, `form.reject-document-form`, function(e) {
			e.preventDefault();

			let _action = $(this).attr(`action`), reason = $(`#form-reason`).val();

			window.location.href = `${_action}&reason=${reason}`;
		});
	});
</script>
</body>
</html>