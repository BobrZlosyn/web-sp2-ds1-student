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
     *      days
     *      time
     *      all
     *      history
     *      detail
     *      type
     *      obyvatel
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
    public function adminLoadServiceDetail($type = "detail", $id)
    {
        $columns = "*";
        if ($type == "all") {
            $columns = "*";
        }

        if ($id + 0 > 0) {
            $table_name =  TABLE_ZAZNAM_VYKONU. " zv";
            $where_array  = "zv.zaznam_vykonu_id = " . $id;

            // 1) pripravit dotaz s dotaznikama
            $query = "select * from ".$table_name. " where " . $where_array;
            // echo $query;

            return $this->executeQuery($query);
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
    public function adminLoadServiceObyvatel($type = "obyvatel", $id)
    {
        $columns = "*";

        $table_name =   TABLE_SLUZBA . " s, " .
            TABLE_TYP_VYKONU . " tv, " .
            TABLE_PLAN_VYKONU . " pv, " .
            TABLE_OBYVATELE . " o, " .
            TABLE_ZAZNAM_VYKONU. " zv";

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
            "s.id = pv.sluzba_id AND " .
            "s.obyvatel_id = o.id AND " .
            "zv.plan_vykonu_id = pv.id AND " .
            "s.obyvatel_id = " . $id;

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;

        return $this->executeQuery($query);


    }

    /**
     * Admin - nacist sluzby podle typu
     *
     * @param string $type - data nebo count
     * @param int $id cislo typu vykonu
     * @return mixed
     */
    public function adminLoadServiceType($type = "type", $id)
    {
        $columns = "*";

        $table_name =   TABLE_SLUZBA . " s, " .
            TABLE_TYP_VYKONU . " tv, " .
            TABLE_PLAN_VYKONU . " pv, " .
            TABLE_OBYVATELE . " o, " .
            TABLE_ZAZNAM_VYKONU. " zv";

        $where_array  = "s.typ_vykonu_id = tv.id AND " .
            "s.id = pv.sluzba_id AND " .
            "s.obyvatel_id = o.id AND " .
            "zv.plan_vykonu_id = pv.id AND " .
            "s.typ_vykonu = " . $id;

        // 1) pripravit dotaz s dotaznikama
        $query = "select " . $columns . " from ".$table_name. " where " . $where_array;
        // echo $query;


        return $this->executeQuery($query);

    }


    /**
     * vraci vysledek dotazu
     * @param $query dotaz do DB
     * @return mixed
     */
    private function executeQuery($query) {
        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);
        // 4) provest dotaz
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


}