<?php
require_once __DIR__ . '/../../services/PostService.php';

$postService = new PostService();
$categorias = $postService->getAllCategorias();

$titulo = '';
$descricao = '';
$conteudo = '';
$categoria_id = '';
$post_id = null;
$erro = '';
$isEdit = false;

// Se está editando, carrega os dados do post
if (isset($_GET['id'])) {
    // Pega o id do post
    $post_id = $_GET['id'];
    // Pega o post pelo id
    $post = $postService->getPostById($post_id);
    // Se o post existe, pega os dados do post
    if ($post) {
        $titulo = $post->getTitulo();
        $descricao = $post->getDescricao();
        $conteudo = $post->getPostPath();
        $categoria_id = $post->getCategoriaId();
        $isEdit = true;
    }
}

// Processa o formulário se o usuário enviou o formulário
if ($_POST) {
    // Pega o título do post
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $conteudo = $_POST['conteudo'];
    $categoria_id = $_POST['categoria_id'];
    
    if (empty($titulo) || empty($descricao) || empty($conteudo)) {
        $erro = 'Todos os campos são obrigatórios!';
    } else {
        if ($isEdit) {
            // Atualizar post
            if ($postService->updatePost($post_id, $categoria_id, $titulo, $descricao, $conteudo)) {
                // Se o post foi atualizado, redireciona para a página de posts com uma mensagem de sucesso
                header('Location: ?page=posts&msg=updated');
                exit();
            } else {
                // Se o post não foi atualizado, redireciona para a página de posts com uma mensagem de erro
                $erro = 'Erro ao atualizar o post!';
            }
        } else {
            // Se não está editando, cria um novo post
            $user_id = $_SESSION['user_id'];
            // Cria o novo post
            if ($postService->createPost($user_id, $categoria_id, $titulo, $descricao, $conteudo)) {
                // Se o post foi criado, redireciona para a página de posts com uma mensagem de sucesso
                header('Location: ?page=posts&msg=created');
                exit();
            } else {
                $erro = 'Erro ao criar o post!';
            }
        }
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Título e botão de voltar -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <?php echo $isEdit ? 'Editar Post' : 'Novo Post'; ?>
        </h2>
        <a href="?page=posts" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Voltar
        </a>
    </div>

    <!-- Mensagem de erro -->
    <?php if ($erro): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de post -->
    <form method="POST" class="space-y-4">
        <!-- Input de título -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Título:
            </label>
            <input type="text" name="titulo" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                   value="<?php echo htmlspecialchars($titulo); ?>">
        </div>

        <!-- Input de categoria -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Categoria:
            </label>
            <select name="categoria_id" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" 
                            <?php echo $categoria_id == $categoria['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['titulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Input de conteúdo -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Descrição:
            </label>
            <textarea name="descricao" required rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                      placeholder="Breve descrição do post"><?php echo htmlspecialchars($descricao); ?></textarea>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Conteúdo:
            </label>
            <textarea name="conteudo" required rows="10"
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                      placeholder="Escreva o conteúdo completo do post aqui..."><?php echo htmlspecialchars($conteudo); ?></textarea>
        </div>

        <!-- Botão de criar ou atualizar post -->
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <?php echo $isEdit ? 'Atualizar Post' : 'Criar Post'; ?>
            </button>
            <!-- Botão de cancelar -->
            <a href="?page=posts" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </a>
        </div>
    </form>
</div>
