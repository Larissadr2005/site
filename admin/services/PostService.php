<?php
require_once __DIR__ . '/../database/Conexao.php';
require_once __DIR__ . '/../database/Post.php';

class PostService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    // Busca todos os posts
    public function getAllPosts() {
        $sql = "SELECT p.*, u.nome as autor_nome, c.titulo as categoria_nome 
                FROM posts p 
                LEFT JOIN usuarios u ON p.user_id = u.id 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->pdo->query($sql);
        $posts = [];
        
        while ($row = $stmt->fetch()) {
            $post = new Post();
            $post->setId($row['id']);
            $post->setUserId($row['user_id']);
            $post->setCategoriaId($row['categoria_id']);
            $post->setTitulo($row['titulo']);
            $post->setDescricao($row['descricao']);
            $post->setPostPath($row['post_path']);
            $post->setCreatedAt($row['created_at']);
            $post->setUpdatedAt($row['updated_at']);
            $posts[] = $post;
        }
        
        return $posts;
    }

    // Busca um post por ID
    public function getPostById($id) {
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $post = new Post();
            $post->setId($row['id']);
            $post->setUserId($row['user_id']);
            $post->setCategoriaId($row['categoria_id']);
            $post->setTitulo($row['titulo']);
            $post->setDescricao($row['descricao']);
            $post->setPostPath($row['post_path']);
            $post->setCreatedAt($row['created_at']);
            $post->setUpdatedAt($row['updated_at']);
            return $post;
        }
        
        return null;
    }

    // Cria um novo post
    public function createPost($user_id, $categoria_id, $titulo, $descricao, $conteudo) {
        $sql = "INSERT INTO posts (user_id, categoria_id, titulo, descricao, post_path, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $categoria_id, $titulo, $descricao, $conteudo]);
    }

    // Atualiza um post
    public function updatePost($id, $categoria_id, $titulo, $descricao, $conteudo) {
        $sql = "UPDATE posts SET categoria_id = ?, titulo = ?, descricao = ?, post_path = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$categoria_id, $titulo, $descricao, $conteudo, $id]);
    }

    // Deleta um post
    public function deletePost($id) {
        $sql = "DELETE FROM posts WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Busca todas as categorias (para o formulário)
    public function getAllCategorias() {
        require_once __DIR__ . '/CategoryService.php';
        $categoryService = new CategoryService();
        $categorias = $categoryService->getAllCategorias();
        
        // Converte para array simples para compatibilidade
        $result = [];
        foreach ($categorias as $categoria) {
            $result[] = [
                'id' => $categoria->getId(),
                'titulo' => $categoria->getTitulo(),
                'descricao' => $categoria->getDescricao()
            ];
        }
        
        return $result;
    }
}
?>