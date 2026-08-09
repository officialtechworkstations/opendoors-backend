<?php 
require 'include/main_head.php';
if(isset($_GET['payout']))
{	
	if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $payout_per)) {
    

    
    header('HTTP/1.1 401 Unauthorized');
    ?>
    <style>
        .loader-wrapper {
            display: none;
        }
    </style>
    <?php
    require 'auth.php';
    exit();
}
}
else 
{
if ($_SESSION['stype'] == 'Staff' && !in_array('Read', $payout_per)) {
    

    
    header('HTTP/1.1 401 Unauthorized');
    ?>
    <style>
        .loader-wrapper {
            display: none;
        }
    </style>
    <?php
    require 'auth.php';
    exit();
}	
}
?>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <?php 
	  require 'include/inside_top.php';
	  ?>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <?php 
		require 'include/sidebar.php';
		?>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  <h3>
                     Payout List Management</h3>
                </div>
                <div class="col-6">
                  
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
         <div class="container-fluid general-widget">
            <div class="row">
             
             <div class="col-sm-12">
                <div class="card">
				<div class="card-body">
				    <?php 
	if(isset($_GET['payout']))
						{
							$payout_id  = (int) $_GET['payout'];
							$prow       = $rstate->query("select * from payout_setting where id=".$payout_id."")->fetch_assoc();
							$powner     = $rstate->query("select name,ccode,mobile from tbl_user where id=".(int)$prow['owner_id']."")->fetch_assoc();
							$active_gws = $rstate->query("select id,title from tbl_payment_list where status=1 order by title asc");

							// Host payout destination (read-only, method-aware).
							if (strtoupper($prow['r_type']) === 'UPI') {
								$acc_html = 'UPI ID: <b>'.htmlspecialchars($prow['upi_id']).'</b>';
							} elseif (stripos($prow['r_type'], 'bank') !== false) {
								$acc_html = 'Bank: <b>'.htmlspecialchars($prow['bank_name']).'</b>'.(!empty($prow['bank_code']) ? ' <span class="text-muted">('.htmlspecialchars($prow['bank_code']).')</span>' : '')
									.'<br>Account No: <b>'.htmlspecialchars($prow['acc_number']).'</b>'
									.'<br>Account Name: <b>'.htmlspecialchars($prow['acc_name']).'</b>';
							} else {
								$acc_html = 'PayPal: <b>'.htmlspecialchars($prow['paypal_id']).'</b>';
							}
							?>
							<div class="mb-3">
								<a href="list_payout.php" class="btn btn-outline-secondary btn-sm">&larr; Back to list</a>
							</div>
							<h5 class="mb-3">
								Payout to <b><?php echo htmlspecialchars($powner['name']); ?></b> &mdash;
								Requested: <b><?php echo $prow['amt'].' '.$set['currency']; ?></b>
								(<?php echo htmlspecialchars($prow['r_type']); ?>)
							</h5>

							<!-- Host account details (read-only) -->
							<div style="background:#f6f7fb;border:1px solid #e6e8f0;padding:12px 14px;border-radius:6px;margin-bottom:16px;max-width:520px;">
								<div style="font-weight:600;margin-bottom:6px;">Host account details</div>
								<div><?php echo $acc_html; ?></div>
							</div>

							<!-- Mode chooser -->
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item"><a class="nav-link active" data-mode="auto"   href="javascript:void(0)">Auto (Gateway)</a></li>
								<li class="nav-item"><a class="nav-link"        data-mode="manual" href="javascript:void(0)">Manual</a></li>
							</ul>

							<div class="pt-3">
							<!-- ============ AUTO ============ -->
							<div id="mode-auto">
								<div class="form-group mb-3" style="max-width:420px">
									<label>Payment Gateway</label>
									<select class="form-control" id="pg-gateway">
										<?php while($gw = $active_gws->fetch_assoc()) { ?>
											<option value="<?php echo $gw['id']; ?>" data-supported="<?php echo ($gw['id']==6?1:0); ?>"><?php echo htmlspecialchars($gw['title']); ?></option>
										<?php } ?>
									</select>
									<small id="pg-unsupported" class="text-danger" style="display:none">Auto disbursement isn't wired up for this gateway yet. Use Manual, or pick Paystack.</small>
								</div>

								<div class="form-group mb-3">
									<button type="button" class="btn btn-outline-primary btn-sm" id="pg-check-balance">Check Balance</button>
									<span id="pg-balance" class="ms-2"></span>
								</div>

								<div class="form-group mb-3" style="max-width:420px">
									<label>Recipient Bank</label>
									<select class="form-control" id="pg-bank"><option value="">Loading banks…</option></select>
									<small id="pg-bank-hint" class="text-muted"></small>
								</div>

								<div class="form-group mb-3" style="max-width:420px">
									<label>Account Number</label>
									<input type="text" class="form-control" id="pg-account" value="<?php echo htmlspecialchars($prow['acc_number']); ?>">
								</div>

								<div class="form-group mb-3">
									<button type="button" class="btn btn-outline-primary btn-sm" id="pg-verify">Verify Account</button>
									<span id="pg-accname" class="ms-2"></span>
								</div>

								<div class="form-group mb-3" id="pg-otp-wrap" style="display:none;max-width:420px">
									<label>OTP (sent by Paystack)</label>
									<input type="text" class="form-control" id="pg-otp" placeholder="Enter OTP">
									<button type="button" class="btn btn-success btn-sm mt-2" id="pg-finalize">Finalize Transfer</button>
								</div>

								<div class="text-left">
									<button type="button" class="btn btn-primary" id="pg-send" disabled>Send Payout <i class="fas fa-paper-plane"></i></button>
								</div>
								<div id="pg-msg" class="mt-3"></div>
							</div>

							<!-- ============ MANUAL ============ -->
							<div id="mode-manual" style="display:none">
								<form class="form" method="post" enctype="multipart/form-data" style="max-width:520px">
									<input type="hidden" name="type" value="manual_payout">
									<input type="hidden" name="payout_id" value="<?php echo $payout_id; ?>"/>
									<div class="form-group mb-3">
										<label>Date Payment Was Made <span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="paid_date" required>
									</div>
									<div class="form-group mb-3">
										<label>Amount <span class="text-danger">*</span></label>
										<input type="number" step="0.01" class="form-control" name="paid_amount" value="<?php echo $prow['amt']; ?>" required>
									</div>
									<div class="form-group mb-3">
										<label>Transaction ID <small class="text-muted">(optional)</small></label>
										<input type="text" class="form-control" name="transaction_id" placeholder="e.g. bank reference">
									</div>
									<div class="form-group mb-3">
										<label>Receipt / Evidence <span class="text-danger">*</span></label>
										<input type="file" class="form-control" name="cat_img" required>
									</div>
									<div class="text-left">
										<button class="btn btn-primary">Record Payout <i class="fas fa-receipt"></i></button>
									</div>
								</form>
							</div>
							</div>

							<script>
							(function(){
								var payoutId = <?php echo $payout_id; ?>;
								var api = 'paystack/payout.php';
								var verified = false;

								// Host-stored destination (for bank pre-select + name comparison).
								var storedBankCode = <?php echo json_encode($prow['bank_code'] ?? ''); ?>;
								var storedBankName = <?php echo json_encode($prow['bank_name'] ?? ''); ?>;
								var storedAccName  = <?php echo json_encode($prow['acc_name'] ?? ''); ?>;
								var resolvedName   = '';
								var nameMismatch   = false;

								function norm(s){ return (s||'').toString().toLowerCase().replace(/[^a-z0-9]/g,''); }

								function q(id){ return document.getElementById(id); }
								function gw(){ return q('pg-gateway').value; }
								function supported(){ var o=q('pg-gateway').selectedOptions[0]; return o && o.getAttribute('data-supported')==='1'; }
								function msg(t,ok){ q('pg-msg').innerHTML = '<span class="'+(ok?'text-success':'text-danger')+'">'+t+'</span>'; }

								function post(params){
									params.action = params.action;
									var body = new URLSearchParams(params);
									body.append('gateway_id', gw());
									return fetch(api, {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: body})
										.then(function(r){ return r.json(); });
								}

								// Tab switching
								document.querySelectorAll('.nav-link[data-mode]').forEach(function(a){
									a.addEventListener('click', function(){
										document.querySelectorAll('.nav-link[data-mode]').forEach(function(x){x.classList.remove('active');});
										a.classList.add('active');
										q('mode-auto').style.display   = a.dataset.mode==='auto'   ? 'block':'none';
										q('mode-manual').style.display = a.dataset.mode==='manual' ? 'block':'none';
									});
								});

								function refreshSupport(){
									var ok = supported();
									q('pg-unsupported').style.display = ok ? 'none':'block';
									q('pg-check-balance').disabled = !ok;
									q('pg-verify').disabled = !ok;
									if(!ok){ q('pg-send').disabled = true; }
									if(ok){ loadBanks(); }
								}
								q('pg-gateway').addEventListener('change', refreshSupport);

								function loadBanks(){
									q('pg-bank').innerHTML = '<option value="">Loading banks…</option>';
									post({action:'banks'}).then(function(res){
										if(!res.ok){ q('pg-bank').innerHTML='<option value="">Could not load banks</option>'; return; }
										var html='<option value="">Select bank…</option>';
										res.banks.forEach(function(b){ html += '<option value="'+b.code+'">'+b.name+'</option>'; });
										q('pg-bank').innerHTML = html;
										preselectBank();
									});
								}

								// Pre-select the host's bank: by stored bank_code if we have it,
								// else a best-effort name match against the dropdown options.
								function preselectBank(){
									var sel = q('pg-bank');
									q('pg-bank-hint').textContent = '';

									if(storedBankCode){
										for(var i=0;i<sel.options.length;i++){
											if(sel.options[i].value === storedBankCode){ sel.selectedIndex = i; return; }
										}
									}
									if(storedBankName){
										var target = norm(storedBankName);
										for(var j=0;j<sel.options.length;j++){
											var t = norm(sel.options[j].text);
											if(t && (t.indexOf(target) > -1 || target.indexOf(t) > -1)){ sel.selectedIndex = j; return; }
										}
										q('pg-bank-hint').textContent = 'Could not auto-match "' + storedBankName + '" — please select the bank manually.';
									}
								}

								q('pg-check-balance').addEventListener('click', function(){
									q('pg-balance').textContent = 'Checking…';
									post({action:'balance'}).then(function(res){
										q('pg-balance').innerHTML = res.ok
											? '<b>Balance: '+Number(res.balance).toLocaleString()+' '+res.currency+'</b>'
											: '<span class="text-danger">'+res.error+'</span>';
									});
								});

								q('pg-verify').addEventListener('click', function(){
									verified=false; q('pg-send').disabled=true;
									q('pg-accname').textContent='Verifying…';
									post({action:'resolve', account_number:q('pg-account').value, bank_code:q('pg-bank').value}).then(function(res){
										if(res.ok){
											resolvedName = res.account_name || '';
											nameMismatch = storedAccName ? (norm(resolvedName) !== norm(storedAccName)) : false;
											var matchTag = !storedAccName
												? '<span class="text-muted">(no stored name to compare)</span>'
												: (nameMismatch ? '<span class="text-danger">&#10007; does not match stored name</span>'
												               : '<span class="text-success">&#10003; matches stored name</span>');
											q('pg-accname').innerHTML =
												'<div>Stored name: <b>'+(storedAccName || '&mdash;')+'</b></div>'+
												'<div>Bank returned: <b>'+resolvedName+'</b> '+matchTag+'</div>';
											verified=true; q('pg-send').disabled=false;
										} else {
											q('pg-accname').innerHTML = '<span class="text-danger">'+res.error+'</span>';
										}
									});
								});

								q('pg-send').addEventListener('click', function(){
									if(!verified){ msg('Please verify the account first.',false); return; }
									// Only ask for confirmation when the resolved name doesn't match the
									// stored account name; a clean match auto-completes.
									if(nameMismatch){
										if(!confirm('⚠ Account name mismatch.\n\nStored name: '+storedAccName+'\nBank returned: '+resolvedName+'\n\nThe names do not match. Send the payout anyway?')) return;
									}
									q('pg-send').disabled=true; msg('Sending…',true);
									post({action:'transfer', payout_id:payoutId, account_number:q('pg-account').value, bank_code:q('pg-bank').value}).then(function(res){
										if(res.ok && res.done){ msg(res.message+' Reloading…',true); setTimeout(function(){location.href='list_payout.php';},1500); return; }
										if(res.ok && res.need_otp){
											q('pg-otp-wrap').style.display='block';
											q('pg-finalize').dataset.transfer = res.transfer_code;
											msg(res.message,true);
											return;
										}
										msg(res.error||'Transfer failed.',false); q('pg-send').disabled=false;
									});
								});

								q('pg-finalize').addEventListener('click', function(){
									msg('Finalizing…',true);
									post({action:'finalize', payout_id:payoutId, transfer_code:q('pg-finalize').dataset.transfer, otp:q('pg-otp').value}).then(function(res){
										if(res.ok && res.done){ msg(res.message+' Reloading…',true); setTimeout(function(){location.href='list_payout.php';},1500); return; }
										msg(res.error||'Finalize failed.',false);
									});
								});

								refreshSupport();
							})();
							</script>
						<?php
						}
						else 
						{ ?>
				<div class="row mb-3">
					<div class="col-md-3">
						<label class="col-form-label">Filter by status</label>
						<select id="payout-status-filter" class="form-control">
							<option value="">All</option>
							<option value="pending">Pending</option>
							<option value="processing">Processing</option>
							<option value="completed">Completed</option>
							<option value="failed">Failed</option>
						</select>
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Requested from</label>
						<input type="date" id="payout-date-from" class="form-control">
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Requested to</label>
						<input type="date" id="payout-date-to" class="form-control">
					</div>
					<div class="col-md-3 d-flex align-items-end">
						<button type="button" id="payout-filter-clear" class="btn btn-outline-secondary">Clear</button>
					</div>
				</div>
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                                                <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                               
                                    <th>Amount</th>
									<th>Requested</th>

									<th>Service Provider Name</th>
									<th>Transfer Details</th>
                                    <th>Transfer Type</th>
									<th>Vendor Mobile</th>
									<th>Transfer Photo</th>
									<th>Method</th>
									<th>Paid On</th>
									<th>Transaction ID</th>

									 <th>Status</th>
<?php 
												if($_SESSION['stype'] == 'Staff')
		{
			if (in_array('Update', $payout_per)) {
			?>
			<th>Action</th>
			<?php
			}			
		}
		else 
		{
												?>
												<th>Action</th>
												<?php } ?>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            <?php 
											 // Pending first, completed last; newest request first within a status.
											 $stmt = $rstate->query("SELECT * FROM `payout_setting` ORDER BY FIELD(status,'pending','processing','failed','completed'), r_date DESC");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$i = $i + 1;
											?>
                                                <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                               
                                    <td><?php echo $row['amt'].' '.$set['currency'];?></td>
									<td><?php echo !empty($row['r_date']) ? date('Y-m-d', strtotime($row['r_date'])) : ''; ?></td>
									<?php
									$vdetails = $rstate->query("select * from tbl_user where id=".$row['owner_id']."")->fetch_assoc();
									?>
									<td><?php echo $vdetails['name'];?></td>
									<?php 
									if(strtoupper($row['r_type']) === 'UPI')
									{
									  ?>
									  <td><?php echo htmlspecialchars($row['upi_id']);?></td>
									  <?php
									}
									else if(stripos($row['r_type'], 'bank') !== false)
									{
									 $bankline  = 'Bank Name: '.htmlspecialchars($row['bank_name']);
									 if(!empty($row['bank_code'])) { $bankline .= ' ('.htmlspecialchars($row['bank_code']).')'; }
									 $bankline .= '<br>A/C No: '.htmlspecialchars($row['acc_number']);
									 $bankline .= '<br>A/C Name: '.htmlspecialchars($row['acc_name']);
									 if(!empty($row['ifsc_code'])) { $bankline .= '<br>IFSC: '.htmlspecialchars($row['ifsc_code']); }
									 ?>
									 <td><?php echo $bankline;?></td>
									 <?php
									}
									else
									{
									   ?>
									   <td><?php echo htmlspecialchars($row['paypal_id']);?></td>
									   <?php
									}
									?>
									
									<td><?php echo $row['r_type'];?></td>
									 <td><?php echo $vdetails['ccode'].$vdetails['mobile'];?></td>
									 <?php
									 if($row['proof'] == '')
									 {
										 ?>
										 <td></td>
										 <?php
									 }else {
									     ?>
									 
									  <td><img src="<?php echo $row['proof']; ?>" width="70" height="80"/></td>
									  <?php } ?>
									 <?php
									 // Method: auto (+ gateway name) / manual / not yet processed.
									 if ($row['payout_mode'] == 'auto') {
										 $gwname = 'Gateway';
										 if (!empty($row['gateway_id'])) {
											 $gwrow = $rstate->query("select title from tbl_payment_list where id=".(int)$row['gateway_id']."")->fetch_assoc();
											 if ($gwrow) { $gwname = $gwrow['title']; }
										 }
										 $method = 'Auto <small class="text-muted">('.htmlspecialchars($gwname).')</small>';
									 } elseif ($row['payout_mode'] == 'manual') {
										 $method = 'Manual';
									 } else {
										 $method = '<span class="text-muted">&mdash;</span>';
									 }
									 ?>
									 <td><?php echo $method; ?></td>
									 <td>
										 <?php
										 if (!empty($row['paid_date'])) {
											 echo htmlspecialchars($row['paid_date']);
											 if (!empty($row['paid_amount'])) { echo '<br><small class="text-muted">'.$row['paid_amount'].' '.$set['currency'].'</small>'; }
										 } else { echo '<span class="text-muted">&mdash;</span>'; }
										 ?>
									 </td>
									 <td><?php echo !empty($row['transaction_id']) ? htmlspecialchars($row['transaction_id']) : '<span class="text-muted">&mdash;</span>'; ?></td>
									 <?php
									 $st = strtolower($row['status']);
									 $badge_colors = ['pending'=>'#ff9f43','processing'=>'#0dcaf0','completed'=>'#28a745','failed'=>'#dc3545'];
									 $badge_bg = isset($badge_colors[$st]) ? $badge_colors[$st] : '#6c757d';
									 ?>
									 <td><span style="background:<?php echo $badge_bg;?>;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;white-space:nowrap;"><?php echo ucfirst($row['status']);?></span></td>
									 
									 <?php 
												if($_SESSION['stype'] == 'Staff')
		{
			if (in_array('Update', $payout_per)) {
				?>
                                     <td>
									 <?php if($row['status'] == 'pending') {?>
									<a href="?payout=<?php echo $row['id'];?>"><button class="btn shadow-z-2 btn-danger gradient-pomegranate">Make A Payout</button></a>
									 <?php } else { ?>
									 <p><?php echo ucfirst($row['status']);?></p>
									 <?php } ?>
									</td>
									
									<?php 
									   }			
		}
		else 
		{
			?>
			<td>
									 <?php if($row['status'] == 'pending') {?>
									<a href="?payout=<?php echo $row['id'];?>"><button class="btn shadow-z-2 btn-danger gradient-pomegranate">Make A Payout</button></a>
									 <?php } else { ?>
									 <p><?php echo ucfirst($row['status']);?></p>
									 <?php } ?>
									</td>
			<?php } ?> 
                                                </tr>
<?php } ?>                                           
                                        </tbody>
                      </table>
					  </div>
					  <?php } ?>
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
<script>
// Payout list filters. Columns: 2 = Requested date, 11 = Status.
window.addEventListener('load', function () {
	var statusSel = document.getElementById('payout-status-filter');
	if (!statusSel || !window.jQuery || !jQuery.fn.dataTable) { return; }

	var dt      = jQuery('#basic-1').DataTable();
	var fromEl  = document.getElementById('payout-date-from');
	var toEl    = document.getElementById('payout-date-to');
	var clearEl = document.getElementById('payout-filter-clear');

	// Status -> column search.
	statusSel.addEventListener('change', function () {
		dt.column(11).search(this.value, false, false).draw();
	});

	// Requested-date range -> custom search on the "Requested" column (index 2, YYYY-MM-DD).
	jQuery.fn.dataTable.ext.search.push(function (settings, data) {
		if (settings.nTable !== dt.table().node()) { return true; }
		var from = fromEl.value, to = toEl.value;
		if (!from && !to) { return true; }
		var d = (data[2] || '').substr(0, 10);
		if (!d) { return false; }
		if (from && d < from) { return false; }
		if (to && d > to) { return false; }
		return true;
	});

	fromEl.addEventListener('change', function () { dt.draw(); });
	toEl.addEventListener('change', function () { dt.draw(); });

	clearEl.addEventListener('click', function () {
		statusSel.value = ''; fromEl.value = ''; toEl.value = '';
		dt.column(11).search('').draw();
	});
});
</script>
  </body>
</html>