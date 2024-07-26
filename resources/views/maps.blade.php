<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Popup Animation</title>
  <link rel="stylesheet" href="styles.css">
</head>
<style>
  body {
    font-family: Arial, sans-serif;
  }

  #popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    padding: 20px;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    z-index: 1000;
  }

  @keyframes bounceIn {
    0% {
      transform: translate(-50%, -50%) scale(0);
    }

    60% {
      transform: translate(-50%, -50%) scale(1.2);
    }

    80% {
      transform: translate(-50%, -50%) scale(0.95);
    }

    100% {
      transform: translate(-50%, -50%) scale(1);
    }
  }

  @keyframes bounceOut {
    0% {
      transform: translate(-50%, -50%) scale(1);
    }

    20% {
      transform: translate(-50%, -50%) scale(1.2);
    }

    100% {
      transform: translate(-50%, -50%) scale(0);
    }
  }

  #popup.show {
    animation: bounceIn 0.5s forwards;
  }

  #popup.hide {
    animation: bounceOut 0.5s forwards;
  }

  #popup.hidden {
    display: none;
  }

  #showPopupBtn,
  #closePopupBtn {
    cursor: pointer;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
  }

  #showPopupBtn:hover,
  #closePopupBtn:hover {
    background-color: #0056b3;
  }
</style>

<body>
  <button id="showPopupBtn">Show Popup</button>
  <div id="popup" class="popup hidden">
    <p>This is a popup!</p>
    <button id="closePopupBtn">Close</button>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      const showPopupBtn = document.getElementById('showPopupBtn');
      const closePopupBtn = document.getElementById('closePopupBtn');
      const popup = document.getElementById('popup');

      showPopupBtn.addEventListener('click', () => {
        popup.classList.remove('hidden');
        popup.classList.remove('hide');
        popup.classList.add('show');

        setTimeout(() => {
          popup.classList.remove('show');
          popup.classList.add('hide');
          popup.addEventListener('animationend', () => {
            popup.classList.add('hidden');
          }, {
            once: true
          });
        }, 5000);
      });

      closePopupBtn.addEventListener('click', () => {
        popup.classList.remove('show');
        popup.classList.add('hide');
        popup.addEventListener('animationend', () => {
          popup.classList.add('hidden');
        }, {
          once: true
        });
      });
    });
  </script>
</body>

</html>