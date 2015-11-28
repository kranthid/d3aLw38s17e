<div class="container">    
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-info" >
			<div class="panel-heading">
				<div class="panel-title">Sign In</div>
				<div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
			</div>     

			<div style="padding-top:30px" class="panel-body" >

				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

				<form id="loginform" class="form-horizontal" role="form">

					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input id="login-username" type="text" class="form-control" name="username" value="" placeholder="userid" required>                                        
					</div>

					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input id="login-password" type="password" class="form-control" name="password" placeholder="password" required>
					</div>
					<div class="input-group">
						<div class="checkbox">
							<label>
								<input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
							</label>
						</div>
					</div>

					<div style="margin-top:10px" class="form-group">
						<!-- Button -->

						<div class="col-sm-12 controls">
							<button class="btn btn-large btn-primary" type="submit" name="btn-login">Sign in</button>
							<a id="btn-login" href="javascript:login();" class="btn btn-success">Login  </a>
							<a id="btn-fblogin" href="http://localhost/dealwebsite/api/login/facebook" class="btn btn-primary">Login with Facebook</a>

						</div>
					</div>


					<div class="form-group">
						<div class="col-md-12 control">
							<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
								Don't have an account! 
								<a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
									Sign Up Here
								</a>
							</div>
						</div>
					</div>    
				</form>
			</div>                     
		</div>  
	</div>
	<div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="panel-title">Sign Up</div>
				<div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
			</div>  
			<div class="panel-body" >
				<form id="signupform" class="form-horizontal" role="form">

					<div id="signupalert" style="display:none" class="alert alert-danger">
						<p>Error:</p>
						<span></span>
					</div>



					<div class="form-group">
						<label for="userid" class="col-md-3 control-label">User ID</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="userid" name="userid" placeholder="User ID" required>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Email</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="email" name="email" placeholder="Email Address" required>
						</div>
					</div>

					<div class="form-group">
						<label for="firstname" class="col-md-3 control-label">First Name</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="lastname" class="col-md-3 control-label">Last Name</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-md-3 control-label">Password</label>
						<div class="col-md-9">
							<input type="password" class="form-control" id="passwd" name="passwd" placeholder="Password" required>
						</div>
					</div>
					<div class="form-group">
						<label for="confirmpassword" class="col-md-3 control-label">Confirm Password</label>
						<div class="col-md-9">
							<input type="password" class="form-control" id="confpasswd" name="confpasswd" placeholder="Retype Password" required>
						</div>
					</div>

					<div class="form-group">
						<!-- Button -->                                        
						<div class="col-md-offset-3 col-md-9">
							<button id="btn-signup" type="button" class="btn btn-info" onclick="signup()"><i class="icon-hand-right"></i> &nbsp Sign Up</button>
							<span style="margin-left:8px;">or</span>  
						</div>
					</div>

					<div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">

						<div class="col-md-offset-3 col-md-9">
							<button id="btn-fbsignup" type="button" class="btn"><i class="icon-facebook"></i>Sign Up with Google</button>
						</div>                                           

					</div>
				</form>
			</div>
		</div>
	</div> 
</div>

<script type="text/javascript">
	function login(){
		var objData = {
			"user_id": $('#login-username').val(),
			"password": $('#login-password').val()
		};
		var Url = "http://localhost/dealwebsite/api/local/login";
		$.ajax({
			url : Url,
			type: "POST",
			data : JSON.stringify(objData),
			success: function(data, textStatus, jqXHR)
			{
				//{"userID":"123455", "oauthtoken":"0930dbe772ab0cf9"}
				var lo = JSON.parse(data);
				if(lo.userID.length > 1){
					localStorage.setItem("dealuser", lo);
					window.location.href ="?page=home";
				}else{
					alert("UserID/Password is incorrect.");
				}
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert(errorThrown);
			}
		});

	}

	function signup(){
		var objData = {
			"user_id": $('#userid').val(),
			"email": $('#email').val(),
			"firstname": $('#firstname').val(),
			"lastname": $('#lastname').val(),
			"passwd": $('#passwd').val()
		};
		var Url = "http://localhost/dealwebsite/api/local/register";
		$.ajax({
			url : Url,
			type: "POST",
			data : JSON.stringify(objData),
			success: function(data, textStatus, jqXHR)
			{
				var lo = JSON.parse(data);
				if(lo.userID.length > 1){
					localStorage.setItem("dealuser", lo);
					window.location.href ="?page=home";
				}else{
					alert(data);
				}
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert(errorThrown);
			}
		});
	}

</script>