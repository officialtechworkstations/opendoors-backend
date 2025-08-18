<?php 
require 'include/main_head.php';
if(isset($_SESSION['restatename']))
{
	?>
	<script>
	window.location.href="dashboard.php";
	</script>
	<?php 
}
else 
{
}
?>
    <!-- Loader ends-->
    <!-- login page start-->
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card">
            <div>
              <div><a class="logo" href="#"><img class="img-fluid for-light" src="<?php echo $set['weblogo'];?>" alt="logo image"></a></div>
              <div class="login-main"> 
                <form class="theme-form">
                  <h2 class="text-center">Sign in to account</h2>
                  <p class="text-center">Enter your username & password to login</p>
                  <div class="form-group">
                    <label class="col-form-label">Username</label>
                    <input class="form-control" type="text" required="" name="username" placeholder="ZYZ">
					<input type="hidden" name="type" value="login"/>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="password" required="" placeholder="*********">
                      
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-form-label">Select User Type</label>
                   <select class="form-control" name="stype" required>
					<option value="">Select A Type</option>
					<option value="Admin">Admin</option>
					<option value="Staff">Staff</option>
					</select>
                  </div>
				  
				  
                  <div class="form-group mb-0">
                    
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit">Sign in                 </button>
                    </div>
                  </div>
                  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- latest jquery-->
     <?php 
require 'include/footer.php';
?>
    </div>
  </body>
</html>