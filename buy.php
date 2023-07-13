<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDAO.php';

require_once 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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


$mail = new PHPMailer(true);
$mail->CharSet = 'utf-8';

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '22jn0119@jec.ac.jp';
    $mail->Password = 'h6D4pVJJXnF5';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('22jn0119@jec.ac.jp', 'お父さん');
    $mail->addAddress($member->email, $member->membername);

    $mail->Subject = 'JecShopping 購入完了';
    $mail->isHTML(true);
    $mail->Body = $member->membername . "さん。<br>購入ありがとうございました。";


    foreach ($cart_list as $cart_item) {
        // 埋込画像の設定
        $mail->AddEmbeddedImage("./images/goods/{$cart_item->goodsimage}", $cart_item->goodsimage);

        $mail->Body .= "
            <table>
                <tr>
                    <td><img src='cid:{$cart_item->goodsimage}'></td>
                </tr>
                <tr>
                    <td>{$cart_item->goodsname}</td>
                </tr>
                <tr>
                    <td>{$cart_item->price}円</td>
                </tr>
                <tr>
                    <td>数量： {$cart_item->num}</td>
                </tr>
            </table>
            <hr>
        ";
    }

    $mail->send();
} catch (Exception $e) {
    echo "邮件发送失败。Mailer Error: {$mail->ErrorInfo}";
}
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