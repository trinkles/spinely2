import React from 'react';
import { Link } from 'react-router-dom';
import './configure.css';

const Configure = () => {
  return (
    <div>
      <h1>Configure</h1>
      <p>Choose the duration of the calibration:</p>
      <div className="duration-buttons">
      <Link to="/calibrate5">
        <button>5s</button>
        </Link>
        <Link to="/calibrate">
          <button>10s</button>
        </Link>
        <span className="note">Recommended for accurate results</span>
      </div>
    </div>
  );
};

export default Configure;
