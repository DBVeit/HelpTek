<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertChamado"){
        $titulo = $mysqli_con->real_escape_string($_POST['titulo']);
        $descricao = $mysqli_con->real_escape_string($_POST['descricao']);
        $gravidade = $mysqli_con->real_escape_string($_POST['gravidade']);
        $urgencia = $mysqli_con->real_escape_string($_POST['urgencia']);
        $tendencia = $mysqli_con->real_escape_string($_POST['tendencia']);

        $prioridade = (intval($gravidade)*intval($urgencia)*intval($tendencia));

        $id_user = $_POST['id_user'];

        $select = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user'";
        $res_select = $mysqli_con->query($select);
        $obj = $res_select->fetch_object();

        if ($res_select->num_rows == 1) {
            $id_user = $obj->id_user;
            $idfr_code_user = $obj->idfr_code_user;

            $sql = "INSERT INTO chamados(`id_user`,`titulo_chamado`,`descricao_chamado`,`gravidade`,`urgencia`,`tendencia`,`prioridade_chamado`,`data_atualizacao`,`id_usuario_atual`,`total_acoes`) VALUES('$id_user','$titulo','$descricao','$gravidade','$urgencia','$tendencia','$prioridade',NOW(),$id_user,1)";

            $result = $mysqli_con->query($sql);

            if ($result){
                $id_chamado = $mysqli_con->insert_id;
                $data_criacao = date('dmy');
                $idfr_chamado = "ID0" . $id_chamado . $id_user . $data_criacao;
                $acao = "USUARIO $idfr_code_user REGISTROU O CHAMADO $idfr_chamado";

                $update_sql = "UPDATE chamados SET idfr_chamado = '$idfr_chamado' WHERE id_chamado = '$id_chamado'";
                $update_result = $mysqli_con->query($update_sql);

                if ($update_result){
                    $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_code_user`, `idfr_chamado`, `acao`, `descricao_acao`, `id_usuario_acao`, `status_chamado`) VALUES('$id_chamado', '$id_user','$idfr_code_user','$idfr_chamado','$acao','$descricao','$id_user',1)";
                    $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                    if ($result_acompanhamento){
                        $upload_dir = "../../public/uploads/"; // Diretório onde os arquivos serão armazenados
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        $total_arquivos = count($_FILES);
                        for ($i = 0; $i < $total_arquivos; $i++) {
                            $file_key = "anexo" . $i;
                            if (isset($_FILES[$file_key])) {
                                $file_name = $_FILES[$file_key]['name'];
                                $file_tmp = $_FILES[$file_key]['tmp_name'];
                                $file_size = $_FILES[$file_key]['size'];

                                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                                $new_file_name = uniqid() . '.' . $file_ext;
                                $file_dest = $upload_dir . $new_file_name;

                                if (move_uploaded_file($file_tmp, $file_dest)) {
                                    $sql_file = "INSERT INTO anexos_chamados (`id_chamado`, `nome_arquivo`, `caminho_arquivo`, `tamanho_arquivo`) VALUES('$id_chamado', '$file_name', '$file_dest', '$file_size')";
                                    $mysqli_con->query($sql_file);
                                }
                            }
                        }

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