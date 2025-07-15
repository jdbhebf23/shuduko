<?php
session_start();
$host = 'localhost';
$port = '3306';
$user = 'root';
$pass = '';
$dbname = 'final';
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $name;
                header("Location: index.php");
                exit();
            } else {
                $error = 'Incorrect username or password';
            }
        } else {
            $error = 'Incorrect username or password';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #181c24;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg-blur {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: url('image/bg.jpg') center center/cover no-repeat;
            filter: blur(1px) brightness(0.7);
        }

        .login-container {
            background: rgba(24,28,36,0.75);
            border-radius: 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 48px 36px 36px 36px;
            width: 370px;
            max-width: 95vw;
            text-align: center;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .error {
            background: #2d2323;
            color: #ff6b6b;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 22px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #b0b8c1;
            font-weight: 700;
            font-size: 1.01rem;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 13px 14px;
            border: 1.5px solid #444857;
            border-radius: 12px;
            font-size: 1.08rem;
            background: #23272f;
            color: #fff;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #fff;
            background: #23272f;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 16px;
            font-size: 1.13rem;
            font-weight: 700;
            cursor: pointer;
            background: #11131a;
            color: #fff;
            margin-bottom: 16px;
            transition: background 0.2s, transform 0.2s;
            text-decoration: none;
            display: block;
        }
        .btn + .btn {
            margin-top: 10px;
        }

        .btn:hover {
            background: #31344b;
            transform: translateY(-2px) scale(1.03);
        }

        .close-btn {
            position: absolute;
            top: 18px;
            right: 22px;
            background: none;
            border: none;
            font-size: 22px;
            color: #b0b8c1;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: #23272f;
            color: #fff;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 24px 8px;
            }
        }
    </style>
</head>
<body class="home">
    <div class="bg-blur"></div>
    
    <div class="login-container">
        <button class="close-btn">×</button>
        
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
            <a href="register.php" class="btn">Signup</a>
        </form>
    </div>
</body>
</html>