<?php
session_start();

// Se já está logado, vai para o dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$erro = '';
$sucesso = '';

// Se enviou o formulário
if ($_POST) {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];
    
    // Validações básicas
    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha)) {
        $erro = 'Todos os campos são obrigatórios!';
    } elseif ($senha !== $confirma_senha) {
        $erro = 'As senhas não coincidem!';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } else {
        // Conecta no banco
        require_once __DIR__ . '/../database/Conexao.php';
        $pdo = Conexao::getConexao();
        
        // Verifica se o email já existe
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        if ($stmt->fetchColumn() > 0) {
            // Se o email já existe, redireciona para a página de cadastro com uma mensagem de erro
            $erro = 'Este email já está cadastrado!';
        } else {
            // Cria o usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            // Insere o usuário no banco de dados
            $sql = "INSERT INTO usuarios (nome, sobrenome, email, password, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$nome, $sobrenome, $email, $senha_hash])) {
                // Se o usuário foi criado, redireciona para a página de login com uma mensagem de sucesso
                $sucesso = 'Cadastro realizado com sucesso! Você já pode fazer login.';
            } else {
                // Se o usuário não foi criado, redireciona para a página de cadastro com uma mensagem de erro
                $erro = 'Erro ao criar o usuário. Tente novamente!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Meu Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Criar Conta</h1>
            <p class="text-gray-600">Cadastre-se para acessar o sistema</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Input de nome -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nome:
                </label>
                <input type="text" name="nome" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                       value="<?php echo $_POST['nome'] ?? ''; ?>">
            </div>
            
            <!-- Input de sobrenome -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Sobrenome:
                </label>
                <input type="text" name="sobrenome" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                       value="<?php echo $_POST['sobrenome'] ?? ''; ?>">
            </div>
            
            <!-- Input de email -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Email:
                </label>
                <input type="email" name="email" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                       value="<?php echo $_POST['email'] ?? ''; ?>">
            </div>
            
            <!-- Input de senha -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Senha:
                </label>
                <input type="password" name="senha" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                       minlength="6">
                <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
            </div>
            
            <!-- Input de confirmação de senha -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Confirmar Senha:
                </label>
                <input type="password" name="confirma_senha" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                       minlength="6">
            </div>
            
            <!-- Botão de cadastro -->
            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                Criar Conta
            </button>
        </form>
        
        <!-- Link de login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Já tem uma conta? 
                <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">
                    Fazer login
                </a>
            </p>
        </div>
        
        <!-- Link de voltar ao blog -->
        <div class="text-center mt-4">
            <a href="../../blog.php" class="text-gray-500 hover:text-gray-700 text-sm">
                ← Voltar ao Blog
            </a>
        </div>
    </div>
</body>
</html> 