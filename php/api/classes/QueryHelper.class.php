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
                        setor.nome_setor AS SETOR,
                        setor.peso AS PESO_SETOR,
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
                            WHEN chamados.status_chamado = 5 THEN 'Detalhar chamado'
                        END AS STATUS,
                        users.name_user AS NOME_SOLICITANTE,
                        users.idfr_code_user AS IDFR_SOLICITANTE,
                        categoria_servico.descricao_categoria_servico AS CAT_SERVICO,
                        categoria_ocorrencia.descricao_categoria_ocorrencia AS CAT_OCORRENCIA,
                        chamados.descricao_solucao AS SOLUCAO,
                        tecnico.idfr_code_user AS IDFR_TECNICO,
                        tecnico.name_user AS NOME_TECNICO,
                        DATE_FORMAT(chamados.data_conclusao, '%d/%m/%Y') AS DTA_CONCLUSAO,
                        CASE
                            WHEN chamados.solicitacao_atendida = 0 THEN 'Não'
                            WHEN chamados.solicitacao_atendida = 1 THEN 'Sim'
                        END AS ATENDIDO,
                        chamados.observacao AS OBSERVACAO,
                        chamados.observacao_cancelamento AS OBS_CANCELAMENTO,
                        chamados.observacao_detalhamento_tecnico AS SOL_DETALHAMENTO,
                        chamados.observacao_detalhamento_solicitante AS RESPOSTA_DETALHAMENTO,
                        chamados.total_acoes AS TOTAL_ACOES,
                        -- Cálculo de dias, horas e minutos de espera
                        CONCAT(
                            FLOOR(TIMESTAMPDIFF(MINUTE, chamados.data_criacao, IF(chamados.data_conclusao IS NOT NULL, chamados.data_conclusao, NOW())) / 1440), ' dia(s), ',  -- 1440 minutos em um dia
                            MOD(FLOOR(TIMESTAMPDIFF(MINUTE, chamados.data_criacao, IF(chamados.data_conclusao IS NOT NULL, chamados.data_conclusao, NOW())) / 60), 24), ' hora(s), ',  -- 60 minutos em uma hora, mod 24 para horas no intervalo de um dia
                            MOD(TIMESTAMPDIFF(MINUTE, chamados.data_criacao, IF(chamados.data_conclusao IS NOT NULL, chamados.data_conclusao, NOW())), 60), ' minuto(s)'  -- minutos restantes após calcular horas e dias
                        ) AS TEMPO_ESPERA
                    FROM helptek.chamados
                    LEFT JOIN users ON users.id_user = chamados.id_user
                    LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico
                    LEFT JOIN categoria_servico ON categoria_servico.id_categoria_servico = chamados.id_categoria_servico
                    LEFT JOIN categoria_ocorrencia ON categoria_ocorrencia.id_categoria_ocorrencia = chamados.id_categoria_ocorrencia
                    LEFT JOIN setor ON setor.id_setor = chamados.id_setor " . $sql_where;

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