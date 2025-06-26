<?php
// Esse arquivo gerencia as sugestões de busca
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$query = trim($_GET['q'] ?? '');

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Inclui o arquivo de serviço de busca
    require_once __DIR__ . '/../services/SearchService.php';
    // Cria uma instância do serviço de busca
    $searchService = new SearchService();
    // Obtém as sugestões de busca
    $suggestions = $searchService->getSearchSuggestions($query, 8);
    
    // Formata as sugestões para o frontend
    $formattedSuggestions = array_map(function($item) {
        $typeIcons = [
            'post' => '📝',
            'usuario' => '👥',
            'categoria' => '📁',
            'endereco' => '📍'
        ];
        
        // Retorna as sugestões formatadas
        return [
            'text' => $item['suggestion'],
            'type' => $item['type'],
            'icon' => $typeIcons[$item['type']] ?? '🔍',
            'category' => ucfirst($item['type'])
        ];
    }, $suggestions);
    
    echo json_encode($formattedSuggestions);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor']);
}
?> 