<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDAO.php';

session_start();

$member = $_SESSION['member'];
$cartDAO = new CartDAO();
$cart_list = $cartDAO->get_cart_by_memberid($member->memberid);

$saleDAO = new SaleDAO();
$saleDAO->insert($member->memberid, $cart_list);

echo 'テスト終了';
exit;
