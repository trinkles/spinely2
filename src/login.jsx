import './styleindex.css';
import { Link } from 'react-router-dom';

const Login = () => {
  return (
    <div>
      <head>
        {/* ...head content */}
      </head>
      <body>
        <div className="wrapper">
          <div className="switchSignLog">
            <div className="actionBtns">
              <button className="actionBtn signupBtn">Sign Up</button>
              <button className="actionBtn">Login</button>
              <button className="moveBtn">Login</button>
            </div>
          </div>
          <div className="modalForm">
            <form method="post">
              {/* form content */}
              <p className="switch-cta">
                No Account Yet? <Link to="/signup" className="cta-text">Sign Up</Link>
              </p>
            </form>
          </div>
        </div>
      </body>
    </div>
  );
};

export default Login;
