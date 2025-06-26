<?php

// Classe para o post
class Post {
    private $id;
    private $user_id;
    private $categoria_id;
    private $titulo;
    private $descricao;
    private $post_path;
    private $created_at;
    private $updated_at;

    // Construtor da classe (Quem inicializa o objeto)
    public function __construct($id = null, $user_id = null, $categoria_id = null, $titulo = null, $descricao = null, $post_path = null, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->categoria_id = $categoria_id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->post_path = $post_path;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters para os atributos da classe
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getCategoriaId() { return $this->categoria_id; }
    public function setCategoriaId($categoria_id) { $this->categoria_id = $categoria_id; }

    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }

    public function getPostPath() { return $this->post_path; }
    public function setPostPath($post_path) { $this->post_path = $post_path; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Retorna o usuário que fez o post
    public function getUsuario($usuarios) {
        // Retorna o usuário deste post
        foreach ($usuarios as $usuario) {
            if ($usuario->getId() == $this->user_id) {
                return $usuario;
            }
        }
        return null;
    }

    // Retorna a categoria deste post
    public function getCategoria($categorias) {
        // Retorna a categoria deste post
        foreach ($categorias as $categoria) {
            if ($categoria->getId() == $this->categoria_id) {
                return $categoria;
            }
        }
        return null;
    }
}

?> 