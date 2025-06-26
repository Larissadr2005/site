<?php
require_once __DIR__ . '/../../services/CommentService.php';

$commentService = new CommentService();
$comentarios = $commentService->getAllComments();

// Deletar comentário se solicitado
if (isset($_GET['delete'])) {
    // Pega o id do comentário
    $id = $_GET['delete'];
    // Deleta o comentário
    if ($commentService->deleteComment($id)) {
        // Se o comentário foi deletado, redireciona para a página de comentários com uma mensagem de sucesso
        header('Location: ?page=comentarios&msg=deleted');
        exit();
    } else {
        // Se o comentário não foi deletado, redireciona para a página de comentários com uma mensagem de erro
        header('Location: ?page=comentarios&msg=error_delete');
        exit();
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Comentários</h2>
        <div class="text-sm text-gray-600">
            Total: <?php echo count($comentarios); ?> comentários
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-4 p-4 rounded bg-green-100 text-green-700">
            <?php 
            if ($_GET['msg'] == 'deleted') echo 'Comentário deletado com sucesso!';
            if ($_GET['msg'] == 'error_delete') echo 'Erro ao deletar comentário!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($comentarios)): ?>
        <div class="text-center py-8 text-gray-500">
            <div class="text-6xl mb-4">💬</div>
            <p>Nenhum comentário encontrado.</p>
            <p class="text-sm mt-2">Os comentários aparecerão aqui quando os usuários começarem a interagir com os posts.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($comentarios as $comentario): ?>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Cabeçalho do comentário -->
                            <div class="flex items-center space-x-4 mb-3">
                                <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                    <?php echo strtoupper(substr($comentario['nome'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        <?php echo htmlspecialchars($comentario['nome'] . ' ' . $comentario['sobrenome']); ?>
                                    </h4>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <span>Em: 
                                            <strong><?php echo htmlspecialchars($comentario['post_titulo']); ?></strong>
                                        </span>
                                        <span>•</span>
                                        <span><?php echo date('d/m/Y H:i', strtotime($comentario['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Conteúdo do comentário -->
                            <div class="mb-3">
                                <p class="text-gray-700">
                                    <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?>
                                </p>
                            </div>

                            <!-- Estatísticas -->
                            <div class="flex items-center space-x-4 text-sm">
                                <div class="flex items-center space-x-1 text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                    </svg>
                                    <span><?php echo $comentario['likes']; ?> likes</span>
                                </div>
                                
                                <div class="flex items-center space-x-1 text-red-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.106-1.79l-.05-.025A4 4 0 0011.057 2H5.641a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                    </svg>
                                    <span><?php echo $comentario['dislikes']; ?> dislikes</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="../post.php?id=<?php echo $comentario['post_id']; ?>" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                               title="Ver post">
                                Ver Post
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="?page=comentarios&delete=<?php echo $comentario['id']; ?>" 
                               class="text-red-600 hover:text-red-800 text-sm font-medium"
                               onclick="return confirm('Tem certeza que deseja deletar este comentário?')"
                               title="Deletar comentário">
                                Deletar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginação simples (se necessário) -->
        <?php if (count($comentarios) > 10): ?>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Mostrando <?php echo count($comentarios); ?> comentários
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div> 