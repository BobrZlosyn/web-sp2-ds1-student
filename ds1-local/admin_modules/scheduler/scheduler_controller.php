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

class scheduler_controller extends ds1_base_controller
{

    public function apiAction(Request $request)
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $obyvatele = new obyvatele();
        $obyvatele->SetPDOConnection($this->ds1->GetPDOConnection());

        // DATA - v postu dostanu field a search

        // POZOR: data jsou ve formatu application/json a neni mozne je prijmout $_POST, musi se to takto:
        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole

        // pro kontrolni vypis zpet do Angularu - zobrazit se to v konzoli:
        // print_r($post_data);

        // nacist vstupni data: field = napr. klicove_slovo, search: vstup od uživatele, např. lyž
        $field = @$post_data["field"];
        $search_string = @$post_data["search"];
        $base_url = @$post_data["base_url"];
        //echo "field: $field, search: $search_string <br/>";

        if ($field == "obyvatele") {
            // hledam obyvatele dle retezce $search

            $count_on_page = 10;    // limit na pocet vysledku pro autocomplete
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminSearchItems($search_string, "count", 1, 1);
            //echo "total: $total"; exit;

            $obyvatele_list = $obyvatele->adminSearchItems($search_string, "data", 1, $count_on_page);

            // slozit data for response - vysledkem musi byt objekt a nikoliv pole kuli
            /* To avoid XSSI JSON Hijacking, you should pass an associative array as the outer-most array to JsonResponse and not an
            indexed array so that the final result is an object (e.g. {"object": "not inside an array"})
            instead of an array (e.g. [{"object": "inside an array"}]).*/
            $data_for_response = array();
            $data_for_response["msg"] = "ok - field: $field, search: $search_string";

            if ($obyvatele_list)
                foreach ($obyvatele_list as $ob) {
                    // slozit desc = co se zobrazi
                    $desc_pom = "$ob[prijmeni] $ob[jmeno]";

                    if (trim($ob["vek"]) != "") {
                        // prihodit vek
                        $desc_pom .= " ($ob[vek] let)";
                    }

                    // pridat k obyvateli
                    $ob["id_klicove_slovo"] = $ob["id"];
                    $ob["klicove_slovo"] = $search_string;
                    $ob["autocomplete_desc"] = $desc_pom;

                    // url, kam se mam dostat na kliknuti - musim na routu obyvatele a nikoliv obyvatele-api
                    $ob["url"] = $this->makeUrlByRoute(DS1_ROUTE_ADMIN_OBYVATELE, array("action" => "obyvatel_detail_show", "obyvatel_id" => $ob["id"]));

                    // pro testovani
                    //$ob["url"] = $base_url."index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=".$ob["id"];

                    // vlozit do vysledku
                    $data_for_response["autocomplete_results"][] = $ob;
                }

            // vratit json response
            return new JsonResponse($data_for_response);
        }

        return new Response("Controller pro scheduler API.");
    }
}