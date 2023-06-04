const signup = () => {
	const signupBtn = document.querySelector(".actionBtn");
				
	signupBtn.addEventListener("click", ()=>{
		window.location.href = "login.jsx";
	})
	return(
		<div>
			<head>
				<meta charset="UTF-8"/>
				<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
				<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				<link rel="stylesheet" href="style.css"/>
				<title>Spinely</title>
			</head>
			<body>
				<div class="wrapper">
					<div class="switchSignLog">
						<div class="actionBtns">
							<button class="actionBtn">Login</button>
							<button class="moveBtn">Sign Up</button>
						</div>
					</div>
					<div class="modalForm">
						<form method="post">
							<h1>Create an Account</h1>
							<p class="caption">Create an account to save your progress</p>
							<div class="inputGroup">
								<input type="text" name="fullname" placeholder="Full name" autocomplete="off" required/>
							</div>
							<div class="inputGroup">
								<input type="email" name="email" placeholder="Email" autocomplete="off" required/>
							</div>
							<div class="inputGroup">
								<input type="text" name="username" placeholder="Username" autocomplete="off" required/>
							</div>
							<div class="inputGroup">
								<input type="password" name="password" placeholder="Password" autocomplete="off" required/>
							</div>
							<input type="submit" class="submitBtn" value="Create an Account"/>
							<p class="switch-cta">Already Have An Account? <a href="login.php" class="cta-text">Sign In</a></p>
						</form>
					</div>
				</div>
			</body>	
		</div>
	)
}
export default signup;