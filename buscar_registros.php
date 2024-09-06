<?php
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

// Função para obter os registros da tabela escolhida
function getRecords($tabela) {
  $conn = connectDB();
  $sql = "SELECT * FROM $tabela"; // Usamos o nome da tabela recebido como parâmetro
  $result = mysqli_query($conn, $sql);

  $records = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
  }

  mysqli_close($conn);
  return $records;
}

// Verificar se foi recebido o nome da tabela como parâmetro
if (isset($_GET['tabela'])) {
  $tabela = $_GET['tabela'];

  // Retorna os registros da tabela escolhida em formato JSON
  header('Content-Type: application/json');
  echo json_encode(getRecords($tabela));
} else {
  // Caso o nome da tabela não tenha sido fornecido, retornar um erro
  http_response_code(400);
  echo json_encode(array('mensagem' => 'Nome da tabela não fornecido.'));
}
?>
