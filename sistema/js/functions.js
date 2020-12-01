$(document).ready(function(){

    $('.btnMenu').click(function(e){
        e.preventDefault();
        if($('nav').hasClass('viewMenu')) 
        {
            $('nav').removeClass('viewMenu');
        }else{
            $('nav').addClass('viewMenu');
        }
    });

    $('nav ul li').click(function(){
        $('nav ul li ul').slideUp();
        $(this).children('ul').slideToggle();
    });

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();
        
        if($("#foto_actual") && $("#foto_remove")) {
            $("#foto_remove").val('img_producto.png');
        }
    });

    // Modal form Add Product
    $('.add_product').click(function(e){
        /* Act on the event */
        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},

            success: function(response){
                if(response != 'error') {

                    var info = JSON.parse(response);

                    //$('#producto_id').val(info.Id_producto);
                    //$('.name_producto').html(info.nombre);

                    $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                                                '<h1><i class="fas fa-tshirt" style="font-size: 45pt;"></i> <br> Agregar Producto </h1>'+
                                                '<h2 class="name_producto">'+info.producto+'</h2><br>'+
                                                '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad" required><br>'+
                                                '<input type="number" name="precio" id="txtPrecio" placeholder="Precio" required>'+
                                                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.Id_producto+'" required>'+
                                                '<input type="hidden" name="action" value="addProduct" required>'+
                                                '<div class="alert alertAddProduct"></div>'+
                                                '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Agregar</button>'+
                                                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                            '</form>');
                }
            },

            error: function(error){
                console.log(error);
            }
        });



        $('.modal').fadeIn();
    });

    // Modal form Delete Product
    $('.del_product').click(function(e){
        /* Act on the event */
        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},

            success: function(response){
                if(response != 'error') {

                    var info = JSON.parse(response);

                    //$('#producto_id').val(info.Id_producto);
                    //$('.name_producto').html(info.nombre);

                    $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                                                '<h1><i class="fas fa-tshirt" style="font-size: 45pt;"></i> <br> Eliminar Producto </h1>'+
                                                '<p>¿Está seguro de eliminar el producto?</p>'+
                                                '<h2 class="name_producto">'+info.producto+'</h2><br>'+
                                                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.Id_producto+'" required>'+
                                                '<input type="hidden" name="action" value="delProduct" required>'+
                                                '<div class="alert alertAddProduct"></div>'+

                                                '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                                '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Confirmar</button>'+
                                            '</form>');
                }
            },

            error: function(error){
                console.log(error);
            }
        });



        $('.modal').fadeIn();
    });


    //Activar campos para registrar pedido
    $('.btn_new_proveedor').click(function(e){
        e.preventDefault();
        $('#nombre_proveedor').removeAttr('disabled');
        $('#telefono_proveedor').removeAttr('disabled');

        $('#div_registro_proveedor').slideDown();
    });

    //Buscar proveedor
    $('#nit_proveedor').keyup(function(e){
        e.preventDefault();

        var pr = $(this).val();
        var action = 'searchProveedor';

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,  
            data: {action:action,proveedor:pr},

            success: function(response)
            {

                if (response == 0) {
                    $('#id_proveedor').val('');
                    $('#nombre_proveedor').val('');
                    $('#telefono_proveedor').val('');
                    //Mostrar boton agregar
                    $('.btn_new_proveedor').slideDown();
                }else{
                    var data = $.parseJSON(response);
                    $('#id_proveedor').val(data.id_proveedor);
                    $('#nombre_proveedor').val(data.nombre);
                    $('#telefono_proveedor').val(data.telefono);
                    //Ocultar boton agregar
                    $('.btn_new_proveedor').slideUp();

                    //Bloque campos
                    $('#nombre_proveedor').attr('disabled','disabled');
                    $('#telefono_proveedor').attr('disabled','disabled');

                    //Ocultar boton guardar
                    $('#div_registro_proveedor').slideUp();
                }
            },
            error: function(error){
            }
        });
    });

    //Crear Proveedor - Factura
    $('#form_new_proveedor_factura').submit(function(e){
        e.preventDefault();

         $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,  
            data: $('#form_new_proveedor_factura').serialize(),

            success: function(response)
            {
               if (response != 'error') {
                //Agregar id a input hidden
                $('#id_proveedor').val(response);
                //Bloqueo de campos
                 $('#nombre_proveedor').attr('disabled','disabled');
                 $('#telefono_proveedor').attr('disabled','disabled');

                 //Ocultar boton agregar
                 $('.btn_new_proveedor').slideUp();
                 //Ocultar boton guardar
                 $('#div_registro_proveedor').slideUp();
               }
            },
            error: function(error){
            }
        });
    });

    //Buscar producto
    $('#txt_Id_producto').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto';

        if(producto != '') 
        {
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,  
                data: {action:action,producto:producto},

                success: function(response)
                {
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        $('#txt_producto').html(info.producto);
                        $('#txt_cantidad').html(info.cantidad);
                        $('#txt_cant_producto').val('1');
                        $('#txt_precio').html(info.precio);
                        $('#txt_precio_total').html(info.precio);

                        //Activar Cantidad
                        $('#txt_cant_producto').removeAttr('disabled');

                        //Mostrar boton agregar
                        $('#add_producto_pedido').slideDown();
                    }else{
                        $('#txt_producto').html('-');
                        $('#txt_cantidad').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');

                        //Bloquear Cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //Ocultar boton agregar
                        $('#add_producto_pedido').slideUp();
                    }
                },
                error: function(error){
                }
            });
        }
    });

    //Validar cantidad del producto antes de agregar
    $('#txt_cant_producto').keyup(function(e){
        e.preventDefault();
        var precio_total = $(this).val() * $('#txt_precio').html();
        var existencia = parseInt($('#txt_cantidad').html()); 
        $('#txt_precio_total').html(precio_total);

        //Ocultar el boton agregar si la cantidad es menor que 1
        if (  $(this).val() < 0 || isNaN($(this).val()) || ($(this).val() > existencia) ) {
            $('#add_producto_pedido').slideUp();
        }else{
            $('#add_producto_pedido').slideDown();
        }
    });

    //Agregar producto al detalle
    $('#add_producto_pedido').click(function(e){
        e.preventDefault();
        if ($('#txt_cant_producto').val() > 0) {
            var Id_producto = $('#txt_Id_producto').val();
            var cantidad = $('#txt_cant_producto').val();
            var action = 'addProductoDetalle';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action,producto:Id_producto,cantidad:cantidad},

                success: function(response)
                {
                    if (response != 'error') 
                    {
                        var info = JSON.parse(response);
                        $('#detalle_pedido').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        $('#txt_Id_producto').val('');
                        $('#txt_producto').html('-');
                        $('#txt_cantidad').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');

                        //Bloquear Cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //Ocultar boton agregar
                        $('#add_producto_pedido').slideUp();
                    }else{
                        console.log('No data');
                    }

                    viewProcesar();
                },
                error: function(error){
                }
            });
        }
    });

    //Anular Pedido
    $('#btn_anular_pedido').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_pedido tr').length;
        if (rows > 0) 
        {
            var action = 'anularPedido';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action},
            
            success: function(response)
            {
                if (response != 'error') {
                    location.reload();
                }
            },
            error: function(error){
            }

            });
        }
    });


    //Facturar Pedido
    $('#btn_facturar_pedido').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_pedido tr').length;
        if (rows > 0) 
        {
            var action = 'procesarPedido';
            var id_proveedor = $('#id_proveedor').val();

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action,id_proveedor:id_proveedor},
            
            success: function(response)
            {
                console.log(response);
                if (response != 'error') {
                    var info = JSON.parse(response);
                    //console.log(info);

                    generarPDF(info.id_proveedor,info.nofactura)

                    location.reload();
                }else{
                    console.log('no data');
                }
            },
            error: function(error){
            }

            });
        }
    });


     // Modal form Anular Factura
    $('.anular_factura').click(function(e){
        /* Act on the event */
        e.preventDefault();
        var nofactura = $(this).attr('fac');
        var action = 'infoFactura';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,nofactura:nofactura},

            success: function(response){
                if(response != 'error') {

                    var info = JSON.parse(response);


                    $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
                                              '<h1><i class="far fa-file-alt" style="font-size: 45pt;"></i> <br> Anular Factura </h1><br>'+
                                              '<p>¿Está seguro de anular la factura?</p>'+
                                              '<p><strong>No. '+info.nofactura+'</strong></p>'+
                                              '<p><strong>Precio $. '+info.totalfactura+'</strong></p>'+
                                              '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
                                              '<input type="hidden" name="action" value="anularFactura">'+
                                              '<input type="hidden" name="no_factura" id="no_factura" value="'+info.nofactura+'" required>'+
                                              '<div class="alert alertAddProduct"></div>'+
                                               '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                               '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Anular</button>'+
                                           '</form>');
                }
            },

            error: function(error){
                console.log(error);
            }
        });



        $('.modal').fadeIn();
    });

    //Ver factura
    $('.view_factura').click(function(e){
        e.preventDefault();

        var codProveedor = $(this).attr('pr');
        var noFactura = $(this).attr('f');

        generarPDF(codProveedor,noFactura);
    });


    //Cambiar Password
    $('.newPass').keyup(function(){
        validPass();
    });


    //FORM CAMBIAR CONTRASEÑA
    $('#frmChangePass').submit(function(e){
        e.preventDefault();

        var passActual = $('#txtPassUser').val();
        var passNuevo = $('#txtNewPassUser').val();
        var confirmPassNuevo = $('#txtPassConfirm').val();
        var action = "changePassword";
    
        if (passNuevo != confirmPassNuevo) {
            $('.alertChangePass').html('<p style="color:red;">Las contraseñas con coinciden.</p>');
            $('.alertChangePass').slideDown();
            return false;
        }

        if (passNuevo.length < 6) {
            $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe tener 6 caracteres minimo.</p>');
            $('.alertChangePass').slideDown();
            return false;
    }

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,passActual:passActual,passNuevo:passNuevo},
        
        success: function(response)
        {
            if (response != 'error') {
                var info = JSON.parse(response);
                if (info.cod == '00') {
                    $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
                    $('#frmChangePass')[0].reset();
                }else{
                    $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');
                }
                $('.alertChangePass').slideDown();
            }
        },
        error: function(error){
            }

        });
    });


    //Actualizar datos empresa
    $('#frmEmpresa').submit(function(e){
        e.preventDefault();

        var intNit    = $('#txtNit').val();
        var strNombre   = $('#txtNombre').val();
        var intTelefono   = $('#txtTelEmpresa').val();
        var strEmail    = $('#txtEmailEmpresa').val();
        var strDireccion    = $('#txtDireccion').val();


        if (intNit == '' || strNombre == '' || intTelefono == '' || strEmail == '' || strDireccion == '') {
            $('.alertFormEmpresa').html('<p style="color:red;">Todos los campos son requeridos.</p>');
            $('.alertFormEmpresa').slideDown();
            return false;
        }

        $.ajax({
            url: 'ajax.php', 
            type: "POST",
            async: true,
            data: $('#frmEmpresa').serialize(),

            beforeSend: function(){
                $('.alertFormEmpresa').slideUp();
                $('.alertFormEmpresa').html('');
                $('.alertFormEmpresa').attr('disabled','disabled');
            },

            success: function(response)
            {
                var info = JSON.parse(response);
                if (info.cod == '00') {
                    $('.alertFormEmpresa').html('<p style="color: #23922d;">'+info.msg+'</p>');
                    $('.alertFormEmpresa').slideDown();
                }else{
                    $('.alertFormEmpresa').html('<p style="color:red;">'+info.msg+'</p>');
                }
                $('.alertFormEmpresa').slideDown();
                $('#frmEmpresa input').removeAttr('disabled');

            },
            error: function(error){
            }

        });


        });

}); //END READY

function validPass(){
    var passNuevo = $('#txtNewPassUser').val();
    var confirmPassNuevo = $('#txtPassConfirm').val();
    if (passNuevo != confirmPassNuevo) {
        $('.alertChangePass').html('<p style="color:red;">Las contraseñas con coinciden.</p>');
        $('.alertChangePass').slideDown();
        return false;
    }

    if (passNuevo.length < 6) {
        $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe tener 6 caracteres minimo.</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
        $('.alertChangePass').html('');
        $('.alertChangePass').slideDown();
}

//Anular Factura
function anularFactura(){
    var noFactura = $('#no_factura').val();
    var action = 'anularFactura';

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async: true,
        data: {action:action,noFactura:noFactura},

        success: function(response)
        {
            if (response == 'error') {
                $('.alertAddProduct').html('<p style="color:red;">Error al anular la factura</p>');
            }else{
                $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                $('#form_anular_factura .btn_ok').remove();
                $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
                $('.alertAddProduct').html('<p>Factura Anulada.</p>');
            }

        },
        error: function(error){
        }

    });
}


function generarPDF(proveedor,factura){
    var ancho = 1000;
    var alto = 800;

    //Calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho / 2));
    var y = parseInt((window.screen.height/2) - (alto / 2));

    $url = 'factura/generaFactura.php?pr='+proveedor+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

function del_product_detalle(correctivo){
    var action = 'del_product_detalle';
    var id_detalle = correctivo; 

      $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_detalle:id_detalle},

            success: function(response)
            {
                if (response != 'error') {
                    var info = JSON.parse(response);

                    $('#detalle_pedido').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    $('#txt_Id_producto').val('');
                    $('#txt_producto').html('-');
                    $('#txt_cantidad').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    //Bloquear Cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');

                    //Ocultar boton agregar
                    $('#add_producto_pedido').slideUp();


                }else{
                    $('#detalle_pedido').html('');
                    $('#detalle_totales').html('');
                }
                viewProcesar();
            },
            error: function(error){
            }
     });
}


//Mostrar/Olcultar boton procesar
function viewProcesar(){
    if ($('#detalle_pedido tr').length > 0) {
        $('#btn_facturar_pedido').show();
    }else{
        $('#btn_facturar_pedido').hide();
    }
}

function serchForDetalle(id){
    var action = 'serchForDetalle';
    var user = id; 

      $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,user:user},

            success: function(response)
            {
                if (response != 'error') {

                var info = JSON.parse(response);
                $('#detalle_pedido').html(info.detalle);
                $('#detalle_totales').html(info.totales);
            }else{
                console.log("No data");
            }
            viewProcesar();

            },
            error: function(error){
            }

     });
}


function sendDataProduct(){

    $('.alertAddProduct').html('');
    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_product').serialize(),

            success: function(response){
                if (response == 'error') 
                {
                    $('.alertAddProduct').html('<p style="color: red;">Error al agregar Producto.</p>');
                }else
                {
                     var info = JSON.parse(response);
                     $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                     $('.row'+info.producto_id+' .celCantidad').html(info.nueva_cantidad);
                     $('#txtCantidad').val('');
                     $('#txtPrecio').val('');
                     $('.alertAddProduct').html('<p>Producto agregado correctamente.</p>');
                }
            },

            error: function(error){
                console.log(error);
            }
    });
}

//Eliminar producto
function delProduct(){

    var pr = $('#producto_id').val();
    $('.alertAddProduct').html('');
    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_product').serialize(),

            success: function(response){
                console.log(response);
                
                if (response == 'error') 
                {
                    $('.alertAddProduct').html('<p style="color: red;">Error al agregar Producto.</p>');
                }else{
                     $('.row'+pr).remove();
                     $('#form_del_product .btn_ok').remove();
                     $('.alertAddProduct').html('<p>Producto Eliminado correctamente.</p>');
                }
                
            },

            error: function(error){
                console.log(error);
            }
    });
}
function closeModal(){
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}

