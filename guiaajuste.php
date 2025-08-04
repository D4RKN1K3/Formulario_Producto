<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<link href="<?=$POSICION?>componente/ActiveWidgets/runtime/styles/classic/awdesis.css?v=1.1" rel="stylesheet" type="text/css" />
<link href="<?=$POSICION?>form/existencias/style.css?v=1.2" rel="stylesheet" type="text/css" />
<link href="<?=$POSICION;?>componente/mascara/lightboxGrande.css" rel="stylesheet" media="screen,projection" type="text/css" />
<link href="<?=$POSICION?>css/fesize.css?v=22.10.03" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$POSICION?>componente/ActiveWidgets/runtime/lib/aw.js"></script>
<script type="text/javascript" src="<?=$POSICION;?>componente/mascara/lightbox-iframe.js"></script>
<script type="text/javascript" src="<?=$POSICION;?>form/existencias/iu.js?var=2023.08.01"></script>
<script type="text/javascript" src="<?=$POSICION?>componente/buscadores/centrocosto/class.js?v=6.1"></script>
<script type='text/javascript' src='<?=$POSICION?>componente/keybox/class.php'></script>

<script type="text/javascript" src="<?=$POSICION?>componente/messagebox/class.php?v=1"></script>
<link href="<?=$POSICION?>css/nuevoestilo.css?v=1.9" rel="stylesheet" type="text/css" >
<?php if($isejecutartutorial === true){?>
	<?=TooltipNewFeature::nextTooltip();?>
	<div id="overlay2"><?=$overlay_inicioguiada?></div>
	<div>
		<div style="position:relative;bottom:30px" id="div-contenedor-tooltip-inicio"><?=$tool1_guia?></div>
		<div style="position:relative;float:right"><?=$tool12_guia?></div>
	</div>
<?php } ?>
<?php
if ($_SESSION["ss_modoprueba"]["EXISTENCIA"] == 't') {
	franjamoduloprueba("EXISTENCIA");
}



if($_SESSION['ss_TIPO_MOVIMIENTO_GUIAAJUSTE'] == ''){
	$sql 		= "SELECT tipo,estado FROM public.usuariopreferencia WHERE idusuario = $1 AND tipo = 'TIPO_MOVIMIENTO_GUIAAJUSTE';";
	$params 	= array($_SESSION["ss_user"]);
	$resultado	= $Base->query_params($Base->conn, $sql, $params);
	$obj = $Base->to_object($resultado);
	if($obj->tipo == 'TIPO_MOVIMIENTO_GUIAAJUSTE'){
		$_SESSION["ss_TIPO_MOVIMIENTO_GUIAAJUSTE"] = $obj->estado;
	}else{
		$_SESSION["ss_TIPO_MOVIMIENTO_GUIAAJUSTE"] = 'I';	
	}
}
$preferenciaTipoMovimiento = $_SESSION["ss_TIPO_MOVIMIENTO_GUIAAJUSTE"];
$ingresoDesdeLibroCompraSinXML = false;

?>


<?php if($SYS_Bodega==true){} echo "<br>"; 
$origencompra = $_REQUEST["origencompra"];
if ($origencompra != "T") {
	$origencompra = "F";
} else {
    $iddocumento = $lc_iddocumento != "" ? $lc_iddocumento :  $_REQUEST["iddocumento"];
    if($lc_iddocumento != "" && $lc_iddocumento*1 > 0){
    	$_SESSION["ss_TIPO_MOVIMIENTO_GUIAAJUSTE"] = 'I';	
    	$ingresoDesdeLibroCompraSinXML = true;
    }
	if ($iddocumento != null) {
		$sql =
			"SELECT
				folio
				,idtipodocumento
				,montototal
			FROM public.documentocompra
			WHERE
				iddocumentocompra = $1";
		$params = array($iddocumento * 1);
		$result = $Base->query_params($Base->conn, $sql, $params);

		$error = $Base->last_error($Base->conn);


		$row = $Base->to_object($result);
		$folio 				= $row->folio;
		$idtipodocumento 	= $row->idtipodocumento;
		$total 				= $row->montototal;
	}
}
$sql = "SELECT controlexistenciasguiasajuste FROM public.empresa LIMIT 1";
$params = array();
$result = $Base->query_params($Base->conn, $sql, $params);

$row = $Base->to_object($result);
$accionajuste = $row->controlexistenciasguiasajuste;
$accionajuste = $accionajuste*1;
//BUSCAMOS BODEGA DE LA SUCURSAL se asigna por defecto ;)
$bodegadefault = "SELECT idbodega, nombre FROM bodega WHERE idsucursal = ".$_SESSION['ss_sucursal']." AND vigente = true AND pordefecto = true";
$Base->send_query($Base->conn, $bodegadefault);
$bodegaresult = $Base->get_result($Base->conn);
$bodegaobj = $Base->to_object($bodegaresult);

if ($bodegaobj->idbodega == ''){
	$idbodegadefault = 0;
}
else{
	$idbodegadefault = $bodegaobj->idbodega;
}
$descripcionbodegadefault = $bodegaobj->nombre;
?>

<style>
	LEGEND{
		font-size: 11px;
		font-weight: normal;
		color: #000000;
	}
	<?php if($isejecutartutorial === true){?>
	#tool1 #div_tool{
		max-width: 460px;
	}
	#tool2 #div_tool1{
		max-width: 430px;
		width: 430px;
	}
	#tool3 #div_tool2{
		max-width: 410px;
		width: 410px;
	}
	#tool4 #div_tool3{
		max-width: 440px;
		width: 440px
	}
	#tool5 #div_tool4, 
	#tool6 #div_tool5, 
	#tool7 #div_tool6, 
	#tool8 #div_tool7,
	#tool9 #div_tool8,
	#tool10 #div_tool9,
	#tool11 #div_tool10{
		max-width: 430px;
		width: 430px;
	}

	#tool11 #div_tool10{
		top: 13px;
	}

	#tool11 #div_tool10 #mensaje-tooltip #indicador-mensaje{
		border-bottom: 15px solid transparent;
	}

	#tool11 #div_tool10 #mensaje-tooltip #indicador-mensaje2{
		top: 166px;
		border-bottom: 3px solid transparent;
		border-top: 15px solid #fff;
		left: 400px
	}

	#tool12 #div_tool11{
		left: auto !important;
		right: 100%;
		max-width: 410px;
		width: 410px;
	}

	
	#tool12 #div_tool11 #mensaje-tooltip #indicador-mensaje{
		border-bottom: 15px solid transparent;
	}

	#tool12 #div_tool11 #mensaje-tooltip #indicador-mensaje2{
		float: right;
	}

	.encimaDeTodo{
		position: relative;
		z-index: 10001;
	}

	.bloqueado{
		pointer-events: none;
	}

<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){?>
	#background_tool2.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 525px;
		height: 38px;
		top: -14px;
		z-index: 10000;
	}

	#background_tool3.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 140px;
		height: 61px;
		top: -2px;
		z-index: 10000;
		left: -3px;
	}

	#background_tool4.backgroundTool{
		position: absolute;
		background-color: #bbc5d4;
		width: 115px;
		height: 45px;
		z-index: 10000;
		left: -2px;
	}

	#background_tool5.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 104%;
		height: 61px;
		top: -51px;
		z-index: 10000;
		left: -2px;
	}

	#background_tool9.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 104%;
		height: 61px;
		top: -2px;
		z-index: 10000;
		left: -2px;
	}

	#background_tool10.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 95px;
		height: 61px;
		top: -51px;
		z-index: 10000;
		left: -2px;
	}

	.posicionTool10{
		position:relative;
		left: 4%;
		top: 10px;
	}

	<?php }else{?>

	#background_tool2.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 524px;
		height: 49px;
		top: -18px;
		z-index: 10000;
	}

	#background_tool3.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 140px;
		height: 75px;
		top: -9px;
		z-index: 10000;
		left: -3px;
	}

	#background_tool4.backgroundTool{
		position: absolute;
		background-color: #bbc5d4;
		width: 115px;
		height: 48px;
		top: -3px;
		z-index: 10000;
		left: -2px;
	}

	#background_tool5.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 104%;
		height: 75px;
		z-index: 10000;
		left: -2px;
		top: -64px;
	}

	#background_tool9.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 104%;
		height: 75px;
		z-index: 10000;
		left: -2px;
		top: -9px;
	}

	#background_tool10.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 106%;
		height: 75px;
		z-index: 10000;
		left: -2px;
		top: -64px;
	}

	.posicionTool10{
		position:relative;
		left: 20%;
		top: 10px;
	}
	<?php }?>

	#background_tool11.backgroundTool{
		position: absolute;
		background-color: #F4F4F4;
		width: 200px;
		height: 108px;
		z-index: 10000;
		left: auto;
		top: -21px;
		right: 0px;
	}
<?php } ?>
</style>
<fieldset style="width:660px">
	<legend>Encabezado</legend>
<table class="FORMULARIONECSS">
	<tr>
		<td class="textonegro">Fecha</td>
		<td class="textonegro">
			<div style="width: 180px;">
				<?php
				$nom_fech                = 'fecha';         
				$btn_eve_fech            = 'fecha_btn1';    
				$btn_clear_fech          = 'fecha_btn2';    
				$btn_borrar_in           = true; 
				$chan_fech               = 'fechanovalidada()';
				$fech_style              = "cursor:pointer; text-align:center; width: 7.9rem;";
				$val_fech                = date('d-m-Y');
				$nom_fech_disabled       = "F";
				$btn_clear_fech_disabled = "F";
				include $POSICION."componente/calendario/componente_fechadd.php";
				?>
			<script type="text/javascript">
				<?php if ($SYS_CENTROCOSTO) { ?>
					var tieneCC = 1;
				<?php } else { ?>
					var tieneCC = 0;
				<?php } ?>
			</script>
			</div>
		</td>
		
        <td class="textonegro">
			<div style="position:relative">
				<div id="background_tool2">
				</div>
			</div>
			<div id="td_descripcion">
        	Descripci&oacute;n
			</div>
		</td>
		<td id='td_descripcion_input'>
			<textarea name="observacion" id="observacion" class="txt_obligatorio" style="width:450px; height:21px;" placeholder= "Ej.: Inventario Inicial ; Salida de Productos Vencidos ; Ajuste de Inventario"></textarea>
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative;top:15px; left: 50%"><?=$tool2_guia?></div>
			<?php  } ?>
		</td>
	</tr>
</table>
</fieldset>
<div class="FILA_bg" style="margin-top:20px">
	<div style="padding-top:6px;padding-left:25px">DETALLE</div>
</div>

<div id="div_resumen"></div>
<table summary="" class="FORMULARIONECSS textonegro"  border="0" width="100%">
	<tr class="textonegro">
		<td>
			<div style="position:relative">
				<div id="background_tool3">
				</div>
			</div>
			<div id="td_producto">
				Producto
			</div>
		</td>
	    <td>Descripci&oacute;n</td>
	    <td>
			<div id="td_tipomovimiento">
				Tipo de Movimiento
			</div>
		</td>
		
	    <td>
			<div style="position:relative">
				<div id="background_tool9">
				</div>
			</div>
			<div id="td_cantidad">
			Cantidad
			</div>
		</td>
	    <td>
			<div id="td_preciocostounitario">
				Precio Costo Unitario
			</div>
		</td>
	    <td>Monto &Iacute;tem</td>
	    <td  id="t_chkbodega" name="t_chkbodega" align="center">Bodega</td>
		<td style="text-align:center">Centro de costo</td>
		<!-- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 -->
		<td width="90px">
			<div style="position:relative">
				<div id="background_tool9">
				</div>
			</div>
			<div id="td_fechamovimiento">
				Fecha de Movimiento
			</div>
		</td>
		<!-- FIN REQUERIMIENTO 14 -->
		<td></td>
	</tr>
	<tr>
		<td width="140px" nowrap>
			<input type="hidden" name="I_idproducto" id="I_idproducto" />
			<input type="hidden" id="stock">
			<input type="hidden" id="perecible">
			<input type="hidden" id="asignarlote">
			<input type="hidden" id="checkperecible">
			<input type="hidden" id="fechaCaducidadObligatoria">
			<input type="hidden" id="asignarserie">
			<input type="hidden" id="nroserieobligatorio">
			<input type="text" name="I_codigo" id="I_codigo" class="txt_editables2" style="width:85px;margin-top:2px" />
			<button type="button" id="BTN_busca" class="textonegro icono" style="width:20px;height:20px;">
				<img alt="" style="vertical-align: middle;" src="<?=$POSICION;?>img/ico/icono-lupa.png"/>
			</button>
			<button type="button" id="BTN_nuevo" class="textonegro icono" style="width:20px;height:20px;">
				<img alt="" style="vertical-align: middle;width:16px;heigth:16px" src="<?=$POSICION;?>img/ico/v2/btn_nuevo.png"/>
			</button>
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative;left: 60px;top: 10px;"><?=$tool3_guia?></div>
			<?php } ?>
		</td>
		<td width="236px">
			<input type="text" name="I_descripcion" id="I_descripcion" style="width:100%" class="txt_no_editables" readonly />
		</td>
		<td width="7%" align="center">
			<select class="txt_editables" id="tipomovimiento" style="width:100%" onchange="activabodegas(this.value, true)"> 
				<option value="I">Entrada</option>
				<option value="E">Salida</option>
				<option value="T">Traspaso</option>
                <option value="INV">Inventario</option>
			</select>
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative">
				<div id="background_tool5">
				</div>
			</div>
			<div style="position:relative;left: 30%;top: 10px;"><?=$tool5_guia?></div>
			<div style="position:relative;left: 30%;top: 10px;"><?=$tool6_guia?></div>
			<div style="position:relative;left: 30%;top: 10px;"><?=$tool7_guia?></div>
			<div style="position:relative;left: 30%;top: 10px;"><?=$tool8_guia?></div>
			<?php } ?>
		</td>
		<td width="90px">
			<input type="text" name="I_cantidad" id="I_cantidad" class="txt_obligatorio" onblur="checkCantidad()" onkeypress="javascript:return solonumeros(event);" style="text-align:right;width:90px" />
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative;left: 20%;top: 10px;"><?=$tool9_guia?></div>
			<?php } ?>
		</td>
		<td width="90px">
			<input type="text" name="I_preciounitario" id="I_preciounitario" class="txt_obligatorio" onkeypress="javascript:return solonumeros(event);" style="text-align:right;width:90px" />
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative">
				<div id="background_tool10">
				</div>
			</div>
			<div class="posicionTool10"><?=$tool10_guia?></div>
			<?php } ?>
		</td>
		<td width="90px">
			<input type="text" name="I_montoitem" id="I_montoitem" class="txt_no_editables" style="text-align:right;width:90px" readonly />
		</td>
		<td nowrap="" align="center" style="width:70px; background-color: #bbc5d4; " id="c_chkbodega" name="c_chkbodega">
			
			<input type="checkbox" name="I_checkbodega" id="I_checkbodega" value="1" onclick="ocultar()" />
			
		</td>
		<td width="90px" style="text-align:center">
			<input name="centro" id="centro" type="checkbox" onClick="comprobarCheck()" <?php if ($SYS_CENTROCOSTO != true) {
				echo "disabled"; 
			} else if ($origencompra == "T") {
				echo "checked";
			}?>>
		</td>	
		<!-- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 -->	
		<td width="90px">
			<?php
				$nom_fech                = 'I_fechamovimiento';

				$nom_fech_disabled       = "F"; //T=disabled, F=habilitado (false). "" =true(Readonly)

				$val_fech                = '';

				$btn_clear_fech          = 'fecha1_btn';

				$btn_clear_fech_disabled    = 'F'; //T=disabled y F=habilitado

				$classPrincipal    = "txt_obligatorio";

				$classAdicional    = "clase2 clase3";

				$btn_borrar_in      = true;

				$fech_style = "width:125px;height:21px;";

				$cnf_to = '';

				$cnf_hasta = '';

				include $POSICION . "componente/calendario/componente_fechadd.php";
			?>
			<div style="position:relative;top:15px; left: 50%"><?=$tool13_guia?></div>
		</td>
		<!-- FIN REQUERIMIENTO 14 -->	
		
		<td  align="center" id="boton_agregar" style="vertical-align:left;" width="10px" class="textonegro">
				<!--<button id="BTN_agregarbodega" name="BTN_agregarbodega" type="button" style="cursor:pointer" title="Muestra bodega">
					<img src="<?=$POSICION?>img/ico/bodega2.gif"  alt="" style="vertical-align:middle" />
				</button>-->
		</td>
		<td align="right">
			<button type="button" id="BTN_inserta" style="width: 110px; height: 34px;margin-bottom:4px">
				<img alt="" style="vertical-align:middle" src="<?=$POSICION?>img/ico/v2/flecha_insertar.png" />Insertar
			</button>
		</td>
		<td width="90px">
			<button type="button" id="BTN_limpia" style="width: 110px; height: 34px;margin-bottom:4px">
				<img alt="" style="vertical-align:middle" src="<?=$POSICION?>img/ico/v2/btn_volver.png" />Cancelar
			</button>		
		</td>
	</tr>
</table>
<style>
/*Movimiento Ingreso*/
#bodegatab[data-tipo-movimiento="I"] .bodega-td-stock {
    display:none;
}
#bodegatab[data-tipo-movimiento="I"] #txtFechaCaducidadDiv {
    display:block;
}
#bodegatab[data-tipo-movimiento="I"] #comboFechaCaducidad {
    display:none;
}
#bodegatab[data-tipo-movimiento="I"] #comboLote {
    display:none;
}
#bodegatab[data-tipo-movimiento="INV"] #txtLote {
    display:block;
}

/*Movimiento Salida*/
#bodegatab[data-tipo-movimiento="E"] .bodega-td-stock {
    display: table-cell;
}
#bodegatab[data-tipo-movimiento="E"] #txtFechaCaducidadDiv {
    display:none;
}
#bodegatab[data-tipo-movimiento="E"] #comboFechaCaducidad {
    display:block;
}
#bodegatab[data-tipo-movimiento="E"] #comboLote {
    display:block;
}
#bodegatab[data-tipo-movimiento="E"] #txtLote {
    display:none;
}

/*Movimiento Inventario*/
#bodegatab[data-tipo-movimiento="INV"] .bodega-td-stock {
    display: table-cell;
}
#bodegatab[data-tipo-movimiento="INV"] #txtFechaCaducidadDiv {
    display:none;
}
#bodegatab[data-tipo-movimiento="INV"] #comboFechaCaducidad {
    display:block;
}
#bodegatab[data-tipo-movimiento="INV"] #comboLote {
    display:block;
}
#bodegatab[data-tipo-movimiento="INV"] #txtLote {
    display:none;
}
</style>
<table class="FORMULARIONECSS" data-tipo-movimiento="I" id="bodegatab" style="margin-top: -3px; border-color: black; border-style: solid; border-width:1px; width: 98%; background-color: #bbc5d4;">
	
	<tr class="textonegro">
    	<td>
			<div style="position:relative">
				<div id="background_tool4">
				</div>
			</div>
			<div id="td_bodega">
			Bodega
			</div>
		</td>
    	<td>Descripci&oacute;n</td>
    	<td>Sucursal</td>
    	<td>Fecha de Caducidad</td>
	    <td>Lote</td>
	    <td class="bodega-td-stock" >Stock</td>
		<td id='td_nserie_label'>N° de Serie</td>
    	<td></td>
	</tr>
	
	<tr>
		<td width="138">
            <input type="hidden" id="codigobodegadefecto" value="<?=$idbodegadefault?>">
			<input type="hidden" id="descripcionbodegadefecto" value="<?=$descripcionbodegadefault?>">
			
			<input type="input" onkeyup="buscarbodega(event);" name="I_codigobodega" id="I_codigobodega" class="txt_obligatorio" style="width:85px;" />
				<button style="width: 20px; height:20px;" name="agregarbodega" id="agregarbodega" type="button" style="cursor:pointer" title="Muestra bodega" onclick="javascript:Ajuste.BuscadorBodega();" class = "icono">
					<img src="<?=$POSICION?>img/ico/icono-lupa.png"  alt="" style="vertical-align:middle" />
				</button>
			<?php if($isejecutartutorial === true){?>
			<div style="position:relative;left: 13%;top: 5px;"><?=$tool4_guia?></div>
			<?php } ?>
		</td>
		<td width="250">	
		<input style="width: 250px" type="input" name="I_descripcionbodega" id="I_descripcionbodega" class="txt_no_editables" readonly />
		</td>	
		<td width="120">
			<input style="width: 120px" type="input" name="I_sucursalbodega" id="I_sucursalbodega" class="txt_no_editables" readonly />
		</td>	
		<td class="textonegro" width="120" nowrap>
			<div id="txtFechaCaducidadDiv" readonly>
					<?php
					$btn_borrar_in           = true;
					$nom_fech                = 'txtFechaCaducidad';
					$btn_eve_fech            = 'fechaa_btn1';
					$btn_clear_fech          = 'fechaa_btn2';
					$fech_style              = "width:120px;";
					$nom_fech_disabled       = "T";
					$btn_clear_fech_disabled = "F";
					include $POSICION."componente/calendario/componente_fechadd.php";
					?>
			</div>
			<select style="width: 120px;" name="comboFechaCaducidad" id="comboFechaCaducidad" class="txt_no_editables" readonly onchange="setLoteStock(this, '');" disabled>
			</select>
		</td>
		<td width="120">
			<input style="width: 120px;" type="text" name="txtLote" id="txtLote" maxlength="20" class="txt_no_editables" readonly />
	       <select style="width: 120px;" name="comboLote" id="comboLote" class="txt_no_editables"></select>
		</td>
		<td class="bodega-td-stock" width="120">
			<input type="text" style="width: 120px;" id="stockLoteFechaInput" class="txt_no_editables" readonly>
		</td>
		<td width="68" style="text-align: center;"  id='td_nserie_btn'>
			<img id="btnNserie" src="<?=$POSICION?>/img/ico/edit_add_gris.png">
		</td>
		<td></td>
	</tr>
</table>
<?php if ($SYS_CENTROCOSTO == true) { ?>
	<div style="background:#BBC5D4; border: 1px solid black; margin: 4px 0 10px; width: 97.8%; display: none" id="ctrcto">
		<table class="FORMULARIONECSS" border="0" cellpadding="1" cellspacing="0">
			<tr>
				<td>
					<label>Centro de Costo</label>
				</td>
				<td>
					<label>Descripci&oacute;n</label>
				</td>
				<td>
					<label>Categor&iacute;a</label>
				</td>
				<td>
					<label>&Aacute;rea</label>
				</td>
				<td>
					<label>Monto</label>
				</td>
			</tr>

			<tr>
				<td width="138" style="padding:3px;padding-right:3px" nowrap>
					<input name="idtemporal_cc" id="idtemporal_cc" type="hidden">
					<input name="fecha_inicio_cc" id="fecha_inicio_cc" type="hidden">
					<input name="fecha_termino_cc" id="fecha_termino_cc" type="hidden">
					<input name="c_codigo" id="c_codigo" type="text" style="width: 85px;" class="txt_obligatorio">
				</td>
				<td style="" nowrap>
					<input name="c_descripcion" id="c_descripcion" type="text" style="width: 250px" class="txt_no_editables" disabled >
				</td>
				<td style="" nowrap>
					<input name="c_categoria" id="c_categoria" type="text" style="width: 120px" class="txt_no_editables" disabled >
				</td>
				<td  style="" nowrap>
					<input name="c_area" id="c_area" type="text" style="width: 120px" class="txt_no_editables" disabled >
				</td>
				<td nowrap>
					<input name="c_monto" id="c_monto" type="text" style="width: 120px" class="txt_no_editables" disabled  >
				</td>
			</tr>
		</table>
	</div>
<?php }?>

<table class="FORMULARIONECSS" id="bodegatab_origen" style="margin-top: -3px; border-color: black; border-style: solid; border-width:1px; width: 98%; background-color: #bbc5d4; display:none">
	<tr class="textonegro">
		<td>Bodega Origen</td>
		<td>Descripci&oacute;n</td>
		<td>Sucursal</td>
		<td>Fecha de Caducidad</td>
		<td>Lote</td>
		<td>Stock</td>
		<td></td>
	</tr>
	<tr>
		<td width="138">
			<input type="input" onkeyup="buscarbodega(event);" name="I_codigobodega_o" id="I_codigobodega_o" class="txt_obligatorio" style="width:85px;" />
				<button class="icono" style="width: 20px; height:20px;" name="agregarbodega" id="agregarbodega" type="button" style="cursor:pointer" title="Muestra bodega" onclick="javascript:Ajuste.BuscadorBodega('O');">
					<img src="<?=$POSICION?>img/ico/icono-lupa.png"  alt="" style="vertical-align:middle" />
				</button>
		</td>
		<td width="250">	
		<input style="width: 250px" type="input" name="I_descripcionbodega_o" id="I_descripcionbodega_o" class="txt_no_editables" readonly />
		</td>	
		<td width="120">
			<input style="width: 120px" type="input" name="I_sucursalbodega_o" id="I_sucursalbodega_o" class="txt_no_editables" readonly />
		</td>	
		<td class="textonegro" width="120">
			<div>
				<select id="comboFechaCaducidadOrigen" style="width:120px;" class="txt_no_editables" onchange="setLoteStock(this, 'T');"></select>
			</div>
		</td>
		<td width="120">
			<select style="width: 120px" id="comboLoteOrigen" class="txt_no_editables" readonly></select>
		</td>
		<td>
			<input style="width: 120px" type="text" id="stockLoteFechaOrigen" class="txt_no_editables" readonly>
		</td>
		<td></td>
	</tr>
</table>
<div id="mensajealerta" class="textorojo" style="display:none;margin: 10px 0px 10px 0px;">
	Aviso:<br>
	El producto seleccionado no posee inventario suficiente en la bodega seleccionada
</div>
<table class="FORMULARIONECSS" id="bodegatab_destino" style="margin-top: -3px; border-color: black; border-style: solid; border-width:1px; width: 98%; background-color: #bbc5d4; display:none">
	<tr class="textonegro">
		<td>Bodega Destino</td>
		<td>Descripci&oacute;n</td>
		<td>Sucursal</td>
		<td>Fecha de Caducidad</td>
		<td>Lote</td>
		<td></td>
	</tr>
	
	<tr>
		<td width="138">
			<input type="input" onkeyup="buscarbodega(event);" name="I_codigobodega_d" id="I_codigobodega_d" class="txt_obligatorio" style="width:85px;" />
				<button class="icono" style="width: 20px; height:20px;" name="agregarbodega" id="agregarbodega" type="button" style="cursor:pointer" title="Muestra bodega" onclick="javascript:Ajuste.BuscadorBodega('D');">
					<img src="<?=$POSICION?>img/ico/icono-lupa.png"  alt="" style="vertical-align:middle" />
				</button>
		</td>
		<td width="250">	
		<input style="width: 250px" type="input" name="I_descripcionbodega_d" id="I_descripcionbodega_d" class="txt_no_editables" readonly />
		</td>	
		<td width="120">
			<input style="width: 120px" type="input" name="I_sucursalbodega_d" id="I_sucursalbodega_d" class="txt_no_editables" readonly />
		</td>	
		<td class="textonegro" width="120">
			<input style="width: 120px" type="text" id="txtFechaCaducidadDestino" class="txt_no_editables" readonly>
		</td>
		<td>
			<input style="width: 120px;" type="text" name="txtLote" id="txtLoteDestino" class="txt_no_editables" readonly />
		</td>
		<td></td>
	</tr>
</table>
<div id="GRD_ajuste"></div>
<div onclick="guardarEstadoFrame()">
<script type="text/javascript">
	var iFramereferencia = new MyIFrame('iFramereferencia', 'REFERENCIAR DOCUMENTO', '', '<?= $POSICION ?>img/<?= $CSS_STYLE ?>/rows/');
    var params = "";
	<?php if ($origencompra == "T") { ?>
		params = '?folio=<?=$folio?>&idtipodocumento=<?=$idtipodocumento?>';
	<?php } ?>
	iFramereferencia.src = 'referenciaguiaajuste.php' + params;
    iFramereferencia.height = 50;
    iFramereferencia.show();
    iFramereferencia.contract();	
	function guardarEstadoFrame(){
		let estadoFrame = iFramereferencia.status == 'expanded' ? 't' : 'f';
		if(estadoFrame == 'f'){
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS2 = BD.send("cmd=guardarConfigFrame");
		}
	}
	<?php if ($_SESSION["ss_FRAME_REFERENCIAR_DOCUMENTOS_GUIAAJUSTE"] == 't'){ ?>
		iFramereferencia.show();
	<?php } ?>
</script>
</div>
<br>
<table id="Buscador" summary="" class="textonegro" border="0" style="width:100%;">
	<tbody>
		<tr>
			<td align="right">
				<?php if($isejecutartutorial === true){?>
				<div style="position: relative">
					<div id='background_tool11'>
					</div>
				</div>
				<div style="position:relative;float:right;right: 480px;bottom: 215px"><?=$tool11_guia?></div>
				<?php } ?>
				<button type="button" id="BTN_guarda"  disabled="true" class="textonegro" style="font-size:9px; font-weight:bold; word-break: break-all; width:160px;height:60px;margin-right:15px">
					<img alt="" style="vertical-align:middle" src="<?=$POSICION?>img/ico/v2/btn_guardar.png" />
					Guardar Documento
				</button>
			</td>
		</tr>
	</tbody>
</table>
<div id="GRD_producto"></div>


<script type="text/javascript" src="<?=$POSICION?>componente/messagebox/class.php?v=1"></script>
   
<?php
if (in_array($_SESSION['ss_TIPO_MOVIMIENTO_GUIAAJUSTE'],array('E','T','INV'))) {
?>
<script>
try {
    document.getElementById("tipomovimiento").value = "<?=$_SESSION['ss_TIPO_MOVIMIENTO_GUIAAJUSTE']?>"
    window.addEventListener("load", function (){
        try {
            activabodegas(document.getElementById("tipomovimiento").value, false);
            Event.observe(document.getElementById("tipomovimiento"),"change",function(event){
				var title ="";
				if(document.getElementById("tipomovimiento").value == "INV"){
					title = "Ingresar nuevas cantidades disponibles por bodega";
				}
				$("I_cantidad").title = title;
			});
			if(document.getElementById("tipomovimiento").value == "INV"){
				title = "Ingresar nuevas cantidades disponibles por bodega";
				$("I_cantidad").title = title;
			}
        } catch (ex) {}    
    });
} catch (ex) {}
</script>
<?php      
}
 ?>   

<script type="text/javascript">
var cadenaNSerie = [];
var cantidadInicial = "";
<?php if($isejecutartutorial === true){?>
function Omitir() {
	event.preventDefault();
	var BD 			= new BASE();
	BD.commandFile 	= "form/existencias/command.php";
	var RS = BD.send ("&cmd=ingresoGuia");
	$('tool1').style.display = "none";
	/*
	$('tool2').childNodes[2].style.display = "none";
	$('tool3').childNodes[2].style.display = "none";
	$('tool4').childNodes[2].style.display = "none";
	$('tool5').childNodes[2].style.display = "none";
	$('tool6').childNodes[2].style.display = "none";
	$('tool7').childNodes[2].style.display = "none";
	$('tool8').childNodes[2].style.display = "none";
	$('tool9').childNodes[2].style.display = "none";
	$('tool10').childNodes[2].style.display = "none";
	$('tool11').childNodes[2].style.display = "none";
	$('tool12').childNodes[2].style.display = "none";
	*/
	$('tool2').style.display = "none";
	$('tool3').style.display = "none";
	$('tool4').style.display = "none";
	$('tool5').style.display = "none";
	$('tool6').style.display = "none";
	$('tool7').style.display = "none";
	$('tool8').style.display = "none";
	$('tool9').style.display = "none";
	$('tool10').style.display = "none";
	$('tool11').style.display = "none";
	$('tool12').style.display = "none";
	document.getElementById('tipomovimiento').value = 'I';
	activabodegas(document.getElementById("tipomovimiento").value, false);
	mostrarOcultarBackground('eliminarEstilos');
	$('overlay2').style.display ="none";
}
function SiguientVolver(actual,siguiente,unavez) {
	//event.preventDefault();
	var elemento = $(actual);
	var select_movimiento = document.getElementById('tipomovimiento');
	switch (siguiente) {
		case 'tool1':
			mostrarOcultarBackground('tool2', false);
			break;
		case 'tool2':
			if(actual == 'tool3'){
				mostrarOcultarBackground('tool3', false);
			}
			mostrarOcultarBackground('tool2', true);
			break;
		case 'tool3':
			if(actual == 'tool2'){
				mostrarOcultarBackground('tool2', false);
			}else if(actual == 'tool4'){
				mostrarOcultarBackground('tool4', false);
			}
			mostrarOcultarBackground('tool3', true);
			break;
		case 'tool4':
			if(actual == 'tool3'){
				mostrarOcultarBackground('tool3', false);
			}else if('tool5'){
				mostrarOcultarBackground('tool5', false);
			}
			mostrarOcultarBackground('tool4', true);
			break;
		case 'tool5':
			select_movimiento.value = 'I';
			activabodegas(document.getElementById("tipomovimiento").value, false);
			if(actual == 'tool4'){
				mostrarOcultarBackground('tool4', false);
			}
			mostrarOcultarBackground('tool5', true);
			break;
		case 'tool6':
			select_movimiento.value = 'E';
			//CAMBIO RC: Se agrega para controlar que se muestren bodegas correctamente, incluyendo la de traspaso que sale en imagenes referenciales
			activabodegas('E', true);
			break;
		case 'tool7':
			//CAMBIO RC: Se agrega para controlar que se muestren bodegas correctamente, incluyendo la de traspaso que sale en imagenes referenciales
			activabodegas('T', true);
			select_movimiento.value = 'T';
			break;
		case 'tool8':
			//CAMBIO RC: Se agrega para controlar que se muestren bodegas correctamente, incluyendo la de traspaso que sale en imagenes referenciales
			activabodegas('INV', true);
			select_movimiento.value = 'INV';
			if(actual == "tool9"){
				mostrarOcultarBackground('tool9', false);
				mostrarOcultarBackground('tool5', true);
			}
			break;
		case 'tool9':
			select_movimiento.value = 'I';
			activabodegas(document.getElementById("tipomovimiento").value, false);
			if(actual == 'tool8'){
				mostrarOcultarBackground('tool5', false);
			}else if('tool10'){
				mostrarOcultarBackground('tool10', false);
			}
			mostrarOcultarBackground('tool9', true);
			break;
		case 'tool10':
			if(actual == 'tool9'){
				mostrarOcultarBackground('tool9', false);
			}else if('tool11'){
				mostrarOcultarBackground('tool11', false);
			}
			mostrarOcultarBackground('tool10', true);
			break;
		case 'tool11':
			if(actual == 'tool10'){
				mostrarOcultarBackground('tool10', false);
			}else if('tool12'){
				mostrarOcultarBackground('tool12', false);
			}
			mostrarOcultarBackground('tool11', true);
			break;
		case 'tool12':
			if(actual == 'tool11'){
				mostrarOcultarBackground('tool11', false);
			}
			mostrarOcultarBackground('tool12', true);
			break;
		default:

			break;
	}
	nextTooltip(elemento,siguiente,unavez);
}
function CerrarToolt() {
	//event.preventDefault();
	var BD 			= new BASE();
	BD.commandFile 	= "form/existencias/command.php";
	var RS 			= BD.send ("&cmd=ingresoGuia");
	//document.getElementById('boton-ayuda').classList.remove('bloqueado');
	//document.getElementById('boton-ayuda').setAttribute('onClick', onclickBtnAyuda);
	//document.getElementsByName('formulario')[0].childNodes[1].style.position = 'static';
	//document.getElementsByName('formulario')[0].childNodes[1].style.zIndex = '0';
	//CAMBIO RC: en vez de repetir todo esto, se llama a la funcion que creo para mostrar y ocultar
	mostrarOcultarBackground('tool12', false);
	document.getElementById('tipomovimiento').value = 'I';
	activabodegas(document.getElementById("tipomovimiento").value, false);
	$('tool12').style.display = "none";
	$('overlay2').style.display ="none";
}
<?php } ?>

var primerInsertarInventario = false;
	var fechaValidada = "false";
	function fechanovalidada() {
		fechaValidada = "false";
	}
	var MSGBOX = {
		ventana 	: typeof IU_MSGBOX,
		initialize 	: function() {
			this.ventana = new IU_MSGBOX ();
		},
		Abrir : function(msj){
            this.ventana.botonera         = arguments[3] || 1;
			this.ventana.url = '<?=$POSICION?>';
            this.ventana.titulo = "Error";
			this.ventana.ret_btn1		= "ACEPTAR";
			this.ventana.fnretorno		= "MSGBOX.ventana.HideInfo();";
            this.ventana.texto = "<center>" + msj + "</center>";

			if(arguments.length > 1){
					this.ventana.titulo 	= arguments[1];	
			}	
			if(arguments.length > 2){
					this.ventana.fnretorno	= arguments[2];
			}	
			this.ventana.Messagebox ();
		}
        ,AbrirParametrisado : function(msj, conf) {
            this.ventana.botonera  = conf.botonera || 1;
            this.ventana.url       = '<?=$POSICION?>';
            this.ventana.titulo    = conf.titulo || "Error";
            this.ventana.ret_btn1  = "ACEPTAR";
            this.ventana.fnretorno = conf.fnretorno || "MSGBOX.ventana.HideInfo();";
            this.ventana.texto     = "<center>" + msj + "</center>";
			this.ventana.objeto 	= conf.objeto || {};
			this.ventana.conCerrar 	= conf.conCerrar || false;
            this.ventana.zzIndex    = 10000;
			this.ventana.alto 	= conf.alto || 210;
            this.ventana.Messagebox ();
        }
	}
	MSGBOX.initialize();

    function MSGBOXfocusElemento(res) {
        try {
            MSGBOX.ventana.objeto.elementofocus.focus();
            MSGBOX.ventana.objeto = null;
        } catch (ex) {}
        MSGBOX.ventana.HideInfo();
    }
    
    function MSGBOXConfirmarGuardarGuia(res) {
        MSGBOX.ventana.HideInfo();
        try {
            Ajuste.variableConfirmarGuardar = true;
            if (res == 'S') {
                Ajuste.variableConfirmarGuardar = false;
                Ajuste.Guardar();
            }
        } catch (ex) {}
    }

    function MSGBOXConfirmarModificar(res) {
        MSGBOX.ventana.HideInfo();
        try {
            Ajuste.variableConfirmarModificarLinea = true;
            if (res == 'S') {
                
                Ajuste.variableConfirmarModificarLinea = false;
                Ajuste.addDetalle();
            }
        } catch (ex) {}
    }

	function accion_val_precio(){

		MSGBOX.ventana.HideInfo();
		$("I_preciounitario").focus();
		
	}

	var modbodega =  0;		//Por defecto sin bodega
	var idocguia	= $('idocguia').value;
	if (idocguia >0){
		$('observacion').value = 'Generada autom\u00E1ticamente desde Orden de Compras Nro ' + idocguia;
	}
	
	function ocultar(){
		if($('I_checkbodega').checked){//muestra
			$('bodegatab').show();
		} else {//oculta
			$('bodegatab').hide();
		}		
		
		checkCantidad();	
	}
	
	function buscarbodega(e){
		tecla = (document.all) ? e.keyCode : e.which;
		if ((tecla==13) && (e.target.value != '')){
			if (e.target.id == 'I_codigobodega'){
				Bodega.Paso_bodega(e.target.value, '');
			}else if (e.target.id == 'I_codigobodega_o'){
				Bodega.Paso_bodega(e.target.value, 'O');
			}else if (e.target.id == 'I_codigobodega_d'){
				Bodega.Paso_bodega(e.target.value, 'D');
			}
		} 
	}
	
	var Bodega = {
		initialize : function(){
			
		},
		Paso_bodega : function(id, tbodega,pordefecto){

			
			var connection = new Ajax.Request("databodega.php" , {
			method: 'post',
			postBody : "&idbod="+id,
			encoding   : "ISO-8859-1",
			asynchronous  : true,
			onSuccess : function(transport){
				var request = transport.responseText;
				if(request.indexOf ("ERROR")!= -1){
					if(!pordefecto){
						MSGBOX.AbrirParametrisado("La bodega no existe.", {
							titulo: "Gu&iacute;a de Ajuste"
						});
					}
					$('I_codigobodega').value 		= '';
					$('I_descripcionbodega').value 	= '';
					$('I_sucursalbodega').value 	= '';
					checkCantidad();
					Ajuste.ventana.HideInfo();
					try{
            	        modificarCamposFechaLote();
            	    } catch (ex){}
					return;
						
				}else if(request.indexOf ("NOVIG")!= -1){
						
					alert('La bodega no se encuentra vigente');
					$('I_codigobodega').value 		= '';
					$('I_descripcionbodega').value 	= '';
					$('I_sucursalbodega').value 	= '';
					checkCantidad();
					Ajuste.ventana.HideInfo();
					try{
            	        modificarCamposFechaLote();
            	    } catch (ex){}
					return;
					
				}else{
					spliter = request.split(':');
					if (tbodega == "" || tbodega == undefined){
						$('I_codigobodega').value = id;
						$('I_descripcionbodega').value 	= spliter[0];
						$('I_sucursalbodega').value 	= spliter[1];
                		try{
                	        modificarCamposFechaLote();
                	    } catch (ex){}
					}else if (tbodega == "O" || tbodega == "D"){
						if (tbodega == "O"){
							$('I_codigobodega_o').value = id;
							$('I_descripcionbodega_o').value 	= spliter[0];
							$('I_sucursalbodega_o').value 	= spliter[1];
							if ($('I_codigobodega_o').value == $('I_codigobodega_d').value){
								//alert ("Las bodegas origen y destino no pueden ser iguales. Verifique e intente nuevamente");
								MSGBOX.AbrirParametrisado("Las bodegas origen y destino no pueden ser iguales.", {
									titulo: "Gu&iacute;a de Ajuste",
									fnretorno: "MSGBOXfocusElemento",
									objeto: {"elementofocus": $('I_codigobodega_o')}
								} );
								$('I_codigobodega_o').value 		= '';
								$('I_descripcionbodega_o').value 	= '';
								$('I_sucursalbodega_o').value 	= '';
                        		try{
                        	        modificarCamposFechaLote();
                        	    } catch (ex){}
								return;
							}
							$('I_codigobodega_d').focus();
                    		try{
                    	        modificarCamposFechaLote();
                    	    } catch (ex){}
						}else{
							$('I_codigobodega_d').value = id;
							$('I_descripcionbodega_d').value 	= spliter[0];
							$('I_sucursalbodega_d').value 	= spliter[1];
							if ($('I_codigobodega_o').value == $('I_codigobodega_d').value){
								//alert ("Las bodegas origen y destino no pueden ser iguales. Verifique e intente nuevamente");
								MSGBOX.AbrirParametrisado("Las bodegas origen y destino no pueden ser iguales.", {
									titulo: "Gu&iacute;a de Ajuste",
									fnretorno: "MSGBOXfocusElemento",
									objeto: {"elementofocus": $('I_codigobodega_d')}
								} );
								$('I_codigobodega_d').value 		= '';
								$('I_descripcionbodega_d').value 	= '';
								$('I_sucursalbodega_d').value 	= '';
								try{
                        	        setLoteStock($('comboFechaCaducidadOrigen'), "T")
                        	    } catch (ex){}
								return;
							}
                    		try{
                    	        setLoteStock($('comboFechaCaducidadOrigen'), "T");
                    	    } catch (ex){}
							$('BTN_inserta').focus();
						}
					}
					
					checkCantidad();
					Ajuste.ventana.HideInfo();
				}
			},
			onFailure : function(){
				alert("ERROR: Error no controlado por la aplicaci\u00f3n.")
			}
			});
				
			
		}
		
	}
	
	
	var Producto = {

		grid : typeof AW.UI.Grid,
		
		initialize : function(){
			var inputs = $("Buscador").getElementsByTagName("input");
			for(var i=0;i<inputs.length;i++){
				if(inputs[i].type == "text"){
					Event.observe(inputs[i],"keypress",function(event){
						if(event.keyCode == Event.KEY_RETURN){
							Producto.Buscar();
						}
					});
				}
			}
		},
			
//************************************************************************************************************************************************************
		
		Cargar : function(arg){
			var connection = new Ajax.Request("server.php" , {
				method			: 'post',
				parameters 		: "&cmd=getListProducto"+arg,
				encoding   		: "ISO-8859-1",
				asynchronous  	: true,
			
//************************************************************************************************************************************************************
				
				onSuccess : function(transport){
					Producto.setTextGrid(new Array());
					var request = transport.responseText;
					if(request.isJSON()){
						var reg = request.evalJSON();
						Producto.setTextGrid(reg);
					}else{
						alert(request);
					}
				},
			
//************************************************************************************************************************************************************
				
				onFailure : function(){
					alert("ERROR: Error no controlado por la aplicaci\u00f3n [Producto].")
				}
			});
		},
			
//************************************************************************************************************************************************************
		
		setTextGrid : function(DataSource){
			this.grid.setScrollProperty ("top", 0);
			this.grid.setCellText(DataSource);
			this.grid.setRowCount(DataSource.length);
			this.grid.refresh();
		},
			
//************************************************************************************************************************************************************
		
		Buscar : function(){
			var fields = $$(".txt_editables");
			var params = new String();
			for(var i=0;i<fields.length;i++){
				params += "&" + fields[i].name + "=" + fields[i].value;
			}
			this.Cargar(params);
		},
			
//************************************************************************************************************************************************************
		
		Seleccionar : function(fila){
			$("I_codigo").value 		= this.grid.getCellText(0, fila);
			$("I_descripcion").value 	= this.grid.getCellText(1, fila);
			$("I_cantidad").value 		= "0";
			$("I_preciounitario").value = this.grid.getCellText(4, fila);
			$("I_montoitem").value 		= "0";
			$("I_idproducto").value 	= this.grid.getCellText(6, fila);
		}
	}

	var Ajuste = {
		grid 		: typeof AW.UI.Grid,
		data 		: typeof Array,
		ventana 	: typeof IU,
		inxt		: typeof numeric,
		colinx		: typeof numeric,
        isInventario : typeof numeric,

		idProducto		: typeof numeric
		,codproducto	: typeof string
		,descripcion	: typeof string
		,pmp			: typeof numeric
		,preciocostounitario : typeof numeric,
		initialize 	: function(){
			this.inxt = 0;
			this.colinx = 0;
			
			Event.observe($("I_cantidad"),"keypress",function(event){
				if (event.keyCode == Event.KEY_RETURN){
					$("I_preciounitario").focus();
					$("I_preciounitario").select();
				}
			});
			Event.observe($("I_preciounitario"),"keypress",function(event){
				if (event.keyCode == Event.KEY_RETURN){
					$("BTN_inserta").focus();
				}
			});
			Event.observe($("BTN_inserta"),"click",function(event){
				<?php if ($SYS_CENTROCOSTO == true) { ?>
					if (($('tipomovimiento').value == 'E'
					|| $('tipomovimiento').value == 'I')
					&& $("centro").checked == true
					){
						var fecha 	= $('fecha').value;
						var idcc	= $('idtemporal_cc').value;

						var BDexistencia1 			= new BASE();
						BDexistencia1.commandFile 	= "form/existencias/command.php";
						var RS 			= BDexistencia1.send("&cmd=validarFechaCCDTE&idcc="+idcc+"&fecha="+fecha);
						
						if (RS.error == "") {
							var resultado	= BDexistencia1.getField(RS, "resultado", 0);
							if (resultado == "MAYOR") {
								KEYBOX_CC.Abrir({
									texto: "La fecha de ingreso del movimiento es superior a la fecha de término del Centro de Costo.<br><br>Para ajustar el ingreso debe ingresar su clave de aprobación"
								});
								return;
							} else if (resultado == "MENOR") {
								KEYBOX_CC.Abrir({
									texto: "La fecha de ingreso del movimiento es inferior a la fecha de inicio del Centro de Costo.<br><br>Para ajustar el ingreso debe ingresar su clave de aprobación"
								});
								return;
							}
						} else {
							alert(RS.error);
						}	
					}
				<?php } ?>
	
				if(modbodega==1){
					//Modulo de Bodegas Activo
					/*if ($('tipomovimiento').value == 'T'){
						Bodega.Paso_bodega($('I_codigobodega_o').value);
						Bodega.Paso_bodega($('I_codigobodega_d').value);
					}else{
						Bodega.Paso_bodega($('I_codigobodega').value);
					}*/
				
					if ($('tipomovimiento').value != 'T'){
						if($('I_codigobodega').value != ""){
							Ajuste.addDetalle();
						}
					}else{
						if($('I_codigobodega_o').value != "" && $('I_codigobodega_d').value != ""){
							Ajuste.addDetalleT();
						}
					}
				} else {
					//Solo Tarjeta (Sin Bodega)
					Ajuste.addDetalle();
				}
				
			});
			Event.observe($("BTN_limpia"),"click",function(event){
				Ajuste.LimpiaCampos();
			});
			Event.observe($("I_codigo"),"keypress",function(event){
			 
				if ($("I_idproducto").value != "" && event.keyCode != Event.KEY_TAB){
					
					var r 				= $("I_codigo").value;
					Ajuste.LimpiaCampos();
					$("I_codigo").value = r;
				}
				if (event.keyCode == Event.KEY_RETURN){
					var element = Event.element(event);
					Ajuste.VerProducto($("I_codigo").value);
				
				}
			});
			
			Event.observe($("I_cantidad"),			"keyup",	function(event){Ajuste.Calcular();});
			Event.observe($("I_preciounitario"),	"keyup",	function(event){Ajuste.Calcular();});
			Event.observe($("I_cantidad"),			"focus",	function(event){Ajuste.valIn($("I_cantidad"));});
			Event.observe($("I_cantidad"),			"blur",		function(event){Ajuste.valNull($("I_cantidad"));});
			Event.observe($("I_preciounitario"),	"focus",	function(event){Ajuste.valIn($("I_preciounitario"));});
			Event.observe($("I_preciounitario"),	"blur",		function(event){Ajuste.valNull($("I_preciounitario"));});
			Event.observe($("BTN_busca"),			"click",	function(event){Ajuste.Buscador();});
			Event.observe($("BTN_nuevo"),			"click",	function(event){Ajuste.Nuevo();});
            Event.observe($("BTN_guarda"),            "click",    function(event){
				<?php if ($accionajuste != 0 && $SYS_Bodega == TRUE) { ?>
					Ajuste.verificarExistencias();
				<?php } else {?>
					Ajuste.confirmarGuardar();
				<?php } ?>
			});
			this.grid 		= new AW.UI.Grid();
			this.data 		= new Array ();
			this.ventana 	= new IU ();
			this.grid.setId("Ajuste");	//Identificador de Estilo CSS
	
			//Definicion de columnas
			this.grid.setHeaderText("C\u00f3digo",		0);
			this.grid.setHeaderText("Descripci\u00f3n",	1);
			this.grid.setHeaderText("Fecha de Caducidad",	16+tieneCC);
			this.grid.setHeaderText("Lote",	17+tieneCC);
			this.grid.setHeaderText("Tipo Movimiento",	2);
			
			this.grid.setHeaderText("Cantidad",			3);
			this.grid.setHeaderText("Precio Costo Unitario",	4);
			this.grid.setHeaderText("Monto Item",		5);
			this.grid.setHeaderText("Bodega",			6);
			if (tieneCC == 1) {
				this.grid.setHeaderText("Centro costo",			7);
				this.grid.setHeaderText("idcc",	12); // NO VISIBLE. id CC
				this.grid.setHeaderText("montoCC",	13); // NO VISIBLE. Monto CC
			}
			this.grid.setHeaderText("Acciones",					7 +tieneCC);
			this.grid.setHeaderText("idproducto",		8+tieneCC); // NO VISIBLE.
			this.grid.setHeaderText("ORIGEN",					9+tieneCC); // NO VISIBLE.  BODEGA ORIGEN
			this.grid.setHeaderText("DESTINO",					10+tieneCC); // NO VISIBLE. BODEGA DESTINO
			this.grid.setHeaderText("asignarlote",18+tieneCC);
			this.grid.setHeaderText("checkperecible",19+tieneCC); // NO VISIBLE. flag asignarlote
			//this.grid.setColumnCount(8+tieneCC);
			// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --	
            this.grid.setHeaderText("Fecha de Movimiento",21);
			this.grid.setColumnWidth(130, 21); 
			// -- FIN REQUERIMIENTO 14 --	
			if (tieneCC == 1) {
				this.grid.setColumnWidth(200, 1);	//Descripcion
				this.grid.setColumnWidth(140, 4);	//Precio Costo Unitario
				this.grid.setColumnWidth(120, 6);	//Bodega
				this.grid.setColumnWidth(120, 7);	//centro costo
			} else {
			this.grid.setColumnWidth(150, 6);	//Bodega
			}

            this.grid.setColumnWidth(120, 16+tieneCC);	//centro costo
            
            
			// Marca la fila seleccionada.
			this.grid.setSelectionMode("single-row");
			
			//Define Template para columna de eliminacion de fila.
			this.grid.setCellTemplate(new AW.Templates.Link, 7+tieneCC);
			
			this.grid.setCellLink (function(col, row) {
				if (col == 7+tieneCC && Ajuste.grid.getCellText(7+tieneCC, row) == "<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrar.gif'>") {
					var idproducto = Ajuste.grid.getCellText(8+tieneCC, row);
					return "javascript:Ajuste.delDetalle("+row+", " + idproducto + ", true)";
				
				}else if (col == 7+tieneCC && Ajuste.grid.getCellText(7+tieneCC, row) == "<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrarT.gif'>"){
					var indexd = Ajuste.grid.getCellText(8+tieneCC, row);

					return "javascript:Ajuste.delDetalleT("+ indexd + ", true)";
				}				 						
			});
			if(tieneCC == 1) {
				// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
			    this.grid.setColumnIndices([0,1,17,18,2,3,4,5,6,7,8,21]);
				// -- FIN REQUERIMIENTO 14 --
				
			} else {
				// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
			    this.grid.setColumnIndices([0,1,16,17,2,3,4,5,6,7,21]);
				// -- FIN REQUERIMIENTO 14 --
		    }
		
			//Anula el ordenamiento de Columnas
			this.grid.onHeaderClicked = function(event, column){return true;};
			
			// Mostramos la columna con los numeros de fila
			this.grid.setSelectorVisible(true);			
			this.grid.setSelectorText(function(r){return this.getRowPosition(r)+1});
			this.grid.setColumnResizable(true); 
			
			//this.grid.setCellEditable(true, 2);
			this.grid.setRowCount(0);
			
			// Evento cuando se marca selecciona fila.
			//this.grid.onSelectedRowsChanged = function(column, row){Ajuste.Modificar(column, row);}
			this.grid.onCellClicked = function(event, column, row){
			    
			    if (event.target.getAttribute("data-eliminar") != "true") {
			        window.event.preventDefault(); 
			    } else {
			        return; 
			    }
			 
				movimientoeditar = Ajuste.grid.getCellText(2, row);
				filagrid = row;
				esModificacion = 1; 
				Ajuste.Modificar(column, row);
			}
			
			//Cargamos la grilla en la interfaz
			$("GRD_ajuste").innerHTML = this.grid;
		},
			
//************************************************************************************************************************************************************
		
		Buscador : function(){
		  
        <?php 
        if ($ingresoDesdeLibroCompraSinXML === true) {
            ?>
				let ancho = Math.min(window.innerWidth * 0.680, 1000);
				let alto = Math.min(window.innerHeight * 0.825, 410);
				this.ventana.ShowInfo("buscador.php?mostrarnoinventariables=true&desdeguiaajuste=true", "Ajuste.ventana.HideInfo", alto, ancho);
            <?php 
        } else {
            ?>
			this.ventana.ShowInfo ("buscador.php?mostrarnoinventariables=true&desdeguiaajuste=true","Ajuste.ventana.HideInfo","420px");
            <?php 
        }
        ?>
		},
			
//************************************************************************************************************************************************************

		BuscadorBodega : function(tbodega){
			var idproducto 	= $("I_idproducto").value;
			var idbodega	= $("I_codigobodega").value;
			var tipobodega	= tbodega == undefined ? "" : tbodega;
			if(idproducto != ""){
			 
            <?php 
            if ($ingresoDesdeLibroCompraSinXML === true) {
                ?>
    				let ancho = Math.min(window.innerWidth * 0.680, 1000);
    				let alto = Math.min(window.innerHeight * 0.825, 410);
    				this.ventana.ShowInfo("agregar_bodega.php?idproducto="+idproducto+"&tbodega=" + tipobodega + "&idbodega=" + idbodega, undefined, alto, ancho);
                <?php 
            } else {
                ?>
				this.ventana.ShowInfo ("agregar_bodega.php?idproducto="+idproducto+"&tbodega=" + tipobodega + "&idbodega=" + idbodega);
                <?php 
            }
            ?>
        	}else{
				alert("Debe ingresar un C\u00f3digo Producto");
			}
		},
			
//************************************************************************************************************************************************************
		Nuevo : function(){
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=validaInsertarProducto");
			if(RS.error == ""){
				if (BD.getField (RS,"permitirinsertar",0) == "t") {
					var alto = 605;

					var ancho = 1310;
					if (window.innerWidth - 100 < ancho) {
						ancho = window.innerWidth - 160;
					}
					this.ventana.ShowInfoParametrizado({
						iframe : '<?=$POSICION?>form/maestros/producto/index.php?nuevo=si&desdeingresoproductos=true',
						alto : alto,
						ancho : ancho,
						cerrar : "Ajuste.ventana.HideInfo",
						idframe : "lightboxProducto"
					});
				}else {
					var conf = {
                        titulo: "Nuevo Producto",
						botonera:	1
                        }
					MSGBOX.AbrirParametrisado("<div style='text-align: center'>Usuario sin permisos para realizar esta acción.</div>"
						,conf);
					return false;
				}
			}else{
				var conf = {
                        titulo: "Nuevo Producto",
						botonera:	1
                        }
				MSGBOX.AbrirParametrisado("<div style='text-align: center'>"+alert(RS.error)+"</div>"
						,conf);
				;
			}
		},
				
//************************************************************************************************************************************************************
	
		Paso : function (id){
			
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=pasoProducto&id="+id);
			
			
			if(RS.error == ""){
				try{
					if (BD.getField (RS,"inventariable",0) == "f") {
						this.codproducto	= BD.getField (RS,"codproducto",0);
						this.descripcion	= BD.getField (RS,"descripcion",0);
						this.idProducto		= id;
						try{
							this.pmp = Math.round(BD.getField (RS,"pmp",0));
							this.preciocostounitario = Math.round(BD.getField (RS,"preciocostounitario",0));
						} catch(e) {
							this.pmp = -1;
						}

						if (BD.getField (RS,"permitirmodificar",0) == "t") {
							MSGBOX.AbrirParametrisado(
								"<br><br>El código de producto ingresado no está marcado como Inventariable.<br><br>¿Desea marcarlo como Inventariable para continuar?<br><br><br>"
								,{
									botonera:	3
									,fnretorno:	"msgAccionNoInventariable"
									,titulo		: "Resultados del Código de Producto"
								}
							);
						} else {
							MSGBOX.AbrirParametrisado(
								"<br><br>El código de producto ingresado no está marcado como Inventariable.<br><br><br><br>"
								,{
									botonera:	1
									,titulo		: "Resultados del Código de Producto"
								}
							);
						}
						Ajuste.ventana.HideInfo();
						return;
					} else {
					i=0;//revisar!
				
					$("I_idproducto").value 	= id;
					$("I_codigo").value 		=  BD.getField (RS,"codproducto",i);
					$("I_descripcion").value 	=  BD.getField (RS,"descripcion",i);
					$("I_cantidad").value 		= "0";
					try{
				
						$("I_preciounitario").value = BD.getField (RS,"preciocostounitario",i);
						recalcularobtenerpreciounitario($("I_preciounitario"),this.idProducto);
						$("I_preciounitario").value = Math.round($("I_preciounitario").value);
						if($("I_preciounitario").value<0){$("I_preciounitario").value="";}
					
						if (tieneCC == 1) {
							var idcentrocosto = BD.getField (RS, "idcentrocosto", 0);
							if (idcentrocosto > 0) {
								$("centro").checked = true;
								$("idtemporal_cc").value = idcentrocosto;
								$("c_codigo").value = BD.getField (RS, "codigocc", 0);
								$("c_descripcion").value = BD.getField (RS, "descripcioncc", 0);
								$("c_area").value = BD.getField (RS, "areacctexto", 0);
								$("c_categoria").value = BD.getField (RS, "categoriacctexto", 0);
								
								if ($("I_montoitem").value != '') {
									$("c_monto").value = $("I_montoitem").value;
								}
								//Se muestra el centro de costo
								$("ctrcto").style.display = "";
							}
						}
					}
					catch(em){

						$("I_preciounitario").value = "";
					}
				    $("perecible").value = BD.getField (RS,"perecible",0);
				    $("fechaCaducidadObligatoria").value = BD.getField (RS,"confechacaducidad",0);
				    $('txtFechaCaducidad').value = "";
					$("asignarlote").value = BD.getField (RS,"asignarlote",0);
					$("checkperecible").value = BD.getField (RS,"checkperecible",0);
					try {
						$('asignarserie').value = BD.getField (RS,"asignarserie",0);
						$('nroserieobligatorio').value = BD.getField (RS,"nroserieobligatorio",0);
						cadenaNSerie = [];
						cantidadInicial = "";
						validarActivarNSerie($('asignarserie').value);
					} catch(ex4){}
				    try{
				        modificarCamposFechaLote();
				    } catch (ex){}
					$("I_montoitem").value = "0";
				}
				}
				catch(e){
					
					//si no trae codigo producto es porque no es inventariable...
					alert('El producto seleccionado no es inventariable');
				}
				this.ventana.HideInfo();
			}else{
				
				alert(RS.error);
			}
		},
			
//************************************************************************************************************************************************************
		
		Modificar : function(columna,fila){
			if(fila != ""){
				if(this.grid.getCellText(7+tieneCC, fila) == "<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrar.gif'>" || this.grid.getCellText(7+tieneCC, fila) == "<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrarT.gif'>"){

					var mov 					= this.grid.getCellText(2, fila);
					$("I_codigo").value 		= this.grid.getCellText(0, fila);
					$("I_descripcion").value 	= this.grid.getCellText(1, fila);
					// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
					$("I_fechamovimiento").value= this.grid.getCellText(21, fila);
					// -- FIN REQUERIMIENTO 14 --	
				    $("tipomovimiento").selectedIndex = mov == "Entrada" ? 0 : mov == "Salida" ? 1 : mov == "Traspaso" ? 2 : 3;
                    if ( mov == "Entrada" || mov == "Salida" || mov == "Inventario") {
					
						$("bodegatab").style.display = "";
						if (tieneCC == 1) {
							if (mov == "Inventario") {
								$("ctrcto").style.display = "none";
								$("centro").disabled = true;
							} else {
								if ($("centro").checked) {
									$("ctrcto").style.display = "";
								} else {
									$("ctrcto").style.display = "none";
								}
					
								if (mov == "Entrada" || mov == "Salida") {
									$("centro").disabled = false;
								} else {
									$("centro").disabled = true;
								}
							}
						}
						$("bodegatab_origen").style.display = "none";
						$("bodegatab_destino").style.display = "none";						
					}else if (mov == "Traspaso"){
						$("bodegatab").style.display = "none";
						$("bodegatab_origen").style.display = "";
						$("bodegatab_destino").style.display = "";				
						if (tieneCC == 1) {
							$("ctrcto").style.display = "none";
							$("centro").disabled = true;
						}		
					}
					if(this.grid.getCellText(3, fila).match(/(\d+)/i))
						$("I_cantidad").value 		= this.grid.getCellText(3, fila).match(/(\d+)/i)[0];//this.grid.getCellText(3, fila);
					else
						$("I_cantidad").value 		= this.grid.getCellText(3, fila);
						$("I_preciounitario").value = this.grid.getCellText(4, fila);
						$("I_montoitem").value 		= this.grid.getCellText(5, fila);
					$("I_idproducto").value 	= this.grid.getCellText(8+tieneCC, fila);
					if (tieneCC == 1) {
						var idcc = this.grid.getCellText(12, fila); //id CC
						if (idcc > 0 && idcc != null) {
							$("c_codigo").value = idcc;
							centrocosto.codigo_c.parentClass.GetCentroPorID();
							$("ctrcto").style.display = "";
							$("centro").checked = true;
						} else {
							$("ctrcto").style.display = "none";
							$("centro").checked = false;
							$("btnBorrarcc").click();
						}
						
						$("c_monto").value  		= $("I_montoitem").value; // Monto CC
					}
				    try {
				        let fechaCaducidad = this.grid.getCellText(16+tieneCC, fila);
				        let nrolote = this.grid.getCellText(17+tieneCC, fila);
				        let habilitalote = this.grid.getCellText(18+tieneCC, fila);
				        let checkperecible = this.grid.getCellText(19+tieneCC, fila);
				        $("checkperecible").value = checkperecible;
				        $("asignarlote").value = habilitalote;
				        $("perecible").value = checkperecible;
						$("asignarserie").value = this.grid.getCellText(21+tieneCC, fila);
						$("nroserieobligatorio").value = this.grid.getCellText(22+tieneCC, fila);

						if(mov == "Entrada" || mov == "Inventario"){
							validarActivarNSerie($('asignarserie').value);
							if($('asignarserie').value == 't'){
								cadenaNSerie = this.grid.getCellText(20+tieneCC, fila).split(";");
								if(cadenaNSerie.length == 1 && $("I_cantidad").value == 0){
									if(cadenaNSerie[0] == ""){
										cadenaNSerie = [];
									}
								}
								cantidadInicial = $("I_cantidad").value;
							}
						}else{
							validarActivarNSerie('f');
						}
				        if (fechaCaducidad != "" || nrolote != '' || habilitalote == 't' || checkperecible == 't') {
				            try{
    					        modificarCamposFechaLote();
    					    } catch (ex){}
    				        if ( mov == "Entrada") {
        				        $("txtFechaCaducidad").value = fechaCaducidad;
		                        $("txtLote").value = this.grid.getCellText(17+tieneCC, fila);
    				        } else {
    				            if (mov == "Salida" || mov == "Inventario") {
    				                let comboFechaCaducidad = document.getElementById("comboFechaCaducidad");
                                    let optionExists = false;
                                    
                                    for (let i = 0; i < comboFechaCaducidad.options.length; i++) {
                                        if (comboFechaCaducidad.options[i].value == fechaCaducidad && comboFechaCaducidad.options[i].getAttribute("data-lote") == this.grid.getCellText(17+tieneCC, fila)) {
                                            comboFechaCaducidad.selectedIndex = (""+i);
                                            setLoteStock(comboFechaCaducidad, "");
                                            break;
                                        }
                                    }
    				            } else {
    				                if (mov == "Traspaso") {
                                        let comboFechaCaducidad = document.getElementById("comboFechaCaducidadOrigen");
                                        let optionExists = false;
                                        for (let i = 0; i < comboFechaCaducidad.options.length; i++) {
                                            if (comboFechaCaducidad.options[i].value == fechaCaducidad && comboFechaCaducidad.options[i].getAttribute("data-lote") == this.grid.getCellText(17+tieneCC, fila)) {
                                                comboFechaCaducidad.selectedIndex = (""+i);
                                                setLoteStock(comboFechaCaducidad, "T");
                                                break;
                                            }
                                        }
                                    }
    				            }
    				        }
				        } else {
				            try{
    					        modificarCamposFechaLote();
    					    } catch (ex){}
				        }

				    } catch (ex1) {}

			}else if ( this.grid.getCellText(7+tieneCC, fila) == "<img width='15px' height='15px' src='<?=$POSICION?>img/ico/eliminar2T.png'>"){
					if (columna != 7+tieneCC){
						alert ("Para modificar una Guia de Ajuste tipo Traspaso debe eliminarla haciendo click en Eliminar Traspaso y crearla nuevamente.");
						return;
					}
				}
			}
		},
			
//************************************************************************************************************************************************************
		
		Calcular : function(){
			$("I_montoitem").value = Math.round ($("I_cantidad").value * $("I_preciounitario").value);
			
			if (tieneCC == 1) {
				$("c_monto").value = $("I_montoitem").value;
			}
		},
			
//************************************************************************************************************************************************************
		
		VerProducto : function(codigo){
			var arg 		= "&cod="+codigo;
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=pasoProducto"+arg);
			$("I_preciounitario").disabled = false;
			$("I_preciounitario").className = "txt_obligatorio";
			if(RS.error == ""){
				if (RS.numrows > 0) {
					try{
						if(BD.getField (RS,"descontinuado",0) == "t"){
							alertDescontinuado()
							return
						}
						if (BD.getField (RS,"inventariable",0) == "f") {
							this.codproducto	= BD.getField (RS,"codproducto",0);
							this.descripcion	= BD.getField (RS,"descripcion",0);
							this.idProducto		= BD.getField (RS,"idproducto",0);
							try{
								this.pmp = Math.round(BD.getField (RS,"pmp",0));
								this.preciocostounitario = Math.round(BD.getField (RS,"preciocostounitario",0));
							}
							catch(e){
								this.pmp = -1;
							}
                            $("I_codigo").value 		= BD.getField (RS,"codproducto",0);
							if (BD.getField (RS,"permitirmodificar",0) == "t") {
								MSGBOX.AbrirParametrisado(
									"<br><br>El código de producto ingresado no está marcado como Inventariable.<br><br>¿Desea marcarlo como Inventariable para continuar?<br><br><br>"
									,{
										botonera:	3
										,fnretorno:	"msgAccionNoInventariable"
										,titulo		: "Resultados del Código de Producto"
									}
								);
							} else {
								MSGBOX.AbrirParametrisado(
									"<br><br>El código de producto ingresado no está marcado como Inventariable.<br><br><br><br>"
									,{
										botonera:	1
										,titulo		: "Resultados del Código de Producto"
									}
								);
							}
							return;
						} else {
						$("I_codigo").value 		= BD.getField (RS,"codproducto",0);
						$("I_descripcion").value 	= BD.getField (RS,"descripcion",0);
						$("I_idproducto").value 	= BD.getField (RS,"idproducto",0);
						$("I_cantidad").value 		= "0";
                        $("I_montoitem").value 		= "0";

						try{
							$("I_preciounitario").value = BD.getField (RS,"preciocostounitario",0);
							recalcularobtenerpreciounitario($("I_preciounitario"),$("I_idproducto").value);
							$("I_preciounitario").value = Math.round($("I_preciounitario").value);
							if($("I_preciounitario").value<0){$("I_preciounitario").value="";}

						}
						catch(e){
							$("I_preciounitario").value = "";
						}

						if (tieneCC == 1) {
								var idcentrocosto = BD.getField (RS, "idcentrocosto", 0);
								
								if (idcentrocosto > 0 && ($("tipomovimiento").value == 'I' || $("tipomovimiento").value == "E")) {
									$("centro").checked = true;
									$("idtemporal_cc").value = idcentrocosto;
									$("c_codigo").value = BD.getField (RS, "codigocc", 0);
									$("c_descripcion").value = BD.getField (RS, "descripcioncc", 0);
									$("c_area").value = BD.getField (RS, "areacctexto", 0);
									$("c_categoria").value = BD.getField (RS, "categoriacctexto", 0);
									
									if ($("I_montoitem").value != '') {
										$("c_monto").value = $("I_montoitem").value;
									}
									//Se muestra el centro de costo
									$("ctrcto").style.display = "";
								}
							}
					    } 
					    
					    $("perecible").value = BD.getField (RS,"perecible",0);
					    $("fechaCaducidadObligatoria").value = BD.getField (RS,"confechacaducidad",0);
					    $('txtFechaCaducidad').value = "";
						$("asignarlote").value = BD.getField (RS,"asignarlote",0);
						$("checkperecible").value = BD.getField (RS,"checkperecible",0);
						$('asignarserie').value = BD.getField (RS,"asignarserie",0);
						$('nroserieobligatorio').value = BD.getField (RS,"nroserieobligatorio",0);
						cadenaNSerie = [];
						cantidadInicial = "";
						validarActivarNSerie($('asignarserie').value);
					    try{
					        modificarCamposFechaLote();
					    } catch (ex){}
					}
					catch(e){
						alert('El producto seleccionado no es inventariable');
					}
				} else {
					MSGBOX.AbrirParametrisado(
						"<br><br>El código de producto ingresado no existe.<br><br>¿Desea ver la lista de Productos?<br><br><br>"
						,{
							botonera	: 3
							,fnretorno	: "msgAccionAbrirBuscador"
							,titulo		: "Resultados del Código de Producto"
					}
					);
				}
			} else {
				alert (RS.error);
			}
		},
			
//************************************************************************************************************************************************************
		
		valNull : function(control){
			if (control.value == "") control.value = "0";
		},
			
//************************************************************************************************************************************************************
		
		valIn : function(control){
			if (control.value == "0") control.value = "";
		},
			
//************************************************************************************************************************************************************
		addDetalle : function(){

			if(this.data.length >= 100){
				MSGBOX.Abrir('Ud. ha superado el maximo de linea de detalle para esta Guia de Ajuste; <br> Por favor Guarde La Informacion ');
				return;
			}

			var codExiste 	= false;
			var registro 	= $("I_idproducto").value;
			var cantidad 	= $("I_cantidad").value;
			var tmovimiento	= $("tipomovimiento").value;
			var punitario   =	$("I_preciounitario").value;
			if (tieneCC == 1) {
				var idcentrocosto   =	$("idtemporal_cc").value;
			}
			var i;
			// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
			var fechamovimiento = $("I_fechamovimiento").value;
			if (fechamovimiento == "" ){
				MSGBOX.AbrirParametrisado("La fecha de movimiento es obligatoria", {
					titulo: "Gu&iacute;a de Ajuste",
					fnretorno: "MSGBOXfocusElemento",
					objeto: {"elementofocus": $("I_fechamovimiento")}
				} );
				return;
			}
			// -- FIN REQUERIMIENTO 14 --
			if (tmovimiento == 'INV') {
				if(cantidad < 0){
					MSGBOX.AbrirParametrisado("La cantidad debe ser mayor a 0.", {
						titulo: "Gu&iacute;a de Ajuste",
						fnretorno: "MSGBOXfocusElemento",
						objeto: {"elementofocus": $("I_cantidad")}
					} );
					$("I_cantidad").value="";
					return;
				}
			} else {
				if(cantidad <= 0){
					//alert("La cantidad debe ser mayor a 0");
					MSGBOX.AbrirParametrisado("La cantidad debe ser mayor a 0.", {
						titulo: "Gu&iacute;a de Ajuste",
						fnretorno: "MSGBOXfocusElemento",
						objeto: {"elementofocus": $("I_cantidad")}
					} );
					$("I_cantidad").value="";
					return;
				}
			}
            if($("perecible").value == "t"){
				if($("fechaCaducidadObligatoria").value == "t" && tmovimiento == "I" && $("txtFechaCaducidad").value == ""){
					MSGBOX.AbrirParametrisado(
							'Ha seleccionado un producto perecible, debe ingresar la fecha de caducidad.'
							,{
								botonera: 1
								,titulo: "Ingresar Fecha de Caducidad"
							}
						);
						return false;
				}
				if((tmovimiento == "E" || tmovimiento == "INV") &&  $("comboFechaCaducidad").value != "" && $("comboFechaCaducidad").value != "No Registrada" && (parseInt($("stockLoteFechaInput").value) < parseInt($("I_cantidad").value) || $("stockLoteFechaInput").value == '' || $("stockLoteFechaInput").value == '0')){
					MSGBOX.AbrirParametrisado(
						'Ha elegido una Fecha de Caducidad/Lote con stock insuficiente.'
						,{
							botonera: 1
							,titulo: "Seleccionar otra Fecha de Caducidad/Lote"
						}
					);
					return false;
				}
			}

			var valpunit = parseFloat(punitario);
			if(isNaN(valpunit)){
				valpunit = 0;
			}
	
			if(valpunit<0 || $("I_preciounitario").value == "" ){
				MSGBOX.Abrir('Debe registrar en el campo \"Precio Costo Unitario\" un valor númerico igual o mayor que 0','Precio Costo Unitario', 'accion_val_precio');
				return;
			}
			if(tmovimiento != 'INV'){
				let respValidMateriaPrima = Ajuste.validacionMateriaPrima($("I_idproducto").value);
				if(respValidMateriaPrima){
					MSGBOX.AbrirParametrisado(
						'Las materias primas del producto ingresado no tienen precio de compra neto asignado, ingresarlo desde la configuraci&oacute;n del Producto en Maestros->Productos/Servicios.',
						{
							titulo: 'Precio de Compra Neto',
							botonera: 1,
							fnretorno: 'Ajuste.retornoMSGBOX',
							conCerrar: false
						}
						);
					return;
				}
			}

			if (registro != ""){
				if (isNaN ($("I_cantidad").value) || isNaN ($("I_preciounitario").value) ||	isNaN ($("I_montoitem").value)){
					//alert ("Debe ingresar valores correctos");
					MSGBOX.AbrirParametrisado("Debe ingresar valores correctos. Verifique cantidad y precio costo.", {
						titulo: "Gu&iacute;a de Ajuste"
					} );
					return;
				}
 				<?php 
 				if ($SYS_Bodega == TRUE) {
 			        if ($accionajuste == 2) { ?>
					if ($("stock").value - $("I_cantidad").value < 0 && $("tipomovimiento").value == "E" && $("I_checkbodega").checked == true) {
						MSGBOX.AbrirParametrisado(
							'El producto seleccionado no posee inventario suficiente.<br><br>'+
							'Cantidad Disponible: '+$("stock").value
                            ,{
								botonera: 1,
								titulo: "Ingresar Producto"
                            }
                        );
                        return false;
					} else {
						$("I_cantidad").style.background = "#E0F4FE";
            			$("mensajealerta").style.display = "none";
					}
				<?php } else { ?>
					$("I_cantidad").style.background = "#E0F4FE";
            		$("mensajealerta").style.display = "none";
				<?php }
				} ?>
				var estraspaso = false;
				for (i = 0; i < this.data.length; i++) {
					if (this.data[i][8+tieneCC] == registro) {
						codExiste = true;
						estraspaso = this.data[i][2] == "Traspaso" ? true : false;
						break;
					}
				}
				
				if (estraspaso && codExiste){
						esModificacion = 1;
						if(Ajuste.delDetalleT(this.data[filagrid][8+tieneCC], false) && registro && esModificacion != 0){
							Ajuste.addDetalle();
				        }
						
				}else{
						var modificado = 0;
						esModificacion = 0;
				}
				if(!validarNroSerieObligatorio()){
					return false;
				}
				if(codExiste == false && esModificacion != 1){
                    if (!Ajuste.verificarInventario(tmovimiento)) {
                            
                        MSGBOX.AbrirParametrisado(
                            'Guia de ajuste inventario debe tener solo<br>movimientos de tipo inventario'
                            ,{
                                botonera: 1
                                ,titulo: "Ingresar Gu&iacute;a de Ajuste"
                            }
                        );
                        return false;
                    }
                
                    if($("tipomovimiento").value == "INV"){
                        if (primerInsertarInventario == false) {
                            primerInsertarInventario = true;
                            
    						MSGBOX.AbrirParametrisado(
    							'El movimiento <b>inventario</b> reemplazar&aacute; las existencias registradas<br>¿Desea continuar?'
    							,{
    								botonera: 3
    								,titulo: "Ingresar Gu&iacute;a de Ajuste"
    								,fnretorno: "Ajuste.retornoMSGBOXInventario"
    							}
    						);
    						return false;
                        }
                    }
				
					// Agregar nueva fila.
					var row = "";
					row = new Array();
					row.push($("I_codigo").value);
					row.push($("I_descripcion").value);
					
					if (tmovimiento == "I"){
						row.push("Entrada");
					}else if (tmovimiento == "E"){
						row.push("Salida");
                    } else if (tmovimiento == "INV"){
                        row.push("Inventario");
					}
					
					if (tmovimiento == "E"){
						$("I_cantidad").value = $("I_cantidad").value *-1;
					}
					
					if($("I_cantidad").value  < 0){
						var	cantidad   = '<span style="color:red;">(' + $("I_cantidad").value   * -1 + ')</span>';
					}
					row.push(cantidad);
					row.push($("I_preciounitario").value);
					row.push($("I_montoitem").value);
					if ($('I_checkbodega').checked == true) {
					    row.push($("I_descripcionbodega").value);
					}else{
						row.push($("descripcionbodegadefecto").value);
					}
					if (tieneCC == 1) {
						if ($('centro').checked == true && tmovimiento != "INV") {
							row.push($('c_codigo').value);
						} else {
							row.push("");
						}
					}
					row.push("<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrar.gif'>");
					
					row.push($("I_idproducto").value);
					if($('I_checkbodega').checked == true){
						row.push($('I_codigobodega').value);
					}else{
						row.push($("codigobodegadefecto").value);
					}
					row.push(0);

					if (tieneCC == 1) {
						//Se inserta el id y el monto
						if ($('centro').checked == true && tmovimiento != "INV") {
							row.push($('idtemporal_cc').value);
							row.push($('c_monto').value);
							
							$("centro").checked 		= false;
							$("ctrcto").style.display	= "none";
						} else {
							row.push(-1);
							row.push(0);
						}
						//Tras insertar, se resetea el CC
						$("btnBorrarcc").click();
						$('c_monto').value = "";
					} else {
					   row.push(-1);
					   row.push(0);
					}
    				row.push("");
    				row.push("");
    				row.push("");
    				
    				if (tmovimiento == "I"){
                        row.push($('txtFechaCaducidad').value);
						row.push($('txtLote').value);
					}else if (tmovimiento == "E" || tmovimiento == "INV"){
                        row.push($('comboFechaCaducidad').value);
						row.push($('comboLote').value);
                    }
					row.push($('asignarlote').value);
					row.push($('checkperecible').value);
					// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
					row.push(fechamovimiento);
					// -- FIN REQUERIMIENTO 14 --
					row.push(cadenaNSerie.join(";"));
					row.push($('asignarserie').value);
					row.push($('nroserieobligatorio').value);
					cadenaNSerie = [];
					cantidadInicial = '';
					this.data.push(row);
					this.grid.setRowCount(this.data.length);
					
                    if (tmovimiento != "INV") {
					   Ajuste.buscarMateriasPrimas($("I_idproducto").value);//Llama método que busca las materias primas asociadas al producto ingresado
                    }
					this.LimpiaCampos();
					
					$("BTN_guarda").disabled=false;

				} else {
                    if (!Ajuste.verificarInventario(tmovimiento, i)) {
                        
                        MSGBOX.AbrirParametrisado(
                            'Guia de ajuste inventario debe tener solo<br>movimientos de tipo inventario'
                            ,{
                                botonera: 1
                                ,titulo: "Ingresar Gu&iacute;a de Ajuste"
                            }
                        );
                        return false;
                    } else if (codExiste && modificado == 0){
					//if (confirm("El producto ya esta ingresado.\r\n\u00bfDesea modificar el ya existente?")){
						if (Ajuste.variableConfirmarModificarLinea == true) {
            			    MSGBOX.AbrirParametrisado("El producto ya esta ingresado.<br>¿Desea modificar el ya existente?.",{
            					titulo: "Gu&iacute;a de Ajuste",
            					fnretorno: "MSGBOXConfirmarModificar",
            					botonera: 3
            				});
            				return;
            	        }	
            	        Ajuste.variableConfirmarModificarLinea = true;			
						this.data[i][0] 	= $("I_codigo").value;
						this.data[i][1] 	= $("I_descripcion").value;
						this.data[i][2]     = $("tipomovimiento").value == "I" ? "Entrada" : $("tipomovimiento").value == "E" ? "Salida" : "Inventario";

						this.data[i][3] 	= $("I_cantidad").value;
                    

						
						if (tmovimiento == "E"){
							this.data[i][3] = this.data[i][3] * -1;
						}
							
						if(this.data[i][3] < 0){
							
							this.data[i][3] = '<span style="color:red;">(' + this.data[i][3] * -1 + ')</span>';
						}
							this.data[i][4] = $("I_preciounitario").value;
							this.data[i][5] = $("I_montoitem").value;
							this.data[i][8+tieneCC] = $("I_idproducto").value;
						if($('I_checkbodega').checked == true){
							this.data[i][9+tieneCC] = $('I_codigobodega').value;
						}else{
							this.data[i][9+tieneCC] = $("codigobodegadefecto").value;
						}
							
						if (tieneCC == 1) {
							if ($('centro').checked == true) {
								if (tmovimiento == "I" || tmovimiento == "E") {	
									this.data[i][7]  = $('c_codigo').value;
									this.data[i][12] = $('idtemporal_cc').value;
									this.data[i][13] = $('c_monto').value;
								} else {
									this.data[i][7]  = "";
									this.data[i][12] = -1;
									this.data[i][13] = 0;
								}
							} else {
								this.data[i][7]  = "";
								this.data[i][12] = -1;
								this.data[i][13] = 0;
							}
						}
							
						this.data[i][16+tieneCC]     = $("tipomovimiento").value == "I" ? $('txtFechaCaducidad').value : $("comboFechaCaducidad").value;
						this.data[i][17+tieneCC]     = $("tipomovimiento").value == "I" ? $('txtLote').value : $('comboLote').value;
						if(!validarNroSerieObligatorio()){
							return false;
						}

						this.data[i][20+tieneCC] = ($("tipomovimiento").value == "I" || $("tipomovimiento").value == "INV" ) ? cadenaNSerie.join(";") : '';
						cadenaNSerie = [];
						cantidadInicial = '';

						this.data[i][21+tieneCC]     = $("asignarserie").value;
						this.data[i][22+tieneCC]     = $("nroserieobligatorio").value;
						// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
						this.data[i][21]     		 = fechamovimiento;
						// -- FIN REQUERIMIENTO 14 --
						//---------------------------------------------------------------------------------------
						
						var BD 			= new BASE();//Llamamos a la clase que nos permite ejecutar el ajax.
						BD.commandFile 	= "form/existencias/command.php";
						var RS 			= BD.send("&cmd=get_padremateriaprima&id=" + $("I_idproducto").value);	
						cantidad = $("I_cantidad").value;
						
						
						
						if(RS.error == ""){
			
							if (RS.numrows > 0) {
								for(i = 0 ; i < RS.numrows ; i++){//Por cada resultado obtenido de la consulta realizamos lo siguiente: 
									registro = BD.getField (RS,"codproducto",i);//le asignamos a la variable registro el codigo de la materia prima.
									
									for (a = 0; a < this.data.length; a++) {//recorremos la grilla en busca de la materia prima que coincida con la obtenida en la consulta.
										if ((this.data[a][0] == registro) && (this.data[a][7+tieneCC] == $("I_idproducto").value)){//Si encontramos coincidencias actualizamos sus valores.
											
											this.data[a][3] = (BD.getField (RS,"cantidad",i) * cantidad) ;
										
											if (tmovimiento == "E"){
												this.data[a][2] = "Salida";	
												this.data[a][3]   = '<span style="color:red;">(' + this.data[a][3] + ')</span>';
											}else if (tmovimiento == "I"){
												this.data[a][2] = "Entrada";
											}
											
											this.data[a][5] = this.data[a][5] * cantidad;
											
											break;
										}
									}
								}
							}
						}
						$("BTN_guarda").disabled=false;
						this.LimpiaCampos();
					}
				//}
			}
				this.grid.setCellText(this.data);
				
				// Actualizamos la informacion de la grilla.
				this.grid.setSelectedRows(new Array());
				$("GRD_ajuste").innerHTML = this.grid;
				
				this.grid.refresh();
				
				
			} else{
				alert ("Debe seleccionar un Producto primero.");
			}
		},
		
		addDetalleT : function(){
			if(this.data.length >= 100){
				MSGBOX.Abrir('Ud. ha superado el maximo de linea de detalle para esta Guia de Ajuste; <br> Por favor Guarde La Informacion ');
				return;
			}
			var codExiste 	= false;
			var registro 	= $("I_idproducto").value;
			var cantidad 	= $("I_cantidad").value;
			var tmovimiento	= $("tipomovimiento").value;
			// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
			var fechamovimiento = $("I_fechamovimiento").value;
			if (fechamovimiento == "" ){
				MSGBOX.AbrirParametrisado("La fecha de movimiento es obligatoria", {
					titulo: "Gu&iacute;a de Ajuste",
					fnretorno: "MSGBOXfocusElemento",
					objeto: {"elementofocus": $("I_fechamovimiento")}
				} );
				return;
			}
			// -- FIN REQUERIMIENTO 14 --
			var i;
			
			if(cantidad <= 0){
				//alert("La cantidad debe ser mayor a 0");
				MSGBOX.AbrirParametrisado("La cantidad debe ser mayor a 0.", {
					titulo: "Gu&iacute;a de Ajuste",
					fnretorno: "MSGBOXfocusElemento",
					objeto: {"elementofocus": $("I_cantidad")}
				} );
				$("I_cantidad").value="";
				return;
			}
			
			if ($('I_codigobodega_o').value == "" || $('I_codigobodega_d').value == ""){
				//alert("Debe elegir Bodega de Origen y Bodega de Destino");
				MSGBOX.AbrirParametrisado("Debe elegir Bodega de Origen y Bodega de Destino.", {
					titulo: "Gu&iacute;a de Ajuste",
					fnretorno: "MSGBOXfocusElemento",
					objeto: {"elementofocus": $("I_codigobodega_o")}
				} );
				return;
			}

            if ($("I_preciounitario").value.trim() == "" ) {
				MSGBOX.Abrir('Debe registrar en el campo \"Precio Costo Unitario\" un valor númerico igual o mayor que 0','Precio Costo Unitario', 'accion_val_precio');
				return;
            }
			const respValidMateriaPrima = Ajuste.validacionMateriaPrima($("I_idproducto").value);
			if(respValidMateriaPrima){
				MSGBOX.AbrirParametrisado(
					'Las materias primas del producto ingresado no tienen precio de compra neto asignado, ingresarlo desde la configuraci&oacute;n del Producto en Maestros->Productos/Servicios.',
					{
						titulo: 'Precio de Compra Neto',
						botonera: 1,
						fnretorno: 'Ajuste.retornoMSGBOX',
						conCerrar: false
					}
					);
				return;
			}
					
			if (registro != ""){
				if (isNaN ($("I_cantidad").value) || isNaN ($("I_preciounitario").value) ||	isNaN ($("I_montoitem").value)){
					MSGBOX.AbrirParametrisado("Debe ingresar valores correctos. Verifique cantidad y precio costo.", {
						titulo: "Gu&iacute;a de Ajuste"
					} );
					return;
				}
				
				<?php 
				if ($SYS_Bodega == TRUE) {    
				    if ($accionajuste == 2) { ?>
					if ($("stock").value - $("I_cantidad").value < 0 && $("tipomovimiento").value == "T") {
						MSGBOX.AbrirParametrisado(
							'El producto seleccionado no posee inventario suficiente.<br><br>'+
							'Cantidad Disponible: '+$("stock").value
                            ,{
								botonera: 1,
								titulo: "Ingresar Producto"
                            }
                        );
                        return false;
					} else {
						$("I_cantidad").style.background = "#E0F4FE";
            			$("mensajealerta").style.display = "none";
					}
				<?php } else { ?>
					$("I_cantidad").style.background = "#E0F4FE";
            		$("mensajealerta").style.display = "none";
				<?php }
				} ?>
				
			
				for (i = 0; i < this.data.length; i++) {
					if (this.data[i][8+tieneCC] == registro){

						codExiste = true;
						break;
					}
				}
				
				if(codExiste == false){
                    if (!Ajuste.verificarInventario(tmovimiento)) {
					
                        MSGBOX.AbrirParametrisado(
                            'Guia de ajuste inventario debe tener solo<br>movimientos de tipo inventario'
                            ,{
                                botonera: 1
                                ,titulo: "Ingresar Gu&iacute;a de Ajuste"
                            }
                        );
                        return false;
                    }                   
					//PRODUCTO DEL ORIGEN
					
					// Agregar nueva fila.
					var row = "";
					row = new Array();
					row.push($("I_codigo").value);
					row.push($("I_descripcion").value);
					
					/*POR ALBERTO SALAZAR EN FECHA 18-05-2016. MOTIVO: REQUERIMIENTO 732*/ 
					row.push("Traspaso");
					//$("I_cantidad").value = $("I_cantidad").value *-1;
					var	cantidad   = '<span style="color:red;">(' + $("I_cantidad").value  + ')</span>';
					row.push(cantidad);
					row.push($("I_preciounitario").value);
					row.push($("I_montoitem").value);
					row.push($("I_descripcionbodega_o").value);
					if (tieneCC == 1) {
						row.push("");
					}
					row.push("<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrarT.gif'>");
 
					row.push($("I_idproducto").value);
					row.push($('I_codigobodega_o').value);
					row.push(0);
					row.push('');
					row.push("TO");//TRASPASO-ORIGEN
					row.push('');
					row.push(this.inxt);
					row.push('');
					row.push($('comboFechaCaducidadOrigen').value);
					row.push($('comboLoteOrigen').value);
					
					row.push($('asignarlote').value);
					row.push($('checkperecible').value);
					// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
					row.push(fechamovimiento);
					// -- FIN REQUERIMIENTO 14 --
					this.data.push(row);
					Ajuste.buscarMateriasPrimasT($("I_idproducto").value, 'O');//Llama método que busca las materias primas asociadas al producto ingresado
					
					
					//BODEGA DESTINO
					row = new Array();
					row.push($("I_codigo").value);
					row.push($("I_descripcion").value);
					
					/*POR ALBERTO SALAZAR EN FECHA 18-05-2016. MOTIVO: REQUERIMIENTO 732*/ 
					row.push("Traspaso");
					//$("I_cantidad").value = $("I_cantidad").value *-1;
					if($("I_cantidad").value  < 0){
						row.push($("I_cantidad").value * -1);
					}else{
						row.push($("I_cantidad").value);
					}
					row.push($("I_preciounitario").value);
					row.push($("I_montoitem").value);
					row.push($("I_descripcionbodega_d").value);
					if (tieneCC == 1) {
						row.push("");
					}
					row.push("<img width='15px' height='15px' src='<?=$POSICION?>img/ico/pencil_add.png'><img width='15px' height='15px' data-eliminar='true' src='<?=$POSICION?>img/ico/borrarT.gif'>");
					row.push($("I_idproducto").value);
					row.push(0);
					row.push($('I_codigobodega_d').value);
					row.push('');
					row.push("TD");//TRASPASO-DESTINO
					row.push('');
					row.push(this.inxt);
					row.push('');
					row.push($('comboFechaCaducidadOrigen').value);
					row.push($('comboLoteOrigen').value);
					// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
					row.push($('asignarlote').value);
					row.push($('checkperecible').value);
					row.push(fechamovimiento);
					// -- FIN REQUERIMIENTO 14 --
					this.data.push(row);
					
					Ajuste.buscarMateriasPrimasT($("I_idproducto").value, 'D');//Llama método que busca las materias primas asociadas al producto ingresado

					this.grid.setRowCount(this.data.length);
					this.LimpiaCampos();
					
					$("BTN_guarda").disabled=false;
				} else{
							esModificacion = 1;
							if(movimientoeditar == 'Traspaso'){
								if(!Ajuste.delDetalleT(this.data[filagrid][8+tieneCC], false) && esModificacion == 0){

									return;
								}								
							}else{
								if(!Ajuste.delDetalle(filagrid, registro, false) && esModificacion == 0){
									return;
								}
							}
							Ajuste.addDetalleT();
							esMateriaPrima = 0;						
				}
				this.grid.setCellText(this.data);
				
				// Actualizamos la informacion de la grilla.
				this.grid.setSelectedRows(new Array());
				$("GRD_ajuste").innerHTML = this.grid;
				
				this.grid.refresh();
				this.inxt++;

			} else
				alert ("Debe seleccionar un Producto primero.");
		},
			
//************************************************************************************************************************************************************
		adddetallemanual : function(){
		var BD 			= new BASE();
		BD.commandFile 	= "form/existencias/command.php";
		var RS 			= BD.send("&cmd=getprodoc&idfolio=" + idocguia);	
		if(RS.error == ""){
			if (RS.numrows > 0) {
				for(i = 0 ; i < RS.numrows ; i++){
					codproducto		= BD.getField (RS,"codproducto",i);
					descripcion		= BD.getField (RS,"descripcion",i);
					cantidad		= BD.getField (RS,"cantidad",i);
					preciounitario	= BD.getField (RS,"preciounitario",i);
					montoitem		= BD.getField (RS,"montoitem",i);
					idproducto		= BD.getField (RS,"idproducto",i);

					var row = "";
					row = new Array();
					row.push(codproducto);
					row.push(descripcion);
					row.push("SI");
					row.push(cantidad);
					row.push(preciounitario);
					row.push(montoitem);
					row.push("Eliminar");
					row.push(idproducto);
					row.push(-1);
					row.push(0);

					this.data.push(row);
			
					this.grid.setCellText(this.data);
					// Actualizamos la informacion de la grilla.
					this.grid.setSelectedRows(new Array());
					//console.log (this.grid );
					$("GRD_ajuste").innerHTML = this.grid;
					this.grid.setRowCount(this.data.length);
					this.grid.refresh();
				}
			}
		}else{
			alert (RS.error);
		}
	},
//************************************************************************************************************************************************************
		
		validacionMateriaPrima: function(idproducto){
			let BD 			= new BASE();//Llamamos a la clase que nos permite ejecutar el ajax.
			BD.commandFile 	= "form/existencias/command.php";
			let RS 			= BD.send("&cmd=get_materiaprima&id=" + idproducto);
			let validaMPrima = false;
			if(RS.error == ""){
				if (RS.numrows > 0) {
					//Data
					for(i = 0 ; i < RS.numrows ; i++){
						let preciounitario = BD.getField (RS,"preciounitario",i);
						if(preciounitario <= 0){
							let RS2 = BD.send("&cmd=materiaprima_existencia&id=" + BD.getField (RS,"idcomponente",i));
							if(RS2.numrows > 0){
								let cantidad = BD.getField (RS2,"cantidad",0);
								let pmp = BD.getField (RS,"pmp",i);
								if((cantidad < 0 || cantidad == '') || (pmp == '' || pmp == 0)){
									validaMPrima = true
								}
							} else {
								validaMPrima = true
							}
						}
						
					}
				}
			}
			return validaMPrima;
		},
		buscarMateriasPrimas : function(idproducto){//Función que sirve para obtener las materias primas del producto padre
			
			var BD 			= new BASE();//Llamamos a la clase que nos permite ejecutar el ajax.
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=get_materiaprima&id=" + idproducto);	
			
			if(RS.error == ""){
			
				if (RS.numrows > 0) {
	
					//Data
					for(i = 0 ; i < RS.numrows ; i++){
						
						var cantidad 	= BD.getField (RS,"cantidad",i);
						cantidad		= (cantidad) * ($("I_cantidad").value);
						var row 		= new Array();
						var tmovimiento	= $("tipomovimiento").value;
						let preciounitario = BD.getField (RS,"preciounitario",i);
						let pmp = BD.getField (RS,"pmp",i);
						let RS2 = BD.send("&cmd=materiaprima_existencia&id=" + BD.getField (RS,"idcomponente",i));
						let cantidadBodega = 0;
						if(RS2.numrows > 0){
							cantidadBodega = BD.getField (RS2,"cantidad",0);
						}
						
						row.push(BD.getField (RS,"codproducto",i));
						row.push(BD.getField (RS,"descripcion",i));
						if (tmovimiento == "I"){
							row.push("Salida");
						}else if (tmovimiento == "E"){
							row.push("Entrada");
						}else if (tmovimiento == "T"){
							row.push("Traspaso");
						}

						if (tmovimiento == "E"){
							$("I_cantidad").value = $("I_cantidad").value *-1;
						}
						
						if(cantidad  < 0){
							
							var cantidadprima   = '<span style="color:red;">(' + cantidad * -1 + ')</span>';
						}else{
							var cantidadprima = cantidad;
						
						}
					
						row.push(cantidadprima);
						if(cantidad < 0 ){
							cantidad = cantidad * -1;
						}
						if(cantidadBodega > 0 && (preciounitario == "" ||  preciounitario == 0)){
							row.push(pmp);	
							row.push(pmp * cantidad);	
						} else {
							row.push(preciounitario);
							row.push(preciounitario*cantidad);
						}
						row.push(""); //bodega
						if (tieneCC == 1) {
						    row.push("");
					    }
						row.push(""); // eliminar
						row.push(idproducto);

						if($('I_checkbodega').checked == true){
							row.push($('I_codigobodega').value);
						}else{
							row.push(-1);
						}
						row.push("");//destino
						row.push(""); // CC
						row.push(""); // CCMonto
						row.push(BD.getField (RS,"idcomponente",i));
						
						this.data.push(row);
						this.grid.setRowCount(this.data.length);
					}
					esMateriaPrima = 1;
				}
			} else {
				alert(RS.error);
			}
		
		},
			
//************************************************************************************************************************************************************

		buscarMateriasPrimasT : function(idproducto, tipomov){//Función que sirve para obtener las materias primas del producto padre
			
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=get_materiaprima&id=" + idproducto);	
			
			if(RS.error == ""){
			
				if (RS.numrows > 0) {
	
					//Data
					for(i = 0 ; i < RS.numrows ; i++){
						
						var cantidad 	= BD.getField (RS,"cantidad",i);
						cantidad		= (cantidad) * ($("I_cantidad").value);
						var cantidadprima = cantidad;
						var row 		= new Array();
						var tmovimiento	= $("tipomovimiento").value;
						
						row.push(BD.getField (RS,"codproducto",i));
						row.push(BD.getField (RS,"descripcion",i));
						row.push("Traspaso");

						if (tipomov == "O"){
							var	cantidad   = '<span style="color:red;">(' + cantidad + ')</span>';
							row.push(cantidad); // cantidad materia prima
							row.push(BD.getField (RS,"preciounitario",i));
							row.push((BD.getField (RS,"preciounitario",i) * cantidadprima));
							row.push($('I_descripcionbodega_o').value); 
						}else if (tipomov == "D"){
							row.push(cantidad); // cantidad materia prima
							row.push(BD.getField (RS,"preciounitario",i));
							row.push(BD.getField (RS,"preciounitario",i) * cantidadprima);
							row.push($('I_descripcionbodega_d').value); 
						}
					    if (tieneCC == 1) {
						    row.push("");
					    }
						row.push(""); // eliminar
						row.push(idproducto);

						if (tipomov == "O"){ //bodega
							row.push($('I_codigobodega_o').value); 
							row.push(0);
							row.push("");//CC
						    row.push("TO");
							row.push(BD.getField (RS,"idcomponente",i));
							row.push(this.inxt);
						}else if (tipomov == "D"){
							row.push(0);
							row.push($('I_codigobodega_d').value); 
							row.push("");//CC
							row.push("TD");
							row.push(BD.getField (RS,"idcomponente",i));
							row.push(this.inxt);
						}
						this.data.push(row);
						this.grid.setRowCount(this.data.length);
					}
				}
			} else {
				alert(RS.error);
			}
		
		},
		retornoMSGBOXInventario: function(resp){
			if(resp == 'S'){
				Ajuste.addDetalle();
			}
			MSGBOX.ventana.HideInfo();
		},
			
//************************************************************************************************************************************************************
		LimpiaCampos : function(){
			
			$("I_codigo").value 				= "";
			$("I_descripcion").value 			= "";
			$("I_cantidad").value 				= "";
			$("I_preciounitario").value 		= "";
			$("I_montoitem").value 				= "";
			$("I_idproducto").value 			= "";
			// -- POR NICOLAS BARRAZA EN FECHA 31-07-2025. MOTIVO: REQUERIMIENTO 14 --
			$("I_fechamovimiento").value		= "";
			// -- FIN REQUERIMIENTO 14 --

			//$("tipomovimiento").selectedIndex 	= 0;
			//$("tipomovimiento").onchange();
			if (tieneCC == 1)  {
				$("ctrcto").style.display = "none";
				$("centro").checked = false;
				$("btnBorrarcc").click();
			}
			this.grid.setSelectedRows(new Array());
			validarActivarNSerie('f');
			$("asignarserie").value = '';
			$("nroserieobligatorio").value = '';
    		try{
    		  $("perecible").value 		= "";
    			$("fechaCaducidadObligatoria").value 				= "";
				$("asignarlote").value 	= "";
				$("checkperecible").value = "";
    	        modificarCamposFechaLote();
    	    } catch (ex){}
		},
			
//************************************************************************************************************************************************************
		
		delDetalle : function(index, idproducto, borraDirecto){	
				
			
			
			if (borraDirecto == true) {
			    MSGBOX.AbrirParametrisado(
					"<div align='center'>¿Está seguro que desea eliminar esta línea de detalle?</div>"
					,{
                        titulo: 'Confirmar Eliminar',
                        fnretorno: 'Ajuste.delDetalleConfirmado',
						objeto: { 	'index':index,
									'idproducto':idproducto
						},
						botonera: 3
					}
				);
			} else {
    			var BD 			= new BASE();//Llamamos a la clase que nos permite ejecutar el ajax.
    			BD.commandFile 	= "form/existencias/command.php";
    			var RS 			= BD.send("&cmd=get_materiaprima&id=" + idproducto);	

    			if (confirm("\u00bfEst\u00e1 seguro(a) que desea eliminar esta l\u00ednea?")) {
    				
    				if(RS.error == ""){
    			
    					if (RS.numrows > 0) {
    						for(i = 0 ; i < RS.numrows ; i++){//Por cada resultado obtenido de la consulta realizamos lo siguiente: 
    							
    							registro = BD.getField (RS,"codproducto",i);//le asignamos a la variable registro el codigo de la materia prima.
    							
    							for (a = 0; a < this.data.length; a++) {//recorremos la grilla en busca de la materia prima que coincida con la obtenida en la consulta.
    										
    								if ((this.data[a][0] == registro) && (this.data[a][8+tieneCC] == idproducto)){//Si encontramos coincidencias actualizamos sus valores.
    									
    										this.data.splice(a, 1);
    										this.grid.setCellText(this.data);
    										this.grid.setRowCount(this.data.length);
    										this.grid.setSelectedRows(new Array());
    										$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    										this.grid.refresh();
    															
    								}
    							}
    						}
    					}
    				}
    				
    				this.data.splice(index, 1);
    				this.grid.setCellText(this.data);
    				this.grid.setRowCount(this.data.length);
    				this.grid.setSelectedRows(new Array());
    				$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    				this.grid.refresh();
    				if(esModificacion != 1 && esMateriaPrima != 1){
        				this.LimpiaCampos();
        			}
    				if(!borraDirecto){
    					return true;
    				}
    
    			}else{
    				esModificacion = 0;
    				return false; 
    			}
    	   }
		},
		delDetalleConfirmado : function (resp) {
		    if (resp == 'S') {
			    try {
			     //debugger;
			        var idproducto = MSGBOX.ventana.objeto.idproducto;
			        var index = MSGBOX.ventana.objeto.index;
    				var BD 			= new BASE();//Llamamos a la clase que nos permite ejecutar el ajax.
			        BD.commandFile 	= "form/existencias/command.php";
			        var RS 			= BD.send("&cmd=get_materiaprima&id=" + idproducto);	
                    if(RS.error == ""){
    			
    					if (RS.numrows > 0) {
    						for(i = 0 ; i < RS.numrows ; i++){//Por cada resultado obtenido de la consulta realizamos lo siguiente: 
    							
    							registro = BD.getField (RS,"codproducto",i);//le asignamos a la variable registro el codigo de la materia prima.
    							
    							for (a = 0; a < this.data.length; a++) {//recorremos la grilla en busca de la materia prima que coincida con la obtenida en la consulta.
    										
    								if ((this.data[a][0] == registro) && (this.data[a][8+tieneCC] == idproducto)){//Si encontramos coincidencias actualizamos sus valores.
    									
    										this.data.splice(a, 1);
    										this.grid.setCellText(this.data);
    										this.grid.setRowCount(this.data.length);
    										this.grid.setSelectedRows(new Array());
    										$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    										this.grid.refresh();
    															
    								}
    							}
    						}
    					}
    				}
    				
    				this.data.splice(index, 1);
    				this.grid.setCellText(this.data);
    				this.grid.setRowCount(this.data.length);
    				this.grid.setSelectedRows(new Array());
    				$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    				this.grid.refresh();
    				if(esModificacion != 1 && esMateriaPrima != 1){
        				this.LimpiaCampos();
        			}
			    } catch (ex){}
		    }
		    MSGBOX.ventana.HideInfo()
		},
		delDetalleT : function(index, borraDirecto){
            
            
            if (borraDirecto == true){
                MSGBOX.AbrirParametrisado(
					"<div align='center'>¿Está seguro que desea eliminar esta línea de detalle?</div>"
					,{
                        titulo: 'Confirmar Eliminar',
                        fnretorno: 'Ajuste.delDetalleTConfirmado',
						objeto: { 	
						      'index':index
						},
                        botonera: 3
					}
				);
            } else {
    			if (confirm("\u00bfEst\u00e1 seguro(a) que desea eliminar el traspaso?")) {
    				var fin = this.data.length;
    				for (a = this.data.length - 1; a >= 0; a--) {
    					if (this.data[a][8+tieneCC] == index) {
    					
    						this.data.splice(a, 1);
    					}
    				}
    				this.grid.setCellText(this.data);
    				this.grid.setRowCount(this.data.length);
    				this.grid.setSelectedRows(new Array());
    				$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    				this.grid.refresh();
    				if(esModificacion != 1 && esMateriaPrima != 1){
        				this.LimpiaCampos();
        			}
    			    if (!borraDirecto) {
    				    return true;
    				}
			    }else{
    				esModificacion = 0;
    				if (!borraDirecto) {
    					return false;
    				}
    			}
	        }
		},
		delDetalleTConfirmado : function(resp){
			if (resp == 'S') {
			    try {
    			    var index = MSGBOX.ventana.objeto.index;
    			    var fin = this.data.length;
    				for (a = this.data.length - 1; a >= 0; a--) {
    					if (this.data[a][8+tieneCC] == index) {
    					
    						this.data.splice(a, 1);
    					}
    				}
    				this.grid.setCellText(this.data);
    				this.grid.setRowCount(this.data.length);
    				this.grid.setSelectedRows(new Array());
    				$("GRD_ajuste").innerHTML 	= this.grid;//Elimina la linea 								
    				this.grid.refresh();
    				if(esModificacion != 1 && esMateriaPrima != 1){
    				    this.LimpiaCampos();
    				}
			    } catch (ex){}
		    }
		    MSGBOX.ventana.HideInfo()
		},
			
//************************************************************************************************************************************************************
        //Si recibe la variable movimiento, ademas verificara los registros ingresados con la variable movimiento
        verificarInventario: function(movimiento, filaAModificar) {
            if (movimiento == "I") {
				movimiento = "Entrada";
            } else if (movimiento == "E") {
				movimiento = "Salida";
            } else if (movimiento == "INV") {
                movimiento = "Inventario";
            } else if (movimiento == "T") {
                movimiento = "Traspaso";
            }

            //Para saber si tiene movimientos normales
            var hasMovimientos = false;
            //Para saber si tiene movimientos de tipo inventario
            var hasInventario = false;
            //Varificar si tiene registros de inventario mezclados con otros no correspondientes
            for (i = 0; i < this.data.length || (hasMovimientos == true && hasInventario == true); i++) {
                //Esto es para que solo se valide contra las demas filas y no la que se esta actualizando
                if (i !== filaAModificar) {
                    //Se verifican los registros de ingreso
                    if (this.data[i][2] == "Inventario"){
                        if (movimiento != null) {
                            if (movimiento != "Inventario"){
                                return false;
                            }
                        }

                        hasInventario = true;
                    } else {
                        if (movimiento == "Inventario"){
                            return false;
                        }
                        hasMovimientos = true;
                    }
                }
            }

            if (hasMovimientos == true && hasInventario == true) {
                return false;
            } else {
                return true;
            }
        },
        //Si recibe la variable movimiento, ademas verificara los registros ingresados con la variable movimiento
        verificarInventarioFinal: function() {
            Ajuste.isInventario = false;

            //Para saber si tiene movimientos normales
            var hasMovimientos = false;
            //Para saber si tiene movimientos de tipo inventario
            var hasInventario = false;
            //Varificar si tiene registros de inventario mezclados con otros no correspondientes
            for (i = 0; i < this.data.length || (hasMovimientos == true && hasInventario == true); i++) {
                //Se verifican los registros de ingreso
                if (this.data[i][2] == "Inventario"){
                    Ajuste.isInventario = true;
                    hasInventario = true;
                } else {
                    hasMovimientos = true;
                }
            }

            if (hasMovimientos == true && hasInventario == true) {
                return false;
            } else {
                return true;
            }
        },
        
        confirmarGuardar: function() {
			if($("fecha").value == ""){
				MSGBOX.AbrirParametrisado(
					'Debe ingresar una fecha',
					{
						titulo: 'Gu&iacute;a de Ajuste',
						botonera: 1,
						fnretorno: 'Ajuste.retornoMSGBOX',
						conCerrar: false
					}
				);
				return;
			}
			var solonum = /^[0-9]*$/;
			var framereferencia = frames["iframe_iFramereferencia"];
			var ndocumento = framereferencia.$("ndocumento").value;
			if(!solonum.test(ndocumento)){
				MSGBOX.AbrirParametrisado(
					'El n&uacute;mero de documento de referencia no cumple con formato v&aacute;lido.',
					{
						titulo: 'Formato No V&aacute;lido',
						botonera: 1,
						fnretorno: 'Ajuste.retornofocusMSGBOX',
						conCerrar: false,
						objeto: {input: framereferencia.$("ndocumento")}
					}
				);
				return;
			}
		
            if (Ajuste.verificarInventarioFinal()) {
               
				<?php if ($origencompra == "T") { ?>
					if (this.verificarTotalDesdeCompra() == false) {
						var framereferencia = frames["iframe_iFramereferencia"];
						var inputDocumento = framereferencia.$("documento");
						var documento	= inputDocumento.options[inputDocumento.selectedIndex].text;
                        <?php 
                        if ($ingresoDesdeLibroCompraSinXML === true) {
                            ?>
						var ndocumento	= framereferencia.$("ndocumento").value;
						var totalDocumento 	= "<?=$total?>" * 1;
						var cadena_sintilde	= documento;	
						var filtro1 = 	cadena_sintilde.replace(/ELECTRONICA/g,"ELECTRÓNICA");	
						var filtro2 = 	filtro1.replace(/CREDITO/g,"CRÉDITO");	
						var cadena = 	filtro2.replace(/DEBITO/g,"DÉBITO");	
						var doc_conector = capitalizeNombreCompleto(cadena);
						var documento2 = 	doc_conector.replace(/De/g,"de");

						MSGBOX.AbrirParametrisado(
							'<br style="line-height: 8px">El monto total de la guía de ajuste no puede ser mayor'
							+ '<br>al monto total del documento referenciado.'
                            + '<br><br><table width="67%"><tr class="tr_lista_fila1" style="font-size:12"><td width="50%">Tipo de documento</td><td style="text-align:center">' + documento2 + '</td></tr> <tr class="tr_lista_fila2" style="font-size:12"><td>Folio</td><td style="text-align:center">' + ndocumento + '</td></tr> <tr class="tr_lista_fila1" style="font-size:12"><td>Monto</td><td style="text-align:center">'  + totalDocumento + '</td></tr></table><br  style="line-height: 15px">'
							,{
								botonera: 1
								,titulo: "Guía de Ajuste"
								,alto : 240
							}
						);               
                            <?php
                        } else {
                            ?>
						var ndocumento	= framereferencia.$("ndocumento").value;
						var totalDocumento 	= "<?=$total?>" * 1;

						MSGBOX.AbrirParametrisado(
							'<br style="line-height: 8px">El monto total de la guía de ajuste generada no puede'
							+ '<br>ser mayor al monto total del documento referenciado'
							+ '<br><br>Tipo de documento: ' + documento + ' Folio: ' + ndocumento
							+ '<br><br>Monto: ' + totalDocumento + '<br><br  style="line-height: 8px">'
							,{
								botonera: 1
								,titulo: "Datos Documento Referencia"
							}
						);
                            <?php
                        }
                            ?>
						return;
					}
				<?php } ?>
                    Ajuste.Guardar();
            } else {
                MSGBOX.AbrirParametrisado(
                    'Guia de ajuste inventario debe tener solo<br>movimientos de tipo inventario'
                    ,{
                        botonera: 1
                        ,titulo: "Ingresar Gu&iacute;a de Ajuste"
                    }
                );
            }
            
        },
		<?php if ($origencompra == "T") { ?>
			verificarTotalDesdeCompra: function() {
				var totalDocumento 	= "<?=$total?>" * 1;
				var totalGuia		= 0;
				for (let index = 0; index < this.data.length; index++) {
					//SE OBTIENE EL TOTAL DE LA LINEA DE DETALLE
					var totaldetalle = this.data[index][5] * 1;
					totalGuia += totaldetalle;
				}
				if (totalGuia > totalDocumento) {
					return false;
				} else {
					return true;
				}
			},
		<?php } ?>
        retornoMSGBOX: function(resp) {
            if (resp == 'S') {
                Ajuste.Guardar();
            }
            MSGBOX.ventana.HideInfo();
        },
        
        retornofocusMSGBOX: function(){
        	try{
	    		if(MSGBOX.ventana.objeto != null){
					var input = MSGBOX.ventana.objeto["input"];
					if(typeof input != "undefined"){
						input.value = "";
						input.focus();
					}
				}
			} catch(ex){}
        	MSGBOX.ventana.HideInfo();
        },
        
        retornoMSGBOXreload: function(){
			window.location.href = window.location.href.substr(0, window.location.href.indexOf("?")) + "?indice=2";
			if (idocguia > 0 ){
				window.location.href = "index.php?indice="+2+"&idoc=";
			}
        },

        variableConfirmarGuardar : true,
        variableConfirmarModificarLinea : true,
		Guardar : function(){
			var argReferencia = new Object();
			var framereferencia = frames["iframe_iFramereferencia"];
			argReferencia = framereferencia.obtenerDatosReferencia();
		
			<?
			if ($SYS_tarjeta == true){
			?>
			if (this.data.length <= 0){
				//alert ("Debe ingresar detalles a la Gu\u00eda de Ajuste.");
				MSGBOX.AbrirParametrisado("Debe ingresar productos a la Gu&iacute;a de Ajuste.", {
					titulo: "Gu&iacute;a de Ajuste"
				} );
				return;
			}
			if ($("observacion").value.length <= 0){
				//alert ("Debe ingresar una Observaci\u00f3n para la Gu\u00eda de Ajuste.");
				//$("observacion").focus ();
			    MSGBOX.AbrirParametrisado("Debe ingresar una descripci&oacute;n a la Gu&iacute;a de Ajuste.",{
					titulo: "Gu&iacute;a de Ajuste",
					fnretorno: "MSGBOXfocusElemento",
					objeto: {"elementofocus": $("observacion") }
				});
				return;
			}
            
            if (!Ajuste.isInventario) {
                if (Ajuste.variableConfirmarGuardar == true) {
    			    MSGBOX.AbrirParametrisado("¿Está seguro(a) que desea ingresar esta Guía de Ajuste?.",{
    					titulo: "Gu&iacute;a de Ajuste",
    					fnretorno: "MSGBOXConfirmarGuardarGuia",
    					botonera: 3
    				});
    				return;
    	        }
    	        
            }
            Ajuste.variableConfirmarGuardar = true;
            var idsucursalstorage = window.localStorage.getItem('idsucursalstorage');
            for (i = 0; i < this.data.length; i++) {
				this.data[i][1] = encodeURIComponent(this.data[i][1].replace(/\\/g, '').replace(/"/g, '\\"')); //Descripción
            }
			<?php if($ingresoDesdeLibroCompraSinXML === true){ ?>
					var vinculardoccompra = <?=$lc_iddocumento*1; ?>;
			<?php } else {?>
					var vinculardoccompra = 0;
			<?php } ?>
			
			var connection = new Ajax.Request("server.php" , {
				method			: 'post',
				postBody 		: "&cmd=setDetalle&Ajuste="+Object.toJSON({
				observacion 	: $("observacion").value.trim(),
				fecha 			: $("fecha").value,
				data 			: this.data
				,estado			: argReferencia.estado
				,tipodte		: argReferencia.tipodte
				,nombretipodte	: argReferencia.nombretipodte
				,nrodocumento	: argReferencia.nrodocumento
				,fechavalidada	: fechaValidada
                ,vinculardoccompra : vinculardoccompra
				}).replace (/&/g, "^")
				+ "&razonreferencia=" + argReferencia.razonreferencia
				+ "&idsucursalstorage=" + idsucursalstorage,
				
				encoding   		: "ISO-8859-1",
				asynchronous  	: true,
				
				onSuccess : function(transport){
					var request = transport.responseText;
					if (request.indexOf ("ERROR") != -1) {
						if (request.indexOf("módulo de prueba") > -1 ) {
							MSGBOX.AbrirParametrisado(
								"<br><br>No es posible realizar acci&oacute;n debido a que se encuentra en m&oacute;dulo de prueba<br><br><br>"
								,{
									botonera:	1
									,titulo		: "Acci&oacute;n no Permitida"
								}
							);
						}else if(request.indexOf("Faltan numeros de serie por asignar") > -1){
							MSGBOX.AbrirParametrisado(
								'Los códigos de Serie son Campos Obligatorios'
								,{
									botonera: 1,
									titulo: "Ingresa Campo Obligatorio"
								}
							);
						}else{
						    alert (request);
						}
						return;
					} else {
						titulo = 'Error Guía de Ajuste';
						if (request.indexOf("Formato no válido para Número de documento.") > -1 ) {
							titulo = 'Formato Incorrecto';
						}
						if (request.indexOf("ERROR: Debe ingresar una fecha") > -1 ) {
							titulo = 'Guía de Ajuste';
						}
						
						txtmsgbox = request;
						if(/^[0-9]*$/.test(request)){
							titulo = 'Registro Guardado';
							txtmsgbox = 'Se ha ingresado correctamente la Gu&iacute;a de Ajuste N&ordm; ' + request;
						}
						<?php if($ingresoDesdeLibroCompraSinXML === true) { ?>
							MSGBOX.AbrirParametrisado(
								txtmsgbox,
								{
						
									botonera: 1,
									titulo : "Guía de Ajuste",
									fnretorno: "Ajuste.finalizarGuardarCompraSinXML",
									conCerrar : false
								
								}
							);
						<?php } else { ?>
						MSGBOX.AbrirParametrisado(
							txtmsgbox,
							{
								titulo: titulo,
								botonera: 1,
								fnretorno: 'Ajuste.retornoMSGBOXreload',
								conCerrar: false
							}
						);
						<?php } ?>
						return;

					}
				},
				onFailure : function(){
					alert("ERROR: Error no controlado por la aplicaci\u00f3n. [Ajuste]")
				}
			});
			<? }  else { ?>
				alert ("La empresa no tiene habilitado el m\u00f3dulo de Tarjeta de Existencias\r\nNo es posible continuar con esta solicitud.");
			<? } ?>
		},
		finalizarGuardarCompraSinXML : function (resp){
			parent.location.reload();
		},
		verificarExistencias: function() {
			<?php if ($SYS_CENTROCOSTO) { ?>
				var tieneCC = 1;
			<?php } else { ?>
				var tieneCC = 0;
			<?php } ?>
			if (this.data.length > 0) {
				var idproductos = "";
				var cantidadsolicitada = "";
				var idbodega = "";
				for (i = 0; i < this.data.length; i++) {
					if ((this.data[i][2] == "Salida") || this.data[i][2] == "Traspaso") {
						if(this.data[i][2] == "Salida") {
							if(this.data[i][13+tieneCC] === undefined) {
								idproductos += idproductos == "" ? this.data[i][8+tieneCC] : ","+this.data[i][8+tieneCC];
							} else {
								idproductos += idproductos == "" ? this.data[i][13+tieneCC] : ","+this.data[i][13+tieneCC];	
							}
							this.data[i][3] = this.data[i][3].toString();
							cantidadsolicitada += cantidadsolicitada == "" ? this.data[i][3].replace(/[^\d-.]/g, '') : ","+this.data[i][3].replace(/[^\d-.]/g, '');
							idbodega += idbodega == "" ? this.data[i][9+tieneCC] : ","+this.data[i][9+tieneCC];
						} else {
							if (this.data[i][2] == "Traspaso" && (this.data[i][12+tieneCC] == "TO" || this.data[i][14+tieneCC] == "TO")) {
									if (this.data[i][13+tieneCC] == "") {
										idproductos += idproductos == "" ? this.data[i][8+tieneCC] : ","+this.data[i][8+tieneCC];
									} else {
										idproductos += idproductos == "" ? this.data[i][13+tieneCC] : ","+this.data[i][13+tieneCC];
									}
									this.data[i][3] = this.data[i][3].toString();
									cantidadsolicitada += cantidadsolicitada == "" ? this.data[i][3].replace(/[^\d-.]/g, '') : ","+this.data[i][3].replace(/[^\d-.]/g, '');
									idbodega += idbodega == "" ? this.data[i][9+tieneCC] : ","+this.data[i][9+tieneCC];
							}	
		}
	}
				}
				var BD = new BASE();
				BD.commandFile = "form/existencias/command.php";
				var RS = BD.send("cmd=verificarExistencias&idproducto="+idproductos+"&cantidad="+cantidadsolicitada+"&idbodega="+idbodega);
				if (RS.numrows > 0) {
					Ajuste.ventana.ShowInfoAsignacionBodega("<?=$POSICION?>form/msg_controlexistencia.php?accioncomercial=<?=$accionajuste?>"+
														"&modulo=guiaajuste"+
														"&btncerrar=parent.Ajuste.ventana.HideInfo()"+
														"&btnaceptar=parent.Ajuste.confirmarGuardar()"+
														"&idproducto="+idproductos+"&cantidad="+cantidadsolicitada+"&idbodega="+idbodega, "Ajuste.ventana.HideInfo", "350px", "700px");
				} else {
					Ajuste.confirmarGuardar();
		}
			} else {
				Ajuste.confirmarGuardar();
			}
		}
	}
	Producto.initialize();
	Ajuste.initialize();
			
//************************************************************************************************************************************************************
	
	function activar_bodega()
	{

		var bodegadef 				= <?=$idbodegadefault*1?>;
		$('I_checkbodega').checked 	= true;
		$('I_codigobodega').value 	= bodegadef;
		Bodega.Paso_bodega(bodegadef,'',true);
	}
	
	function deshabilitar_bodega()
	{
		$('I_checkbodega').disabled 	= true;
		$('I_checkbodega').checked 		= false;
		$('I_codigobodega').value 		= '';
		$('I_codigobodega').disabled 	= true;
		$('agregarbodega').disabled 	= true;
		$('bodegatab').hide();
	}
	//Si proviene de Orden de Compra
	if (idocguia > 0 ){
		Ajuste.adddetallemanual();
		$("BTN_guarda").disabled=false;
	}

	<?php IF($SYS_Bodega == TRUE): ?>
	modbodega = 1;
	activar_bodega();
	<?php ELSE: ?>
	deshabilitar_bodega();
	modbodega = 0;
	<?php ENDIF; ?>
	
	
	function modificarCamposFechaLote() {
        let comboFechaCaducidad             = $('comboFechaCaducidad');
		let comboLote                       = $("comboLote");
		comboFechaCaducidad.innerHTML       = '';
		comboLote.innerHTML                 = '';
		let txtFechaCaducidad = $("txtFechaCaducidad");
		let txtLote = $("txtLote");
	    txtFechaCaducidad.value = "";
		txtLote.value = "";
		$("stockLoteFechaInput").value = "";
		$("stockLoteFechaOrigen").value = "";
		$("comboFechaCaducidadOrigen").innerHTML       = '';
		$("comboLoteOrigen").innerHTML       = '';
		$("txtFechaCaducidadDestino").value = "";
		$("txtLoteDestino").value = "";

        if ($("perecible").value ==  "t") {
			comboFechaCaducidad.removeAttribute("disabled");
			txtFechaCaducidad.removeAttribute("disabled");
			comboLote.removeAttribute("disabled");
			$("comboFechaCaducidadOrigen").removeAttribute("disabled");
			
			txtLote.removeAttribute("readonly");
			txtLote.className = 'txt_editable';
			if ($("fechaCaducidadObligatoria").value) {
			    comboFechaCaducidad.className = 'txt_obligatorio';
			    txtFechaCaducidad.className = 'txt_obligatorio';
			    $("comboFechaCaducidadOrigen").className = 'txt_obligatorio';
			} else {
			 	comboFechaCaducidad.className = 'txt_editable';
			    txtFechaCaducidad.className = 'txt_editable';
			    $("comboFechaCaducidadOrigen").className = 'txt_editable';
			}
		    if ($('tipomovimiento').value != "I") {
		        cargarFechaLoteBodega();  
		    }
        } else if ($("perecible").value == "f" || $("perecible").value == "") {
            comboFechaCaducidad.setAttribute("disabled", true);
            $("comboFechaCaducidadOrigen").setAttribute("disabled", true);
            txtFechaCaducidad.setAttribute("disabled", true);
            $("txtFechaCaducidadDestino").setAttribute("disabled", true);
            
			comboFechaCaducidad.className = 'txt_no_editables';
			txtFechaCaducidad.className = 'txt_no_editables';
			$("txtFechaCaducidadDestino").className = 'txt_no_editables';
			$("comboFechaCaducidadOrigen").className = 'txt_no_editables';
			
			txtLote.setAttribute("readonly", true);
			txtLote.className = 'txt_no_editables';
			comboLote.setAttribute("disabled", true);
        }
    	if($("asignarlote").value ==  "t" && $('tipomovimiento').value == "I"){
    		txtLote.removeAttribute("readonly");
			txtLote.className = 'txt_editable';
			if($("checkperecible").value !=  "t"){
				comboFechaCaducidad.setAttribute("disabled", true);
	            $("comboFechaCaducidadOrigen").setAttribute("disabled", true);
	            txtFechaCaducidad.setAttribute("disabled", true);
	            $("txtFechaCaducidadDestino").setAttribute("disabled", true);
	            
				comboFechaCaducidad.className = 'txt_no_editables';
				txtFechaCaducidad.className = 'txt_no_editables';
			}
    	}
	}

    function cargarFechaLoteBodega() {

        if ($('tipomovimiento').value == "E" || $('tipomovimiento').value == "INV" ) {
            if ($('I_codigobodega').value == "") {
                return;    
            }
            if ($('I_idproducto').value == "") {
                return;    
            }
            let comboFechaCaducidad       = $('comboFechaCaducidad');
			let comboLote                 = $("comboLote");
            if (comboFechaCaducidad.options.length > 0) {
        		comboFechaCaducidad.innerHTML       = '';
        		comboLote.innerHTML                 = '';
            }

			let BDFechaCaducidad 			= new BASE();
			BDFechaCaducidad.commandFile 	= "form/existencias/command.php";
			var RS = BDFechaCaducidad.send("&cmd=obtenerFechadeCaducidadYLote&idproducto="+$('I_idproducto').value+"&idbodega="+$('I_codigobodega').value);
			if (RS.error == "" && RS.numrows > 0){
				for(i = 0; i < RS.numrows; i++){
					let fechacaducidad = BDFechaCaducidad.getField (RS,"fechacaducidad",i);
					let comboLoteCaducidad = BDFechaCaducidad.getField (RS,"lote",i);
					let txtstock = BDFechaCaducidad.getField (RS,"cantidad",i);
					let option = document.createElement("option");
					option.value = fechacaducidad;
					option.setAttribute("data-lote", comboLoteCaducidad);
					option.setAttribute("data-stock", txtstock);
					option.text = fechacaducidad;
					
					comboFechaCaducidad.appendChild(option);
					
				}
			    setLoteStock(comboFechaCaducidad, "");
		    } else {
	          	let option = document.createElement("option");
				option.value = "No Registrada";
				option.setAttribute("data-lote", "No Registrada");
				option.setAttribute("data-stock", "0");
				option.text = "No Registrada";
				comboFechaCaducidad.appendChild(option);
				setLoteStock(comboFechaCaducidad, "");
		    }
        } 
        if ($('tipomovimiento').value == "T") {
            if ($('I_codigobodega_o').value == "") {
                return;    
            }
            if ($('I_idproducto').value == "") {
                return;    
            }
            let comboFechaCaducidadOrigen       = $('comboFechaCaducidadOrigen');
			let comboLoteOrigen                 = $("comboLoteOrigen");
            if (comboFechaCaducidadOrigen.options.length > 0 || comboLoteOrigen.options.length > 0) {
        		comboFechaCaducidadOrigen.innerHTML       = '';
        		comboLoteOrigen.innerHTML                 = '';
            }

			let BDFechaCaducidad 			= new BASE();
			BDFechaCaducidad.commandFile 	= "form/existencias/command.php";
			var RS = BDFechaCaducidad.send("&cmd=obtenerFechadeCaducidadYLote&idproducto="+$('I_idproducto').value+"&idbodega="+$('I_codigobodega_o').value);
			if (RS.error == "" && RS.numrows > 0){
				for(i = 0; i < RS.numrows; i++){
					let fechacaducidad = BDFechaCaducidad.getField (RS,"fechacaducidad",i);
					let comboLoteCaducidad = BDFechaCaducidad.getField (RS,"lote",i);
					let txtstock = BDFechaCaducidad.getField (RS,"cantidad",i);
					let option = document.createElement("option");
					option.value = fechacaducidad;
					option.setAttribute("data-lote", comboLoteCaducidad);
					option.setAttribute("data-stock", txtstock);
					option.text = fechacaducidad;
					
					comboFechaCaducidadOrigen.appendChild(option);
					
				}
			    setLoteStock(comboFechaCaducidadOrigen, "T");
		    } else {
	          	let option = document.createElement("option");
				option.value = "No Registrada";
				option.setAttribute("data-lote", "No Registrada");
				option.setAttribute("data-stock", "0");
				option.text = "No Registrada";
				comboFechaCaducidadOrigen.appendChild(option);
				setLoteStock(comboFechaCaducidad, "T");
		    }
        }
           
    }

    function setLoteStock(combo, tipo) {
        if (tipo == "") {//Salida o Inventario
            var selectedOption = combo.options[combo.selectedIndex];
            let valorlote = selectedOption.getAttribute("data-lote");
            let valorstock = selectedOption.getAttribute("data-stock");
            
            let comboLote                 = $("comboLote");
            comboLote.innerHTML = '';
			option = document.createElement("option");
			option.value = valorlote;
			option.text = valorlote;
			comboLote.appendChild(option);
			$("stockLoteFechaInput").value = valorstock;
        }
        if (tipo == "T") {//Salida o Inventario
            var selectedOption = combo.options[combo.selectedIndex];
            let valorlote = selectedOption.getAttribute("data-lote");
            let valorstock = selectedOption.getAttribute("data-stock");
            
            let comboLote                 = $("comboLoteOrigen");
            comboLote.innerHTML = '';
			option = document.createElement("option");
			option.value = valorlote;
			option.text = valorlote;
			comboLote.appendChild(option);
			$("stockLoteFechaOrigen").value = valorstock;
			if ($("I_codigobodega_d").value != "") {
			    $("txtLoteDestino").value = valorlote;
			    $("txtFechaCaducidadDestino").value = $('comboFechaCaducidadOrigen').value;
			}
        }
    }


	
	function activabodegas (tmovimiento, guardarpreferenciasguia){
        if (tmovimiento == "I" || tmovimiento == "E" || tmovimiento == "INV") {
            $("bodegatab").setAttribute("data-tipo-movimiento",tmovimiento);
			$("bodegatab").style.display = "";
			$("bodegatab_origen").style.display = "none";
			$("bodegatab_destino").style.display = "none";
			$("c_chkbodega").style.display = "";
			$("t_chkbodega").style.display = "";
			if (tmovimiento == "INV") {
			     <?php if ($SYS_CENTROCOSTO) { ?>
				$("ctrcto").style.display = "none";
				$("centro").disabled = true;
				$("centro").checked = false;
				$("btnBorrarcc").click();
				<?php } ?>
			} else {
			    <?php if ($SYS_CENTROCOSTO) { ?>
				if ($("centro").checked) {
					$("ctrcto").style.display = "";
				}
				$("centro").disabled = false;
				<?php } ?>
			}
		}else if (tmovimiento == "T" ){
			$("bodegatab").style.display = "none";
			$("bodegatab_origen").style.display = "";
			$("bodegatab_destino").style.display = "";
			$("c_chkbodega").style.display = "none";
			$("t_chkbodega").style.display = "none";
			<?php if ($SYS_CENTROCOSTO) { ?>
			$("ctrcto").style.display = "none";
			$("centro").disabled = true;
			$("centro").checked = false;
			$("btnBorrarcc").click();
			<?php } ?>
		}
        
		if(tmovimiento != "I" && tmovimiento != "INV"){
			$("td_nserie_label").style.display = 'none';
			$("td_nserie_btn").style.display = 'none';
		}else{
			$("td_nserie_label").style.display = '';
			$("td_nserie_btn").style.display = '';
			validarActivarNSerie($("asignarserie").value);
		}
        if(guardarpreferenciasguia == true) {
          var BDguardarPreferencias =  new BASE();
		  BDguardarPreferencias.commandFile = "form/existencias/command.php";
		  var nombremov = 'TIPO_MOVIMIENTO_GUIAAJUSTE';
		  var RS = BDguardarPreferencias.send("cmd=preferenciaTipoMov&tipomov="+tmovimiento+"&nombremov="+nombremov);
        }
		checkCantidad();
		try{
	        modificarCamposFechaLote();
	    } catch (ex){}
	}
	
	function msgAccionAbrirBuscador(resp) { 
		if (resp == "S") {
			Ajuste.Buscador();
		}
		MSGBOX.ventana.HideInfo();
	}

	function msgAccionNoInventariable(resp) { 
		if (resp == "S") {
			var BD 			= new BASE();
			BD.commandFile 	= "form/existencias/command.php";
			var RS 			= BD.send("&cmd=cambiarAInventariable&id="+Ajuste.idProducto);

			if (RS.error != "") {
				MSGBOX.AbrirParametrisado(
					error
					,{
						botonera	: 1
						,titulo		: "Resultados del Código de Producto"
					}
				);
			}
			
			$("I_codigo").value 		= Ajuste.codproducto;
			$("I_descripcion").value 	= Ajuste.descripcion;
			$("I_idproducto").value 	= Ajuste.idProducto;
            $("I_preciounitario").value = Ajuste.preciocostounitario < 0 ? "" : Ajuste.preciocostounitario;
			$("I_cantidad").value 		= "0";
            $("I_montoitem").value 		= "0";

		}
		MSGBOX.ventana.HideInfo();
	}
	
    <?php if ($SYS_CENTROCOSTO == true) { ?>
    var centrocosto = new CENTROCOSTO();
    
    //centrocosto.parentClass             = this;
    centrocosto.POSICION                = POSICION;
    centrocosto.codigo_c                = document.formulario.c_codigo;
    centrocosto.descripcion_c           = document.formulario.c_descripcion;
    centrocosto.descripcion_c.maxLength = 80;
    centrocosto.categoria               = document.formulario.c_categoria;
    centrocosto.area                    = document.formulario.c_area;
    centrocosto.monto                   = document.formulario.c_monto;
    //producto.cc                         = centrocosto;
	centrocosto.id                      = document.formulario.idtemporal_cc;
	centrocosto.trae                      = true;
	centrocosto.islightbox 				= true;
	centrocosto.New();
	
	function respuesta_keybox_cc(resp) {
		if (resp == 't') {
			if (modbodega == 1) {
				//Modulo de Bodegas Activo
				Bodega.Paso_bodega($('I_codigobodega').value);
			
				if ($('I_codigobodega').value != "") {
					Ajuste.addDetalle();
				}
			} else {
				//Solo Tarjeta (Sin Bodega)
				Ajuste.addDetalle();
			}
			fechaValidada = "true";
			KEYBOX_CC.ventana.HideInfo();
		} else if (resp == "c") {
			KEYBOX_CC.ventana.HideInfo();
		}

	}

	function cerrarKEYBOX_CC() {
		KEYBOX_CC.ventana.HideInfo();
		identificador_KEYBOX = "";
	}

	var KEYBOX_CC = {
		ventana 	: typeof IU_KEYBOX,
		initialize 	: function() {
			this.ventana = new IU_KEYBOX ();
		},
		Abrir : function(conf){
			this.ventana.url 		     = "<?=$POSICION?>";		
			this.ventana.fnretorno	     = 'respuesta_keybox_cc';
			this.ventana.texto           = conf.texto   || '<b>Esta operaci&oacute;n requiere que ingrese nuevamente sus credenciales de acceso.<b>';
			this.ventana.titulo          = conf.titulo  || 'Clave de Usuario';
			this.ventana.alto            = 240;
			this.ventana.cerrar          = "cerrarKEYBOX_CC";
			this.ventana.Keybox();
			identificador_KEYBOX = "KEYBOX_CC";

			$('MSGBOX_').style.zIndex    = 100000;
			$('MSGBOX_OL_').style.zIndex = 99999;

		}
	}
	KEYBOX_CC.initialize();
	<?php } ?>
	
	function comprobarCheck() {
		if(document.formulario.centro.checked) {
			$('c_monto').value = $('I_cantidad').value * $('I_preciounitario').value
			document.getElementById("ctrcto").style.display= 'block';					
		} else {
			var id = centrocosto.id.value;
			var idtemporaldetalle = "";
			//var idtemporaldetalle = Detalle.idtemporaldetalle;
			if (idtemporaldetalle != "" && id != "") {
				if(confirm("Este Producto tiene un Centro de Costo asociado, Desea Eliminarlo?")){
					centrocosto.codigo_c.value = '';
					centrocosto.descripcion_c.value= '';
					centrocosto.categoria.value = '';
					centrocosto.area.value = '';
					centrocosto.monto.value = 0;
					document.getElementById("ctrcto").style.display= 'none';
				} else {
					Detalle.producto.centro.checked = true;
				}
			} else {
				document.getElementById("ctrcto").style.display= 'none';
			}
		}
	}
	function checkCantidad() {

		<?php if ($SYS_Bodega == TRUE) { ?>
        var inputorigen = $("I_cantidad");
        var accion = <?=$accionajuste?>;
        if(($("tipomovimiento").value == "I" || $("tipomovimiento").value == "INV") && !validarNSerieCantidad()){
        	return false;
        }
        if (accion != "") {
			if (($("tipomovimiento").value == "T") || ($("tipomovimiento").value == "E" && $("I_checkbodega").checked == true)) {
				var idproducto = $("I_idproducto").value;
				if ($("tipomovimiento").value == "T") {
					var idbodega = $("I_codigobodega_o").value;
				} else {
					var idbodega = $("I_codigobodega").value;
				}
				
				if (idproducto != "" && idbodega != "") {
					var BD 			= new BASE();
					BD.commandFile 	= "form/existencias/command.php";

					var RS = BD.send("cmd=getStock&idproducto="+idproducto+"&idbodega="+idbodega);
					
					if (RS.numrows > 0) {
						isInventariable = BD.getField(RS, "inventariable", 0);
						$("stock").value = BD.getField(RS, "stock", 0);
					} else {
						isInventariable = "f";
						$("stock").value = 0;
					}
				} else {
					isInventariable = "f";
					$("stock").value = 0;
				}

				if ($("stock").value - inputorigen.value < 0 && isInventariable == "t") {
					if (accion == 1) {
						inputorigen.style.background = "#FFF200";
						$("mensajealerta").style.display = "";
					} else if (accion == 2) {
						inputorigen.style.background = "#FF0000";
						$("mensajealerta").style.display = "";
					}
				} else {
					inputorigen.style.background = "#E0F4FE";
					$("mensajealerta").style.display = "none";
				}
			} else {
				inputorigen.style.background = "#E0F4FE";
				$("mensajealerta").style.display = "none";
			}
        } else {
            inputorigen.style.background = "#E0F4FE";
            $("mensajealerta").style.display = "none";
		}
		<?php } else { ?>
			return;
		<?php } ?>
	}
	if ($("centro").checked) {
		comprobarCheck();
	}
<?php if($isejecutartutorial === true) { ?>
	document.getElementById("tipomovimiento").value = "I";
	activabodegas(document.getElementById("tipomovimiento").value, false);
	try {
		document.getElementById("div-contenedor-tooltip-inicio").style.marginLeft = top.document.getElementById("link_3951").getBoundingClientRect().left+10;
	} catch (ex) {}

	try {
		firstTooltip('tool1');
	} catch (ex) {}

	
	
	function mostrarOcultarBackground(tool, mostrar_ocultar){
		let backgroundElement;
		switch (tool) {
			case 'tool2':
				backgroundElement = document.getElementById('background_tool2');
				if(mostrar_ocultar){
					document.getElementById('td_descripcion').classList.add('encimaDeTodo');
					document.getElementById('td_descripcion_input').classList.add('encimaDeTodo');
					document.getElementById('observacion').classList.add('bloqueado');
				}else{
					document.getElementById('td_descripcion').classList.remove('encimaDeTodo');
					document.getElementById('td_descripcion_input').classList.remove('encimaDeTodo');
					document.getElementById('observacion').classList.remove('bloqueado');
				}
				break;

			case 'tool3':
				backgroundElement = document.getElementById('background_tool3');
				if(mostrar_ocultar){
					document.getElementById('td_producto').classList.add('encimaDeTodo');
					document.getElementById('I_codigo').classList.add('encimaDeTodo', 'bloqueado');
					document.getElementById('BTN_busca').classList.add('encimaDeTodo', 'bloqueado');
					document.getElementById('BTN_nuevo').classList.add('encimaDeTodo', 'bloqueado');
					
				}else{
					document.getElementById('td_producto').classList.remove('encimaDeTodo');
					document.getElementById('I_codigo').classList.remove('encimaDeTodo', 'bloqueado');
					document.getElementById('BTN_busca').classList.remove('encimaDeTodo', 'bloqueado');
					document.getElementById('BTN_nuevo').classList.remove('encimaDeTodo', 'bloqueado');
				}
				break;

			case 'tool4':
				backgroundElement = document.getElementById('background_tool4');
				if(mostrar_ocultar){
					document.getElementById('td_bodega').classList.add('encimaDeTodo');
					document.getElementById('I_codigobodega').classList.add('encimaDeTodo', 'bloqueado');
					document.getElementById('agregarbodega').classList.add('encimaDeTodo', 'bloqueado');
				}else{
					document.getElementById('td_bodega').classList.remove('encimaDeTodo');
					document.getElementById('I_codigobodega').classList.remove('encimaDeTodo', 'bloqueado');
					document.getElementById('agregarbodega').classList.remove('encimaDeTodo', 'bloqueado');
				}
				break;
			
			case 'tool5':
				backgroundElement = document.getElementById('background_tool5');
				if(mostrar_ocultar){
					document.getElementById('td_tipomovimiento').classList.add('encimaDeTodo');
					document.getElementById('tipomovimiento').classList.add('encimaDeTodo', 'bloqueado');
					document.getElementById('tipomovimiento').disabled = true;
				}else{

					document.getElementById('td_tipomovimiento').classList.remove('encimaDeTodo');
					document.getElementById('tipomovimiento').classList.remove('encimaDeTodo', 'bloqueado');
					document.getElementById('tipomovimiento').disabled = false;
				}
				break;
			
			case 'tool9':
				backgroundElement = document.getElementById('background_tool9');
				if(mostrar_ocultar){

					document.getElementById('td_cantidad').classList.add('encimaDeTodo');
					document.getElementById('I_cantidad').classList.add('encimaDeTodo', 'bloqueado');
				}else{

					document.getElementById('td_cantidad').classList.remove('encimaDeTodo');
					document.getElementById('I_cantidad').classList.remove('encimaDeTodo', 'bloqueado');
				}
				break;

			case 'tool10':
				backgroundElement = document.getElementById('background_tool10');
				if(mostrar_ocultar){
					
					document.getElementById('td_preciocostounitario').classList.add('encimaDeTodo');
					document.getElementById('I_preciounitario').classList.add('encimaDeTodo', 'bloqueado');
				}else{

					document.getElementById('td_preciocostounitario').classList.remove('encimaDeTodo');
					document.getElementById('I_preciounitario').classList.remove('encimaDeTodo', 'bloqueado');
				}
				break;
			
			case 'tool11':
				backgroundElement = document.getElementById('background_tool11');
				if(mostrar_ocultar){
					
					document.getElementById('BTN_guarda').classList.add('encimaDeTodo', 'bloqueado');
				}else{

					document.getElementById('BTN_guarda').classList.remove('encimaDeTodo', 'bloqueado');
				}
				break;
			case 'tool12':
				if(mostrar_ocultar){
					document.getElementById('boton-ayuda').classList.add('bloqueado');
					document.querySelector("table[name=maintable]").style.position = 'relative';
					document.querySelector("table[name=maintable]").style.zIndex = '5001';	
				}else{
					document.getElementById('boton-ayuda').classList.remove('bloqueado');
					document.querySelector("table[name=maintable]").style.position = 'static';
					document.querySelector("table[name=maintable]").style.zIndex = '0';
				}
				break;
			case 'eliminarEstilos':
				let arrayEstilos = ["backgroundTool", "encimaDeTodo", "bloqueado"];
				for (let i = 0; i < arrayEstilos.length; i++) {
					let estilotemp = arrayEstilos[i];
					let listbackground = document.getElementsByClassName(estilotemp);
					for (let j = 0; j < listbackground.length; j++) {
						listbackground[j].classList.remove('backgroundTool');
					}
				}

				document.querySelector("table[name=maintable]").style.position = 'static';
				document.querySelector("table[name=maintable]").style.zIndex = '0';
				document.getElementById('tipomovimiento').disabled = false;
				break;
		}
		if (backgroundElement != null) {
			if(mostrar_ocultar){
				backgroundElement.classList.add('backgroundTool');
			} else {
				backgroundElement.classList.remove('backgroundTool');
			}
		}

	}
<?php } ?>
	function muestraTutorial(){
	    HideDiv();
	    let BDtutorial 			= new BASE();
		BDtutorial.commandFile 	= "form/existencias/command.php";
		var RS = BDtutorial.send("&cmd=forzarmostrartutorialguiaajuste");
	    console.log();
	    setTimeout(function()  {
	        location.reload();
	    }, 100);
	}
	function alertDescontinuado(){
        MSGBOX.AbrirParametrisado(
            "No se puede seleccionar el producto, está descontinuado. <br> ¿Desea mostrar el buscador?",{
            titulo : 'Confirmar Acción',
            botonera: 3,
            fnretorno: 'fnRetornoDescontinuado'
        });
	}
	
	function fnRetornoDescontinuado(resp){
        if(resp == 'S'){
			Ajuste.Buscador()
		}
		MSGBOX.ventana.HideInfo()
    }

	function recalcularobtenerpreciounitario(elemento, idproducto){
		let BDpreciopmp 			= new BASE();
		BDpreciopmp.commandFile 	= "form/existencias/command.php";
		elemento.disabled = false;
		elemento.className = "txt_obligatorio";
		debugger;
		var RSpreciopmp = BDpreciopmp.send("&cmd=obtenerpreciopmpmatprima&idproducto="+idproducto);
		if(RSpreciopmp.error == ''){
			let cantidadmateriales = BDpreciopmp.getField (RSpreciopmp,"cantidad",0);
			let preciopmpconjunto = BDpreciopmp.getField (RSpreciopmp,"precioconjunto",0);
			if(cantidadmateriales > 0){
				elemento.value = preciopmpconjunto;
				elemento.disabled = true;
				elemento.className = "txt_no_editables";
			}
			
		}
		
	}
	function lightboxNSerie(){
		let idproducto  = $("I_idproducto").value;
		let codproducto = $("I_codigo").value;
		let descripcion = $("I_descripcion").value;
		let cantidad    = $("I_cantidad").value;

		let url = "<?=$POSICION?>form/existencias/nserie.php?idproducto="+idproducto+"&codproducto="+encodeURIComponent(codproducto)+"&descripcion="+encodeURIComponent(descripcion)+"&cantidad="+cantidad;
		for (i = 0; i < cadenaNSerie.length; i++) {
			url += "&nserie[]=" + encodeURI(cadenaNSerie[i]).replace(/#/g, "%23").replace(/&/g, "%26").replace(/\+/g, "%2B");
		}
		
		let ancho = "800px";
		let alto = "430px";

		if (navigator.userAgent.indexOf("Chrome") !== -1 && navigator.userAgent.indexOf("Edge") === -1) {
			if(window.innerHeight < 430){
				alto = "380px";
			}
		}
	
		Ajuste.ventana.ShowInfo(url, 'Ajuste.ventana.HideInfo',alto,ancho);

		if(window.innerHeight <= 470){
			$('lightbox').style.top = '0px';
		}
	}
	function validarNroSerieObligatorio(){
		if($("tipomovimiento").value == "I"|| $("tipomovimiento").value == "INV"){
			if($('nroserieobligatorio').value == 't'){
				let cantidad = $("I_cantidad").value;
				
				if(cadenaNSerie.length != cantidad){
					MSGBOX.AbrirParametrisado(
						'Los códigos de Serie son Campos Obligatorios'
						,{
							botonera: 1,
							titulo: "Ingresa Campo Obligatorio"
						}
					);
					return false;
				} 
			}
		}else{
			cadenaNSerie = [];
			cantidadInicial = '';
		}

		return true;
	}
	function validarActivarNSerie(activar){
		if(activar == "t"){
			$("btnNserie").onclick = function () {
				lightboxNSerie();
			}
			$("btnNserie").setAttribute("src", "<?=$POSICION?>img/ico/edit_add.png")
			$("btnNserie").style.cursor = "pointer";
		}else{
			$("btnNserie").setAttribute("src", "<?=$POSICION?>img/ico/edit_add_gris.png")
			$("btnNserie").onclick = null;
			$("btnNserie").style.cursor = "";
			cadenaNSerie = [];
			cantidadInicial = '';
		}
	}
	function validarNSerieCantidad()
	{
		let largoSerieArray = cadenaNSerie.length;
		let cantidad = $("I_cantidad").value;
		if(largoSerieArray > 0 && $('nroserieobligatorio').value == 't'){
			if(largoSerieArray > cantidad){
				MSGBOX.AbrirParametrisado(
					'La cantidad de N° de Series registradas es mayor a la cantidad de<br>productos ingresados, por lo que se borrarán los N° de Series seleccionados.<br><br>¿Desea continuar?',
					{
						titulo : 'Cantidad Modificada',
						botonera: 3,
						fnretorno: 'respSerie',
						conCerrar: false
					}
				);
				return false;
			}
		}
		
		return true;
	}
	
	function respSerie(resp)
	{
		MSGBOX.ventana.HideInfo()
		if(resp == "S"){
			cadenaNSerie = [];
			cantidadInicial = "";
		}else{
			$("I_cantidad").value = cantidadInicial;
			checkCantidad();
			Ajuste.Calcular()
		}
	}
</script>
