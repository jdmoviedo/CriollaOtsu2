<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/Template.php');
?>
<?= verificar('User') ?>
<?= head('Usuarios') ?>
<?= startBody() ?>
<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-12">
            <div class="page-header-title">
                <i class="fas fa-user bg-blue"></i>
                <div class="d-inline">
                    <h5>Usuarios</h5>
                    <span>Gestión de Usuarios</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-state card-state-success">
            <div class="card-header">
                <h3>Usuarios</h3>
                <div class="card-header-right crud-card">
                    <ul class="list-unstyled d-flex">
                        <li><i class="ik ik-plus mr-1 cursor-pointer" onclick="showModalRegistro();" data-toggle="tooltip" data-placement="top" title="Crear Usuario"></i></li>
                        <li><i class="ik ik-refresh-ccw cursor-pointer" onclick="filtrarRegistros();" data-toggle="tooltip" data-placement="top" title="Actualizar Tabla"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
            <table id="dtUsuarios" class="table ml-0 w-100 table-hover"></table>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="frmRegistro">
                    <input type="hidden" name="IdUser" id="IdUser">
                    <!-- Primer y Segundo Nombre -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <a class="tooltips">
                                <label for="Names">Nombres</label>
                                <input type="text" class="form-control requerido maxlength-input" name="Names" id="Names" title="Nombres" placeholder="Nombres" minlength="3" maxlength="200" pattern="^[a-zA-Z\s]+$" data-pattern="Solo se permiten letras" data-pattern-replace="[^a-zA-Z\s]" oninput="limitecaracteres(this);">
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-6 form-group">
                            <a class="tooltips">
                                <label for="UserName">Nombre de Usuario</label>
                                <input type="text" class="form-control requerido maxlength-input" placeholder="Nombre de Usuario" name="UserName" id="UserName" title="Segundo Nombre" minlength="3" maxlength="50" pattern="^[a-zA-Z]+$" data-pattern="Solo se permiten letras" data-pattern-replace="[^a-zA-Z]" oninput="limitecaracteres(this);">
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                        </div>
                    </div>

                    <!-- Contraseña y Confirmar Contraseña -->
                    <div class="row" id="passwords">
                        <div class="col-md-6 form-group">

                            <a class="tooltips">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control requerido maxlength-input" name="password" id="password" title="Contraseña" placeholder="Contraseña" minlength="6" maxlength="20" oninput="limitecaracteres(this);">
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-6 form-group">

                            <a class="tooltips">
                                <label for="password1">Confirmar Contraseña</label>
                                <input type="password" class="form-control requerido maxlength-input" placeholder="Confirmar Contraseña" name="password1" id="password1" title="Confirmar Contraseña" minlength="6" maxlength="20" oninput="limitecaracteres(this);">
                                <span class="spanValidacion hidden hidden"></span>
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

<div class="modal fade" id="ModalAsignarSubmodulo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="frmRegistroAsignarSubmodulo">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <a class="tooltips">
                                <label for="selectHome">Pagina de Inicio</label>
                                <select class="form-control requerido" name="selectHome" id="selectHome" title="Pagina de Inicio" style="width:100%">
                                </select>
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                        </div>
                    </div>
                    <div class="row" id="modulos">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetAsignarSubmodulo();">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnRegistroAsignarSubmodulo"></button>
            </div>
        </div>
    </div>
</div>

<?= endBody(); ?>
<script src="scripts/User.js?v=<?php echo (rand()); ?>"></script>