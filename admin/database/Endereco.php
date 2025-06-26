<?php

// Classe para o endereço
class Endereco {
    // Atributos da classe
    private $id;
    private $user_id;
    private $nome;
    private $rua;
    private $numero;
    private $cidade;
    private $bairro;
    private $created_at;
    private $updated_at;

    // Construtor da classe (Quem inicializa o objeto)
    public function __construct($id = null, $user_id = null, $nome = null, $rua = null, $numero = null, $cidade = null, $bairro = null, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->nome = $nome;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getRua() { return $this->rua; }
    public function setRua($rua) { $this->rua = $rua; }

    public function getNumero() { return $this->numero; }
    public function setNumero($numero) { $this->numero = $numero; }

    public function getCidade() { return $this->cidade; }
    public function setCidade($cidade) { $this->cidade = $cidade; }

    public function getBairro() { return $this->bairro; }
    public function setBairro($bairro) { $this->bairro = $bairro; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Retorna o usuário deste endereço
    public function getUsuario($usuarios) {
        // Retorna o usuário deste endereço
        foreach ($usuarios as $usuario) {
            if ($usuario->getId() == $this->user_id) {
                return $usuario;
            }
        }
        return null;
    }
}

?> 