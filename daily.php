<?php 
require 'include/main_head.php';
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
                     Daily Report</h3>
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
				<div class="d-flex">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <?php 
			$timestamp = date("Y-m-d");
			?>
            <input type="date" class="form-control"  id="check_date" value="<?php echo $timestamp; ?>" required="">
			
			<div id="error_message" class="text-danger"></div>
        </div>
    </div>
    <div class="form-group mb-3">
        <label></label>
        <button type="button" id="search_data" class="btn btn-primary">Search</button>
    </div>
	</div>
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                          <tr>
                            <th>Particulars</th>
							<th>Value</th>
											
												</thead>
												<tbody class="tblcontent">
                          <?php 
						  $query = "select count(*) as total_book from tbl_book where book_date='".$timestamp."'";
		  $totalbook = $rstate->query($query)->fetch_assoc();
		  
		  $query = "select count(*) as total_complete from tbl_book where book_date='".$timestamp."' and book_status='Completed' ";
		  $totalcom = $rstate->query($query)->fetch_assoc();
						
					$query = "select count(*) as total_complete from tbl_book where book_date='".$timestamp."' and book_status='Cancelled'";
		  $totalcan = $rstate->query($query)->fetch_assoc();
		  
		   $query ="select sum(`total`) as total from tbl_book where book_status='Completed' and book_date='".$timestamp."'"; 
		   $sales = $rstate->query($query)->fetch_assoc();
                           $bs=0;
	if(empty($sales['total'])){}else {$bs = number_format((float)($sales['total']), 2, '.', ''); }
						  ?>
						  
						  <tr>
											
											<td>
                                                   <b> Total Booked Property</b>
                                                </td>
												
                                                <td>
                                                    <b><span class="text text-warning"><?php echo $totalbook['total_book']; ?></span></b>
                                                </td>
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Booking Completed</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-success"><?php echo $totalcom['total_complete']; ?></span></b>
                                                </td>
												
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Cancelled Booking</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-danger"><?php echo $totalcan['total_complete']; ?></span></b>
                                                </td>
												
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Booked Earning</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-primary"><?php echo $bs.$set['currency']; ?></span></b>
                                                </td>
												
                                                </tr>
												
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
<script>

$(document).ready(function () {
    $("#search_data").click(function () {
		
        var selectedDate = $("#check_date").val();
		
        var errorElement = $("#error_message");

        if (selectedDate === "") {
            errorElement.text("Please Select A Date.");
            // You can style the error message as needed using CSS.
        } else {
			
            errorElement.text(""); // Clear the error message.
            // Continue with your search logic here as the date is selected.
            // You can use the 'selectedDate' variable for further processing.
			$.ajax({
    type: "POST",
    url: "getdaily.php",
    data: {
        book_date: selectedDate
    },
    success: function (res) {
        $(".tblcontent").html(res);
    }
});
        }
    });
});
</script>
  </body>
</html>