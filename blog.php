<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca LA- Compartilhando Conhecimento</title>
    <!-- Importa o Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header público -->
    <header class="bg-white border-b border-gray-300">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Biblioteca LA</h1>
                <nav class="space-x-4">
                    <a href="blog.php" class="text-gray-600">Home</a>
                    <?php 
                    // Verifica se o usuário está logado
                    require_once __DIR__ . '/session.php';
                    if (isUserLoggedIn()): ?>
                        <!-- Se o usuário está logado, exibe o nome do usuário e o link para sair -->
                        <span class="text-gray-600">Olá, <?php echo htmlspecialchars(getCurrentUserName()); ?>!</span>
                        <a href="admin/logout.php" class="text-gray-600">Sair</a>
                        <a href="admin/index.php" class="bg-blue-500 text-white px-3 py-1">Admin</a>
                    <?php else: ?>
                        <!-- Se o usuário não está logado, exibe o link para cadastro e login -->
                        <a href="admin/pages/register.php" class="text-gray-600">Cadastro</a>
                        <a href="admin/pages/login.php" class="bg-blue-500 text-white px-3 py-1">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
            
            <!-- Campo de Busca -->
            <div class="flex justify-center">
                <form method="GET" action="search.php" class="flex w-full max-w-md">
                    <input type="text" 
                           name="q" 
                           placeholder="Buscar posts..." 
                           class="flex-1 px-3 py-2 border border-gray-300">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white">
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <?php
        // Conecta no banco e busca os posts
        require_once __DIR__ . '/admin/database/Conexao.php';
        
        $pdo = Conexao::getConexao();
        
        // Filtro por categoria
        $categoria_filtro = $_GET['categoria'] ?? '';
        $where_categoria = $categoria_filtro ? "AND c.id = :categoria_id" : "";
        
        // Busca posts com categoria e autor
        $sql = "SELECT p.*, u.nome as autor_nome, u.sobrenome as autor_sobrenome, c.titulo as categoria_titulo, c.id as categoria_id
                FROM posts p 
                LEFT JOIN usuarios u ON p.user_id = u.id 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE 1=1 $where_categoria
                ORDER BY p.created_at DESC";
        $stmt = $pdo->prepare($sql);
        if ($categoria_filtro) {
            $stmt->bindParam(':categoria_id', $categoria_filtro, PDO::PARAM_INT);
        }
        $stmt->execute();
        $posts = $stmt->fetchAll();
        
        // Categorias com contagem de posts
        $categorias = $pdo->query("
            SELECT c.*, COUNT(p.id) as total_posts 
            FROM categorias c 
            LEFT JOIN posts p ON c.id = p.categoria_id 
            GROUP BY c.id 
            ORDER BY c.titulo
        ")->fetchAll();
        ?>

        <!-- Filtros por Categoria -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Posts</h2>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="blog.php" 
                   class="px-3 py-1 border <?php echo !$categoria_filtro ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300'; ?>">
                    Todas
                </a>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="blog.php?categoria=<?php echo $categoria['id']; ?>" 
                       class="px-3 py-1 border <?php echo $categoria_filtro == $categoria['id'] ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300'; ?>">
                        <?php echo htmlspecialchars($categoria['titulo']); ?> (<?php echo $categoria['total_posts']; ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Lista de Posts -->
        <?php if (empty($posts)): ?>
            <div class="text-center py-12 bg-white p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Nenhum post encontrado</h2>
                <p class="text-gray-600 mb-4">
                    <?php if ($categoria_filtro): ?>
                        Tente escolher outra categoria.
                    <?php else: ?>
                        O blog ainda não tem conteúdo publicado.
                    <?php endif; ?>
                </p>
                <?php if ($categoria_filtro): ?>
                    <a href="blog.php" class="bg-blue-500 text-white px-4 py-2">Ver Todos os Posts</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <article class="bg-white p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-sm text-gray-500">
                                <a href="blog.php?categoria=<?php echo $post['categoria_id']; ?>" 
                                   class="bg-blue-500 text-white px-2 py-1 text-xs">
                                    <?php echo htmlspecialchars($post['categoria_titulo'] ?? 'Sem categoria'); ?>
                                </a>
                                <span class="mx-2">•</span>
                                <span><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-800 mb-3">
                            <a href="post.php?id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['titulo']); ?>
                            </a>
                        </h2>
                        
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars(substr($post['descricao'], 0, 200)); ?>
                            <?php if (strlen($post['descricao']) > 200): ?>...<?php endif; ?>
                        </p>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                <span>Por <strong><?php echo htmlspecialchars($post['autor_nome'] . ' ' . ($post['autor_sobrenome'] ?? '')); ?></strong></span>
                            </div>
                            
                            <a href="post.php?id=<?php echo $post['id']; ?>" 
                               class="bg-blue-500 text-white px-4 py-2 text-sm">
                                Ler Post
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer público -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="text-center">
                <h3 class="text-lg font-bold mb-2">Biblioteca LA</h3>
                <p class="text-gray-300 text-sm mb-4">
                    Compartilhando conhecimento e experiências.
                </p>
                <div class="space-x-4 text-sm">
                    <a href="search.php" class="text-gray-300">Buscar</a>
                    <a href="admin/pages/login.php" class="text-gray-300">Admin</a>
                    <?php if (!isUserLoggedIn()): ?>
                        <a href="admin/pages/register.php" class="text-gray-300">Criar Conta</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; 2025 - Todos os direitos reservados BibliotecaLA
                </p>
            </div>
        </div>
    </footer>
</body>
</html>