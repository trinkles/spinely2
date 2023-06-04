import React from 'react';
import { Link } from 'react-router-dom';
import './calibration.css';

const Calibration = () => {
  return (
    <div>
      <h1 className="title">Prepare</h1>
      <div className="instructions">
        Let's calibrate the device while you are sitting straight up.<br/>
        Please make sure to place the sensors correctly before we begin.
      </div>
      <Link to="/configure">
        <button className="ready-button">Ready!</button>
      </Link>
    </div>
  );
};

export default Calibration;
