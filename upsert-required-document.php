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
	$document = [];

	if ($_GET['doc_id']) {
		$documents = $rstate->query("SELECT * FROM `tbl_required_documents` WHERE `id` = '{$_GET['doc_id']}' AND `deleted_at` IS NULL");

		if (!$documents->num_rows) {
			header('Location: required-documents.php');
			exit;
		}

		$document = $documents->fetch_assoc();
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
								Save Document
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
									<input type="hidden" name="type" value="save_required_doc" />
									<input type="hidden" name="doc_id" value="<?php echo $document['id'] ?>"/>

									<div class="row">
										<div class="form-group mb-3 col-12">
											<div class="d-flex justify-content-between">
												<h5 class="h5_set"><i class="fa fa-file" aria-hidden="true"></i> Save Document</h5>

												<div class="">
													<a href="<?php echo "required-documents.php"; ?>" class="btn btn-danger btn-sm">
														Back
													</a>
												</div>
											</div>
										</div>

										<?php if ($_GET['doc_id']) { ?>
											<h5 class="fe-bold"><?php echo ucwords($document['user_type']); ?></h5>

											<hr />
										<?php } else { ?>
											<div class="form-group mb-3 col-12">
												<label><span class="text-danger">*</span> User Type</label>
												<select name="user_type" id="" class="form-control" required>
													<option value="" disabled selected>--Choose--</option>
													<option value="host" <?php echo ($document['user_type'] == 'host') ? 'selected' : '' ?>>Host</option>
													<option value="user" <?php echo ($document['user_type'] == 'user') ? 'selected' : '' ?>>User</option>
												</select>
											</div>
										<?php } ?>

                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Name</label>
                                            <input type="text" class="form-control" placeholder="Enter a name" value="<?php echo $document['name'];?>" name="doc_name" required="">
                                        </div>

                                        <div class="form-group mb-3 col-6">
                                            <label class="" id="doc_desc">Description</label>
                                            <textarea name="doc_desc" id="doc_desc" class="form-control" placeholder="Enter a description"><?php echo $document['description'] ?></textarea>
                                        </div>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Upload Type</label>
                                            <select name="upload_type" id="" class="form-control" required>
                                                <option value="" disabled selected>--Choose--</option>
                                                <option value="single" <?php echo ($document['upload_type'] == 'single') ? 'selected' : '' ?>>Single</option>
                                                <option value="multiple" <?php echo ($document['upload_type'] == 'multiple') ? 'selected' : '' ?>>Multiple</option>
                                            </select>
                                        </div>
                                        <?php
                                            $accepted_file_types = [];

                                            if ($document && $document['accpetable_file_types']) {
                                                $accepted_file_types = explode(',', $document['accpetable_file_types']);
                                            }
                                        ?>
                                        <div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Acceptable File Type</label>
                                            
                                            <div class="">
                                                <input type="checkbox" class="form-check-input" id="pdf-file-type" name="file_type[]" <?php echo in_array('pdf', $accepted_file_types) ? 'checked' : ''; ?> value="pdf" />
                                                <label class="form-check-label" for="pdf-file-type">PDF</label>
                                            </div>

                                            <div class="">
                                                <input type="checkbox" class="form-check-input" id="image-file-type" name="file_type[]" <?php echo in_array('image', $accepted_file_types) ? 'checked' : ''; ?> value="image" />
                                                <label class="form-check-label" for="image-file-type">Image</label>
                                            </div>
                                        </div>

										<div class="col-12">
											<button type="submit" name="save_required_doc" class="btn btn-primary mb-2">Save Changes</button>
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
<script>
    const checkboxes = document.querySelectorAll(`input[name="file_type[]"]`);

    function validateCheckboxGroup() {
        const oneChecked = Array.from(checkboxes).some(cb => cb.checked);

        checkboxes.forEach(cb => {
            cb.required = !oneChecked;
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener(`change`, validateCheckboxGroup);
    });

    validateCheckboxGroup();
</script>
</body>
</html>