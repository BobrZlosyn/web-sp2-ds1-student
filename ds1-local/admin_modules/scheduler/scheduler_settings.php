<?php

/**
 *  Konfigurace modulu. Vsechny konfigurace pro vsechny moduly se nacitaji vzdy. Nesmi tam tedy dochazet k zadnym
 *  kolizim a konstanty je treba pojmenovavat poradne a radne rozlisovat konstanty pro modul a konstanty pro vice modoulu.
 */

    // obyvatele = pacienti
    // musim vzdy testovat, jestli nahodou neexistuje. Nekdo to mohl presunout do hlavni konfigurace.
    if (!defined("TABLE_SLUZBA")) {
        define("TABLE_SLUZBA", TABLE_PREFIX."sluzba");
    }


    if (!defined("TABLE_PLAN_VYKONU")) {
        define("TABLE_PLAN_VYKONU", TABLE_PREFIX."plan_vykonu");
    }

    if (!defined("TABLE_ZAZNAM_VYKONU")) {
        define("TABLE_ZAZNAM_VYKONU", TABLE_PREFIX."zaznam_vykonu");
    }

    if (!defined("TABLE_TYP_VYKONU")) {
        define("TABLE_TYP_VYKONU", TABLE_PREFIX."typ_vykonu");
    }

    if (!defined("TABLE_OBYVATELE")) {
        define("TABLE_OBYVATELE", TABLE_PREFIX."ds1_obyvatele");
    }

    if (!defined("TABLE_UZIVATELE")) {
        define("TABLE_UZIVATELE", TABLE_PREFIX."ds1_uzivatele");
    }

    if (!defined("DS1_ROUTE_ADMIN_SCHEDULER")) {
        define("DS1_ROUTE_ADMIN_SCHEDULER", "scheduler");
    }

    if (!defined("DS1_ADMIN_SCHEDULER_API")) {
        define("DS1_ADMIN_SCHEDULER_API", "scheduler_api");
    }