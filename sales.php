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
                     Total Book Property Report Management</h3>
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
            
            <input type="date" class="form-control"  id="min_date"  required="">
			
			
			<div id="error_message" class="text-danger"></div>
        </div>
		
    </div>
	&nbsp;&nbsp;&nbsp;
	<div class="col-md-3">
        <div class="form-group mb-3">
            
            <input type="date" class="form-control"  id="max_date"  required="">
			
			
			<div id="errors_message" class="text-danger"></div>
        </div>
		
    </div>
	
	&nbsp;&nbsp;&nbsp;
	
	&nbsp;&nbsp;&nbsp;
    <div class="form-group mb-3">
        <label></label>
        <button type="button" id="search_data" class="btn btn-primary">Search</button>
    </div>
	</div>
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
							<th>Property Title</th>
							<th>Property Image</th>
											
												<th>Property Price</th>
												<th>Property Total Day</th>
												
                          </tr>
                        </thead>
                        <tbody class="tblcontent">
                           <?php 
										$city = $rstate->query("select * from tbl_book  where book_status='Completed' order by id desc ");
										$i=0;
										while($row = $city->fetch_assoc())
										{
											$i = $i + 1;
											?>
											<tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $row['prop_title'];?>
                                                </td>
												
                                                <td class="align-middle">
                                                   <img src="<?php echo $row['prop_img']; ?>" width="70" height="80"/>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $row['prop_price'].$set['currency'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['total_day'].' Days';?>
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
    <?php 
require 'include/footer.php';
?>
<script>

$(document).ready(function () {
    $("#search_data").click(function () {
        var selectedDate = $("#min_date").val();
        var selectedDates = $("#max_date").val();
		

        var errorElement = $("#error_message");
        var errorElements = $("#errors_message");
		

        if (selectedDate === "" && selectedDates === "") {
            errorElement.text("Please select a date.");
            errorElements.text("Please select a date.");
			
        } else if (selectedDate !== "" && selectedDates === "") {
            errorElements.text("Please select a date.");
            errorElement.text("");
        } else if (selectedDate === "" && selectedDates !== "") {
            errorElement.text("Please select a date.");
            errorElements.text("");
        } else if (new Date(selectedDate) > new Date(selectedDates)) {
            errorElement.text("Start date cannot be greater than end date.");
            errorElements.text("Start date cannot be greater than end date.");
        } else {
            errorElement.text(""); // Clear the error message.
            errorElements.text("");
			

            // Continue with your search logic here as the date is selected.
            // You can use the 'selectedDate' variable for further processing.
            $.ajax({
                type: "POST",
                url: "getsales.php",
                data: {
                    start_date: selectedDate,
                    end_date: selectedDates
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