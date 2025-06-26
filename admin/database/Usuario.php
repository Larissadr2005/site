<?php

// Classe para o usuário
class Usuario {
    private $id;
    private $nome;
    private $sobrenome;
    private $email;
    private $password;
    private $phone1;
    private $tipo_phone1;
    private $phone2;
    private $tipo_phone2;
    private $created_at;
    private $updated_at;

    // Construtor da classe (Quem inicializa o objeto)
    public function __construct($id = null, $nome = null, $sobrenome = null, $email = null, $password = null, $phone1 = null, $tipo_phone1 = null, $phone2 = null, $tipo_phone2 = null, $created_at = null, $updated_at = null) {
        // Inicializa os atributos da classe
        $this->id = $id;
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->email = $email;
        $this->password = $password;
        $this->phone1 = $phone1;
        $this->tipo_phone1 = $tipo_phone1;
        $this->phone2 = $phone2;
        $this->tipo_phone2 = $tipo_phone2;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters para os atributos da classe
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getSobrenome() { return $this->sobrenome; }
    public function setSobrenome($sobrenome) { $this->sobrenome = $sobrenome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getPhone1() { return $this->phone1; }
    public function setPhone1($phone1) { $this->phone1 = $phone1; }

    public function getTipoPhone1() { return $this->tipo_phone1; }
    public function setTipoPhone1($tipo_phone1) { $this->tipo_phone1 = $tipo_phone1; }

    public function getPhone2() { return $this->phone2; }
    public function setPhone2($phone2) { $this->phone2 = $phone2; }

    public function getTipoPhone2() { return $this->tipo_phone2; }
    public function setTipoPhone2($tipo_phone2) { $this->tipo_phone2 = $tipo_phone2; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Retorna os endereços deste usuário
    public function getEnderecos($enderecos) {
        // Retorna todos os endereços deste usuário
        return array_filter($enderecos, function($endereco) {
            return $endereco->getUserId() == $this->id;
        });
    }

    // Retorna os posts deste usuário
    public function getPosts($posts) {
        // Retorna todos os posts deste usuário
        return array_filter($posts, function($post) {
            return $post->getUserId() == $this->id;
        });
    }
}

?> 