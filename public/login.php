<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($pdo)) {
        $error = "Kesalahan koneksi database.";
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            $error = "Username atau Password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Kontak | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f5f5dc; 
            font-family: 'Inter', sans-serif;
        }

        .image-area {
            background-image: url('gambar.jpeg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 md:p-8">
    
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Selamat Datang</h1>
            <p class="text-gray-600 mb-8">Silakan masukkan detail akun Anda untuk melanjutkan.</p>

            <form method="POST">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <span class="block sm:inline"><?= $error ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                           id="username" 
                           type="text" 
                           placeholder="Masukkan Username Anda" 
                           name="username" 
                           required>
                </div>
                
                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                           id="password" 
                           type="password" 
                           placeholder="********" 
                           name="password" 
                           required>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline w-full shadow-md transition duration-200" type="submit">
                        Login ke Sistem
                    </button>
                </div>
            </form>
        </div>

        <div class="hidden md:block md:w-1/2 image-area rounded-r-xl">
        </div>
    </div>
</body>
</html>

