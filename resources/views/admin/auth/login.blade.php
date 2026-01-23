<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="{{ asset('admin/assets/images/fav.png') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

  <!-- Optional: if FontAwesome is not already included in plugins.css -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->

  <style>
    :root{
      --bg1:#f4f7fb;
      --bg2:#eef2f7;
      --card:#ffffff;
      --text:#111827;
      --muted:#6b7280;
      --border:#e5e7eb;
      --shadow: 0 20px 50px rgba(17,24,39,.10);
      --brand:#22c55e;
      --brand2:#16a34a;
      --danger:#ef4444;
      --ring: 0 0 0 .25rem rgba(34,197,94,.18);
    }

    *{ box-sizing:border-box; }

    body{
      margin:0;
      min-height:100vh;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      color: var(--text);
      background:
        radial-gradient(900px 500px at 12% 18%, rgba(34,197,94,.14), transparent 55%),
        radial-gradient(800px 450px at 85% 20%, rgba(59,130,246,.10), transparent 55%),
        linear-gradient(135deg, var(--bg1), var(--bg2));
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 24px;
    }

    .auth-shell{
      width: 100%;
      max-width: 980px;
      display:grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 18px;
      align-items:stretch;
    }

    /* Left brand panel (hidden on small screens) */
    .auth-brand{
      background: linear-gradient(135deg, rgba(34,197,94,.16), rgba(34,197,94,.06));
      border: 1px solid rgba(34,197,94,.18);
      border-radius: 18px;
      box-shadow: var(--shadow);
      padding: 34px;
      position: relative;
      overflow:hidden;
    }

    .auth-brand::after{
      content:"";
      position:absolute;
      right:-120px;
      top:-140px;
      width: 360px;
      height: 360px;
      background: radial-gradient(circle at 30% 30%, rgba(34,197,94,.25), transparent 60%);
      transform: rotate(20deg);
    }

    .brand-title{
      display:flex;
      align-items:center;
      gap:12px;
      font-weight: 800;
      letter-spacing:-.4px;
      font-size: 20px;
      margin: 0 0 14px;
      color: #064e3b;
    }

    .brand-badge{
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: rgba(34,197,94,.15);
      border: 1px solid rgba(34,197,94,.20);
      color: #065f46;
      font-weight: 900;
      font-size: 18px;
      flex: 0 0 auto;
    }

    .brand-copy{
      color: #065f46;
      font-size: 14px;
      line-height: 1.55;
      margin: 0 0 18px;
      max-width: 48ch;
    }

    .brand-points{
      margin: 18px 0 0;
      padding: 0;
      list-style: none;
      display:grid;
      gap: 10px;
      color:#065f46;
      font-size: 13px;
    }

    .brand-points li{
      display:flex;
      gap: 10px;
      align-items:flex-start;
    }

    .dot{
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--brand);
      margin-top: 4px;
      flex: 0 0 auto;
      box-shadow: 0 0 0 6px rgba(34,197,94,.10);
    }

    /* Right login card */
    .login-card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow);
      padding: 28px;
    }

    .login-header{
      margin-bottom: 18px;
    }

    .login-title{
      margin: 0 0 6px;
      font-size: 22px;
      font-weight: 750;
      letter-spacing: -0.3px;
    }

    .login-subtitle{
      margin: 0;
      color: var(--muted);
      font-size: 13.5px;
      line-height: 1.4;
    }

    .field{
      margin-top: 14px;
    }

    label{
      display:block;
      font-size: 13px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
    }

    .control{
      position:relative;
    }

    .input{
      width:100%;
      height: 46px;
      border-radius: 12px;
      border: 1px solid var(--border);
      padding: 0 14px;
      font-size: 14px;
      outline: none;
      background: #fff;
      transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
    }

    .input:focus{
      border-color: rgba(34,197,94,.75);
      box-shadow: var(--ring);
    }

    .input.input-error{
      border-color: rgba(239,68,68,.85);
      background: rgba(239,68,68,.06);
      box-shadow: 0 0 0 .25rem rgba(239,68,68,.10);
    }

    .password-actions{
      position:absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      display:flex;
      align-items:center;
      gap: 8px;
    }

    .icon-btn{
      border:none;
      background: transparent;
      color: #6b7280;
      cursor: pointer;
      padding: 6px 8px;
      border-radius: 10px;
      transition: background .15s ease, color .15s ease;
    }

    .icon-btn:hover{
      background: #f3f4f6;
      color: #111827;
    }

    .error-text{
      margin-top: 8px;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--danger);
      min-height: 16px;
    }

    .loginError{
      text-align:center;
      margin-top: 12px;
      padding: 10px 12px;
      border-radius: 12px;
      background: rgba(239,68,68,.08);
      color: #991b1b;
      border: 1px solid rgba(239,68,68,.18);
      display:none;
      font-size: 13px;
      font-weight: 650;
    }

    .btn{
      width: 100%;
      height: 46px;
      margin-top: 16px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--brand), var(--brand2));
      color: white;
      font-weight: 700;
      font-size: 14.5px;
      cursor: pointer;
      transition: transform .08s ease, filter .15s ease, box-shadow .15s ease;
      box-shadow: 0 12px 25px rgba(34,197,94,.22);
    }

    .btn:active{ transform: translateY(1px); }
    .btn:hover{ filter: brightness(0.98); }

    .btn[disabled]{
      cursor: not-allowed;
      filter: grayscale(.2);
      opacity: .85;
    }

    .footer{
      margin-top: 14px;
      text-align:center;
      color: var(--muted);
      font-size: 12.5px;
    }

    /* Mobile responsiveness */
    @media (max-width: 920px){
      .auth-shell{ grid-template-columns: 1fr; max-width: 520px; }
      .auth-brand{ display:none; }
      .login-card{ padding: 24px; border-radius: 16px; }
    }
  </style>
</head>

<body>
  <div class="auth-shell">

    <section class="auth-brand" aria-hidden="true">
      <div class="brand-title">
        <div class="brand-badge">UF</div>
        Unique Foods Admin
      </div>
      <p class="brand-copy">
        Manage products, categories, and orders securely. Use your admin credentials to continue.
      </p>
      <ul class="brand-points">
        <li><span class="dot"></span><span>Clean dashboard experience across devices.</span></li>
        <li><span class="dot"></span><span>Better form feedback and error visibility.</span></li>
        <li><span class="dot"></span><span>Keyboard-friendly, accessible inputs.</span></li>
      </ul>
    </section>

    <main class="login-card">
      <header class="login-header">
        <h1 class="login-title">Admin Login</h1>
        <p class="login-subtitle">Sign in to access the dashboard.</p>
      </header>

      <form id="adminLoginForm" novalidate>
        <div class="field">
          <label for="email">Email address</label>
          <div class="control">
            <input id="email" class="input" type="email" name="email" placeholder="admin@example.com" autocomplete="username" />
          </div>
          <div class="error-text error-email"></div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="control">
            <input id="password" class="input" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" />
            <div class="password-actions">
              <button type="button" class="icon-btn" id="togglePassword" aria-label="Show password">
                Show
              </button>
            </div>
          </div>
          <div class="error-text error-password"></div>
        </div>

        <div class="loginError" id="loginError"></div>

        <button type="submit" class="btn" id="loginBtn">Sign in</button>

        <div class="footer">
          Secure access • Admin Panel
        </div>
      </form>
    </main>

  </div>

  <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>

  <script>
    // Show/Hide password
    $('#togglePassword').on('click', function () {
      const $pwd = $('#password');
      const isPwd = $pwd.attr('type') === 'password';
      $pwd.attr('type', isPwd ? 'text' : 'password');
      $(this).text(isPwd ? 'Hide' : 'Show');
      $(this).attr('aria-label', isPwd ? 'Hide password' : 'Show password');
    });

    $('#adminLoginForm').on('submit', function (e) {
      e.preventDefault();

      // Reset errors
      $('.error-email, .error-password').text('');
      $('#loginError').hide().text('');
      $('.input').removeClass('input-error');

      // Loading state
      const $btn = $('#loginBtn');
      const oldText = $btn.text();
      $btn.prop('disabled', true).text('Signing in...');

      $.ajax({
        url: "{{ route('admin.login.submit') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
          if (res.status) {
            window.location.href = res.redirect;
          } else {
            $('#loginError').show().text('Login failed. Please try again.');
          }
        },
        error: function (xhr) {
          // Validation errors
          if (xhr.status === 422 && xhr.responseJSON?.errors) {
            const errors = xhr.responseJSON.errors;

            if (errors.email) {
              $('#email').addClass('input-error');
              $('.error-email').text(errors.email[0]);
            }
            if (errors.password) {
              $('#password').addClass('input-error');
              $('.error-password').text(errors.password[0]);
            }
            return;
          }

          // Invalid credentials / server error
          if (xhr.responseJSON?.message) {
            $('#loginError').show().text(xhr.responseJSON.message);
          } else {
            $('#loginError').show().text('Something went wrong. Please try again.');
          }
        },
        complete: function () {
          $btn.prop('disabled', false).text(oldText);
        }
      });
    });

    // Remove error highlight on typing
    $('.input').on('input', function () {
      $(this).removeClass('input-error');
    });
  </script>
</body>
</html>
