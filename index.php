<?php
// Redirect to dashboard if already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: url('welcome.png') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(252, 245, 238, 0.15);
            pointer-events: none;
            z-index: 0;
        }

        .welcome-container {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .welcome-title {
            font-family: 'Poppins', sans-serif;
            font-size: 52px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 10px;
            margin-top: -140px;

            background: linear-gradient(135deg, #EE6983 0%, #850E35 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;

            letter-spacing: 2px;
            text-transform: uppercase;

            text-shadow: 0 4px 15px rgba(133, 14, 53, 0.25);
            
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            animation: fadeInUp 1s ease 0.3s both;

        }
        
        .welcome-title:hover {
            transform: translateY(-8px) scale(1.05);
        }

        .buttons-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.3s both;
            z-index: 1;
            margin-top: 350px;
        }

        .welcome-container {
    position: relative;
}

.welcome-title {
    position: absolute;
    top: 25%;
    transform: translateY(-50%);
}




        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-btn {
            padding: 20px 60px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            box-shadow: 0 12px 35px rgba(133, 14, 53, 0.4);
            position: relative;
            overflow: hidden;
            min-width: 200px;
            text-align: center;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-block;
        }

        .btn-login {
            background: linear-gradient(135deg, #EE6983 0%, #850E35 100%);
            color: #FCF5EE;
        }

        .btn-signup {
            background: #FCF5EE;
            color: #850E35;
            border: 4px solid #EE6983;
        }

        .welcome-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }

        .welcome-btn:hover::before {
            left: 100%;
        }

        .welcome-btn:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 20px 50px rgba(133, 14, 53, 0.5);
        }

        .btn-signup:hover {
            background: linear-gradient(135deg, #EE6983 0%, #850E35 100%);
            color: #FCF5EE;
            border-color: transparent;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #850E35 0%, #EE6983 100%);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 36px;
                margin-bottom: 40px;
            }
            .buttons-container {
                gap: 20px;
            }

            .welcome-btn {
                padding: 18px 50px;
                font-size: 18px;
                min-width: 180px;
            }
        }

        @media (max-width: 576px) {
            body {
                background-attachment: scroll;
            }

            .welcome-container {
                padding: 30px 15px;
            }

            .buttons-container {
                flex-direction: column;
                gap: 15px;
                width: 100%;
                max-width: 300px;
            }

            .welcome-btn {
                width: 100%;
                padding: 16px 40px;
                font-size: 16px;
                min-width: unset;
            }
        }

        @media (max-width: 400px) {
            .welcome-btn {
                padding: 14px 30px;
                font-size: 15px;
            }
        }

        /* Landscape orientation for mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .welcome-container {
                padding: 20px 15px;
            }

            .welcome-title {
                font-size: 32px;
                margin-bottom: 30px;
            }

            .buttons-container {
                gap: 15px;
            }

            .welcome-btn {
                padding: 12px 35px;
                font-size: 15px;
            }
        }

        /* High resolution displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            body {
                background-size: cover;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1 class="welcome-title">Library Management System</h1>

        <div class="buttons-container">
            <a href="login.php" class="welcome-btn btn-login">Sign In</a>
            <a href="signup.php" class="welcome-btn btn-signup">Sign Up</a>
        </div>
    </div>
</body>
</html>