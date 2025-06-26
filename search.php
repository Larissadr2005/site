<?php
session_start();

require_once __DIR__ . '/admin/services/SearchService.php';

$searchService = new SearchService();
$query = trim($_GET['q'] ?? '');
$results = [];
$totalResults = 0;

if (!empty($query)) {
    // Para o frontend público, buscar apenas posts e comentários públicos
    $posts = $searchService->searchPosts($query);
    $comments = $searchService->searchComentarios($query);
    
    $results = [
        'posts' => $posts,
        'comments' => $comments
    ];
    
    $totalResults = count($posts) + count($comments);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca - Meu Blog</title>
    <!-- Importa o Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100"> <!-- Cor de fundo -->
    <!-- Header público, com navegação e login -->
    <header class="bg-blue-600 text-white p-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold"> <!-- Título do blog -->
                <a href="blog.php">📝 Meu Blog</a>
            </h1>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">🔍 Buscar Posts</h2>
            <!-- Formulário de busca -->
            <form method="GET" class="mb-6">
                <div class="flex gap-4">
                    <!-- Campo de busca -->
                    <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>"
                           placeholder="Digite sua busca..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                    <!-- Botão de busca -->
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Buscar</button>
                </div>
            </form>
            
            <!-- Se a busca não está vazia, exibe os resultados -->
            <?php if (!empty($query)): ?>
                <p class="mb-4">Resultados para: "<strong><?php echo htmlspecialchars($query); ?></strong>"</p>
                <!-- Se os posts não estão vazios, exibe os posts -->
                <?php if (!empty($results['posts'])): ?>
                    <div class="space-y-4">
                        <!-- Para cada post, exibe uma linha na tabela -->
                        <?php foreach ($results['posts'] as $post): ?>
                            <div class="border p-4 rounded-lg">
                                <!-- Exibe o título do post -->
                                <h3 class="text-lg font-semibold">
                                    <a href="post.php?id=<?php echo $post['id']; ?>" class="text-blue-600">
                                        <?php echo htmlspecialchars($post['titulo']); ?>
                                    </a>
                                </h3>
                                <!-- Exibe a descrição do post -->
                                <p class="text-gray-600 mt-2">
                                    <?php echo htmlspecialchars(substr($post['descricao'], 0, 150)); ?>...
                                </p>
                                <!-- Exibe a categoria e a data de criação do post -->
                                <div class="text-sm text-gray-500 mt-2">
                                    Categoria: <?php echo htmlspecialchars($post['categoria_nome']); ?> | 
                                    <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <!-- Se os posts estão vazios, exibe uma mensagem -->
                <?php else: ?>
                    <p class="text-gray-500">Nenhum post encontrado.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html> 