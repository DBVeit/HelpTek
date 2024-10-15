<?php

class QueryHelper {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function getDadosRelatorioSelect ($sql_where, $params, $types) {
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
                        tecnico.idfr_code_user AS IDFR_TECNICO,
                        tecnico.name_user AS NOME_TECNICO,
                        DATE_FORMAT(chamados.data_conclusao, '%d/%m/%Y') AS DTA_CONCLUSAO,
                        chamados.observacao AS OBSERVACAO,
                        chamados.total_acoes AS TOTAL_ACOES
                    FROM helptek.chamados
                    LEFT JOIN users ON users.id_user = chamados.id_user
                    LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico
                    LEFT JOIN categoria_servico ON categoria_servico.id_categoria_servico = chamados.id_categoria_servico
                    LEFT JOIN categoria_ocorrencia ON categoria_ocorrencia.id_categoria_ocorrencia = chamados.id_categoria_ocorrencia " . $sql_where;

        $stmt = $this->mysqli->prepare($sql_select);

        if ($stmt === false) {
            throw new Exception("Erro ao preparar a consulta SQL: " . $this->mysqli->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
}
?>