<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Gerenciar Tabela</title>
</head>
<body>
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

    // Função para obter os registros da tabela
    function getRecords() {
      $conn = connectDB();
      $sql = "SELECT * FROM tabela";
      $result = mysqli_query($conn, $sql);

      $records = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
      }

      mysqli_close($conn);
      return $records;
    }

    // Exibir a tabela
    $records = getRecords();
    if (!empty($records)) {
      echo "<h2>Tabela</h2>";
      echo "<table border='1'>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Data</th>
              </tr>";
      foreach ($records as $record) {
        echo "<tr>
                <td>{$record['id']}</td>
                <td>{$record['nome']}</td>
                <td>{$record['telefone']}</td>
                <td>{$record['data']}</td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "A tabela está vazia.";
    }
  ?>

  <h2>Adicionar Informações</h2>
  <form method="post" action="">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome"><br>
    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone"><br>
    <label for="data">Data:</label>
    <input type="date" id="data" name="data"><br>
    <button type="submit" name="adicionar" value="adicionar">Adicionar</button>
  </form>

  <?php
    // Função para adicionar um novo registro
    if (isset($_POST['adicionar'])) {
      $nome = $_POST['nome'];
      $telefone = $_POST['telefone'];
      $data = $_POST['data'];

      $conn = connectDB();
      $sql = "INSERT INTO tabela (nome, telefone, data) VALUES ('$nome', '$telefone', '$data')";
      if (mysqli_query($conn, $sql)) {
        echo "Registro adicionado com sucesso!";
      } else {
        echo "Erro ao adicionar o registro: " . mysqli_error($conn);
      }
      mysqli_close($conn);
    }
  ?>

  <h2>Excluir Informações</h2>
  <form method="post" action="">
    <label for="id">ID do Registro a Excluir:</label>
    <input type="number" id="id" name="id"><br>
    <button type="submit" name="excluir" value="excluir">Excluir</button>
  </form>

  <?php
    // Função para excluir um registro
    if (isset($_POST['excluir'])) {
      $id = $_POST['id'];

      $conn = connectDB();
      $sql = "DELETE FROM tabela WHERE id = $id";
      if (mysqli_query($conn, $sql)) {
        echo "Registro excluído com sucesso!";
      } else {
        echo "Erro ao excluir o registro: " . mysqli_error($conn);
      }
      mysqli_close($conn);
    }
  ?>
</body>
</html>
