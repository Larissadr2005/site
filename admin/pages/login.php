<?php
session_start();

// Se já está logado, vai para o dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$erro = '';

// Se enviou o formulário
if ($_POST) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Conecta no banco
    require_once __DIR__ . '/../database/Conexao.php';
    $pdo = Conexao::getConexao();
    
    // Busca o usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    // Verifica se encontrou o usuário e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['password'])) {
        // Login ok - salva na sessão
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['user_email'] = $usuario['email'];
        
        // Vai para o dashboard
        header('Location: ../index.php');
        exit();
    } else {
        $erro = 'Email ou senha incorretos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Meu Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> Biblioteca LA</h1>
            <p class="text-gray-600">Faça login para continuar</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="bg-red-100 text-red-700 px-4 py-3 mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Input de email -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Email:
                </label>
                <input type="email" name="email" required 
                       class="w-full px-3 py-2 border border-gray-300"
                       value="<?php echo $_POST['email'] ?? ''; ?>">
            </div>
            
            <!-- Input de senha -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Senha:
                </label>
                <input type="password" name="senha" required 
                       class="w-full px-3 py-2 border border-gray-300">
            </div>
            
            <!-- Botão de login -->
            <button type="submit" 
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4">
                Entrar
            </button>
        </form>
        
        <p class="text-center text-gray-600 mt-4">
            Login: admin@test.com<br>
            Senha: 123456
        </p>

        <div class="text-center mt-4 pt-4">
            <p class="text-sm text-gray-600">
                Não tem uma conta? 
                <a href="register.php" class="text-blue-600">
                    Cadastre-se
                </a>
            </p>
        </div>
    </div>
</body>
</html>