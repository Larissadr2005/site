<?php
require_once __DIR__ . '/../../services/CategoryService.php';

$categoryService = new CategoryService();

$titulo = '';
$descricao = '';
$categoria_id = null;
$erro = '';
$isEdit = false;

// Se está editando, carrega os dados da categoria
if (isset($_GET['id'])) {
    // Pega o id da categoria
    $categoria_id = $_GET['id'];
    // Pega a categoria pelo id
    $categoria = $categoryService->getCategoriaById($categoria_id);
    // Se a categoria existe, pega os dados da categoria
    if ($categoria) {
        $titulo = $categoria->getTitulo();
        $descricao = $categoria->getDescricao();
        $isEdit = true;
    }
}

// Processa o formulário
if ($_POST) {
    // Pega o título da categoria
    $titulo = trim($_POST['titulo']);
    // Pega a descrição da categoria
    $descricao = trim($_POST['descricao']);
    
    if (empty($titulo) || empty($descricao)) {
        $erro = 'Todos os campos são obrigatórios!';
    } else {
        // Se está editando, atualiza a categoria
        if ($isEdit) {
            // Atualizar categoria
            if ($categoryService->updateCategoria($categoria_id, $titulo, $descricao)) {
                header('Location: ?page=categorias&msg=updated');
                exit();
            } else {
                $erro = 'Erro ao atualizar a categoria!';
            }
        } else {
            // Se não está editando, cria uma nova categoria
            if ($categoryService->createCategoria($titulo, $descricao)) {
                header('Location: ?page=categorias&msg=created');
                exit();
            } else {
                $erro = 'Erro ao criar a categoria!';
            }
        }
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <?php echo $isEdit ? 'Editar Categoria' : 'Nova Categoria'; ?>
        </h2>
        <a href="?page=categorias" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Voltar
        </a>
    </div>

    <?php if ($erro): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Título:
            </label>
            <input type="text" name="titulo" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500"
                   value="<?php echo htmlspecialchars($titulo); ?>"
                   placeholder="Digite o título da categoria">
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Descrição:
            </label>
            <textarea name="descricao" required rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500"
                      placeholder="Descreva do que se trata esta categoria"><?php echo htmlspecialchars($descricao); ?></textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                <?php echo $isEdit ? 'Atualizar Categoria' : 'Criar Categoria'; ?>
            </button>
            <a href="?page=categorias" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </a>
        </div>
    </form>

    <?php if ($isEdit): ?>
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informações Adicionais</h3>
            <div class="bg-blue-50 p-4 rounded">
                <?php $countPosts = $categoryService->countPostsByCategoria($categoria_id); ?>
                <p class="text-blue-800">
                    <strong>Posts nesta categoria:</strong> <?php echo $countPosts; ?>
                </p>
                <?php if ($countPosts > 0): ?>
                    <p class="text-blue-600 text-sm mt-1">
                        Esta categoria não pode ser deletada enquanto tiver posts associados.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
