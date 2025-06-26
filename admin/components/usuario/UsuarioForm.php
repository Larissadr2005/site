<?php
require_once __DIR__ . '/../../services/UserService.php';

$userService = new UserService();

$nome = '';
$sobrenome = '';
$email = '';
$usuario_id = null;
$erro = '';
$isEdit = false;

// Se está editando, carrega os dados do usuário
if (isset($_GET['id'])) {
    // Pega o id do usuário
    $usuario_id = $_GET['id'];
    // Pega o usuário pelo id
    $usuario = $userService->getUserById($usuario_id);
    if ($usuario) {
        // Pega os dados do usuário
        $nome = $usuario->getNome();
        $sobrenome = $usuario->getSobrenome();
        $email = $usuario->getEmail();
        $isEdit = true;
    }
}

// Processa o formulário se o usuário enviou o formulário
if ($_POST) {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    // Validações básicas
    if (empty($nome) || empty($sobrenome) || empty($email)) {
        $erro = 'Nome, sobrenome e email são obrigatórios!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } elseif (!$isEdit && empty($senha)) {
        $erro = 'Senha é obrigatória para novos usuários!';
    } elseif (!empty($senha) && $senha !== $confirma_senha) {
        $erro = 'As senhas não coincidem!';
    } elseif (!empty($senha) && strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } else {
        // Verifica se o email já existe (exceto para o próprio usuário na edição)
        require_once __DIR__ . '/../../database/Conexao.php';
        $pdo = Conexao::getConexao();
        
        // Se está editando, verifica se o email já existe (exceto para o próprio usuário)
        if ($isEdit) {
            // Busca o email do usuário
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $usuario_id]);
        } else {
            // Busca o email do usuário
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
        }
        

        if ($stmt->fetchColumn() > 0) {
            $erro = 'Este email já está cadastrado!';
        } else {
            if ($isEdit) {
                // Atualizar usuário
                if (empty($senha)) {
                    // Sem alterar senha
                    if ($userService->updateUser($usuario_id, $nome, $sobrenome, $email)) {
                        header('Location: ?page=usuarios&msg=updated');
                        exit();
                    } else {
                        $erro = 'Erro ao atualizar o usuário!';
                    }
                } else {
                    // Com nova senha
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    // Atualiza o usuário
                    $sql = "UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, password = ?, updated_at = NOW() WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    // Atualiza o usuário
                    if ($stmt->execute([$nome, $sobrenome, $email, $senha_hash, $usuario_id])) {
                        // Se o usuário foi atualizado, redireciona para a página de usuários com uma mensagem de sucesso
                        header('Location: ?page=usuarios&msg=updated');
                        exit();
                    } else {
                        // Se o usuário não foi atualizado, redireciona para a página de usuários com uma mensagem de erro
                        $erro = 'Erro ao atualizar o usuário!';
                    }
                }
            } else {
                // Criar novo usuário
                if ($userService->createUser($nome, $sobrenome, $email, $senha)) {
                    // Se o usuário foi criado, redireciona para a página de usuários com uma mensagem de sucesso
                    header('Location: ?page=usuarios&msg=created');
                    exit();
                } else {
                    // Se o usuário não foi criado, redireciona para a página de usuários com uma mensagem de erro
                    $erro = 'Erro ao criar o usuário!';
                }
            }
        }
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Título e botão de voltar -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <?php echo $isEdit ? 'Editar Usuário' : 'Novo Usuário'; ?>
        </h2>
        <a href="?page=usuarios" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Voltar
        </a>
    </div>

    <!-- Mensagem de erro -->
    <?php if ($erro): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de usuário -->
    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Input de nome -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nome:
                </label>
                <input type="text" name="nome" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                       value="<?php echo htmlspecialchars($nome); ?>"
                       placeholder="Digite o nome">
            </div>

            <!-- Input de sobrenome -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Sobrenome:
                </label>
                <input type="text" name="sobrenome" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                       value="<?php echo htmlspecialchars($sobrenome); ?>"
                       placeholder="Digite o sobrenome">
            </div>
        </div>

        <!-- Input de email -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Email:
            </label>
            <input type="email" name="email" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                   value="<?php echo htmlspecialchars($email); ?>"
                   placeholder="Digite o email">
        </div>

        <!-- Input de senha -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <?php echo $isEdit ? 'Nova Senha (opcional):' : 'Senha:'; ?>
                </label>
                <input type="password" name="senha" <?php echo $isEdit ? '' : 'required'; ?>
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                       minlength="6"
                       placeholder="<?php echo $isEdit ? 'Deixe vazio para manter a atual' : 'Digite a senha'; ?>">
                <?php if (!$isEdit): ?>
                    <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Confirmar Senha:
                </label>
                <input type="password" name="confirma_senha" <?php echo $isEdit ? '' : 'required'; ?>
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                       minlength="6"
                       placeholder="Confirme a senha">
            </div>
        </div>

        
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                <?php echo $isEdit ? 'Atualizar Usuário' : 'Criar Usuário'; ?>
            </button>
            <a href="?page=usuarios" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </a>
        </div>
    </form>
</div>
