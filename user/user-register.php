<form class="custom-plugin-form" id="registerForm">
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>

    <label for="email"><b>First Name</b></label>
    <input type="text" placeholder="First Name" name="fname" id="fname" required>
    <label for="email"><b>Last Name</b></label>
    <input type="text" placeholder="Last Name" name="lname" id="lname" required>
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
    <button type="button" class="registerbtn" id="pluginRegister" data-url="<?php echo site_url('/'); ?>">Register</button>
  </div>

  <div class="container signin">
    <p>Already have an account? <a href=<?php echo site_url("/user-login") ?>>Sign in</a>.</p>
  </div>
</form>
