<?php

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
            <tr>
                <td>メールアドレス＊</td>
                <td><input type="email" name="email" required autofocus></td>
            </tr>
            <tr>
                <td>パスワード(4文字以上)＊</td>
                <td>
                    <input type="password" name="password1" pattern=".{4,}" required oninvalid="setCustomValidity('このテキストは4文字以上で指定してください（現在は' + this.value.length + '文字です）。')" oninput="setCustomValidity('')">
                </td>
            </tr>

            <tr>
                <td>パスワード(再入力)＊</td>
                <td><input type="password" name="password2"></td>
            </tr>
            <tr>
                <td>お名前＊</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>
                    郵便番号＊
                </td>
                <td>
                    <input type="text" name="postAddress" pattern="\d{3}-\d{4}" title="郵便番号は３桁ー４桁でハイフン(-)を入れて入力してください">
                </td>
            </tr>

            <tr>
                <td>住所＊</td>
                <td><input type="text" name="address"></td>
            </tr>
            <tr>
                <td>電話番号</td>
                <td>
                    <input type="tel" name="tel1" size="4"> -
                    <input type="tel" name="tel2" size="4"> -
                    <input type="tel" name="tel3" size="4">
                </td>
            </tr>
        </table>
        <input type="submit" value="登録する">
    </form>
</body>

</html>