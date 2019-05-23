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
        $limit = @$post_data["limit"];

        if (!empty($limit)) {
            $scheduler->setLimit($limit);
        }

        switch($select) {
            case "days":    return $this->selectServicesDays($scheduler);
            case "detail":  return $this->selectDetailServices($scheduler, $id);
            case "time": return $this->selectServicesTime($scheduler);
            case "all": return $this->selectServicesAll($scheduler);
            case "obyvatel": return $this->selectServicesObyvatele($scheduler, $id);
            case "type": return $this->selectServicesType($scheduler, $id);
            case "history": return $this->selectServicesHistory($scheduler);
            case "types": return $this->selectTypes($scheduler);
            case "obInService": return $this->selectObyvatelInServices($scheduler);
            case "users": return $this->selectServicesUsers($scheduler);
            case "service": return $this->selectSpecificService($scheduler, $id);
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
     * nacita typy vykonu
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectTypes($scheduler ){
        $result = $scheduler->adminLoadServiceTypes();
        return $this->transformToJSON($result);
    }

    /**
     * nacita jednotlive obyvatele kteri maji naplanovanou sluzbu
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectObyvatelInServices($scheduler ){
        $result = $scheduler->adminLoadObyvatelInService();
        return $this->transformToJSON($result);
    }

    /**
     * nacita jednotlive uzivatele s naplanovanou sluzbou
     * @var $scheduler Scheduler
     * @return JsonResponse
     */
    private function selectServicesUsers($scheduler ){
        $result = $scheduler->adminLoadServiceUsers();
        return $this->transformToJSON($result);
    }

    /**
     * nacita sluzbu podle jejiho ID
     * @var $scheduler Scheduler
     * @var int $id - ID sluzby
     * @return JsonResponse
     */
    private function selectSpecificService($scheduler, $id ){
        $result = $scheduler->adminLoadServiceSpecific($id);
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
            $data_for_response["msg"] = "fail - no data found!";
        }
        return new JsonResponse($data_for_response);
    }
}