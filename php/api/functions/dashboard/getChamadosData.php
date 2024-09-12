<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'data' => []);

if (isset($_GET['action']) && $_GET['action'] == 'getChamadosData' && isset($_GET['consulta'])) {
    $consulta = $_GET['consulta'];

    // Determinar a consulta SQL com base no tipo de consulta selecionado
    switch ($consulta) {
        case 'status':
            $sql_select = "SELECT status_chamado, COUNT(*) as total 
                           FROM chamados 
                           GROUP BY status_chamado";
            break;

        case 'prioridade':
            $sql_select = "SELECT prioridade_chamado, COUNT(*) as total 
                           FROM chamados 
                           GROUP BY prioridade_chamado";
            break;

        case 'tecnico':
            $sql_select = "SELECT id_user_tecnico, COUNT(*) as total 
                           FROM chamados 
                           GROUP BY id_user_tecnico";
            break;

        // Adicione as demais opções aqui (periodo, setor, usuario)
        default:
            $res['error'] = true;
            $res['msg'] = "Tipo de consulta inválido";
            echo json_encode($res);
            die();
    }

    $result_select = $mysqli_con->query($sql_select);

    if ($result_select) {
        while ($row = $result_select->fetch_assoc()) {
            // Criar labels e dados dinâmicos para o gráfico
            $label = ''; // Definir o label conforme a consulta
            switch ($consulta) {
                case 'status':
                    $label = "Status " . $row['status_chamado'];
                    break;
                case 'prioridade':
                    $label = "Prioridade " . $row['prioridade_chamado'];
                    break;
                case 'tecnico':
                    $label = "Técnico " . $row['id_user_tecnico'];
                    break;
            }
            $res['data'][] = [
                'label' => $label,
                'total' => $row['total']
            ];
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Erro ao buscar dados";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Parâmetros inválidos";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>
