import React from 'react';
import { useNavigate } from 'react-router-dom';
import './finish.css';

const Finish = () => {
  const navigate = useNavigate();

  const handleSeeResults = () => {
    navigate('/calibrationResults');
  };

  const handleYourPostures = () => {
    navigate('/yourPostures');
  };

  return (
    <div>
      <title>Finish</title>
      <h1>Finish</h1>
      <div className="done-text">Done!</div>
      <div className="small-text">See results</div>
      <div className="action-buttons">
        <button className="see-results-button" onClick={handleSeeResults}>
          See Results
        </button>
        <button className="your-postures-button" onClick={handleYourPostures}>
          Your Postures
        </button>
      </div>
    </div>
  );
};

export default Finish;
