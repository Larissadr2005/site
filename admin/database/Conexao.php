<?php

// Classe para a conexão com o banco de dados
class Conexao {
    // Atributos da classe
    private static $host = 'localhost';
    private static $port = '3306';
    private static $dbname = 'meu_blog';
    private static $usuario = 'root';
    private static $senha = '';
    private static $pdo = null;

    // Método para obter a conexão com o banco de dados
    public static function getConexao() {
        if (self::$pdo === null) {
            try {
                // Cria a conexão com o banco de dados
                self::$pdo = new PDO(
                    'mysql:host=' . self::$host . ';port=' . self::$port . ';dbname=' . self::$dbname . ';charset=utf8',
                    self::$usuario,
                    self::$senha
                );
                // Define o modo de erro para exceção
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erro de conexão: ' . $e->getMessage());
            }
        }
        // Retorna a conexão com o banco de dados
        return self::$pdo;
    }
}

?> 