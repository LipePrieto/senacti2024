<?php
session_start();

// Verificar se o usuário já está logado, redirecionar para dashboard se estiver
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sistema_login";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obter dados do formulário
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta SQL para verificar o usuário
    $sql = "SELECT * FROM usuarios WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Login bem-sucedido, salvar o nome de usuário na sessão
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        // Login falhou
        $login_error = "Usuário ou senha incorretos.";
    }

    // Fechar conexão com o banco de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<link rel="stylesheet" href="styles.css"> <!-- Importando o arquivo styles.css -->
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
