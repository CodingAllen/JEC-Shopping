<?php
require_once './helpers/CartDAO.php';
require_once './helpers/MemberDAO.php';

session_start();

if (empty($_SESSION['member'])) {
    header('Location :login.php');
    exit;
}
//ログイン中の会員情報を取得
$member = $_SESSION['member'];
//POSTメソッドでリクエストされた時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $goodscode = $_POST['goodscode'];
        $num = $_POST['num'];
        $cartDAO = new CartDAO();
        $cartDAO->insert($member->memberid, $goodscode, $num);
    } elseif (isset($_POST['change'])) {
        $goodscode = $_POST['goodscode'];
        $num = $_POST['num'];

        $cartDAO = new CartDAO();
        $cartDAO->update($member->memberid, $goodscode, $num);
    } elseif (isset($_POST['delete'])) {
        $goodscode = $_POST['goodscode'];
        $cartDAO = new CartDAO();
        $cartDAO->delete($member->memberid, $goodscode);
    }
    header("Location:" . $_SERVER['PHP_SELF']);
    exit;
}

$cartDAO = new CartDAO();
$cart_list = $cartDAO->get_cart_by_memberid($member->memberid);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショッピングカード</title>
</head>

<body>
    <?php include "header.php" ?>
    <?php if (empty($cart_list)) : ?>
        <P>カートに商品はありません。</P>
    <?php else : ?>
        <?php foreach ($cart_list as $cart) : ?>
            <table>
                <tr>
                    <td rowspan="4">
                        <img src="images/goods/<?= $cart->goodsimage ?>">
                    </td>
                    <td>
                        <?= $cart->goodsname ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $cart->detail ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        \<?= number_format($cart->price) ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <form action="" method="POST">
                            数量
                            <input type="number" min="1" max="10" name="num" value="<?= $cart->num ?>">
                            <input type="hidden" name="goodscode" value="<?= $cart->goodscode ?>">
                            <input type="submit" name="change" value="変更">
                            <input type="submit" name="delete" value="削除">

                        </form>
                    </td>
                </tr>

            </table>
            <hr>
        <?php endforeach; ?>
        <form action="buy.php" method="post">
            <input type="submit" value="購入">
        </form>
    <?php endif; ?>
</body>

</html>