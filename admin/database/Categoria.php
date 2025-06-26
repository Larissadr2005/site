<?php

// Classe para a categoria
class Categoria {
    // Atributos da classe
    private $id;
    private $titulo;
    private $descricao;
    private $created_at;
    private $updated_at;

    // Construtor da classe (Quem inicializa o objeto)
    public function __construct($id = null, $titulo = null, $descricao = null, $created_at = null, $updated_at = null) {
        // Inicializa os atributos da classe
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters para os atributos da classe
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Relacionamento
    public function getPosts($posts) {
        // Retorna todos os posts desta categoria
        return array_filter($posts, function($post) {
            return $post->getCategoriaId() == $this->id;
        });
    }
}

?> 