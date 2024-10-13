<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";
include "../../../../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$res = array('error' => false, 'data' => []);

if (isset($_POST['status_chamado'])) {
    $status_chamado = $mysqli_con->real_escape_string($_POST['status_chamado']);
    $data_criacao_inicio = $mysqli_con->real_escape_string($_POST['dta_criacao_inicio']);
    $data_criacao_fim = $mysqli_con->real_escape_string($_POST['dta_criacao_fim']);

    $sql_select = "SELECT 
                        chamados.id_chamado AS ID_CHAMADO,
                        chamados.idfr_chamado AS IDFR_CHAMADO,
                        chamados.titulo_chamado AS TITULO_CHAMADO,
                        chamados.descricao_chamado AS DESCRICAO_CHAMADO,
                        chamados.gravidade AS GRAVIDADE,
                        chamados.urgencia AS URGENCIA,
                        chamados.tendencia AS TENDENCIA,
                        CASE 
                            WHEN chamados.prioridade_chamado > 100 THEN 'Crítica'
                            WHEN chamados.prioridade_chamado > 60 THEN 'Alta'
                            WHEN chamados.prioridade_chamado > 20 THEN 'Média'
                            ELSE 'Baixa'
                        END AS PRIORIDADE,
                        DATE_FORMAT(chamados.data_criacao, '%d/%m/%Y') AS DTA_CRIACAO,
                        CASE
                            WHEN chamados.status_chamado = 0 THEN 'Cancelado'
                            WHEN chamados.status_chamado = 1 THEN 'Em aberto'
                            WHEN chamados.status_chamado = 2 THEN 'Em atendimento'
                            WHEN chamados.status_chamado = 3 THEN 'Respondido'
                            WHEN chamados.status_chamado = 4 THEN 'Concluído'
                        END AS STATUS,
                        users.name_user AS NOME_SOLICITANTE,
                        users.idfr_code_user AS IDFR_SOLICITANTE,
                        categoria_servico.descricao_categoria_servico AS CAT_SERVICO,
                        categoria_ocorrencia.descricao_categoria_ocorrencia AS CAT_OCORRENCIA,
                        chamados.descricao_solucao AS SOLUCAO,
                        DATE_FORMAT(chamados.data_conclusao, '%d/%m/%Y') AS DTA_CONCLUSAO,
                        chamados.observacao AS OBSERVACAO,
                        chamados.total_acoes AS TOTAL_ACOES
                    FROM helptek.chamados
                    LEFT JOIN users ON users.id_user = chamados.id_user
                    LEFT JOIN categoria_servico ON categoria_servico.id_categoria_servico = chamados.id_categoria_servico
                    LEFT JOIN categoria_ocorrencia ON categoria_ocorrencia.id_categoria_ocorrencia = chamados.id_categoria_ocorrencia";

    $params = [];
    $types = "";

    if ($data_criacao_inicio == "" && $data_criacao_fim == ""){
        $sql_select .= " WHERE chamados.status_chamado = ?";
        $params[] = $status_chamado;
        $types .= 's';
    } else if ($data_criacao_inicio != "" && $data_criacao_fim != "") {
        $sql_select .= " WHERE chamados.status_chamado = ? AND chamados.data_criacao BETWEEN ? AND ?";
        $params[] = $status_chamado;
        $params[] = $data_criacao_inicio;
        $params[] = $data_criacao_fim;
        $types .= 'sss';
    } else if ($data_criacao_inicio != "" && $data_criacao_fim == "") {
        $sql_select .= " WHERE chamados.status_chamado = ? AND chamados.data_criacao >= ?";
        $params[] = $status_chamado;
        $params[] = $data_criacao_inicio;
        $types .= 'ss';
    } else if ($data_criacao_inicio == "" && $data_criacao_fim != "") {
        $sql_select .= " WHERE chamados.status_chamado = ? AND chamados.data_criacao <= ?";
        $params[] = $status_chamado;
        $params[] = $data_criacao_fim;
        $types .= 'ss';
    }

    $stmt = $mysqli_con->prepare($sql_select);

    if ($stmt === false) {
        $res['error'] = true;
        $res['msg'] = "Erro ao preparar a consulta SQL";
    } else {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        $result_select = $stmt->get_result();

        // Criando a planilha Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID_CHAMADO');
        $sheet->setCellValue('B1', 'TITULO_CHAMADO');
        $sheet->setCellValue('C1', 'DESCRICAO_CHAMADO');
        $sheet->setCellValue('D1', 'GRAVIDADE');
        $sheet->setCellValue('E1', 'URGENCIA');
        $sheet->setCellValue('F1', 'TENDENCIA');
        $sheet->setCellValue('G1', 'PRIORIDADE');
        $sheet->setCellValue('H1', 'DTA_CRIACAO');
        $sheet->setCellValue('I1', 'STATUS');

        // Preenchendo os dados
        $row = 2;
        while ($row_data = $result_select->fetch_assoc()) {
            $sheet->setCellValue('A' . $row, $row_data['ID_CHAMADO']);
            $sheet->setCellValue('B' . $row, $row_data['TITULO_CHAMADO']);
            $sheet->setCellValue('C' . $row, $row_data['DESCRICAO_CHAMADO']);
            $sheet->setCellValue('D' . $row, $row_data['GRAVIDADE']);
            $sheet->setCellValue('E' . $row, $row_data['URGENCIA']);
            $sheet->setCellValue('F' . $row, $row_data['TENDENCIA']);
            $sheet->setCellValue('G' . $row, $row_data['PRIORIDADE']);
            $sheet->setCellValue('H' . $row, $row_data['DTA_CRIACAO']);
            $sheet->setCellValue('I' . $row, $row_data['STATUS']);
            $row++;
        }

        // Criando o arquivo Excel para download
        $writer = new Xlsx($spreadsheet);
        $file_name = 'relatorio_chamados_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        $writer->save('php://output');

        if ($result_select) {
            $total = mysqli_num_rows($result_select);
            $res['total'] = $total;
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro ao buscar dados";
        }
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
