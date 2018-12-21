<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" /> 
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>CAMI || Plantillas</title>
    <link href="img/favicon.ico" rel="shortcut icon" />
    <!-- CSS -->
    <link href="libs/bootstrap/css/bootstrap.css?v=<?php echo filemtime( "libs/bootstrap/css/bootstrap.css" )?>"	type="text/css"	rel="stylesheet" />
    <link href="libs/fontawesome/css/fontawesome.css?v=<?php echo filemtime( "libs/fontawesome/css/fontawesome.css" )?>"	type="text/css"	rel="stylesheet" />
    <link href="css/style.css?v=<?php echo filemtime( "css/style.css" )?>"	type="text/css" rel="stylesheet" /> 
    <!-- /CSS -->
    <!-- JS -->
    <script src="js/jquery-3.3.1.min.js?v=<?php echo filemtime( "js/jquery-3.3.1.min.js" )?>"	type="text/javascript"></script>
    <script src="js/popper.min.js?v=<?php echo filemtime( "js/popper.min.js" )?>"	type="text/javascript"></script>
    <script src="js/tooltip.min.js?v=<?php echo filemtime( "js/tooltip.min.js" )?>"	type="text/javascript"></script>
    <script src="libs/bootstrap/js/bootstrap.js?v=<?php echo filemtime( "libs/bootstrap/js/bootstrap.js" )?>"	type="text/javascript"></script>
    <script src="libs/fontawesome/js/fontawesome.js?v=<?php echo filemtime( "libs/fontawesome/js/fontawesome.js" )?>"	type="text/javascript"></script>
    <script src="libs/jquery-ui/jquery-ui.js?v=<?php echo filemtime( "libs/jquery-ui/jquery-ui.js" )?>"	type="text/javascript"></script>

    <!-- /JS -->
  </head> 
  <body > 
    <!-- ERRORES Y MENSAJES --> 
    <!-- /ERRORES Y MENSAJES -->
    <!-- Barra de Navegacion -->        
    <?php
  require_once("views/layout/header.php");    
    ?>
    <!-- /Barra de Navegacion --> 
    <!-- Contenedor General -->
    <div class="main" id="main">
      <?php
      require_once("views/layout/sidebar.php");    
      ?>
      <div class="content" id="content-container">
        <div class="block">
          <p class="title">Prospectos <span class="f-left">ID de proveedor: <span>9999</span></span></p>
          <div class="row">
            <div class="col-sm-4 placeholder-img" >
              <img src="img/1x1.png">
              <div class="uploaded-picture"></div>
              <div class="upload-picture">
                <div>
                  <img src="img/svg/pencil.svg">
                  <p>Editar fotografía</p>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <label for="productName">Etiqueta</label>
                <input type="text" class="form-control" placeholder="Etiqueta del producto">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary one-edit" type="button">Editar</button>
                </div>
                <div class="form-control-feedback">Try again.</div>
              </div>
              <div class="input-group">
                <label for="productName">Etiqueta</label>
                <textarea type="text" class="form-control" id="productDescription" aria-describedby="productDescriptionHelp" placeholder="Product Name">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur libero, sint ratione architecto error labore praesentium odio soluta aliquam illo, delectus nesciunt quibusdam corporis earum, cupiditate quaerat, vel odit autem.</textarea>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary one-edit" type="button">Editar</button>
                </div>
                <div class="form-control-feedback">Try again.</div>
              </div>

            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <label for="productName">Etiqueta</label>
                <input type="text" class="form-control" placeholder="Etiqueta del producto">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary one-edit" type="button">Editar</button>
                </div>
                <div class="form-control-feedback">Try again.</div>
              </div>
              <div class="input-group">
                <label for="productName">Etiqueta</label>
                <input type="text" class="form-control" placeholder="Etiqueta del producto">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary one-edit" type="button">Editar</button>
                </div>
                <div class="form-control-feedback">Try again.</div>
              </div>
              <div class="input-group">
                <label for="productName">Etiqueta</label>
                <input type="text" class="form-control" placeholder="Etiqueta del producto">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary one-edit" type="button">Editar</button>
                </div>
                <div class="form-control-feedback">Try again.</div>
              </div>

              <div class="form-group">
                <label for="productName">Precio Público</label>
                <input type="text" class="form-control" id="productName" 
                       aria-describedby="productNameHelp" placeholder="Product Name">
                <small id="productNameHelp" class="form-text text-muted">This is an error baby</small>
              </div>
              <div class="form-group">
                <label for="productName">Costo Proveedor</label>
                <input type="text" class="form-control" id="productName" 
                       aria-describedby="productNameHelp" placeholder="Product Name">
                <small id="productNameHelp" class="form-text text-muted">This is an error baby</small>
              </div>
              <div class="form-group">
                <label for="productName">Precio Sugerido</label>
                <input type="text" class="form-control" id="productName" 
                       aria-describedby="productNameHelp" placeholder="Product Name">
                <small id="productNameHelp" class="form-text text-muted">This is an error baby</small>
              </div>
              <div class="form-group">
                <label for="productStatus">Example multiple select</label>
                <select class="form-control select-main" id="productStatus">
                  <option>Activo</option>
                  <option>Inactivo</option>
                </select>
              </div>

            </div>

          </div>
          <hr>
          <div class="col">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" href="#general" role="tab" data-toggle="tab">General</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#logistic" role="tab" data-toggle="tab">Huella</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#additional" role="tab" data-toggle="tab">Adicionales</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#related" role="tab" data-toggle="tab">Relacionados</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade in active" id="general">...</div>
              <div role="tabpanel" class="tab-pane fade" id="logistic">bbb</div>
              <div role="tabpanel" class="tab-pane fade" id="additional">ccc</div>
              <div role="tabpanel" class="tab-pane fade" id="related">ddd</div>
            </div>
          </div>
        </div>
        <div class="block">
          <p class="title">Prospectos</p>
          <p class="subtitle">Filtro por:</p>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="productStatus">Rango</label>
                <select class="form-control select-main" id="productStatus">
                  <option>Rango</option>
                  <option>r1</option>
                  <option>r2</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="productStatus">Categoría</label>
                <select class="form-control select-main" id="productStatus">
                  <option>Categoría</option>
                  <option>c1</option>
                  <option>c2</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="productStatus">Marcas</label>
                <select class="form-control select-main" id="productStatus">
                  <option>Marca</option>
                  <option>m1</option>
                  <option>m2</option>
                </select>
              </div>
            </div>
          </div>
          <div class="toggler"><span class="list"></span></div>
          <div class="art-list-wrap">
            <div class="art-list list">
              <div class="art-item">
                <div class="art-check">
                  <p> </p>
                </div>
                <div class="art-img">
                  <p>IMG</p>
                </div>
                <div class="art-title">
                  <p>Título</p>
                </div>
                <div class="art-price">
                  <p>Precio</p>
                </div>
                <div class="art-status">
                  <p>Estado</p>
                </div>
                <div class="art-actions">
                  <p>Acciones</p>
                </div>
              </div>  
              <div class="art-item">
                <div class="art-check">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                  </label>
                </div>
                <div class="art-img">
                  <img src="img/1x1.png" alt="SKU">
                </div>
                <div class="art-title">
                  <p>Carrito eléctrico X-Speed, negro. Mytoy®</p>
                </div>
                <div class="art-price">
                  <p>$123.456.00</p>
                </div>
                <div class="art-status">
                  <p>Activo</p>
                </div>
                <div class="art-actions">
                  <div class="form-group">
                    <select class="form-control select-main" id="productStatus">
                      <option>Acciones</option>
                      <option>Ver</option>
                      <option>Editar</option>
                      <option>Eliminar</option>
                    </select>
                  </div>
                </div>
              </div>    
              <div class="art-item">
                <div class="art-check">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                  </label>
                </div>
                <div class="art-img">
                  <img src="img/1x1.png" alt="SKU">
                </div>
                <div class="art-title">
                  <p>Procesador de alimentos Prime 1000 W. Nutribullet®</p>
                </div>
                <div class="art-price">
                  <p>$123.456.00</p>
                </div>
                <div class="art-status">
                  <p>Activo</p>
                </div>
                <div class="art-actions">
                  <div class="form-group">
                    <select class="form-control select-main" id="productStatus">
                      <option>Acciones</option>
                      <option>Ver</option>
                      <option>Editar</option>
                      <option>Eliminar</option>
                    </select>
                  </div>
                </div>
              </div>   
              <div class="art-item">
                <div class="art-check">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                  </label>
                </div>
                <div class="art-img">
                  <img src="img/1x1.png" alt="SKU">
                </div>
                <div class="art-title">
                  <p>Altavoz inalámbrico SoundTouch 30, negro. Bose®</p>
                </div>
                <div class="art-price">
                  <p>$123.456.00</p>
                </div>
                <div class="art-status">
                  <p>Activo</p>
                </div>
                <div class="art-actions">
                  <div class="form-group">
                    <select class="form-control select-main" id="productStatus">
                      <option>Acciones</option>
                      <option>Ver</option>
                      <option>Editar</option>
                      <option>Eliminar</option>
                    </select>
                  </div>
                </div>
              </div>    
              <div class="art-item">
                <div class="art-check">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                  </label>
                </div>
                <div class="art-img">
                  <img src="img/1x1.png" alt="SKU">
                </div>
                <div class="art-title">
                  <p>Smart TV LED UHD 4K 55". LG®</p>
                </div>
                <div class="art-price">
                  <p>$123.456.00</p>
                </div>
                <div class="art-status">
                  <p>Activo</p>
                </div>
                <div class="art-actions">
                  <div class="form-group">
                    <select class="form-control select-main" id="productStatus">
                      <option>Acciones</option>
                      <option>Ver</option>
                      <option>Editar</option>
                      <option>Eliminar</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="art-item">
                <div class="art-check">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                  </label>
                </div>
                <div class="art-img">
                  <img src="img/1x1.png" alt="SKU">
                </div>
                <div class="art-title">
                  <p>Smart TV LED UHD 4K 55". LG®</p>
                </div>
                <div class="art-price">
                  <p>$123.456.00</p>
                </div>
                <div class="art-status">
                  <p>Activo</p>
                </div>
                <div class="art-actions">
                  <div class="form-group">
                    <select class="form-control select-main" id="productStatus">
                      <option>Acciones</option>
                      <option>Ver</option>
                      <option>Editar</option>
                      <option>Eliminar</option>
                    </select>
                  </div>
                </div>
              </div>       
            </div>
          </div>




          <div>
            <p>Modificar seleccionados:</p>
            <select name="" id="" class="select-main">
              <option value="">Acciones seleccionadas</option>
              <option value="">Acción 1</option>
              <option value="">Acción 2</option>
            </select>
          </div>
          <div>
            <p>Agregar prospecto:</p>
            <button class="btn-main">Individual</button>
            <button class="btn-main">Excel</button>
          </div>
        </div>


        <div class="block">
          <form>
            <div class="form-group row">
              <label for="inputHorizontalSuccess" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control form-control-success" id="inputHorizontalSuccess" placeholder="name@example.com">
                <div class="form-control-feedback">Success! You've done it.</div>
                <small class="form-text text-muted">Example help text that remains unchanged.</small>
              </div>
            </div>
            <div class="form-group row has-warning">
              <label for="inputHorizontalWarning" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control form-control-warning" id="inputHorizontalWarning" placeholder="name@example.com">
                <div class="form-control-feedback">Shucks, check the formatting of that and try again.</div>
                <small class="form-text text-muted">Example help text that remains unchanged.</small>
              </div>
            </div>
            <div class="form-group row has-danger">
              <label for="inputHorizontalDnger" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control form-control-danger" id="inputHorizontalDnger" placeholder="name@example.com">
                <div class="form-control-feedback">Sorry, that username's taken. Try another?</div>
                <small class="form-text text-muted">Example help text that remains unchanged.</small>
              </div>
            </div>
          </form>
        </div>
        <?php
        require_once("views/layout/footer.php");    
        ?>
      </div>
    </div>
    <!-- /Contenedor General -->
    <!-- Footer -->

    <!-- /Footer -->
    <script type="text/javascript" src="js/default.js?v=<?php echo filemtime( "js/default.js" )?>"></script>
  </body>
</html>