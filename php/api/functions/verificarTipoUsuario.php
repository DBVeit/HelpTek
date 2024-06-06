<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'isTecnico' => false);

if (isset($_GET['id_user'])){
    $id_user = $_GET['id_user'];

    $sql = "SELECT id_permissao FROM users WHERE id_user = '$id_user'";
    $result = $mysqli_con->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['id_permissao'] == 2){
            $res['isTecnico'] = true;
            $res['msg'] = "Usuario TECNICO logado";
        } elseif ($row['id_permissao'] == 1){
            $res['msg'] = "Usuario SOLICITANTE logado";
        } elseif ($row['id_permissao'] == 3) {
            $res['msg'] = "Usuario GERENTE logado";
        } elseif ($row['id_permissao'] == 4) {
            $res['msg'] = "Usuario ADMINISTRADOR logado";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Usuário não logado!";
    }
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
