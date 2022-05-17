<div id="loading-bar-spinner" class="spinner"><div class="spinner-icon"></div></div>
<div class="container-register" id="container-register">
	<div class="form-container sign-up-container">
		<form id="registerForm">
			<h1>Create Account</h1>

			<span>or use your email for registration</span>
			<input type="text" placeholder="First Name" name="fname" id="fname"/>
			<input type="text" placeholder="Last Name" name="lname" id="lname"/>
			<input type="email" placeholder="Email" name="email" id="email"/>
			<input type="password" placeholder="Password" name="psw" id="psw"/>
      <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat"/>
			<button class="registerbtn" id="pluginRegister" data-url="<?php echo site_url('/'); ?>">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="#" id="loginForm">
			<h1>Sign in</h1>

			<span>or use your account</span>
			<input type="email" placeholder="Email" name="email" id="email"/>
			<input type="password" placeholder="Password" name="psw" id="psw" required/>
			<!-- <a href="#">Forgot your password?</a> -->
			<button id="pluginLogin" data-url="<?php echo site_url('/'); ?>">Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>

