<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/admin/services/CommentService.php';

// Processa ações de comentários
$message = '';
$messageType = '';

if ($_POST) {
    // Se a ação for setada, processa a ação
    if (isset($_POST['action'])) {
        $commentService = new CommentService();
        
        // Se a ação for adicionar comentário e o usuário estiver logado, adiciona o comentário
        if ($_POST['action'] == 'add_comment' && isUserLoggedIn()) {
            $post_id = $_POST['post_id'];
            $comentario = trim($_POST['comentario']);
            
            // Se o comentário não está vazio, adiciona o comentário
            if (!empty($comentario)) {
                if ($commentService->createComment($post_id, getCurrentUserId(), $comentario)) {
                    $message = 'Comentário adicionado com sucesso!';
                    $messageType = 'success';
                } else {
                    $message = 'Erro ao adicionar comentário.';
                    $messageType = 'error';
                }
            } else {
                $message = 'O comentário não pode estar vazio.';
                $messageType = 'error';
            }
        // Se a ação for like, adiciona um like
        } elseif ($_POST['action'] == 'like') {
            $comment_id = $_POST['comment_id'];
            $commentService->addLike($comment_id);
        // Se a ação for dislike, adiciona um dislike
        } elseif ($_POST['action'] == 'dislike') {
            $comment_id = $_POST['comment_id'];
            $commentService->addDislike($comment_id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post - Meu Blog</title>
    <!-- Importa o Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header público, com navegação e login -->
    <header class="bg-white border-b border-gray-300">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Biblioteca</h1>
                <nav class="space-x-4">
                    <a href="blog.php" class="text-gray-600">Home</a>
                    <!-- Se o usuário estiver logado, exibe o nome do usuário e o link para sair -->
                    <?php if (isUserLoggedIn()): ?>
                        <span class="text-gray-600">Olá, <?php echo htmlspecialchars(getCurrentUserName()); ?>!</span>
                        <a href="admin/logout.php" class="text-gray-600">Sair</a>
                        <!-- Se o usuário for admin, exibe o link para a página de administração -->
                        <a href="admin/index.php" class="bg-gray-200 text-gray-800 px-3 py-1 border border-gray-300">Admin</a>
                    <?php else: ?>
                        <a href="admin/pages/register.php" class="text-gray-600">Cadastro</a>
                        <a href="admin/pages/login.php" class="bg-gray-200 text-gray-800 px-3 py-1 border border-gray-300">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <?php
        // Busca o post pelo ID
        require_once __DIR__ . '/admin/database/Conexao.php';
        
        $post_id = $_GET['id'] ?? 0;
        
        if (!$post_id) {
            echo '<div class="text-center py-12">';
            echo '<h2 class="text-xl font-bold text-gray-800 mb-4">Post não encontrado</h2>';
            echo '<a href="blog.php" class="text-gray-800">Voltar ao Blog</a>';
            echo '</div>';
            exit;
        }
        
        $pdo = Conexao::getConexao();
        
        // Busca o post com categoria e autor
        $sql = "SELECT p.*, u.nome as autor_nome, c.titulo as categoria_titulo 
                FROM posts p 
                LEFT JOIN usuarios u ON p.user_id = u.id 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();
        
        if (!$post) {
            echo '<div class="text-center py-12">';
            echo '<h2 class="text-xl font-bold text-gray-800 mb-4">Post não encontrado</h2>';
            echo '<a href="blog.php" class="text-gray-800">Voltar ao Blog</a>';
            echo '</div>';
            exit;
        }
        ?>

        <!-- Navegação -->
        <div class="mb-4">
            <a href="blog.php" class="text-gray-800">← Voltar ao Blog</a>
        </div>

        <!-- Mensagem de feedback -->
        <?php if ($message): ?>
            <div class="mb-4 p-3 border border-gray-300 <?php echo $messageType == 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Post -->
        <article class="bg-white p-6 border border-gray-300 mb-6">
            <!-- Meta informações -->
            <div class="text-sm text-gray-600 mb-4">
                <span class="bg-gray-200 text-gray-800 px-2 py-1 text-xs border border-gray-300">
                    <?php echo htmlspecialchars($post['categoria_titulo'] ?? 'Sem categoria'); ?>
                </span>
                <span class="mx-2">•</span>
                <span>Por <?php echo htmlspecialchars($post['autor_nome'] ?? 'Autor'); ?></span>
                <span class="mx-2">•</span>
                <span><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
            </div>

            <!-- Título -->
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <?php echo htmlspecialchars($post['titulo']); ?>
            </h1>

            <!-- Descrição -->
            <div class="text-lg text-gray-600 mb-6 p-4 bg-gray-100 border-l-4 border-gray-300">
                <?php echo htmlspecialchars($post['descricao']); ?>
            </div>

            <!-- Conteúdo -->
            <div class="text-gray-800">
                <?php echo nl2br(htmlspecialchars($post['post_path'])); ?>
            </div>

            <!-- Footer do post -->
            <div class="mt-6 pt-4 border-t border-gray-300">
                <div class="text-sm text-gray-600">
                    Publicado em <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                    <?php if ($post['updated_at'] != $post['created_at']): ?>
                        • Atualizado em <?php echo date('d/m/Y', strtotime($post['updated_at'])); ?>
                    <?php endif; ?>
                </div>
                
                <a href="blog.php" class="text-gray-800 text-sm">Ver mais posts</a>
            </div>
        </article>

        <!-- Seção de Comentários -->
        <div class="bg-white p-6 border border-gray-300">
            <?php
            $commentService = new CommentService();
            $comentarios = $commentService->getCommentsByPost($post_id);
            $totalComentarios = count($comentarios);
            ?>
            
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                Comentários (<?php echo $totalComentarios; ?>)
            </h3>

            <!-- Formulário de novo comentário -->
            <?php if (isUserLoggedIn()): ?>
                <div class="mb-6 p-4 bg-gray-100 border border-gray-300">
                    <h4 class="text-lg font-bold text-gray-800 mb-3">Deixe seu comentário</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_comment">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <div class="mb-4">
                            <textarea name="comentario" required rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300"
                                    placeholder="Escreva seu comentário aqui..."></textarea>
                        </div>
                        <button type="submit" 
                                class="bg-gray-200 text-gray-800 px-4 py-2 border border-gray-300">
                            Publicar Comentário
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="mb-6 p-4 bg-gray-100 border border-gray-300 text-center">
                    <p class="text-gray-600 mb-3">Você precisa estar logado para comentar.</p>
                    <div class="space-x-4">
                        <a href="admin/pages/login.php" 
                           class="bg-gray-200 text-gray-800 px-3 py-1 border border-gray-300">
                            Fazer Login
                        </a>
                        <a href="admin/pages/register.php" 
                           class="bg-gray-200 text-gray-800 px-3 py-1 border border-gray-300">
                            Cadastrar-se
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lista de comentários -->
            <?php if (empty($comentarios)): ?>
                <div class="text-center py-8 text-gray-600">
                    <p>Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                </div>
            <?php else: ?>
                <div>
                    <!-- Para cada comentário, exibe uma linha na tabela -->
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="border-b border-gray-300 pb-4 mb-4">
                            <div class="mb-2">
                                <!-- Exibe o nome e sobrenome do usuário -->
                                <h5 class="font-bold text-gray-800">
                                    <?php echo htmlspecialchars($comentario['nome'] . ' ' . $comentario['sobrenome']); ?>
                                </h5>
                                <span class="text-sm text-gray-600">
                                    <?php echo date('d/m/Y H:i', strtotime($comentario['created_at'])); ?>
                                </span>
                            </div>

                            <!-- Exibe o comentário -->
                            <p class="text-gray-800 mb-3">
                                <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?>
                            </p>
                            
                            <!-- Botões de like/dislike -->
                            <div class="text-sm">
                                <!-- Botão de like -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="like">
                                    <input type="hidden" name="comment_id" value="<?php echo $comentario['id']; ?>">
                                    <button type="submit" class="text-green-600">
                                        👍 <?php echo $comentario['likes']; ?>
                                    </button>
                                </form>
                                
                                <!-- Botão de dislike -->
                                <form method="POST" class="inline ml-4">
                                    <input type="hidden" name="action" value="dislike">
                                    <input type="hidden" name="comment_id" value="<?php echo $comentario['id']; ?>">
                                    <button type="submit" class="text-red-600">
                                        👎 <?php echo $comentario['dislikes']; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer público -->
    <footer class="bg-gray-800 text-white mt-8">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center">
            <p>&copy; 2024 Meu Blog - Projeto Escolar</p>
        </div>
    </footer>
</body>
</html>