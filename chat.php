<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* Estilos do CSS */
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h2 {
      margin-bottom: 20px;
    }

    /* Estilo para a textarea */
    #tabela-container {
      width: 100%;
      height: 200px;
      resize: none;
      border: 1px solid #ccc;
      padding: 8px;
    }

    /* Estilo para o formulário de adicionar e excluir registros */
    form {
      margin-top: 20px;
    }

    form label {
      margin-right: 10px;
    }

    form input[type="text"] {
      padding: 5px;
      border: 1px solid #ccc;
    }

    form button {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    /* Estilo para a tabela */
    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }

    /* Estilo para os botões de ações */
    .action-buttons button {
      padding: 5px 10px;
      background-color: #f44336;
      color: #fff;
      border: none;
      cursor: pointer;
      margin-right: 5px;
    }

    .action-buttons button.select {
      background-color: #4CAF50;
    }

    /* Resto dos estilos do CSS */
    /* ... */
  </style>
</head>
<body>
  <h2>Chat</h2>
  <textarea id="tabela-container" rows="10" cols="80" readonly></textarea>

  <script>
    // Função para atualizar o conteúdo da textarea com os registros do banco de dados
    function atualizarTabela() {
      $.ajax({
        url: 'buscar_registros.php',
        type: 'GET',
        dataType: 'json',
        data: { tabela: '<?php echo $_GET["tabela"]; ?>' }, // Passa o nome da tabela como parâmetro
        success: function(data) {
          if (data.length > 0) {
            var tabelaText = '';
            $.each(data, function(index, record) {
              tabelaText += '' + record.id + ' - ' + record.nome + ': ' + record.telefone + '\n';
            });
            // Atualizar a textarea com o novo conteúdo
            $('#tabela-container').val(tabelaText);

            // Rolagem para o final da textarea
            var textarea = document.getElementById('tabela-container');
            textarea.scrollTop = textarea.scrollHeight;
          } else {
            $('#tabela-container').val('A tabela está vazia.');
          }
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição AJAX: ' + error);
        }
      });
    }

    // Atualizar a tabela a cada 2 segundos (2000 milissegundos)
    setInterval(atualizarTabela, 2000);

    // Chamar a função para exibir a tabela pela primeira vez
    atualizarTabela();
  </script>

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
    function getRecords($tableName) {
      $conn = connectDB();
      $sql = "SELECT id, nome, telefone FROM $tableName"; // Removido o campo data
      $result = mysqli_query($conn, $sql);

      $records = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
      }

      mysqli_close($conn);
      return $records;
    }

    // Função para adicionar um novo registro
    if (isset($_POST['adicionar'])) {
      $tableName = $_GET['tabela'];
      $nome = $_POST['nome'];
      $telefone = $_POST['telefone'];

      // Obter a data e hora atual do computador
      date_default_timezone_set('America/Sao_Paulo'); // Definir o fuso horário
      $data = date('Y-m-d H:i:s');

      $conn = connectDB();
      $sql = "INSERT INTO $tableName (nome, telefone, data) VALUES ('$nome', '$telefone', '$data')";
      if (mysqli_query($conn, $sql)) {
        echo "Mensagem enviada com sucesso!";
      } else {
        echo "Erro ao adicionar o registro: " . mysqli_error($conn);
      }
      mysqli_close($conn);
    }

    // Função para excluir um registro
    if (isset($_POST['excluir'])) {
      $tableName = $_GET['tabela'];
      $id = $_POST['id'];

      $conn = connectDB();
      $sql = "DELETE FROM $tableName WHERE id = $id";
      if (mysqli_query($conn, $sql)) {
        echo "Mensagem excluída com sucesso!";
      } else {
        echo "Erro ao excluir o envio: " . mysqli_error($conn);
      }
      mysqli_close($conn);
    }
  ?>

  <form method="post" action="">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>"><br>
    <label for="telefone">Mensagem:</label>
    <textarea id="telefone" name="telefone" rows="3" cols="50"><?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?></textarea><br>


    <button type="submit" name="adicionar" value="adicionar">Enviar</button>
  </form>

    <h2>Excluir Informações</h2>
  <form method="post" action="">
    <label for="id">ID do Registro a Excluir:</label>
    <input type="number" id="id" name="id"><br>
    <button type="submit" name="excluir" value="excluir">Excluir</button>
  </form>

  <br>
  <button onclick="window.location.href='index.php'">Voltar</button>

</body>
</html>

