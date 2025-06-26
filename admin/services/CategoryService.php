<?php
require_once __DIR__ . '/../database/Conexao.php';
require_once __DIR__ . '/../database/Categoria.php';

class CategoryService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    // Busca todas as categorias
    public function getAllCategorias() {
        $sql = "SELECT * FROM categorias ORDER BY titulo";
        $stmt = $this->pdo->query($sql);
        $categorias = [];
        
        while ($row = $stmt->fetch()) {
            $categoria = new Categoria();
            $categoria->setId($row['id']);
            $categoria->setTitulo($row['titulo']);
            $categoria->setDescricao($row['descricao']);
            $categoria->setCreatedAt($row['created_at']);
            $categoria->setUpdatedAt($row['updated_at']);
            $categorias[] = $categoria;
        }
        
        return $categorias;
    }

    // Busca uma categoria por ID
    public function getCategoriaById($id) {
        $sql = "SELECT * FROM categorias WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        // Se a categoria existe, pega os dados da categoria
        if ($row) {
            $categoria = new Categoria();
            $categoria->setId($row['id']);
            $categoria->setTitulo($row['titulo']);
            $categoria->setDescricao($row['descricao']);
            $categoria->setCreatedAt($row['created_at']);
            $categoria->setUpdatedAt($row['updated_at']);
            return $categoria;
        }
        // Se a categoria não existe, retorna null
        return null;
    }

    // Cria uma nova categoria
    public function createCategoria($titulo, $descricao) {
        $sql = "INSERT INTO categorias (titulo, descricao, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        // Insere a nova categoria no banco de dados
        return $stmt->execute([$titulo, $descricao]);
    }

    // Atualiza uma categoria
    public function updateCategoria($id, $titulo, $descricao) {
        $sql = "UPDATE categorias SET titulo = ?, descricao = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titulo, $descricao, $id]);
    }

    // Deleta uma categoria
    public function deleteCategoria($id) {
        // Verifica se a categoria tem posts
        $sql = "SELECT COUNT(*) FROM posts WHERE categoria_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            return false; // Não pode deletar categoria com posts
        }
        
        $sql = "DELETE FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Conta posts por categoria
    public function countPostsByCategoria($categoria_id) {
        // Conta os posts da deleteCategoria
        $sql = "SELECT COUNT(*) FROM posts WHERE categoria_id = ?";
        $stmt = $this->pdo->prepare($sql);
        // Executa a consulta
        $stmt->execute([$categoria_id]);
        // Retorna o número de posts da categoria
        return $stmt->fetchColumn();
    }
}
?>