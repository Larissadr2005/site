<?php
require_once __DIR__ . '/../database/Conexao.php';
require_once __DIR__ . '/../database/Comentario.php';

class CommentService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    // Busca todos os comentários de um post
    public function getCommentsByPost($post_id) {
        $sql = "SELECT c.*, u.nome, u.sobrenome 
                FROM comentarios c 
                LEFT JOIN usuarios u ON c.user_id = u.id 
                WHERE c.post_id = ? 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchAll();
    }

    // Cria um novo comentário
    public function createComment($post_id, $user_id, $comentario) {
        $sql = "INSERT INTO comentarios (post_id, user_id, comentario, likes, dislikes, created_at, updated_at) 
                VALUES (?, ?, ?, 0, 0, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$post_id, $user_id, $comentario]);
    }

    // Adiciona like a um comentário
    public function addLike($comment_id) {
        $sql = "UPDATE comentarios SET likes = likes + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comment_id]);
    }

    // Adiciona dislike a um comentário
    public function addDislike($comment_id) {
        $sql = "UPDATE comentarios SET dislikes = dislikes + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comment_id]);
    }

    // Busca um comentário por ID
    public function getCommentById($id) {
        $sql = "SELECT * FROM comentarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Deleta um comentário
    public function deleteComment($id) {
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Conta comentários por post
    public function countCommentsByPost($post_id) {
        $sql = "SELECT COUNT(*) FROM comentarios WHERE post_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchColumn();
    }

    // Busca todos os comentários (para admin)
    public function getAllComments() {
        $sql = "SELECT c.*, u.nome, u.sobrenome, p.titulo as post_titulo 
                FROM comentarios c 
                LEFT JOIN usuarios u ON c.user_id = u.id 
                LEFT JOIN posts p ON c.post_id = p.id 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?> 