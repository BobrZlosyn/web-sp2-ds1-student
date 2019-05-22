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
                <div class="col-md-12">
                    <button type="button" class=" fc-button fc-button-primary"  id="test"> OK </button>
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

<!-- jen pro ukazku (bylo by dobre aby se po kliknuti na akci otevrelo modalni okno s detailem akce -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Large Modal</button>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>This is a large modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>



<script src='/admin/dist/scheduler.js'></script>