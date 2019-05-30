<div class="container-fluid" ng-app="ds1">

    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                Planovaci tabule
            </div>
            <div class="pull-right">

            </div>
        </div>
        <div class="card-body" ng-controller="adminScheduler">
            <div class="row">
                <div class="col-md-2">
                    <span>
                        Typ služby:
                        <div class="dropdown" id="dropdown-service">
                            <input type="text" list="datalist-service" id="chooseService" onkeyup="filterFunction('chooseService', 'dropdown-service')">
                            <datalist id="datalist-service">


                            </datalist>
                        </div>
                    </span>
                </div>
                <div class="col-md-2">
                    <span>
                        Obyvatel:
                        <div class="dropdown" id="dropdown-obyvatel">
                            <input type="text" list="datalist-obyvatel" id="chooseObyvatel" onkeyup="filterFunction('chooseObyvatel', 'dropdown-obyvatel')">
                            <datalist id="datalist-obyvatel">


                            </datalist>
                        </div>
                    </span>

                </div>
                <div class="col-md-1">
                    <input type="submit" id="filter" class="btn btn-primary" value="Zobraz" />
                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col-md-12">
                    <div id='calendar'></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        window.url_scheduler_api = "<?php echo $url_scheduler_api;?>";
        window.base_url = "<?php echo $base_url;?>";
    </script>
</div>

<!-- Modal -->
<div class="modal fade" id="eventInfo" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id ="eventTitle"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Výkon služby od: <span id="event-from"></span></p>
                <p>Výkon služby do: <span id="event-to"></span></p>
                <p>Obyvatel: <span id="event-obyvatel"></span></p>
                <p>Popis: <span id="description"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="service-id">ID služby: </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
            </div>
        </div>
    </div>
</div>

</div>



<script src='/admin/template/js/scheduler/dist/scheduler.js'></script>
<script src='/admin/template/js/filter.js'></script>