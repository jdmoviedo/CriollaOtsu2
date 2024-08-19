<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/Template.php');
?>
<?= verificar('Restaurant') ?>
<?= head('Restaurante') ?>
<?= startBody() ?>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="page-header-title">
                <i class="fa-solid fa-store bg-blue"></i>
                <div class="d-inline">
                    <h5>Restaurante</h5>
                    <span>Gestión de Restaurante</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-state card-state-success">
            <div class="card-header">
                <h3>Restaurante</h3>
                <div class="card-header-right crud-card">
                    <ul class="list-unstyled d-flex">
                        <li><i class="ik ik-plus mr-5 cursor-pointer" onclick="showModalRegistro();" data-toggle="tooltip" data-placement="top" title="Crear Restaurante"></i></li>
                        <li><i class="ik ik-refresh-ccw cursor-pointer" onclick="filtrarRegistros();" data-toggle="tooltip" data-placement="top" title="Actualizar Tabla"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
            <table id="dtRestaurant" class="table ml-0 w-100 table-hover"></table>
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

<div class="modal fade" id="ModalRegistro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="frmRegistro">
                    <input type="hidden" name="IdRestaurant" id="IdRestaurant">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="Description">Descripcion</label>
                                <input type="text" class="form-control requerido maxlength-input" name="Description" id="Description" title="Descripcion" placeholder="Descripcion" minlength="5" maxlength="100" pattern="^[a-zA-Z0-9ñÑ\s]+$" data-pattern="Solo se permiten letras" data-pattern-replace="[^a-zA-Z0-9ñÑ\s]" oninput="limitecaracteres(this);">
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="reset();">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnRegistro"></button>
            </div>
        </div>
    </div>
</div>

<?= endBody(); ?>
<script src="scripts/Restaurant.js?v=<?php echo (rand()); ?>"></script>