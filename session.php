<?php
session_start();
//Esse arquivo gerencia as sessões do usuário

// Verifica se o usuário está logado
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Retorna o ID do usuário logado
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Retorna o nome do usuário logado
function getCurrentUserName() {
    return $_SESSION['user_name'] ?? null;
}

// Retorna o email do usuário logado
function getCurrentUserEmail() {
    return $_SESSION['user_email'] ?? null;
}
?> 