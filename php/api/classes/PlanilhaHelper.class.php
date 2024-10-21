<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PlanilhaHelper {
    public static function gerarPlanilha($dados, $tituloPlanilha) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'IDFR_CHAMADO');
        $sheet->setCellValue('B1', 'TITULO_CHAMADO');
        $sheet->setCellValue('C1', 'DESCRICAO_CHAMADO');
        $sheet->setCellValue('D1', 'SETOR');
        $sheet->setCellValue('E1', 'PESO_SETOR');
        $sheet->setCellValue('F1', 'GRAVIDADE');
        $sheet->setCellValue('G1', 'URGENCIA');
        $sheet->setCellValue('H1', 'TENDENCIA');
        $sheet->setCellValue('I1', 'PRIORIDADE');
        $sheet->setCellValue('J1', 'DTA_CRIACAO');
        $sheet->setCellValue('K1', 'STATUS');
        $sheet->setCellValue('L1', 'NOME_SOLICITANTE');
        $sheet->setCellValue('M1', 'IDFR_SOLICITANTE');
        $sheet->setCellValue('N1', 'CAT_SERVICO');
        $sheet->setCellValue('O1', 'CAT_OCORRENCIA');
        $sheet->setCellValue('P1', 'SOLUCAO');
        $sheet->setCellValue('Q1', 'IDFR_TECNICO');
        $sheet->setCellValue('R1', 'NOME_TECNICO');
        $sheet->setCellValue('S1', 'DTA_CONCLUSAO');
        $sheet->setCellValue('T1', 'OBSERVACAO');
        $sheet->setCellValue('U1', 'TOTAL_ACOES');

        $linha = 2;
        foreach ($dados as $dado) {
            $sheet->setCellValue('A' . $linha, $dado['IDFR_CHAMADO']);
            $sheet->setCellValue('B' . $linha, $dado['TITULO_CHAMADO']);
            $sheet->setCellValue('C' . $linha, $dado['DESCRICAO_CHAMADO']);
            $sheet->setCellValue('D' . $linha, $dado['SETOR']);
            $sheet->setCellValue('E' . $linha, $dado['PESO_SETOR']);
            $sheet->setCellValue('F' . $linha, $dado['GRAVIDADE']);
            $sheet->setCellValue('G' . $linha, $dado['URGENCIA']);
            $sheet->setCellValue('H' . $linha, $dado['TENDENCIA']);
            $sheet->setCellValue('I' . $linha, $dado['PRIORIDADE']);
            $sheet->setCellValue('J' . $linha, $dado['DTA_CRIACAO']);
            $sheet->setCellValue('K' . $linha, $dado['STATUS']);
            $sheet->setCellValue('L' . $linha, $dado['NOME_SOLICITANTE']);
            $sheet->setCellValue('M' . $linha, $dado['IDFR_SOLICITANTE']);
            $sheet->setCellValue('N' . $linha, $dado['CAT_SERVICO']);
            $sheet->setCellValue('O' . $linha, $dado['CAT_OCORRENCIA']);
            $sheet->setCellValue('P' . $linha, $dado['SOLUCAO']);
            $sheet->setCellValue('Q' . $linha, $dado['IDFR_TECNICO']);
            $sheet->setCellValue('R' . $linha, $dado['NOME_TECNICO']);
            $sheet->setCellValue('S' . $linha, $dado['DTA_CONCLUSAO']);
            $sheet->setCellValue('T' . $linha, $dado['OBSERVACAO']);
            $sheet->setCellValue('U' . $linha, $dado['TOTAL_ACOES']);
            $linha++;
        }
        return $spreadsheet;
    }

    public static function salvarPlanilha($spreadsheet, $nomeArquivo) {
        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $download_dir = "../../public/downloads/";

        // Verifica se a pasta de downloads existe, caso contrário, cria a pasta
        if (!is_dir($download_dir)) {
            mkdir($download_dir, 0777, true);
        }

        $filepath = $download_dir . $nomeArquivo;
        $writer->save($filepath);

        exit;
    }
}