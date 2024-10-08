<?php
// Configurações de conexão
require_once('conn.php');

$conn = mysqli_connect($servername, $username, $password, $dbname) or die('Erro ao conectar ao banco de dados');

$sql = "SELECT * FROM paciente";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pacientes</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<a href="inicio.php">Voltar</a>
<a href="cadastroPaciente.php">Cadastrar P</a>
<style>
    td {
        max-width: 250px;  /* Largura máxima */
        overflow-wrap: break-word;  /* Quebra de palavras */
        white-space: normal;  /* Permite múltiplas linhas */
    }
    
</style>

<h1>Lista de Pacientes</h1>
<input type="text" id="nomeBusca" placeholder="Buscar por nome..." onkeyup="buscarPacientes()">

<div id="resultados">
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Bairro</th>
                    <th>Gênero</th>
                    <th>Status Trabalhista</th>
                    <th>Contato</th>
                    <th>Documento</th>
                    <th>Observações</th>
                    <th>Atendimento</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            // Verifica e formata o gênero
            $genero = $row['generoPaciente'] === 'M' ? 'Masculino' : 'Feminino';
            
            // Formata o status trabalhista
            switch ($row['statusTrabalho']) {
                case 'empregado':
                    $statusTrabalhista = 'Empregado';
                    break;
                case 'autonomo':
                    $statusTrabalhista = 'Autônomo';
                    break;
                case 'desempregado':
                    $statusTrabalhista = 'Desempregado';
                    break;
                case 'estagiario':
                    $statusTrabalhista = 'Estagiário';
                    break;
                case 'aposentado':
                    $statusTrabalhista = 'Aposentado';
                    break;
                case 'pensionista':
                    $statusTrabalhista = 'Pensionista';
                    break;
                case 'licenca':
                    $statusTrabalhista = 'Licença';
                    break;
                default:
                    $statusTrabalhista = 'Não especificado';
                    break;
            }

            echo "<tr>
                    <td>{$row['nomePaciente']}</td>
                    <td>{$row['dataNasc']}</td>
                    <td>{$row['bairro']}</td>
                    <td>$genero</td>
                    <td>$statusTrabalhista</td>
                    <td>{$row['contatoPaciente']}</td>
                    <td>{$row['documentoPaciente']}</td>
                    <td>{$row['observacaoPaciente']}</td>
                    <td>
                        <form action='atendimento.php' method='post'>
                            <input type='hidden' name='idPaciente' value='{$row['idPaciente']}'>
                            <button type='submit'>Atender</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum paciente encontrado.";
    }

    mysqli_close($conn);
    ?>
</div>

<script>
    function buscarPacientes() {
        var nomeBusca = $("#nomeBusca").val();
        $.ajax({
            url: 'buscar_pacientes.php',
            type: 'POST',
            data: { nomeBusca: nomeBusca },
            success: function(response) {
                $("#resultados").html(response);
            }
        });
    }
</script>
</body>
</html>
