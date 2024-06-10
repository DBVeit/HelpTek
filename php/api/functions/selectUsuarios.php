<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'usuarios' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "selectUsuarios") {
        if (isset($_GET['status_user'])){
            $status_user = $_GET['status_user'];
            $sql = "SELECT *,DATE_FORMAT(date_user, '%d/%m/%Y') AS data_criacao_fm FROM users WHERE status_user = '$status_user'";
        }else{
            $sql = "SELECT *,DATE_FORMAT(date_user, '%d/%m/%Y') AS data_criacao_fm FROM users";
        }

        if (isset($sql)){
            $result = $mysqli_con->query($sql);

            if ($result){
                $rows = mysqli_num_rows($result);
                $usuarios = array();

                if ($rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status_user_ret = $row['status_user'];
                        $permissao_user_ret = $row['id_permissao'];
                        switch ($status_user_ret){
                            case 1:
                                $status_user_desc = "Ativo";
                                break;
                            case 0:
                                $status_user_desc = "Inativo";
                                break;
                            }
                            switch ($permissao_user_ret){
                                case 1:
                                    $permissao_user_desc = "Solicitante";
                                    break;
                                case 2:
                                    $permissao_user_desc = "Técnico";
                                    break;
                                case 3:
                                    $permissao_user_desc = "Gerente técnico";
                                    break;
                                case 4:
                                    $permissao_user_desc = "Administrador";
                                    break;
                            }
                            $row['status_user_desc'] = $status_user_desc;
                            $row['permissao_user_desc'] = $permissao_user_desc;
                            $usuarios[] = $row;
                        }

                        $res['error'] = false;
                        $res['msg'] = "Listagem";
                        $res['usuarios'] = $usuarios;
                    } else {
                        $res['error'] = false;
                        $res['msg'] = "Não há dados para exibição";
                    }
            }else {
                $res['error'] = true;
                $res['msg'] = "Erro na execução da consulta";
            }
        }else {
            $res['error'] = true;
            $res['msg'] = "Falha ao consultar";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação inválida";
    }
}else {
    $res['error'] = true;
    $res['msg'] = "Parâmetros insuficientes";
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>