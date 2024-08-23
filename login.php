<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./resources/style/login-style.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./js/LoginPage.js"></script>
</head>

<body>

    <?php if(!isset($_SESSION['error'])): ?>
    <div class="logo-container logo-container-animation">
        <img src="./resources/svg/logo.svg" alt="Logo" class="logo">

        <div class="circle"></div>
    </div>
    <?php endif; ?>

    <div id="form-container" class="login-form align-content-center">
        <div class="card login-card" style="border-radius: 20px;">
            <div class="card-logo">
                <img src="./resources/svg/logo.svg" alt="Logo" style="display: none;">
            </div>
            <div class="card-body">
                <form id="login-form" action="./api/process_login.php" method="POST">
                    <div class="mb-3">

                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control rounded-pill" id="email" placeholder="Inserisci email"
                            required autocomplete="on">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill" id="password"
                            placeholder="Inserisci password" required autocomplete="on">
                    </div>
                    <div class="d-grid">
                        <p id="login-result"></p>
                        <button id="login-button" type="submit" class="btn btn-primary rounded-pill">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>