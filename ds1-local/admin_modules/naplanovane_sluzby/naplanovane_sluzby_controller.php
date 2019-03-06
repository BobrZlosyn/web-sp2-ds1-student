<?php
/**
 * Created by PhpStorm.
 * User: BobrZlosyn
 * Date: 06.03.2019
 * Time: 16:04
 */

namespace ds1\admin_modules\naplanovane_sluzby;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ds1\core\ds1_base_controller;

class naplanovane_sluzby_controller extends ds1_base_controller
{
    public function indexAction(Request $request, $page = "")
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, $page);

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $plan_table = new naplanovane_sluzby($this->ds1->GetPDOConnection());


        // univerzalni content params
        $content_params = array();
        $content_params["base_url_link"] = $this->webGetBaseUrlLink();
        $content_params["page_number"] = $this->page_number;
        $content_params["route"] = $this->route;        // mam tam orders, je to automaticky z routingu
        $content_params["route_params"] = array();
        $content_params["controller"] = $this;


        // defaultni vysledek akce
        $result_msg = "";
        $result_ok = true;


        $content = "";
        $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "naplanovane_sluzby/templates/admin_naplanovane_sluzby_overview.inc.php",
            $content_params,
            true);


        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["result_msg"] = $result_msg;
        $main_params["result_ok"] = $result_ok;

        return $this->renderAdminTemplate($main_params);
    }
}