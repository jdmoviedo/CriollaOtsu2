<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/Template.php');
?>
<?= verificar('Order') ?>
<?= head('Pedidos') ?>
<?= startBody() ?>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="page-header-title">
                <i class="fa-solid fa-cash-register bg-blue"></i>
                <div class="d-inline">
                    <h5>Pedidos</h5>
                    <span>Gestión de Pedidos</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-state card-state-success">
            <div class="card-header">
                <h3>Pedidos</h3>
                <div class="card-header-right crud-card">
                    <ul class="list-unstyled d-flex">                        
                        <li><i class="ik ik-refresh-ccw cursor-pointer" onclick="filtrarRegistros();" data-toggle="tooltip" data-placement="top" title="Actualizar Tabla"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table id="dtOrder" class="table ml-0 w-100 table-hover"></table>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mt-4 d-flex aligns-items-center justify-content-between">
                        <div class="negrita info-pagination-registro"></div>
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination btn-paginador-registro"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width:90%">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
                <div class="row" id="OrderDetail">
                </div>                           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= endBody(); ?>
<script src="scripts/Order.js?v=<?php echo (rand()); ?>"></script>