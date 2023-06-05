import React from 'react';
import './style.css';

const Login = () => {
  const handleSignup = () => {
    window.location.href = "signup";
  };

  return (
    <div>
      <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="style.css" />
        <title>Spinely</title>
      </head>
      <body>
        <div className="wrapper">
          <div className="switchSignLog">
            <div className="actionBtns">
              <button className="actionBtn signupBtn" onClick={handleSignup}>Sign Up</button>
              <button className="actionBtn">Login</button>
              <button className="moveBtn">Login</button>
            </div>
          </div>
          <div className="modalForm">
            <form method="post">
              <h1>Access Your Account</h1>
              <p className="caption">Enter your login credentials</p>
              <div className="inputGroup">
                <input type="text" name="username" placeholder="Username" autoComplete="off" />
              </div>
              <div className="inputGroup">
                <input type="password" name="password" placeholder="Password" autoComplete="off" />
              </div>
              <input type="submit" className="submitBtn" value="Login" />
              <p className="switch-cta">No Account Yet? <a href="signup.php" className="cta-text">Sign Up</a></p>
            </form>
          </div>
        </div>
      </body>
    </div>
  );
};

export default Login;
