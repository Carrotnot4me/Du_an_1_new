function switchToRegister() {
      document.getElementById('formArea').classList.add('active');
      const msg = document.querySelector('.custom-require-register');
      if (msg) msg.style.display = 'none';
    }

    function switchToLogin() {
      document.getElementById('formArea').classList.remove('active');
    }

    function showRegisterNotice() {
      const msg = document.querySelector('.custom-require-register');
      if (msg) msg.style.display = 'block';
    }