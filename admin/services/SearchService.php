<?php
require_once __DIR__ . '/../database/Conexao.php';

class SearchService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    public function searchAll($query, $limit = 50) {
        $results = [
            'posts' => $this->searchPosts($query, $limit),
            'usuarios' => $this->searchUsuarios($query, $limit),
            'categorias' => $this->searchCategorias($query, $limit),
            'comentarios' => $this->searchComentarios($query, $limit),
            'enderecos' => $this->searchEnderecos($query, $limit)
        ];
        
        // Calcula total de resultados
        $results['total'] = array_sum(array_map('count', $results));
        
        return $results;
    }

    public function searchPosts($query, $limit = 20) {
        try {
            $sql = "SELECT p.*, c.titulo as categoria_nome, u.nome as autor_nome, u.sobrenome as autor_sobrenome,
                          (SELECT COUNT(*) FROM comentarios WHERE post_id = p.id) as total_comentarios
                   FROM posts p 
                   LEFT JOIN categorias c ON p.categoria_id = c.id 
                   LEFT JOIN usuarios u ON p.user_id = u.id
                   WHERE p.titulo LIKE :query 
                      OR p.descricao LIKE :query 
                      OR p.post_path LIKE :query
                      OR c.titulo LIKE :query
                   ORDER BY p.created_at DESC 
                   LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca de posts: " . $e->getMessage());
            return [];
        }
    }

    public function searchUsuarios($query, $limit = 20) {
        try {
            $sql = "SELECT u.*, 
                          (SELECT COUNT(*) FROM posts WHERE user_id = u.id) as total_posts,
                          (SELECT COUNT(*) FROM comentarios WHERE user_id = u.id) as total_comentarios,
                          (SELECT COUNT(*) FROM enderecos WHERE user_id = u.id) as total_enderecos
                   FROM usuarios u 
                   WHERE u.nome LIKE :query 
                      OR u.sobrenome LIKE :query 
                      OR u.email LIKE :query 
                      OR CONCAT(u.nome, ' ', u.sobrenome) LIKE :query
                   ORDER BY u.nome, u.sobrenome 
                   LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca de usuários: " . $e->getMessage());
            return [];
        }
    }

    public function searchCategorias($query, $limit = 20) {
        try {
            $sql = "SELECT c.*, 
                          (SELECT COUNT(*) FROM posts WHERE categoria_id = c.id) as total_posts
                   FROM categorias c 
                   WHERE c.titulo LIKE :query 
                      OR c.descricao LIKE :query
                   ORDER BY c.titulo 
                   LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca de categorias: " . $e->getMessage());
            return [];
        }
    }

    public function searchComentarios($query, $limit = 20) {
        try {
            $sql = "SELECT co.*, p.titulo as post_titulo, u.nome as usuario_nome, u.sobrenome as usuario_sobrenome
                   FROM comentarios co 
                   LEFT JOIN posts p ON co.post_id = p.id 
                   LEFT JOIN usuarios u ON co.user_id = u.id
                   WHERE co.comentario LIKE :query
                   ORDER BY co.created_at DESC 
                   LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca de comentários: " . $e->getMessage());
            return [];
        }
    }

    public function searchEnderecos($query, $limit = 20) {
        try {
            $sql = "SELECT e.*, u.nome as usuario_nome, u.sobrenome as usuario_sobrenome, u.email as usuario_email
                   FROM enderecos e 
                   LEFT JOIN usuarios u ON e.user_id = u.id
                   WHERE e.nome LIKE :query 
                      OR e.rua LIKE :query 
                      OR e.numero LIKE :query 
                      OR e.cidade LIKE :query 
                      OR e.bairro LIKE :query
                      OR u.nome LIKE :query
                      OR u.sobrenome LIKE :query
                   ORDER BY e.created_at DESC 
                   LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca de endereços: " . $e->getMessage());
            return [];
        }
    }

    public function getSearchSuggestions($query, $limit = 5) {
        try {
            $suggestions = [];
            
            // Sugestões de posts (títulos)
            $sql = "SELECT DISTINCT titulo as suggestion, 'post' as type FROM posts WHERE titulo LIKE :query LIMIT :limit";
            $stmt = $this->pdo->prepare($sql);
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $suggestions = array_merge($suggestions, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Sugestões de usuários (nomes)
            $sql = "SELECT DISTINCT CONCAT(nome, ' ', sobrenome) as suggestion, 'usuario' as type FROM usuarios WHERE nome LIKE :query OR sobrenome LIKE :query LIMIT :limit";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $suggestions = array_merge($suggestions, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Sugestões de categorias
            $sql = "SELECT DISTINCT titulo as suggestion, 'categoria' as type FROM categorias WHERE titulo LIKE :query LIMIT :limit";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $suggestions = array_merge($suggestions, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Sugestões de cidades
            $sql = "SELECT DISTINCT cidade as suggestion, 'endereco' as type FROM enderecos WHERE cidade LIKE :query LIMIT :limit";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $suggestions = array_merge($suggestions, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            return array_slice($suggestions, 0, $limit * 2); // Retorna até 10 sugestões
        } catch (PDOException $e) {
            error_log("Erro ao buscar sugestões: " . $e->getMessage());
            return [];
        }
    }

    public function highlightSearchTerm($text, $searchTerm) {
        if (empty($searchTerm) || empty($text)) {
            return $text;
        }
        
        return preg_replace(
            '/(' . preg_quote($searchTerm, '/') . ')/i',
            '<mark class="bg-yellow-200 px-1 rounded">$1</mark>',
            $text
        );
    }

    public function getSearchStats() {
        try {
            $stats = [];
            
            $stats['total_posts'] = $this->pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
            $stats['total_usuarios'] = $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
            $stats['total_categorias'] = $this->pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
            $stats['total_comentarios'] = $this->pdo->query("SELECT COUNT(*) FROM comentarios")->fetchColumn();
            $stats['total_enderecos'] = $this->pdo->query("SELECT COUNT(*) FROM enderecos")->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            return [];
        }
    }
}
?> 