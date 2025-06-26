<?php
require_once __DIR__ . '/../database/Conexao.php';
require_once __DIR__ . '/../database/Endereco.php';

class EnderecoService {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    public function getAllEnderecos() {
        try {
            $sql = "SELECT e.*, u.nome as usuario_nome, u.sobrenome as usuario_sobrenome 
                   FROM enderecos e 
                   LEFT JOIN usuarios u ON e.user_id = u.id 
                   ORDER BY e.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $enderecos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $endereco = new Endereco(
                    $row['id'],
                    $row['user_id'], 
                    $row['nome'],
                    $row['rua'],
                    $row['numero'],
                    $row['cidade'],
                    $row['bairro'],
                    $row['created_at'],
                    $row['updated_at']
                );
                $endereco->setUsuarioNome($row['usuario_nome'] . ' ' . $row['usuario_sobrenome']);
                $enderecos[] = $endereco;
            }
            
            return $enderecos;
        } catch (PDOException $e) {
            error_log("Erro ao buscar endereços: " . $e->getMessage());
            return [];
        }
    }

    public function getEnderecoById($id) {
        try {
            $sql = "SELECT * FROM enderecos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Endereco(
                    $row['id'],
                    $row['user_id'],
                    $row['nome'],
                    $row['rua'],
                    $row['numero'],
                    $row['cidade'],
                    $row['bairro'],
                    $row['created_at'],
                    $row['updated_at']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar endereço: " . $e->getMessage());
            return null;
        }
    }

    public function getEnderecosByUserId($user_id) {
        try {
            $sql = "SELECT * FROM enderecos WHERE user_id = :user_id ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $enderecos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $enderecos[] = new Endereco(
                    $row['id'],
                    $row['user_id'],
                    $row['nome'],
                    $row['rua'],
                    $row['numero'],
                    $row['cidade'],
                    $row['bairro'],
                    $row['created_at'],
                    $row['updated_at']
                );
            }
            
            return $enderecos;
        } catch (PDOException $e) {
            error_log("Erro ao buscar endereços do usuário: " . $e->getMessage());
            return [];
        }
    }

    public function createEndereco($endereco) {
        try {
            $sql = "INSERT INTO enderecos (user_id, nome, rua, numero, cidade, bairro, created_at, updated_at) 
                   VALUES (:user_id, :nome, :rua, :numero, :cidade, :bairro, NOW(), NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $endereco->getUserId(), PDO::PARAM_INT);
            $stmt->bindParam(':nome', $endereco->getNome(), PDO::PARAM_STR);
            $stmt->bindParam(':rua', $endereco->getRua(), PDO::PARAM_STR);
            $stmt->bindParam(':numero', $endereco->getNumero(), PDO::PARAM_STR);
            $stmt->bindParam(':cidade', $endereco->getCidade(), PDO::PARAM_STR);
            $stmt->bindParam(':bairro', $endereco->getBairro(), PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $endereco->setId($this->pdo->lastInsertId());
                return $endereco;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao criar endereço: " . $e->getMessage());
            return false;
        }
    }

    public function updateEndereco($endereco) {
        try {
            $sql = "UPDATE enderecos SET 
                   user_id = :user_id,
                   nome = :nome, 
                   rua = :rua,
                   numero = :numero,
                   cidade = :cidade,
                   bairro = :bairro,
                   updated_at = NOW() 
                   WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $endereco->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $endereco->getUserId(), PDO::PARAM_INT);
            $stmt->bindParam(':nome', $endereco->getNome(), PDO::PARAM_STR);
            $stmt->bindParam(':rua', $endereco->getRua(), PDO::PARAM_STR);
            $stmt->bindParam(':numero', $endereco->getNumero(), PDO::PARAM_STR);
            $stmt->bindParam(':cidade', $endereco->getCidade(), PDO::PARAM_STR);
            $stmt->bindParam(':bairro', $endereco->getBairro(), PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar endereço: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEndereco($id) {
        try {
            $sql = "DELETE FROM enderecos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar endereço: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsuarios() {
        try {
            $sql = "SELECT id, nome, sobrenome, email FROM usuarios ORDER BY nome, sobrenome";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuários: " . $e->getMessage());
            return [];
        }
    }
}
?>