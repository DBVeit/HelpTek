<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$response = array('error' => false, 'menus' => array(), 'msg' => '');

if (isset($_GET['id_user'])){
    $id_user = $_GET['id_user'];

    $sql = "SELECT id_permissao FROM users WHERE id_user = ?";
    $stmt = $mysqli_con->prepare($sql);
    $stmt->bind_param("i",$id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_permissao = $row['id_permissao'];

        $sql = "SELECT m.descricao_menu FROM menus m INNER JOIN permissoes_menus pm ON m.id_menu=pm.id_menu WHERE pm.id_permissao = ?";
        $stmt = $mysqli_con->prepare($sql);
        $stmt->bind_param("i",$id_permissao);
        $stmt->execute();
        $result = $stmt->get_result();

        $menus = array();

        while ($row = $result->fetch_assoc()){
            $menus[] = $row['descricao_menu'];
        }
        $response['menus'] = $menus;

    }else{
        $response['error'] = true;
        $response['msg'] = "Usuário não encontrado";
    }
    $stmt->close();

}else{
    $response['error'] = true;
    $response['msg'] = 'ID do usuário é inválido';
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($response);
die();

?>