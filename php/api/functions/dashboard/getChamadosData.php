<?php
global $mysqli_con;
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
            $sql_select = "SELECT 
                            chamados.id_user_tecnico,
                            tecnico.idfr_code_user AS IDFR_TECNICO,
                            tecnico.name_user AS NOME_TECNICO,
                            COUNT(*) as total
                           FROM chamados 
                            LEFT JOIN users ON users.id_user = chamados.id_user
                            LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico
                           WHERE chamados.id_user_tecnico <> ''
                           GROUP BY chamados.id_user_tecnico";
            break;

        case 'setor':
            $sql_select = "SELECT 
	                        chamados.id_setor,
	                        setor.nome_setor AS SETOR,
	                        setor.peso AS PESO,
	                        COUNT(*) as total 
                        FROM chamados 
                        LEFT JOIN setor ON setor.id_setor = chamados.id_setor
                        WHERE chamados.id_setor >= 0
                        GROUP BY id_setor";
            break;

        case 'solicitante':
            $sql_select = "SELECT 
                            chamados.id_user,
                            users.idfr_code_user AS IDFR_SOLICITANTE,
                            users.name_user AS NOME_SOLICITANTE,
                            COUNT(*) as total
                           FROM chamados 
                            LEFT JOIN users ON users.id_user = chamados.id_user
                           WHERE chamados.id_user <> ''
                           GROUP BY chamados.id_user";
            break;

        case 'cat_serv':
            $sql_select = "SELECT 
                            chamados.id_categoria_servico,
                            categoria_servico.descricao_categoria_servico AS CAT_SERV,
                            COUNT(*) as total 
                        FROM chamados 
                        LEFT JOIN categoria_servico ON categoria_servico.id_categoria_servico = chamados.id_categoria_servico
                        WHERE chamados.id_categoria_servico <> ''
                        GROUP BY chamados.id_categoria_servico";
            break;

        case 'cat_ocor':
            $sql_select = "SELECT 
                            chamados.id_categoria_ocorrencia,
                            categoria_ocorrencia.descricao_categoria_ocorrencia AS CAT_OCOR,
                            COUNT(*) as total 
                        FROM chamados 
                        LEFT JOIN categoria_ocorrencia ON categoria_ocorrencia.id_categoria_ocorrencia = chamados.id_categoria_ocorrencia
                        WHERE chamados.id_categoria_ocorrencia <> ''
                        GROUP BY chamados.id_categoria_ocorrencia";
            break;

        case 'dta_abr':
            $sql_select = "SELECT 
                            DATE_FORMAT(DATE(chamados.data_criacao), '%d/%m/%Y') AS DTA_CRIACAO,
                            COUNT(*) as total 
                        FROM chamados 
                        GROUP BY DATE(chamados.data_criacao)";
            break;

        case 'dta_conc':
            $sql_select = "SELECT 
                            DATE_FORMAT(DATE(chamados.data_conclusao), '%d/%m/%Y') AS DTA_CONCLUSAO,
                            COUNT(*) as total 
                        FROM chamados 
                        WHERE chamados.data_conclusao IS NOT NULL
                        GROUP BY DATE(chamados.data_conclusao)";
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
                    $data['nome_tecnico'] = $row['NOME_TECNICO'];
                    $data['idfr_tecnico'] = $row['IDFR_TECNICO'];
                    break;
                case 'setor':
                    $data['id_setor'] = $row['id_setor'];
                    $data['setor'] = $row['SETOR'];
                    $data['peso'] = $row['PESO'];
                    break;
                case 'solicitante':
                    $data['id_user'] = $row['id_user'];
                    $data['nome_solicitante'] = $row['NOME_SOLICITANTE'];
                    $data['idfr_solicitante'] = $row['IDFR_SOLICITANTE'];
                    break;
                case 'cat_serv':
                    $data['id_categoria_servico'] = $row['id_categoria_servico'];
                    $data['cat_serv'] = $row['CAT_SERV'];
                    break;
                case 'cat_ocor':
                    $data['id_categoria_ocorrencia'] = $row['id_categoria_ocorrencia'];
                    $data['cat_ocor'] = $row['CAT_OCOR'];
                    break;
                case 'dta_abr':
                    $data['dta_criacao'] = $row['DTA_CRIACAO'];
                    break;
                case 'dta_conc':
                    $data['dta_conclusao'] = $row['DTA_CONCLUSAO'];
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
