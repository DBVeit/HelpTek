<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";
include "../../../../../vendor/autoload.php";
require_once "../../classes/PlanilhaHelper.class.php";
require_once "../../classes/QueryHelper.class.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$res = array('error' => false, 'data' => []);

if (isset($_POST['dta_conclusao_inicio']) || isset($_POST['dta_conclusao_fim'])) {
    $dta_conclusao_inicio = $mysqli_con->real_escape_string($_POST['dta_conclusao_inicio']);
    $dta_conclusao_fim = $mysqli_con->real_escape_string($_POST['dta_conclusao_fim']);

    list($sql_where, $params, $types) = montarWhere($dta_conclusao_inicio, $dta_conclusao_fim);

    $sql_count = "SELECT COUNT(*) AS total FROM chamados " . $sql_where;

    $stmt_count = $mysqli_con->prepare($sql_count);
    if ($stmt_count === false) {
        $res['error'] = true;
        $res['msg'] = "Erro ao preparar a consulta de contagem";
    } else {
        if (!empty($params)) {
            $stmt_count->bind_param($types, ...$params);
        }
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $total = $result_count->fetch_assoc()['total'];
        $res['total'] = $total;

        $stmt_count->close();
    }

    $queryHelper = new QueryHelper($mysqli_con);
    try {
        $result = $queryHelper->getDadosRelatorioSelect($sql_where, $params, $types);
        $chamados = [];

        while ($row = $result->fetch_assoc()) {
            $chamados[] = $row;
        }

        if (!empty($chamados)) {
            $spreadsheet = PlanilhaHelper::gerarPlanilha($chamados, "Chamados conclusão");
            PlanilhaHelper::salvarPlanilha($spreadsheet, "chamados_conclusao.xlsx");
        } else {
            $res['error'] = true;
            $res['msg'] = "Nenhum dado encontrado para os parâmetros fornecidos.";
        }
    } catch (Exception $e) {
        $res['error'] = true;
        $res['msg'] = $e->getMessage();
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Parâmetros inválidos";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();

function montarWhere($dta_conclusao_inicio, $dta_conclusao_fim) {
    $sql_where = "WHERE 1=1";
    $params = [];
    $types = "";

    if ($dta_conclusao_inicio != "" && $dta_conclusao_fim != "") {
        $sql_where .= " AND chamados.data_conclusao BETWEEN ? AND ?";
        $params[] = $dta_conclusao_inicio;
        $params[] = $dta_conclusao_fim;
        $types .= 'ss';
    } else if ($dta_conclusao_inicio != "" && $dta_conclusao_fim == "") {
        $sql_where .= " AND chamados.data_conclusao >= ?";
        $params[] = $dta_conclusao_inicio;
        $types .= 's';
    } else if ($dta_conclusao_inicio == "" && $dta_conclusao_fim != "") {
        $sql_where .= " AND chamados.data_conclusao <= ?";
        $params[] = $dta_conclusao_inicio;
        $types .= 's';
    }

    return [$sql_where, $params, $types];

}

?>
