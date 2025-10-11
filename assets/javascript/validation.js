document.addEventListener("DOMContentLoaded", () => {
    const loginValidation = new JustValidate("#login-form");

    loginValidation
        .addField("#role", [
            {
                rule: 'required',
                errorMessage: "Please select your account type"
            }
        ])
        .addField("#email", [
            {
                rule: 'required',
                errorMessage: "Email is required"
            },
            {
                rule: 'email',
                errorMessage: "Invalid email"
            }
        ])
        .addField("#password", [
            {
                rule: 'required',
                errorMessage: "Password is required"
            },
            {
                rule: 'minLength',
                value: 8,
                errorMessage: "Password is at least 8 characters"
            },
            // {
            //     validator: (value) => {
            //         const hasUpperCase = /[A-Z]/.test(value);
            //         return hasUpperCase;
            //     },
            //     errorMessage: "Password has at least one upper case character"
            // },
            {
                validator: (value) => {
                    const hasLowerCase = /[a-z]/.test(value);
                    return hasLowerCase;
                },
                errorMessage: "Password has at least one lower case character"
            },
            {
                validator: (value) => {
                    const hasNumber = /[0-9]/.test(value);
                    return hasNumber;
                },
                errorMessage: "Password has at least one number"
            }
        ])
        .onSuccess((event) => {
        const formData = new FormData(event.target);
        console.log("Fade-out triggered");

        fetch('/assets/server/login-process.php', {
          method: 'POST',
          body: formData,
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const card = document.getElementById('login-card');
            const welcome = document.getElementById('welcome-screen');

            document.getElementById('welcome-screen').classList.add('show');

            // Fade out the login card
            card.style.transition = 'opacity 0.5s ease';
            card.style.opacity = '0';

            // After card fades, show welcome screen
            setTimeout(() => {
              welcome.style.display = 'flex';
              welcome.style.opacity = '0';
              welcome.style.transform = 'scale(0.8)';
              welcome.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
              // Trigger fade-in and zoom
              setTimeout(() => {
                welcome.style.opacity = '1';
                welcome.style.transform = 'scale(1)';
              }, 50);
            });
        
            // Redirect after animation
            setTimeout(() => {
              window.location.href = data.redirect || "home.php";
            }, 2000);
            } else {
                showError(data.message);
            }
        })
        .catch(() => {
          showError("Something went wrong, please try again.");
        });
      });

    function showError(message) {
      const existing = document.querySelector('.error-box');
      if (existing) existing.remove();

      const errorBox = document.createElement('div');
      errorBox.className = 'error-box';
      errorBox.textContent = message;
      document.querySelector('#login-form').appendChild(errorBox);
    }
});


