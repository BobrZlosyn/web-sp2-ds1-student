<?php
/**
 * Created by PhpStorm.
 * User: BobrZlosyn
 * Date: 06.03.2019
 * Time: 16:11
 */

namespace ds1\admin_modules\scheduler;

use PDO;

class scheduler extends \ds1\core\ds1_base_model
{

    /**
     * Types
     *      days - vraci dny sluzeb
     *      time - vraci sluzby s casem
     *      all - vraci vsechny sluzby (bez zaznamu)
     *      history - vraci vsechny sluzby co maji zaznam
     *      detail - vraci detail provedeneho vykonu (zaznam)
     *      type - vraci sluzby podle typu vykonu
     *      obyvatel - vraci sluzby podle id obyvatele
     *      types - vraci vsechny typy vykonu
     *      obInService - vraci obcany kteri maji sluzbu
     *      users - vraci uzivatele kteri maji sluzbu
     */


    /**
     * Admin - nacist dny sluzeb.
     *
     * @param string $type  - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadServices($type = "days")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        //$table_name = TABLE_PLAN_VYKONU + " pv , " + TABLE_SLUZBA + " s , " + TABLE_TYP_VYKONU + " tv, " + TABLE_DS1_OBYVATELE + "o ";
        $table_name =  TABLE_OBYVATELE. " o, " .  TABLE_SLUZBA . " s, " . TABLE_TYP_VYKONU . " tv";
        $where_array  = "s.obyvatel_id = o.id AND ";
        $where_array  .= "s.typ_vykonu_id = tv.id ";


        // 1) pripravit dotaz s dotaznikama
        $query = "select * from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);
    }

    /**
     * Admin - nacist cas sluzeb .
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadTimeServices($type = "time")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        //$table_name = TABLE_PLAN_VYKONU + " pv , " + TABLE_SLUZBA + " s , " + TABLE_TYP_VYKONU + " tv, " + TABLE_DS1_OBYVATELE + "o ";
        $table_name =  TABLE_OBYVATELE. " o, " .  TABLE_SLUZBA . " s, " . TABLE_TYP_VYKONU . " tv";
        $where_array  = "s.obyvatel_id = o.id AND ";
        $where_array  .= "s.typ_vykonu_id = tv.id ";


        // 1) pripravit dotaz s dotaznikama
        $query = "select * from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);
    }

    /**
     * Admin - nacist vsechny sluzby s casem i datem bez zaznamu jeste (probehle i neprobhle).
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadAllService($type = "all")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        $table_name =   TABLE_SLUZBA . " s, " .
                        TABLE_TYP_VYKONU . " tv, " .
                        TABLE_PLAN_VYKONU . " pv, " .
                        TABLE_OBYVATELE . " o " ;

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
                        "s.id = pv.sluzba_id AND " .
                        "s.obyvatel_id = o.id ";

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);


    }


    /**
     * Admin - nacist vsechny sluzby s casem i datem jen historicke (ukazou se jen se zaznamem jiz.
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadHistoryService($type = "history")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        $table_name =   TABLE_SLUZBA . " s, " .
            TABLE_TYP_VYKONU . " tv, " .
            TABLE_PLAN_VYKONU . " pv, " .
            TABLE_OBYVATELE . " o, " .
            TABLE_ZAZNAM_VYKONU. " zv";

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
            "s.id = pv.sluzba_id AND " .
            "s.obyvatel_id = o.id AND " .
            "zv.plan_vykonu_id = pv.id";

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);


    }

    /**
     * Admin - nacist detail zaznamu vykonu.
     *
     * @param string $type - data nebo count
     * @param int $id - cislo zaznamu
     * @return mixed
     */
    public function adminLoadServiceDetail($id, $type = "detail")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        if ($id + 0 > 0) {
            $table_name =  TABLE_ZAZNAM_VYKONU. " zv";
            $where_array  = "zv.zaznam_vykonu_id = ? ";

            // 1) pripravit dotaz s dotaznikama
            $query = "select * from ".$table_name. " where " . $where_array;
            // echo $query;

            return $this->executeQuery($query, $id);
        }

        return array();

    }

    /**
     * Admin - nacist sluzby pro jednoho obyvatele
     *
     * @param string $type - data nebo count
     * @param int $id cislo obyvatele
     * @return mixed
     */
    public function adminLoadServiceObyvatel($id, $type = "obyvatel")
    {
        $columns = "*";

        $table_name =   TABLE_SLUZBA . " s, " .
            TABLE_TYP_VYKONU . " tv, " .
            TABLE_PLAN_VYKONU . " pv, " .
            TABLE_OBYVATELE . " o " ;

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
            "s.id = pv.sluzba_id AND " .
            "s.obyvatel_id = o.id AND " .
            "s.obyvatel_id = ? ";

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query, $id);


    }

    /**
     * Admin - nacist sluzby podle typu
     *
     * @param string $type - data nebo count
     * @param int $id cislo typu vykonu
     * @return mixed
     */
    public function adminLoadServiceType($id, $type = "type")
    {
        $columns = "*";

        $table_name =   TABLE_SLUZBA . " s, " .
            TABLE_TYP_VYKONU . " tv, " .
            TABLE_PLAN_VYKONU . " pv, " .
            TABLE_OBYVATELE . " o" ;

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
            "s.id = pv.sluzba_id AND " .
            "s.obyvatel_id = o.id AND " .
            "s.typ_vykonu_id = ? ";

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;


        return $this->executeQuery($query, $id);

    }



    /**
     * Admin - nacist vsechny typy sluzeb
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadServiceTypes($type = "types")
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        $table_name =
            TABLE_TYP_VYKONU . " tv " ;

        $where_array  = "";

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name;
        // echo $query;

        return $this->executeQuery($query);

    }

    /**
     * Admin - nacist vsechny obyvatele s naplanovanou sluzbou
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadObyvatelInService($type = "obInService")
    {
        $columns = "o.*";


        $table_name =   TABLE_SLUZBA . " s, " .
                        TABLE_OBYVATELE . " o ";

        $where_array  =
            "s.obyvatel_id = o.id ";

        // 1) pripravit dotaz s dotaznikama
        $query = "select DISTINCT " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);


    }


    /**
     * Admin - nacist vsechny uzivatele s naplanovanou sluzbou.
     *
     * @param string $type - typ zatim jen pro prehlednost
     * @return mixed
     */
    public function adminLoadServiceUsers($type = "users")
    {
        $columns = "u.*";


        $table_name =   TABLE_UZIVATELE . " u, " .
            TABLE_ZAZNAM_VYKONU . " zv ";

        $where_array  =
            "zv.uzivatel_id = u.id ";

        // 1) pripravit dotaz s dotaznikama
        $query = "select DISTINCT " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);


    }


    /**
     * vraci vysledek dotazu
     * @param $query dotaz do DB
     * @param $param value to be added to query
     * @return mixed
     */
    private function executeQuery($query, $param = null) {
        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        if ($param != null) {
            $statement->bindValue(1, $param);
        }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

       // print_r($query);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);


        return $rows;
    }


}