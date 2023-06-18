<?php
require_once 'DAO.php';
require_once 'CartDAO.php';
require_once 'SaleDetailDAO.php';

class SaleDAO
{
    private function get_saleno()
    {
        $dbh = DAO::get_db_connect();
        //sqlから最新の販売番号を取得する
        $sql = "SELECT IDENT_CURRENT('Sale') AS saleno";

        $stmt = $dbh->query($sql);
        $row = $stmt->fetchObject();
        return $row->saleno; //最新のsalenoを返す
    }
    public function insert(int $memberid, array $cart_list)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO Sale (saledate,memberid) VALUES (:saledate,:memberid)";

        $stmt = $dbh->prepare($sql);
        $saledate = date(
            'Y-m-d H:i:s'
        );

        $stmt->bindValue('saledate', $saledate, PDO::PARAM_STR);
        $stmt->bindValue('memberid', $memberid, PDO::PARAM_INT);

        $stmt->execute();
        //最新のsalenoの値を取得する
        $saleno = $this->get_saleno();
        $saleDetailDAO = new SaleDetailDAO();

        //カートの商品をsaledetailテーブルに追加する
        foreach ($cart_list as $cart) {
            $saleDetail = new SaleDetail();

            $saleDetail->saleno = $this->get_saleno();
            $saleDetail->goodscode = $cart->goodscode;
            $saleDetail->num = $cart->num;
            $saleDetailDAO->insert($saleDetail, $dbh);
        }
    }
}
