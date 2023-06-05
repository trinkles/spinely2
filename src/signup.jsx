import React from 'react';
import './style.css';

const Signup = () => {
  const handleSignup = () => {
    window.location.href = "login";
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
              <button className="actionBtn" onClick={handleSignup}>Login</button>
              <button className="moveBtn">Sign Up</button>
            </div>
          </div>
          <div className="modalForm">
            <form method="post">
              <h1>Create an Account</h1>
              <p className="caption">Create an account to save your progress</p>
              <div className="inputGroup">
                <input type="text" name="fullname" placeholder="Full name" autoComplete="off" required />
              </div>
              <div className="inputGroup">
                <input type="email" name="email" placeholder="Email" autoComplete="off" required />
              </div>
              <div className="inputGroup">
                <input type="text" name="username" placeholder="Username" autoComplete="off" required />
              </div>
              <div className="inputGroup">
                <input type="password" name="password" placeholder="Password" autoComplete="off" required />
              </div>
              <input type="submit" className="submitBtn" value="Create an Account" />
              <p className="switch-cta">Already Have An Account? <a href="login.php" className="cta-text">Sign In</a></p>
            </form>
          </div>
        </div>
      </body>
    </div>
  );
};

export default Signup;
