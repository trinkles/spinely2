
import './styleindex.css';
import { Link } from 'react-router-dom';

const settings = () => {
	return(
		<div>
			<head>
				<meta charset="UTF-8"/>
				<meta http-equiv="X-UA-Compatible"content="IE=edge"/>
				<meta name="viewport"content="width=device-width,initial-scale=1.0"/>
				<title>Spinely</title>
				<link rel="stylesheet"href="styleindex.css"/>
				<link rel="stylesheet"href="responsiveindex.css"/>
			</head>
		<body>

				<header>
					<div class="logosec">
						<div class="logo">Spinely</div>
						<img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"class="icn menuicn"id="menuicn"alt="menu-icon"/>
					</div>
				</header>
				<div className="main-container">
        <div className="navcontainer">
          <nav className="nav">
            <div className="nav-upper-options">
              <div className="nav-option option6">
                <Link to="/">
                  <h3>Dashboard</h3>
                </Link>
              </div>

              <div className="nav-option option6">
                <Link to="/history">
                  <h3>History</h3>
                </Link>
              </div>

              <div className="option2 nav-option">
                <Link to="/calibration">
                  <h3>Calibrate</h3>
                </Link>
              </div>

              <div className="nav-option option1">
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

				<div class="main">
					<div class="report-container">
						<div class="report-header">
							<h1 class="recent-Articles">Profile settings</h1>
						</div>
					</div>
				</div>
			</div>
			<script src="./index.js"></script>
		</body>
		</div>
	)
}
export default settings;
