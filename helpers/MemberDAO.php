<?php
require_once 'DAO.php';
class Member
{
    public int $memberid;
    public string $email;
    public string $membername;
    public string $zipcode;
    public string $address;
    public string $tel;
    public string $password;
}
class MemberDAO
{
    public function get_member(string $email, string $password)
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM Member WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $member = $stmt->fetchObject('Member');
        if ($member !== false) {
            if (password_verify($password, $member->password)) {
                return $member;
            }
        }
        return false;
    }
}
