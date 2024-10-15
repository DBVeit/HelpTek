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
        $sheet->setCellValue('D1', 'GRAVIDADE');
        $sheet->setCellValue('E1', 'URGENCIA');
        $sheet->setCellValue('F1', 'TENDENCIA');
        $sheet->setCellValue('G1', 'PRIORIDADE');
        $sheet->setCellValue('H1', 'DTA_CRIACAO');
        $sheet->setCellValue('I1', 'STATUS');
        $sheet->setCellValue('J1', 'NOME_SOLICITANTE');
        $sheet->setCellValue('K1', 'IDFR_SOLICITANTE');
        $sheet->setCellValue('L1', 'CAT_SERVICO');
        $sheet->setCellValue('M1', 'CAT_OCORRENCIA');
        $sheet->setCellValue('N1', 'SOLUCAO');
        $sheet->setCellValue('O1', 'IDFR_TECNICO');
        $sheet->setCellValue('P1', 'NOME_TECNICO');
        $sheet->setCellValue('Q1', 'DTA_CONCLUSAO');
        $sheet->setCellValue('R1', 'OBSERVACAO');
        $sheet->setCellValue('S1', 'TOTAL_ACOES');

        $linha = 2;
        foreach ($dados as $dado) {
            $sheet->setCellValue('A' . $linha, $dado['IDFR_CHAMADO']);
            $sheet->setCellValue('B' . $linha, $dado['TITULO_CHAMADO']);
            $sheet->setCellValue('C' . $linha, $dado['DESCRICAO_CHAMADO']);
            $sheet->setCellValue('D' . $linha, $dado['GRAVIDADE']);
            $sheet->setCellValue('E' . $linha, $dado['URGENCIA']);
            $sheet->setCellValue('F' . $linha, $dado['TENDENCIA']);
            $sheet->setCellValue('G' . $linha, $dado['PRIORIDADE']);
            $sheet->setCellValue('H' . $linha, $dado['DTA_CRIACAO']);
            $sheet->setCellValue('I' . $linha, $dado['STATUS']);
            $sheet->setCellValue('J' . $linha, $dado['NOME_SOLICITANTE']);
            $sheet->setCellValue('K' . $linha, $dado['IDFR_SOLICITANTE']);
            $sheet->setCellValue('L' . $linha, $dado['CAT_SERVICO']);
            $sheet->setCellValue('M' . $linha, $dado['CAT_OCORRENCIA']);
            $sheet->setCellValue('N' . $linha, $dado['SOLUCAO']);
            $sheet->setCellValue('O' . $linha, $dado['IDFR_TECNICO']);
            $sheet->setCellValue('P' . $linha, $dado['NOME_TECNICO']);
            $sheet->setCellValue('Q' . $linha, $dado['DTA_CONCLUSAO']);
            $sheet->setCellValue('R' . $linha, $dado['OBSERVACAO']);
            $sheet->setCellValue('S' . $linha, $dado['TOTAL_ACOES']);
            $linha++;
        }
        return $spreadsheet;
    }

    public static function salvarPlanilha($spreadsheet, $nomeArquivo) {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
        $writer->save($nomeArquivo);
    }
}