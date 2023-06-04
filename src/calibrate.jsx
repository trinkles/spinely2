import React, { useState } from 'react';
import './calibrate.css';

const Calibrate = () => {
  const [timerActive, setTimerActive] = useState(false);
  const [timeRemaining, setTimeRemaining] = useState(2);

  const startTimer = () => {
    setTimerActive(true);
  };

  const formatTime = (time) => {
    const seconds = time.toString().padStart(2, '0');
    return `00:${seconds}`;
  };

  React.useEffect(() => {
    let interval;

    if (timerActive) {
      interval = setInterval(() => {
        setTimeRemaining((prevTime) => prevTime - 1);
      }, 1000);
    }

    if (timeRemaining <= 0) {
      clearInterval(interval);
      window.location.href = 'calibrationResults';
    }

    return () => {
      clearInterval(interval);
    };
  }, [timerActive, timeRemaining]);

  return (
    <div>
      <h1>Calibrate</h1>
      <button className="start-button" onClick={startTimer}>
        Start Calibrating
      </button>
      <div className="timer">Time Remaining: {formatTime(timeRemaining)}</div>
      <div className="message">Please don't move.</div>
    </div>
  );
};

export default Calibrate;
