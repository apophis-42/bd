<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Palantiri</title>
  <style>
    /* Estilos do CSS */
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h2 {
      margin-bottom: 20px;
    }

    /* Estilos para a tabela */
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }

    /* Estilo para o formulário de criação de tabela */
    .create-table-form {
      margin-bottom: 20px;
    }

    .create-table-form label {
      margin-right: 10px;
    }

    .create-table-form input[type="text"] {
      width: 200px;
      padding: 5px;
      border: 1px solid #ccc;
    }

    .create-table-form button {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    /* Estilo para os botões de ações */
    form button {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
      margin-right: 5px;
    }

    /* Resto dos estilos do CSS */
    /* ... */
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h2>Palantiri</h2>

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

  // Função para obter as tabelas existentes no banco de dados
  function getTables() {
    $conn = connectDB();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    $tables = array();
    while ($row = mysqli_fetch_row($result)) {
      $tables[] = $row[0];
    }

    mysqli_close($conn);
    return $tables;
  }

  // Função para criar uma nova tabela
  function createTable($tableName) {
    $conn = connectDB();
    $sql = "CREATE TABLE $tableName (
              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              data DATETIME NOT NULL,
              nome VARCHAR(50) NOT NULL,
              telefone VARCHAR(50) NOT NULL
            )";

    if (mysqli_query($conn, $sql)) {
      echo "Nova sala '$tableName' criada com sucesso!";
    } else {
      echo "Erro ao criar a sala: " . mysqli_error($conn);
    }

    mysqli_close($conn);
  }

  // Função para excluir uma tabela
  function deleteTable($tableName) {
    $conn = connectDB();
    $sql = "DROP TABLE $tableName";

    if (mysqli_query($conn, $sql)) {
      echo "Sala '$tableName' excluída com sucesso!";
    } else {
      echo "Erro ao excluir a sala: " . mysqli_error($conn);
    }

    mysqli_close($conn);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['criar'])) {
      $tableName = $_POST['novatabela'];
      createTable($tableName);
    } elseif (isset($_POST['excluir'])) {
      $tableName = $_POST['tabelaexcluir'];
      deleteTable($tableName);
    }
  }
  ?>

  <div class="create-table-form">
    <h3>Criar Nova Sala:</h3>
    <form method="post" action="">
      <label for="novatabela">Nome da Nova Sala:</label>
      <input type="text" id="novatabela" name="novatabela">
      <button type="submit" name="criar" value="criar">Criar</button>
    </form>
  </div>

  <h3>Salas Existentes:</h3>
  <table>
    <tr>
      <th>Nomes das Salas</th>
      <th>Ações</th>
    </tr>
    <?php
    $tables = getTables();
    foreach ($tables as $table) {
      echo "<tr>
              <td>$table</td>
              <td>
                <form method=\"get\" action=\"chat.php\">
                  <input type=\"hidden\" name=\"tabela\" value=\"$table\">
                  <button type=\"submit\">Selecionar</button>
                </form>
                <form method=\"post\" action=\"\">
                  <input type=\"hidden\" name=\"tabelaexcluir\" value=\"$table\">
                  <button type=\"submit\" name=\"excluir\" value=\"excluir\">Excluir</button>
                </form>
                
              </td>
            </tr>";
    }
    ?>
  </table>
</body>
</html>
