<?php
// Verifica se o formulário foi enviado
if (isset($_POST['criar'])) {
  // Obtém o nome da nova tabela do formulário
  $novatabela = $_POST['novatabela'];

  // Função para conectar ao banco de dados
  function connectDB() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'teste';

    $conn = mysqli_connect($host, $username, $password, $dbname);

    if (!$conn) {
      die('Não foi possível conectar ao banco de dados: ' . mysqli_connect_error());
    }

    return $conn;
  }

  // Conectar ao banco de dados
  $conn = connectDB();

  // Cria a consulta SQL para criar a nova tabela
  $sql = "CREATE TABLE $novatabela (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            data DATETIME NOT NULL,
            nome VARCHAR(50) NOT NULL,
            telefone VARCHAR(50) NOT NULL
          )";

  // Executa a consulta SQL
  if (mysqli_query($conn, $sql)) {
    echo "Nova tabela '$novatabela' criada com sucesso!";
  } else {
    echo "Erro ao criar a tabela: " . mysqli_error($conn);
  }

  // Fecha a conexão com o banco de dados
  mysqli_close($conn);
}
?>
