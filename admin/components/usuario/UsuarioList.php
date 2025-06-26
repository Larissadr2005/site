<?php
require_once __DIR__ . '/../../services/UserService.php';
require_once __DIR__ . '/../../services/EnderecoService.php';

$userService = new UserService();
$enderecoService = new EnderecoService();
$usuarios = $userService->getAllUsers();

// Deletar usuário se solicitado
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Não permite deletar o próprio usuário logado
    if ($id == $_SESSION['user_id']) {
        header('Location: ?page=usuarios&msg=error_self_delete');
        exit();
    }
    
    // Deleta o usuário
    if ($userService->deleteUser($id)) {
        // Se o usuário foi deletado, redireciona para a página de usuários com uma mensagem de sucesso
        header('Location: ?page=usuarios&msg=deleted');
        exit();
    } else {
        // Se o usuário não foi deletado, redireciona para a página de usuários com uma mensagem de erro
        header('Location: ?page=usuarios&msg=error_delete');
        exit();
    }
}
?>

<div>
    <!-- Título e botão de novo usuário -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2>Gerenciar Usuários</h2>
        <a href="?page=usuarios&action=create">Novo Usuário</a>
    </div>

    <!-- Mensagem de sucesso ou erro -->
    <?php if (isset($_GET['msg'])): ?>
        <div style="margin-bottom: 16px; padding: 8px; border: 1px solid #ccc; background: #f9f9f9;">
            <?php 
            if ($_GET['msg'] == 'created') echo 'Usuário criado com sucesso!';
            if ($_GET['msg'] == 'updated') echo 'Usuário atualizado com sucesso!';
            if ($_GET['msg'] == 'deleted') echo 'Usuário deletado com sucesso!';
            if ($_GET['msg'] == 'error_delete') echo 'Erro ao deletar usuário!';
            if ($_GET['msg'] == 'error_self_delete') echo 'Erro: Você não pode deletar sua própria conta!';
            ?>
        </div>
    <?php endif; ?>

    <!-- Se não houver usuários, exibe uma mensagem -->
    <?php if (empty($usuarios)): ?>
        <div style="text-align: center; padding: 32px; color: #888;">
            <p>Nenhum usuário encontrado.</p>
            <a href="?page=usuarios&action=create">Criar o primeiro usuário</a>
        </div>
    <?php else: ?>
        <!-- Se houver usuários, exibe uma tabela com os usuários -->
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Posts</th>
                    <th>Endereços</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Para cada usuário, exibe uma linha na tabela -->
                <?php foreach ($usuarios as $usuario): ?>
                    <?php 
                    $countPosts = $userService->countPostsByUser($usuario->getId()); 
                    $enderecos = $enderecoService->getEnderecosByUserId($usuario->getId());
                    $countEnderecos = count($enderecos);
                    ?>
                    <tr>
                        <!-- Exibe o nome e sobrenome do usuário -->
                        <td><?php echo htmlspecialchars($usuario->getNome() . ' ' . $usuario->getSobrenome()); ?></td>
                        <!-- Exibe o email do usuário -->
                        <td><?php echo htmlspecialchars($usuario->getEmail()); ?></td>
                        <!-- Exibe o número de posts do usuário -->
                        <td><?php echo $countPosts; ?> posts</td>
                        <td>
                            <!-- Exibe o número de endereços do usuário -->
                            <a href="?page=enderecos&action=view&user_id=<?php echo $usuario->getId(); ?>">
                                <?php echo $countEnderecos; ?> endereços
                            </a>
                        </td>
                        <td>
                            <!-- Exibe o status do usuário -->
                            <?php if ($usuario->getId() == $_SESSION['user_id']): ?>
                                Você (Logado)
                            <?php else: ?>
                                Ativo
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Exibe o link para editar o usuário -->
                            <a href="?page=usuarios&action=edit&id=<?php echo $usuario->getId(); ?>">Editar</a> |
                
                            <!-- Se o usuário não for o próprio usuário logado, exibe o link para deletar o usuário -->
                            <?php if ($usuario->getId() != $_SESSION['user_id']): ?>
                                <a href="?page=usuarios&delete=<?php echo $usuario->getId(); ?>" onclick="return confirm('Tem certeza que deseja deletar este usuário?')">Deletar</a>
                            <?php else: ?>
                                <!-- Se o usuário for o próprio usuário logado, exibe o link para deletar o usuário -->
                                <span style="color: #aaa;">Deletar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
