<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './helpers/GoodsGroupDAO.php';
require_once './helpers/GoodsDAO.php';
$goodsGroupDAO = new GoodsGroupDAO();
$goodsgroup_list = $goodsGroupDAO->get_goodsgroup();
$goodsDAO = new GoodsDAO();

if (isset($_GET['groupcode'])) {
    $groupcode = $_GET['groupcode'];
    $goods_list = $goodsDAO->get_goods_by_groupcode($groupcode);
} else {
    $goods_list = $goodsDAO->get_recommend_goods();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JecShopping</title>
    <link href="css/IndexStyle.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <table id="goodsgroup">
        <?php foreach ($goodsgroup_list as $goodsgroup) : ?>
            <tr>
                <td>
                    <a href="index.php?groupcode=<?= $goodsgroup->groupcode ?>">
                        <?php echo $goodsgroup->groupname ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
    <div id="goodslist">
        <?php foreach ($goods_list as $goods) : ?>
            <table align="left">
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods->goodscode ?>">
                            <img src="images/goods/<?= $goods->goodsimage ?>"></a>

                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods->goodscode ?>">
                            <?= $goods->goodsname ?></a>

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
            </table>
        <?php endforeach ?>
    </div>

</body>

</html>