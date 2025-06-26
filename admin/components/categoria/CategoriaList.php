<?php
require_once __DIR__ . '/../../services/CategoryService.php';

$categoryService = new CategoryService();
$categorias = $categoryService->getAllCategorias();

// Deletar categoria se solicitado
if (isset($_GET['delete'])) {
    // Pega o id da categoria
    $id = $_GET['delete'];
    // Deleta a categoria
    $deleted = $categoryService->deleteCategoria($id);
    // Se a categoria foi deletada, redireciona para a página de categorias com uma mensagem de sucesso
    if ($deleted) {
        header('Location: ?page=categorias&msg=deleted');
        exit();
    } else {
        // Se a categoria não foi deletada, redireciona para a página de categorias com uma mensagem de erro
        header('Location: ?page=categorias&msg=error_delete');
        exit();
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Categorias</h2>
        <a href="?page=categorias&action=create" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Nova Categoria
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-4 p-4 rounded bg-green-100 text-green-700">
            <?php 
            if ($_GET['msg'] == 'created') echo 'Categoria criada com sucesso!';
            if ($_GET['msg'] == 'updated') echo 'Categoria atualizada com sucesso!';
            if ($_GET['msg'] == 'deleted') echo 'Categoria deletada com sucesso!';
            if ($_GET['msg'] == 'error_delete') echo 'Erro: Não é possível deletar categoria que possui posts!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($categorias)): ?>
        <div class="text-center py-8 text-gray-500">
            <p>Nenhuma categoria encontrada.</p>
            <a href="?page=categorias&action=create" class="text-green-500 hover:text-green-600">
                Criar a primeira categoria
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($categorias as $categoria): ?>
                        <?php $countPosts = $categoryService->countPostsByCategoria($categoria->getId()); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($categoria->getTitulo()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars($categoria->getDescricao()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo $countPosts; ?> posts
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y', strtotime($categoria->getCreatedAt())); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="?page=categorias&action=edit&id=<?php echo $categoria->getId(); ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <?php if ($countPosts == 0): ?>
                                    <a href="?page=categorias&delete=<?php echo $categoria->getId(); ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Tem certeza que deseja deletar esta categoria?')">Deletar</a>
                                <?php else: ?>
                                    <span class="text-gray-400" title="Não é possível deletar categoria com posts">Deletar</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
