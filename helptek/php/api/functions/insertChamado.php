<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertChamado"){
        var_dump($_POST);
        $titulo = $mysqli_con->real_escape_string($_POST['titulo']);
        $descricao = $mysqli_con->real_escape_string($_POST['descricao']);
        $cidadeC = $mysqli_con->real_escape_string($_POST['cidade']);
        $gravidade = $mysqli_con->real_escape_string($_POST['gravidade']);
        $urgencia = $mysqli_con->real_escape_string($_POST['urgencia']);
        $tendencia = $mysqli_con->real_escape_string($_POST['tendencia']);
        //$anexo = $mysqli_con->real_escape_string($_POST['anexo']);

        $prioridade = (intval($gravidade)*intval($urgencia)*intval($tendencia));

        $id_user = $_POST['id_user'];

        $select = "SELECT id_user FROM users WHERE id_user = '$id_user'";
        $res_select = $mysqli_con->query($select);
        $obj = $res_select->fetch_object();

        if ($res_select->num_rows == 1) {
            $id_user = $obj->id_user;

            $sql = "INSERT INTO chamados(`id_user`,`titulo_chamado`,`cidade`,`descricao_chamado`,`gravidade`,`urgencia`,`tendencia`,`prioridade_chamado`)
            VALUES('$id_user','$titulo','$cidadeC','$descricao','$gravidade','$urgencia','$tendencia','$prioridade')";

            $result = $mysqli_con->query($sql);

            if ($result){
                $id_chamado = $mysqli_con->insert_id;
                $data_criacao = date('dmy');
                $idfr_chamado = "ID0" . $id_chamado . $id_user . $data_criacao;
                $acao = "USUARIO $id_user REGISTROU O CHAMADO $idfr_chamado";

                $update_sql = "UPDATE chamados SET idfr_chamado = '$idfr_chamado' WHERE id_chamado = '$id_chamado'";
                $update_result = $mysqli_con->query($update_sql);

                if ($update_result){
                    $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_chamado`, `acao`) VALUES('$id_chamado', '$id_user','$idfr_chamado','$acao')";
                    $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                    if ($result_acompanhamento){
                        $res['msg'] = "Chamado registrado com sucesso!";
                    }else{
                        $res['error'] = true;
                        $res['msg'] = "Erro ao registrar acompanhamento";
                    }
                }else{
                    $res['error'] = true;
                    $res['msg'] = "Erro ao atualizar identificador do chamado!";
                }
            }else{
                $res['error'] = true;
                $res['msg'] = "Erro ao registrar chamado!";
            }
        } else{
            $res['error'] = true;
            $res['msg'] = "Erro ao registrar chamado, acionar TI";
        }

    }else{
        $res['error'] = true;
        $res['msg'] = "Ocorreu um erro inesperado! Tente novamente mais tarde";
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>