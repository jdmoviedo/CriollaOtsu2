<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/Template.php');
?>
<?= verificar('Product') ?>
<?= head('Productos') ?>
<?= startBody() ?>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="page-header-title">
                <i class="fa-solid fa-cart-shopping bg-blue"></i>
                <div class="d-inline">
                    <h5>Productos</h5>
                    <span>Gestión de Productos</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-state card-state-success">
            <div class="card-header">
                <h3>Productos</h3>
                <div class="card-header-right crud-card">
                    <ul class="list-unstyled d-flex">
                        <li><i class="ik ik-plus mr-5 cursor-pointer" onclick="showModalRegistro();" data-toggle="tooltip" data-placement="top" title="Crear Productos"></i></li>
                        <li><i class="ik ik-refresh-ccw cursor-pointer" onclick="filtrarRegistros();" data-toggle="tooltip" data-placement="top" title="Actualizar Tabla"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table id="dtProduct" class="table ml-0 w-100 table-hover"></table>
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
                    <input type="hidden" name="IdProduct" id="IdProduct">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="selectRestaurant">Restaurante</label>
                                <select class="form-control requerido" name="selectRestaurant" id="selectRestaurant" title="Restaurante" style="width:100%">
                                </select>
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="selectTypeProduct">Tipo Producto</label>
                                <select class="form-control requerido" name="selectTypeProduct" id="selectTypeProduct" title="Restaurante" style="width:100%">
                                </select>
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="Description">Descripcion</label>
                                <input type="text" class="form-control requerido maxlength-input" name="Description" id="Description" title="Descripcion" placeholder="Descripcion" minlength="5" maxlength="100">
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="LongDescription">Descripcion Larga</label>
                                <input type="text" class="form-control maxlength-input" name="LongDescription" id="LongDescription" title="Descripcion Larga" placeholder="Descripcion Larga" minlength="5" maxlength="500">
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                        <div class="col-md-12 form-group">
                            <a class="tooltips">
                                <label for="Value">Valor</label>
                                <input type="text" class="form-control requerido maxlength-input numero" name="Value" id="Value" title="Valor" placeholder="Valor" minlength="1" maxlength="20">
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

<div class="modal fade" id="ModalRegistroProductHasIngredient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="frmRegistroProductHasIngredient">
                    <input type="hidden" name="IdProductHasIngredient" id="IdProductHasIngredient">
                    <div class="row" id="divProductHasIngredient">

                    </div>
                </form>
                <div class="text-end">
                    <button type="button" class="btn btn-info" onclick="AddIngredient()">Agregar Ingrediente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnRegistroProductHasIngredient"></button>
            </div>
        </div>
    </div>
</div>

<?= endBody(); ?>
<script src="scripts/Product.js?v=<?php echo (rand()); ?>"></script>