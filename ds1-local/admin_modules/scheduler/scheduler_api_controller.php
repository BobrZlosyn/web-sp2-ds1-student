<?php
/**
 * Created by PhpStorm.
 * User: BobrZlosyn
 * Date: 06.03.2019
 * Time: 16:04
 */

namespace ds1\admin_modules\scheduler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ds1\core\ds1_base_controller;

class scheduler_api_controller extends ds1_base_controller
{


    public function apiAction(Request $request)
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $scheduler = new Scheduler();
        $scheduler->SetPDOConnection($this->ds1->GetPDOConnection());

        // DATA - v postu dostanu field a search

        // POZOR: data jsou ve formatu application/json a neni mozne je prijmout $_POST, musi se to takto:
        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole

        // pro kontrolni vypis zpet do Angularu - zobrazit se to v konzoli:
        // print_r($post_data);

        // nacist vstupni data: field = napr. klicove_slovo, search: vstup od uživatele, např. lyž
        $select = @$post_data["select"];
        $id = @$post_data["id"];
        //echo "field: $field, search: $search_string <br/>";


        if ($select == "days") {
            return $this->selectServicesDays($scheduler);
        }

        if ($select == "detail") {
            return $this->selectDetailServices($scheduler, $id);
        }

        if ($select == "time") {
            return $this->selectServicesTime($scheduler);
        }

        if ($select == "all") {
            return $this->selectServicesAll($scheduler);
        }

        if ($select == "obyvatel") {
            return $this->selectServicesObyvatele($scheduler, $id);
        }

        if ($select == "type") {
            return $this->selectServicesType($scheduler, $id);
        }

        if ($select == "history") {
            return $this->selectServicesHistory($scheduler);
        }

        return new JsonResponse("We have nothing to offer you.");
    }

    /**
     * nacita dny sluzeb
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectServicesDays($scheduler){
        $result = $scheduler->adminLoadServices();
        return $this->transformToJSON($result);
    }

    /**
     * nacita detail jedne sluzby
     * @var $scheduler Scheduler
     * @var $id int - cislo zaznamu vykonu
     * @return JsonResponse
     */
    private function selectDetailServices($scheduler, $id){
        $result = $scheduler->adminLoadServiceDetail($id);
        return $this->transformToJSON($result);
    }

    /**
     * nacita hodiny sluzeb
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectServicesTime($scheduler){
        $result = $scheduler->adminLoadTimeServices();
        return $this->transformToJSON($result);
    }


    /**
     * nacita vse o sluzbach
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectServicesAll($scheduler){
        $result = $scheduler->adminLoadAllService();
        return $this->transformToJSON($result);
    }

    /**
     * nacita sluzby podle obyvatele
     * @var $scheduler Scheduler
     * @var $id int - cislo obyvatele ktereho chceme filtrovat
     * @return JsonResponse
     */
    private function selectServicesObyvatele($scheduler, $id){
        $result = $scheduler->adminLoadServiceObyvatel($id);
        return $this->transformToJSON($result);
    }


    /**
     * nacita sluzby podle typu
     * @var $scheduler Scheduler
     * @var $id int - cislo typu vykonu
     * @return JsonResponse
     */
    private function selectServicesType($scheduler, $id){
        $result = $scheduler->adminLoadServiceType($id);
        return $this->transformToJSON($result);
    }

    /**
     * nacita sluzby se zaznamem (jen ty co uz probehli tedy - moznost pro nacteni detailu )
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectServicesHistory($scheduler ){
        $result = $scheduler->adminLoadHistoryService();
        return $this->transformToJSON($result);
    }

    /**
     * transformuje vysledky z DB do JSONu
     * @param $result
     * @return JsonResponse
     */
    private function transformToJSON ($result) {
        $data_for_response = array();
        if ($result){
            $data_for_response["msg"] = "ok";

            foreach ($result as $service) {
                $data_for_response["results"][] = $service;
            }

        }else {
            $data_for_response["msg"] = "fail";
        }
        return new JsonResponse($data_for_response);
    }
}