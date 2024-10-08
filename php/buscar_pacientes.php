<?php

require_once('conn.php');

$conn = mysqli_connect($servername, $username, $password, $dbname) or die('Erro ao conectar ao banco de dados');

// Verifica se foi enviado um nome para pesquisa
$nomeBusca = isset($_POST['nomeBusca']) ? $_POST['nomeBusca'] : '';

// Consulta para obter os pacientes filtrados pelo nome
if ($nomeBusca) {
    $sql = "SELECT * FROM paciente WHERE nomePaciente LIKE ? COLLATE utf8mb4_general_ci";
    $stmt = mysqli_prepare($conn, $sql);
    $param = "%$nomeBusca%";
    mysqli_stmt_bind_param($stmt, 's', $param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT * FROM paciente";
    $result = mysqli_query($conn, $sql);
}

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
                <td class='observacao'>{$row['observacaoPaciente']}</td> 
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

// Fechar a conexão
mysqli_close($conn);
?>
