<?php
require_once './helpers/GoodsDAO.php';

if (isset($_GET['goodscode'])) {
    $goodscode = $_GET['goodscode'];
    $goodsDAO = new GoodsDAO();
    $goods = $goodsDAO->get_goods_by_goodscode($goodscode);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include "header.php" ?>
    <table>
        <tr>
            <td rowspan="5">
                <img src="images/goods/<?= $goods->goodsimage ?>">
            </td>
            <td>
                <?= $goods->goodsname ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $goods->detail ?>
            </td>
        </tr>
        <tr>
            <td>
                \<?= number_format($goods->price) ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $goods->recommend ? "おすすめ" : " " ?>
            </td>
        </tr>
        <tr>
            <td>
                <form action="cart.php" method="POST">
                    個数
                    <select name="num">
                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                            <!--ここに注意してください 1-10 の数字を表示する-->
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" name="goodscode" value="<?= $goods->goodscode ?>">
                    <input type="submit" name="add" value="カートに入れる">
                </form>
            </td>
        </tr>
    </table>

</body>

</html>