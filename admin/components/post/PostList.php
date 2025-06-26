<?php
require_once __DIR__ . '/../../services/PostService.php';

$postService = new PostService();
$posts = $postService->getAllPosts();

// Deletar post se solicitado
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Deleta o post
    if ($postService->deletePost($id)) {
        // Se o post foi deletado, redireciona para a página de posts com uma mensagem de sucesso
        header('Location: ?page=posts&msg=deleted');
        exit();
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Posts</h2>
        <a href="?page=posts&action=create" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Novo Post
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-4 p-4 rounded bg-green-100 text-green-700">
            <?php 
            if ($_GET['msg'] == 'created') echo 'Post criado com sucesso!';
            if ($_GET['msg'] == 'updated') echo 'Post atualizado com sucesso!';
            if ($_GET['msg'] == 'deleted') echo 'Post deletado com sucesso!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
        <div class="text-center py-8 text-gray-500">
            <p>Nenhum post encontrado.</p>
            <a href="?page=posts&action=create" class="text-blue-500 hover:text-blue-600">
                Criar o primeiro post
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($post->getTitulo()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars(substr($post->getDescricao(), 0, 100)); ?>...
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y', strtotime($post->getCreatedAt())); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="?page=posts&action=edit&id=<?php echo $post->getId(); ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <a href="?page=posts&delete=<?php echo $post->getId(); ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Tem certeza que deseja deletar este post?')">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
