import './styleindex.css';
import { Link } from 'react-router-dom';

const Dashboard = () => {
  return (
    <div>
      <header>
        <div className="logosec">
          <div className="logo">Spinely</div>
          <img
            src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
            className="icn menuicn"
            id="menuicn"
            alt="menu-icon"
          />
        </div>
      </header>
      <div className="main-container">
        <div className="navcontainer">
          <nav className="nav">
            <div className="nav-upper-options">
              <div className="nav-option option1">
                <Link to="/">
                  <h3>Dashboard</h3>
                </Link>
              </div>

              <div className="option2 nav-option">
                <Link to="/history">
                  <h3>History</h3>
                </Link>
              </div>

              <div className="option2 nav-option">
                <Link to="/calibration">
                  <h3>Calibrate</h3>
                </Link>
              </div>

              <div className="nav-option option6">
                <Link to="/settings">
                  <h3>Settings</h3>
                </Link>
              </div>

              <div className="nav-option logout">
                <Link to="/login">
                  <h3>Logout</h3>
                </Link>
              </div>
            </div>
          </nav>
        </div>

        <div className="main">
          <div className="report-container">
            <div className="report-header">
              <h1 className="recent-Articles">Report</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
