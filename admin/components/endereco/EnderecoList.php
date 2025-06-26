<?php
require_once __DIR__ . '/../../services/EnderecoService.php';

// Cria o serviço de endereço
$enderecoService = new EnderecoService();
// Inicializa as mensagens
$message = '';
$messageType = '';

// Processa a exclusão do endereço
if (isset($_GET['delete']) && $_GET['delete']) {
    $id = $_GET['delete'];
    $redirect = $_GET['redirect'] ?? '';
    $redirectUserId = $_GET['user_id'] ?? '';
    
    if ($enderecoService->deleteEndereco($id)) {
        // Se o endereço foi deletado, redireciona para a página de endereços com uma mensagem de sucesso
        if ($redirect == 'enderecos_user' && $redirectUserId) {
            header('Location: ?page=enderecos&action=view&user_id=' . $redirectUserId . '&msg=deleted');
            exit;
        } else {
            // Se o endereço foi deletado, redireciona para a página de endereços com uma mensagem de sucesso
            $message = 'Endereço excluído com sucesso!';
            $messageType = 'success';
        }
    } else {
        // Se o endereço não foi deletado, redireciona para a página de endereços com uma mensagem de erro
        $message = 'Erro ao excluir endereço!';
        $messageType = 'error';
    }
}

// Buscar todos os endereços
$enderecos = $enderecoService->getAllEnderecos();

// Filtro por usuário (se especificado)
// Pega o id do usuário
$filterUserId = $_GET['user_id'] ?? '';
if ($filterUserId) {
    // Filtra os endereços pelo usuário
    $enderecos = array_filter($enderecos, function($endereco) use ($filterUserId) {
        return $endereco->getUserId() == $filterUserId;
    });
}

// Busca todos os usuários
$usuarios = $enderecoService->getAllUsuarios();
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Endereços</h2>
        <a href="?page=enderecos&action=create" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Novo Endereço
        </a>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <input type="hidden" name="page" value="enderecos">
            
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Usuário</label>
                <select name="user_id" id="user_id" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os usuários</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['id']; ?>" 
                                <?php echo $filterUserId == $usuario['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Filtrar
                </button>
                <?php if ($filterUserId): ?>
                    <a href="?page=enderecos" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                        Limpar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-blue-600">Total de Endereços</h3>
            <p class="text-2xl font-bold text-blue-900"><?php echo count($enderecos); ?></p>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-green-600">Usuários com Endereços</h3>
            <p class="text-2xl font-bold text-green-900">
                <?php 
                $usuariosComEnderecos = array_unique(array_map(function($e) { return $e->getUserId(); }, $enderecos));
                echo count($usuariosComEnderecos); 
                ?>
            </p>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-yellow-600">Cidades Diferentes</h3>
            <p class="text-2xl font-bold text-yellow-900">
                <?php 
                $cidades = array_unique(array_map(function($e) { return $e->getCidade(); }, $enderecos));
                echo count($cidades); 
                ?>
            </p>
        </div>
    </div>

    <!-- Tabela de endereços -->
    <?php if (empty($enderecos)): ?>
        <div class="text-center py-8 text-gray-500">
            <p class="text-lg">Nenhum endereço encontrado.</p>
            <p class="mt-2">
                <a href="?page=enderecos&action=create" class="text-blue-500 hover:text-blue-700">
                    Clique aqui para criar o primeiro endereço
                </a>
            </p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nome do Endereço
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Endereço Completo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cidade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Criado em
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($enderecos as $endereco): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($endereco->getUsuarioNome() ?? 'Usuário não encontrado'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    <?php echo htmlspecialchars($endereco->getNome()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($endereco->getRua() . ', ' . $endereco->getNumero()); ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($endereco->getBairro()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($endereco->getCidade()); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($endereco->getCreatedAt())); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="?page=enderecos&action=edit&id=<?php echo $endereco->getId(); ?>" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Editar
                                </a>
                                <a href="?page=enderecos&delete=<?php echo $endereco->getId(); ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Tem certeza que deseja excluir este endereço?')">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
