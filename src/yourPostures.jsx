import React from 'react';
import './yourPostures.css';

const YourPostures = () => {
  const handleSettingsChange = (value) => {
    if (value === "update") {
      window.location.href = "calibrate";
    } else if (value === "delete") {
      // Handle delete functionality
    }
  };

  return (
    <div>
      <title>Your Posture</title>
      <h1>Your Posture</h1>
      <div className="dashboard">
        {/* POSTURE 1 */}
        <div className="session-item">
          <div className="session-title">Posture 1</div>
          <div className="session-settings">
            <label htmlFor="posture-settings-1">Settings:</label>
            <select id="posture-settings-1" onChange={(e) => handleSettingsChange(e.target.value)}>
              <option value="">Select an option</option>
              <option value="update">Update Calibration</option>
              <option value="delete">Delete Recorded Session</option>
            </select>
          </div>
        </div>
        {/* ADD MORE SESSION ITEMS HERE */}
      </div>

      <button className="add-new-button" onClick={() => window.location.href = 'calibration'}>Add New Posture</button>
      <button className="back-home-button" onClick={() => window.location.href = './.'}>Back to Dashboard</button>
    </div>
  );
};

export default YourPostures;
