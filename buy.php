<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDAO.php';

session_start();

if (empty($_SESSION['member'])) {
    header('Location :login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location :cart.php');
    exit;
}
$member = $_SESSION['member'];
$cartDAO = new CartDAO();
$cart_list = $cartDAO->get_cart_by_memberid($member->memberid);

$saleDAO = new SaleDAO();
$saleDAO->insert($member->memberid, $cart_list);

$cartDAO->delete_by_memberid($member->memberid);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入完了</title>
</head>

<body>
    <img src="images/JecShoppingLogo.jpg" alt="JecShopping ロゴ">
    <p>購入完了しました。</p><br />
    <hr>
    <a href="index.php">
        <input type="submit" value="トップページへ">

    </a>
</body>

</html>