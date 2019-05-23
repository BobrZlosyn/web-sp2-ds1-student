<div class="container-fluid" ng-app="ds1">

    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                Planovaci tabule
            </div>
            <div class="pull-right">
                neco dalsiho
            </div>
        </div>
        <div class="card-body" ng-controller="adminScheduler">
            <div class="row">

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

                <p id="description"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>



<script src='/admin/dist/scheduler.js'></script>