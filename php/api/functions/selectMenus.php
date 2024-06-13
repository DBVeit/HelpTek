<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'menus' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_user = $_GET['id_user'];
    $permission = $_GET['permission'];

    if ($action == "selectMenus") {
        $sql = "SELECT * FROM permissoes_menus WHERE id_permissao='$permission'";
        $result = $mysqli_con->query($sql);
        $num = mysqli_num_rows($result);
        $menus = array();

        if ($num > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($menus, $row);
            }
            $res['error'] = false;
            $res['msg'] = "Listagem";
            $res['menus'] = $menus;
        } else {
            $res['error'] = false;
            $res['msg'] = "Não há dados para exibição";
        }
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>