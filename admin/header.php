<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: pages/login.php');
    exit();
}

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Blog - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Biblioteca LA</h1>
            
            <nav class="flex space-x-4 items-center">
                <a href="index.php" class="hover:bg-blue-700 px-3 py-2 rounded">Home</a>
                <a href="?page=posts" class="hover:bg-blue-700 px-3 py-2 rounded">Posts</a>
                <a href="?page=categorias" class="hover:bg-blue-700 px-3 py-2 rounded">Categorias</a>
                <a href="?page=usuarios" class="hover:bg-blue-700 px-3 py-2 rounded">Usuários</a>
                <a href="?page=comentarios" class="hover:bg-blue-700 px-3 py-2 rounded">Comentários</a>
                <a href="?page=enderecos" class="hover:bg-blue-700 px-3 py-2 rounded">Endereços</a>
                
                <!-- Campo de Busca -->
                <div class="relative">
                    <form method="GET" action="index.php" class="flex">
                        <input type="hidden" name="page" value="search">
                        <input type="text" 
                               name="q" 
                               placeholder="Buscar..." 
                               class="px-3 py-1 rounded-l bg-blue-500 text-white placeholder-blue-200 border border-blue-400 focus:outline-none focus:bg-white focus:text-gray-800 focus:border-blue-300 transition-colors w-32 focus:w-48"
                               autocomplete="off">
                        <button type="submit" 
                                class="px-2 py-1 bg-blue-400 hover:bg-blue-300 rounded-r border border-blue-400 border-l-0">
                            🔍
                        </button>
                    </form>
                </div>
                
                <span class="text-blue-200">Olá, <?php echo $user_name; ?>!</span>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded">Sair</a>
            </nav>
        </div>
    </header>
    
    <main class="max-w-6xl mx-auto p-4">
