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
            $sql_select = "SELECT 
                          CASE 
                            WHEN prioridade_chamado <= 20 THEN 'Baixa'
                            WHEN prioridade_chamado > 20 AND prioridade_chamado <= 60 THEN 'Média'
                            WHEN prioridade_chamado > 60 AND prioridade_chamado <= 100 THEN 'Alta'
                            ELSE 'Crítica'
                          END as prioridade_desc,
                          COUNT(*) as total
                       FROM chamados
                       GROUP BY prioridade_desc";
            break;

        case 'tecnico':
            $sql_select = "SELECT id_user_tecnico, COUNT(*) as total 
                           FROM chamados 
                           GROUP BY id_user_tecnico";
            break;

        case 'setor':
            $sql_select = "SELECT id_setor, COUNT(*) as total 
                           FROM chamados 
                           GROUP BY id_setor";
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

            $data = [
                'total' => $row['total']
            ];

            switch ($consulta) {
                case 'status':
                    $data['status_chamado'] = $row['status_chamado'];
                    break;
                case 'prioridade':
                    $data['prioridade_chamado'] = $row['prioridade_desc'];
                    break;
                case 'tecnico':
                    $data['id_user_tecnico'] = $row['id_user_tecnico'];
                    break;
            }
            $res['data'][] = $data;
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
