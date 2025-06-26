<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Blog Admin</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Importa o Tailwind CSS -->
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <?php include 'header.php'; ?>

<?php
// Sistema de rotas
$page = $_GET['page'] ?? 'posts'; // Página padrão
$action = $_GET['action'] ?? ''; // Ação padrão

// Se a página for posts, inclui o formulário ou a lista de posts
if ($page == 'posts') {
    if ($action == 'create' || $action == 'edit') {
        include 'components/post/PostForm.php';
    } else {
        include 'components/post/PostList.php';
    }
} elseif ($page == 'categorias') {
    if ($action == 'create' || $action == 'edit') {
        include 'components/categoria/CategoriaForm.php';
    } else {
        include 'components/categoria/CategoriaList.php';
    }
} elseif ($page == 'usuarios') {
    if ($action == 'create' || $action == 'edit') {
        include 'components/usuario/UsuarioForm.php';
    } else {
        include 'components/usuario/UsuarioList.php';
    }
} elseif ($page == 'enderecos') {
    if ($action == 'create' || $action == 'edit') {
        include 'components/endereco/EnderecoForm.php';
    } elseif ($action == 'view') {
        include 'components/endereco/EnderecoView.php';
    } else {
        include 'components/endereco/EnderecoList.php';
    }
} elseif ($page == 'comentarios') {
    include 'components/comentario/ComentarioList.php';
} elseif ($page == 'search') {
    include 'components/search/SearchResults.php';
}
?>

    <?php include 'footer.php'; ?>
</body>
</html>