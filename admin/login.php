<?php
// File: admin/login.php
session_start();

// Kalau sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: indexadm.php');
    exit;
}

include '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = MD5($_POST['password']); // Enkripsi password dengan MD5
    
    $query = "SELECT * FROM admin_users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // Set session (tandai sebagai logged in supaya halaman admin mengizinkan akses)
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = isset($admin['id']) ? $admin['id'] : 1;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_nama'] = isset($admin['nama_lengkap']) ? $admin['nama_lengkap'] : $admin['username'];
        
        header('Location: indexadm.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - pesenhotel.com</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        
        .btn-back {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 10px
        }

        .btn-back:hover {
            transform: translateY(-2px);
        }
        
        .info-box strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🏨 Admin Panel</h1>
            <p>pesenhotel.com Management System</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                ❌ <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">👤 Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">
                Login
            </button>

            <button type="button" class="btn-back" onclick="window.location.href='../index.php'">
                Kembali ke Website
            </button>
        </form>
            
        </div>
    </div>
</body>
</html>