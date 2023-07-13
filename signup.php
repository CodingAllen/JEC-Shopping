<?php
require_once './helpers/MemberDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password1 = $_POST['password1'];
    $membername = $_POST['membername'];
    $zipcode = $_POST['zipcode'];
    $address = $_POST['address'];
    $tel1 = $_POST['tel1'];
    $tel2 = $_POST['tel2'];
    $tel3 = $_POST['tel3'];

    $memberDAO = new MemberDAO();

    // Initialize errors array
    $errs = [];

    //メールアドレスの形式チェック
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errs['email'] = 'メールアドレスの形式が正しくありません。';
    } elseif ($memberDAO->email_exists($email)) {
        $errs['email'] = 'このメールアドレスはすでに登録されています。';
    }
    //パスワードの文字数をチェック
    if (!preg_match('/\A.{4,}\z/', $password)) {
        $errs['password'] = 'パスワードは４文字以上で入力してください。';
    }
    //パスワードの一致チェック
    elseif ($password !== $password1) {
        $errs['password'] = 'パスワードが一致しません。';
    }
    //名前の入力チェック
    if ($membername === '') {
        $errs['membername'] = 'お名前を入力してください。';
    }
    //郵便番号の形式チェック
    if (!preg_match("/^\d{3}-\d{4}$/", $zipcode)) {
        $errs['zipcode'] = '郵便番号は3桁ー4桁で入力してください。';
    }
    //住所の入力チェック
    if ($address === '') {
        $errs['address'] = '住所を入力してください。';
    }
    //電話番号の桁数チェック
    if (
        !preg_match('/\A(\d{2,5})?\z/', $tel1) || !preg_match('/\A(\d{1,4})?\z/', $tel2)
        || !preg_match('/\A(\d{4})?\z/', $tel3)
    ) {
        $errs['tel'] = '電話番号は半角英数字２～５桁、1～4桁で入力してください';
    }

    //エラーが無ければDBに会員データを追加する
    if (empty($errs)) {
        $member = new Member();
        $member->email = $email;
        $member->password = $password;
        $member->membername = $membername;
        $member->zipcode = $zipcode;
        $member->address = $address;

        $member->tel = '';
        if ($tel1 !== '' && $tel2 !== '' && $tel3 !== '') {
            $member->tel = "{$tel1}-{$tel2}-{$tel3}";
        }
        $memberDAO->insert($member);

        header('Location: signupEnd.php');

        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録</title>
</head>

<body>
    <?php include 'header2.php'; ?>

    <h1>会員登録</h1>
    <P>
        以下の項目を入力し、登録ボタンをクリックしてください(*は必須)
    </P>
    <form action="" method="post">
        <table>
            <!-- Email -->
            <tr>
                <td>メールアドレス＊</td>
                <td>
                    <input type="email" name="email" required autofocus>
                    <?php if (isset($errs['email'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['email']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Password -->
            <tr>
                <td>パスワード(4文字以上)＊</td>
                <td>
                    <input type="password" name="password" pattern=".{4,}" required>
                    <?php if (isset($errs['password'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['password']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Confirm Password -->
            <tr>
                <td>パスワード(再入力)＊</td>
                <td>
                    <input type="password" name="password1">
                    <?php if (isset($errs['password'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['password']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Member Name -->
            <tr>
                <td>お名前＊</td>
                <td>
                    <input type="text" name="membername" required>
                    <?php if (isset($errs['membername'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['membername']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Zipcode -->
            <tr>
                <td>
                    郵便番号＊
                </td>
                <td>
                    <input type="text" id="zipcode" name="zipcode" pattern="\d{3}-\d{4}" required>
                    <button type="button" id="fillAddress">住所検索</button>
                    <?php if (isset($errs['zipcode'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['zipcode']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Address -->
            <tr>
                <td>住所＊</td>
                <td>
                    <input type="text" id="address" name="address" required>
                    <?php if (isset($errs['address'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['address']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Phone Number -->
            <tr>
                <td>電話番号</td>
                <td>
                    <input type="tel" name="tel1" size="4"> -
                    <input type="tel" name="tel2" size="4"> -
                    <input type="tel" name="tel3" size="4">
                    <?php if (isset($errs['tel'])) : ?>
                        <p style="color: red; display:inline;"><?php echo $errs['tel']; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <input type="submit" value="登録する">
    </form>
    <script>
        document.getElementById('fillAddress').addEventListener('click', function() {
            var zipcode = document.getElementById('zipcode').value;
            if (zipcode) {
                fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + zipcode)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results) {
                            var result = data.results[0];
                            var address = result.address1 + result.address2 + result.address3;
                            document.getElementById('address').value = address;
                        } else {
                            alert('該当する郵便番号が見つかりませんでした。');
                        }
                    });
            } else {
                alert('郵便番号を入力してください。');
            }
        });
    </script>



</body>

</html>