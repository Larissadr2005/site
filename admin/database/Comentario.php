<?php

class Comentario {
    private $id;
    private $post_id;
    private $user_id;
    private $comentario;
    private $created_at;
    private $updated_at;

    // Construtor da classe
    public function __construct($id = null, $post_id = null, $user_id = null, $comentario = null, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->comentario = $comentario;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters para os atributos da classe
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getPostId() { return $this->post_id; }
    public function setPostId($post_id) { $this->post_id = $post_id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }      

    public function getComentario() { return $this->comentario; }
    public function setComentario($comentario) { $this->comentario = $comentario; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }  
}