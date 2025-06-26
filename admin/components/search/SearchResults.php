<?php
require_once __DIR__ . '/../../services/SearchService.php';

$searchService = new SearchService();
$query = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? 'all'; // all, posts, usuarios, categorias, comentarios, enderecos
$results = [];
$stats = [];
$totalResults = 0;

if (!empty($query)) {
    if ($type === 'all') {
        $results = $searchService->searchAll($query);
        $totalResults = $results['total'];
    } else {
        switch ($type) {
            case 'posts':
                $results['posts'] = $searchService->searchPosts($query);
                $totalResults = count($results['posts']);
                break;
            case 'usuarios':
                $results['usuarios'] = $searchService->searchUsuarios($query);
                $totalResults = count($results['usuarios']);
                break;
            case 'categorias':
                $results['categorias'] = $searchService->searchCategorias($query);
                $totalResults = count($results['categorias']);
                break;
            case 'comentarios':
                $results['comentarios'] = $searchService->searchComentarios($query);
                $totalResults = count($results['comentarios']);
                break;
            case 'enderecos':
                $results['enderecos'] = $searchService->searchEnderecos($query);
                $totalResults = count($results['enderecos']);
                break;
        }
    }
    
    $stats = $searchService->getSearchStats();
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Cabeçalho da Busca -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            🔍 Busca Avançada
        </h2>
        
        <!-- Formulário de Busca -->
        <form method="GET" class="space-y-4">
            <input type="hidden" name="page" value="search">
            
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="q" 
                           value="<?php echo htmlspecialchars($query); ?>"
                           placeholder="Digite sua busca aqui..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                           autocomplete="off"
                           id="searchInput">
                </div>
                
                <div class="md:w-48">
                    <select name="type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="all" <?php echo $type === 'all' ? 'selected' : ''; ?>>🌐 Todos</option>
                        <option value="posts" <?php echo $type === 'posts' ? 'selected' : ''; ?>>📝 Posts</option>
                        <option value="usuarios" <?php echo $type === 'usuarios' ? 'selected' : ''; ?>>👥 Usuários</option>
                        <option value="categorias" <?php echo $type === 'categorias' ? 'selected' : ''; ?>>📁 Categorias</option>
                        <option value="comentarios" <?php echo $type === 'comentarios' ? 'selected' : ''; ?>>💬 Comentários</option>
                        <option value="enderecos" <?php echo $type === 'enderecos' ? 'selected' : ''; ?>>📍 Endereços</option>
                    </select>
                </div>
                
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($query)): ?>
        <!-- Resumo dos Resultados -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    Resultados para: "<span class="text-blue-600"><?php echo htmlspecialchars($query); ?></span>"
                </h3>
                <span class="text-sm text-gray-600">
                    <?php echo $totalResults; ?> resultado(s) encontrado(s)
                </span>
            </div>
            
            <?php if ($type === 'all' && !empty($results)): ?>
                <div class="mt-3 flex flex-wrap gap-2">
                    <?php if (!empty($results['posts'])): ?>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            📝 <?php echo count($results['posts']); ?> posts
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($results['usuarios'])): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            👥 <?php echo count($results['usuarios']); ?> usuários
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($results['categorias'])): ?>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                            📁 <?php echo count($results['categorias']); ?> categorias
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($results['comentarios'])): ?>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            💬 <?php echo count($results['comentarios']); ?> comentários
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($results['enderecos'])): ?>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                            📍 <?php echo count($results['enderecos']); ?> endereços
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($totalResults === 0): ?>
            <!-- Nenhum Resultado -->
            <div class="text-center py-12">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum resultado encontrado</h3>
                <p class="text-gray-500 mb-6">Tente usar termos diferentes ou mais gerais</p>
                
                <div class="bg-blue-50 p-4 rounded-lg text-left max-w-md mx-auto">
                    <h4 class="font-medium text-blue-900 mb-2">💡 Dicas de busca:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Use palavras-chave simples</li>
                        <li>• Verifique a ortografia</li>
                        <li>• Tente sinônimos</li>
                        <li>• Use filtros por tipo de conteúdo</li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Resultados -->
            <div class="space-y-8">
                
                <!-- Posts -->
                <?php if (!empty($results['posts'])): ?>
                    <div class="search-section">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            📝 Posts 
                            <span class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                <?php echo count($results['posts']); ?>
                            </span>
                        </h3>
                        
                        <div class="grid gap-4">
                            <?php foreach ($results['posts'] as $post): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-lg mb-2">
                                                <a href="?page=posts&action=edit&id=<?php echo $post['id']; ?>" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($post['titulo']), $query); ?>
                                                </a>
                                            </h4>
                                            <p class="text-gray-600 mb-2">
                                                <?php echo $searchService->highlightSearchTerm(htmlspecialchars(substr($post['descricao'], 0, 150)), $query); ?>...
                                            </p>
                                            <div class="flex items-center text-sm text-gray-500 space-x-4">
                                                <span>📁 <?php echo htmlspecialchars($post['categoria_nome']); ?></span>
                                                <span>👤 <?php echo htmlspecialchars($post['autor_nome'] . ' ' . $post['autor_sobrenome']); ?></span>
                                                <span>💬 <?php echo $post['total_comentarios']; ?> comentários</span>
                                                <span>📅 <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Usuários -->
                <?php if (!empty($results['usuarios'])): ?>
                    <div class="search-section">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            👥 Usuários 
                            <span class="ml-2 text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <?php echo count($results['usuarios']); ?>
                            </span>
                        </h3>
                        
                        <div class="grid gap-4">
                            <?php foreach ($results['usuarios'] as $usuario): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                                <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold">
                                                    <a href="?page=usuarios&action=edit&id=<?php echo $usuario['id']; ?>" 
                                                       class="text-blue-600 hover:text-blue-800">
                                                        <?php echo $searchService->highlightSearchTerm(htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome']), $query); ?>
                                                    </a>
                                                </h4>
                                                <p class="text-gray-600 text-sm">
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($usuario['email']), $query); ?>
                                                </p>
                                                <div class="flex items-center text-xs text-gray-500 space-x-3 mt-1">
                                                    <span>📝 <?php echo $usuario['total_posts']; ?> posts</span>
                                                    <span>💬 <?php echo $usuario['total_comentarios']; ?> comentários</span>
                                                    <span>📍 <?php echo $usuario['total_enderecos']; ?> endereços</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="?page=enderecos&action=view&user_id=<?php echo $usuario['id']; ?>" 
                                               class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                Endereços
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Categorias -->
                <?php if (!empty($results['categorias'])): ?>
                    <div class="search-section">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            📁 Categorias 
                            <span class="ml-2 text-sm bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                <?php echo count($results['categorias']); ?>
                            </span>
                        </h3>
                        
                        <div class="grid gap-4">
                            <?php foreach ($results['categorias'] as $categoria): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-lg mb-2">
                                                <a href="?page=categorias&action=edit&id=<?php echo $categoria['id']; ?>" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($categoria['titulo']), $query); ?>
                                                </a>
                                            </h4>
                                            <p class="text-gray-600 mb-2">
                                                <?php echo $searchService->highlightSearchTerm(htmlspecialchars($categoria['descricao']), $query); ?>
                                            </p>
                                            <div class="text-sm text-gray-500">
                                                📝 <?php echo $categoria['total_posts']; ?> posts nesta categoria
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Comentários -->
                <?php if (!empty($results['comentarios'])): ?>
                    <div class="search-section">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            💬 Comentários 
                            <span class="ml-2 text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                <?php echo count($results['comentarios']); ?>
                            </span>
                        </h3>
                        
                        <div class="grid gap-4">
                            <?php foreach ($results['comentarios'] as $comentario): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="mb-3">
                                        <p class="text-gray-800 mb-2">
                                            <?php echo $searchService->highlightSearchTerm(htmlspecialchars($comentario['comentario']), $query); ?>
                                        </p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-3">
                                                <span>👤 <?php echo htmlspecialchars($comentario['usuario_nome'] . ' ' . $comentario['usuario_sobrenome']); ?></span>
                                                <span>📝 Post: 
                                                    <a href="../post.php?id=<?php echo $comentario['post_id']; ?>" 
                                                       class="text-blue-600 hover:text-blue-800">
                                                        <?php echo htmlspecialchars($comentario['post_titulo']); ?>
                                                    </a>
                                                </span>
                                                <span>📅 <?php echo date('d/m/Y H:i', strtotime($comentario['created_at'])); ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-green-600">👍 <?php echo $comentario['likes']; ?></span>
                                                <span class="text-red-600">👎 <?php echo $comentario['dislikes']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Endereços -->
                <?php if (!empty($results['enderecos'])): ?>
                    <div class="search-section">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            📍 Endereços 
                            <span class="ml-2 text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                <?php echo count($results['enderecos']); ?>
                            </span>
                        </h3>
                        
                        <div class="grid gap-4">
                            <?php foreach ($results['enderecos'] as $endereco): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-lg mb-2">
                                                <a href="?page=enderecos&action=edit&id=<?php echo $endereco['id']; ?>" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($endereco['nome']), $query); ?>
                                                </a>
                                            </h4>
                                            <div class="text-gray-600 mb-2">
                                                <div>
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($endereco['rua'] . ', ' . $endereco['numero']), $query); ?>
                                                </div>
                                                <div>
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($endereco['bairro'] . ' - ' . $endereco['cidade']), $query); ?>
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                👤 Usuário: 
                                                <a href="?page=usuarios&action=edit&id=<?php echo $endereco['user_id']; ?>" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    <?php echo $searchService->highlightSearchTerm(htmlspecialchars($endereco['usuario_nome'] . ' ' . $endereco['usuario_sobrenome']), $query); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Estado Inicial -->
        <div class="text-center py-12">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-4">Busque em todo o sistema</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Use a busca para encontrar posts, usuários, categorias, comentários e endereços rapidamente.
            </p>
            
            <?php if (!empty($stats)): ?>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 max-w-4xl mx-auto">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600"><?php echo $stats['total_posts']; ?></div>
                        <div class="text-sm text-blue-800">Posts</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600"><?php echo $stats['total_usuarios']; ?></div>
                        <div class="text-sm text-green-800">Usuários</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600"><?php echo $stats['total_categorias']; ?></div>
                        <div class="text-sm text-purple-800">Categorias</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600"><?php echo $stats['total_comentarios']; ?></div>
                        <div class="text-sm text-yellow-800">Comentários</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-red-600"><?php echo $stats['total_enderecos']; ?></div>
                        <div class="text-sm text-red-800">Endereços</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Auto-focus no campo de busca
document.getElementById('searchInput').focus();

// Atalho de teclado Ctrl+F para busca
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }
});
</script> 