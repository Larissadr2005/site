<?php
require_once __DIR__ . '/../../services/EnderecoService.php';

$enderecoService = new EnderecoService();
$endereco = null;
$message = '';
$messageType = '';

// Buscar usuários para o select
$usuarios = $enderecoService->getAllUsuarios();

// Usuário pré-selecionado (vindo da visualização de usuário específico)
$preselectedUserId = $_GET['user_id'] ?? '';

// Se é edição, buscar o endereço
if (isset($_GET['id']) && $_GET['id']) {
    $endereco = $enderecoService->getEnderecoById($_GET['id']);
    if (!$endereco) {
        $message = 'Endereço não encontrado!';
        $messageType = 'error';
    }
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $rua = $_POST['rua'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    
    // Validações
    if (empty($user_id) || empty($nome) || empty($rua) || empty($numero) || empty($cidade) || empty($bairro)) {
        $message = 'Todos os campos são obrigatórios!';
        $messageType = 'error';
    } else {
        // Se está editando, atualiza o endereço
        if ($endereco) {
            // Atualiza os dados do endereço
            $endereco->setUserId($user_id);
            $endereco->setNome($nome);
            $endereco->setRua($rua);
            $endereco->setNumero($numero);
            $endereco->setCidade($cidade);
            $endereco->setBairro($bairro);
            // Atualiza o endereço
            if ($enderecoService->updateEndereco($endereco)) {
                $message = 'Endereço atualizado com sucesso!';
                $messageType = 'success';
            } else {
                $message = 'Erro ao atualizar endereço!';
                $messageType = 'error';
            }
        } else {
            // Se não está editando, cria um novo endereço
            $novoEndereco = new Endereco(null, $user_id, $nome, $rua, $numero, $cidade, $bairro);
            
            if ($enderecoService->createEndereco($novoEndereco)) {
                // Se o endereço foi criado, redireciona para a página de endereços com uma mensagem de sucesso
                $message = 'Endereço criado com sucesso!';
                $messageType = 'success';
                // Redireciona para a página de endereços com uma mensagem de sucesso
                header('Location: ?page=enderecos');
                exit;
            } else {
                // Se o endereço não foi criado, redireciona para a página de endereços com uma mensagem de erro
                $message = 'Erro ao criar endereço!';
                $messageType = 'error';
            }
        }
    }
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <?php echo $endereco ? 'Editar Endereço' : 'Novo Endereço'; ?>
        </h2>
        <a href="?page=enderecos" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Voltar
        </a>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuário *</label>
                <select name="user_id" id="user_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione um usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['id']; ?>" 
                                <?php 
                                $selected = false;
                                if ($endereco && $endereco->getUserId() == $usuario['id']) {
                                    $selected = true;
                                } elseif (!$endereco && $preselectedUserId == $usuario['id']) {
                                    $selected = true;
                                }
                                echo $selected ? 'selected' : ''; 
                                ?>>
                            <?php echo htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome'] . ' (' . $usuario['email'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Endereço *</label>
                <input type="text" name="nome" id="nome" required 
                       value="<?php echo $endereco ? htmlspecialchars($endereco->getNome()) : ''; ?>"
                       placeholder="Ex: Casa, Trabalho, Casa dos Pais..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="rua" class="block text-sm font-medium text-gray-700 mb-2">Rua *</label>
                <input type="text" name="rua" id="rua" required 
                       value="<?php echo $endereco ? htmlspecialchars($endereco->getRua()) : ''; ?>"
                       placeholder="Nome da rua"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">Número *</label>
                <input type="text" name="numero" id="numero" required 
                       value="<?php echo $endereco ? htmlspecialchars($endereco->getNumero()) : ''; ?>"
                       placeholder="Número"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro *</label>
                <input type="text" name="bairro" id="bairro" required 
                       value="<?php echo $endereco ? htmlspecialchars($endereco->getBairro()) : ''; ?>"
                       placeholder="Nome do bairro"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                <input type="text" name="cidade" id="cidade" required 
                       value="<?php echo $endereco ? htmlspecialchars($endereco->getCidade()) : ''; ?>"
                       placeholder="Nome da cidade"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4">
            <a href="?page=enderecos" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                <?php echo $endereco ? 'Atualizar' : 'Criar'; ?> Endereço
            </button>
        </div>
    </form>
</div>

<script>
// Opcional: Auto-completar cidades brasileiras ou validações adicionais
document.getElementById('numero').addEventListener('input', function(e) {
    // Remove caracteres não numéricos e letras para endereços como "123A"
    this.value = this.value.replace(/[^0-9A-Za-z]/g, '');
});
</script>
