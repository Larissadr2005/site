<?php
require_once __DIR__ . '/../database/Conexao.php';
require_once __DIR__ . '/../database/Usuario.php';

class UserService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    // Busca todos os usuários
    public function getAllUsers() {
        $sql = "SELECT * FROM usuarios ORDER BY nome";
        $stmt = $this->pdo->query($sql);
        $users = [];
        
        while ($row = $stmt->fetch()) {
            $user = new Usuario();
            $user->setId($row['id']);
            $user->setNome($row['nome']);
            $user->setSobrenome($row['sobrenome']);
            $user->setEmail($row['email']);
            $users[] = $user;
        }
        
        return $users;
    }

    // Busca um usuário por ID
    public function getUserById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $user = new Usuario();
            $user->setId($row['id']);
            $user->setNome($row['nome']);
            $user->setSobrenome($row['sobrenome']);
            $user->setEmail($row['email']);
            return $user;
        }
        
        return null;
    }

    // Cria um novo usuário
    public function createUser($nome, $sobrenome, $email, $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, sobrenome, email, password, created_at, updated_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $sobrenome, $email, $senha_hash]);
    }

    // Atualiza um usuário
    public function updateUser($id, $nome, $sobrenome, $email) {
        $sql = "UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $sobrenome, $email, $id]);
    }

    // Deleta um usuário
    public function deleteUser($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Conta posts por usuário
    public function countPostsByUser($user_id) {
        $sql = "SELECT COUNT(*) FROM posts WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }
}
?>