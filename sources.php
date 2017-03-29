<?php
// Abrir archivo de sources
$jsources = file_get_contents('sources.json');
$sources = json_decode($jsources, true);

// parametro format: plain|html|qgis js???????json???
$format = $_GET['format'];
$type = $_GET['type'];
$id = $_GET['id'];

$clase = '';


function getLink($url, $text) {
    echo '<a href="' .$url .'" target="_blank">' .$text .'</a><br />';
}

function getRowOrganismo($nodo) {
	global $clase;
	//Organismo
	echo '<tr>';
	echo '<td class="' .$clase .'"></td>';
	echo '<td class="' .$clase .'">' .$nodo['title'] .'</td>';
	//mapa Interactivo
	echo '<td class="' . $clase . '">';
	echo ($nodo['viewer'] ? getLink($nodo['viewer'], 'Abrir') : '');
	echo '</td>';
	//geoservicios
	echo '<td class="' . $clase . '">';
	echo ($nodo['wms'] ? getLink($nodo['wms'], 'WMS') : '');
	echo ($nodo['wfs'] ? getLink($nodo['wfs'], 'WFS') : '');
	echo ($nodo['csw'] ? getLink($nodo['csw'], 'CSW') : '');
	echo '</td>';
	//catalogo
	echo '<td class="' . $clase . '">';
	echo ($nodo['catalog'] ? getLink($nodo['catalog'], 'Abrir') : '');
	echo '</td>';
	//portal datos Abiertos
	echo '<td class="' . $clase . '">';
	echo ($nodo['portal'] ? getLink($nodo['portal'], 'Abrir') : '');
	echo '</td>';
	//cierro organismo
	echo '</tr>';
}


// Responder peticion plana (cron)
if ($format == 'plain') {

	foreach ($sources as $nombre => $nodos) {
		//echo "-- nom:".$nombre." nod:".$nodos;
		foreach ($nodos as $nodo) {
				echo $nodo['id'] . "," ;

				if ($type) {
					echo $nodo[$type];
				} else {
					echo $nodo['wms'];
				}

				echo ",";

			// foreach ($nodo as $atrib => $value) {
			// 	// echo "--".$atrib.":".$value;
			// }
		}
	}
}

//Responder peticion HTML
else if ($format =='html'){
	$fila_oscura = true;

	echo '<table>';
	echo '<tr>
		<td class="Table_title_row" width="30%">Nombre de institución</td>
		<td class="Table_title_row" width="14%">Organismo</td>
		<td class="Table_title_row" width="14%">Mapa Interactivo</td>
		<td class="Table_title_row" style="width: 14%;">Geoservicios<br /><span style="font-size: 11px;">(copiar enlace con botón derecho del mouse)</span></td>
		<td class="Table_title_row" width="14%">Catálogo</td>
		<td class="Table_title_row" width="14%">Portal de datos abiertos</td>
		</tr>';


	//recorre ministerio|provincia|...
	foreach ($sources as $nombre => $nodos) {

		$clase = $fila_oscura ? 'filas_oscuras' : 'filas_claras';

		//nombre del ministerio|provincia|...
		echo '<tr>';
		echo '<td class="' . $clase . '">' . $nombre . '</td>';
		echo '</tr>';

		//recorre nodos
		foreach ($nodos as $nodo) {
			getRowOrganismo($nodo);
		}

		$fila_oscura = ! $fila_oscura;
	}

	echo '</table>';
}


//
// <table style="width: 100%;">
// 	<tbody style="font-size: 14px;">
// 		<tr>
// 			<td class="Table_titular" colspan="7">IDE de jurisdicción nacional:</td>
// 		</tr>
// 		<tr>
// 			<td class="Table_title_row" width="30%">Nombre de institución</td>
// 			<td class="Table_title_row" width="14%">Organismo</td>
// 			<td class="Table_title_row" width="14%">Mapa Interactivo</td>
// 			<td class="Table_title_row" style="width: 14%;">Geoservicios<br /><span style="font-size: 11px;">(copiar enlace con botón derecho del mouse)</span>
// 			</td>
// 			<td class="Table_title_row" width="14%">Portal de datos abiertos</td>
// 			<td class="Table_title_row" width="14%">Catálogo</td>
// 		</tr>
// 		<tr>
// 			<td class="filas_oscuras">Ministerio de Hacienda y Finanzas Públicas</td>
// 			<td class="filas_oscuras">INDEC</td>
// 			<td class="filas_oscuras"><a href="http://mapas.ambiente.gob.ar/" target="_blank">Abrir enlace</a></td>
// 			<td class="filas_oscuras"><a title="Servicio Web de Mapas" href="vv" data-toggle="tooltip" data-placement="right">WMS</a><br /><a title="Servicio Web de Capas vectoriales" href="vv" data-toggle="tooltip" data-placement="right">WFS</a><br /><a title="Servicio Web de Metadatos" href="vv" data-toggle="tooltip" data-placement="right">CSW</a></td>
// 			<td class="filas_oscuras">-</td>
// 			<td class="filas_oscuras"><a href="vvvv">Catálogo</a>
// 			</td>
// 		</tr>
// 		<tr>
// 			<td class="filas_claras">Ministerio de Agroindustria</td>
// 			<td class="filas_claras">-</td>
// 			<td class="filas_claras"><a href="http://mapas.ambiente.gob.ar/" target="_blank">Abrir enlace</a>
// 			</td>
// 			<td class="filas_claras"><a href="vv">WMS</a><br /><a href="http://mapas.ambiente.gob.ar/" target="_blank">WFS</a>
// 			</td>
// 			<td class="filas_claras"><a href="cccc">Datos Abiertos MAGYP</a>
// 			</td>
// 			<td class="filas_claras">-</td>
// 		</tr>
// 		<tr>
// 			<td class="filas_oscuras">Ministerio de Comunicaciones</td>
// 			<td class="filas_oscuras">-</td>
// 			<td class="filas_oscuras"><a href="http://geoportal.conae.gov.ar/geoexplorer/composer/">Abrir enlace</a>
// 			</td>
// 			<td class="filas_oscuras"><a href="vvv">-</a>
// 			</td>
// 			<td class="filas_oscuras">-</td>
// 			<td class="filas_oscuras">-</td>
// 		</tr>
// 		<tr>
// 			<td class="pie_tabla" colspan="7">IDERA</td>
// 		</tr>
// 	</tbody>
// </table>

















// Responder capabilities cacheados (.xml)
// else if ($format =='xml') {
// 	//paso a json para usar array
// 	$aSources = json_decode($jSources,true);
//
// 	//recorro array
// 	foreach ($aSources as $nombre=>$datos){
// 		//genero nombre del archivo
// 		$filename = 'ogc/'.$nombre.'.xml';
//
// 		//modifico solo si es wms
// 		if (($datos['ptype']=='gxp_wmssource') || ($datos['ptype']=='gxp_wmscsource')) {
// 			if (file_exists($filename)) {
// 				//reemplazo url con path al xml
// 				$aSources[$nombre]['url'] = $filename;
// 			} else {
// 				//quito source
// 				unset($aSources[$nombre]);
// 			}
// 		}
// 	}
//
// 	//vuelta al formato original
// 	$jSources = json_encode($aSources, JSON_UNESCAPED_UNICODE);
// 	echo "var sources = ".stripslashes($jSources);
// }

//Solo responder servicios WMS (app estado servicios wms)
else if ($format =='wms') {
	//paso a json para usar array
	$aSources = json_decode($jSources,true);

	//recorro array
	foreach ($aSources as $nombre=>$datos){
		//modifico solo si es wms
		if (!(($datos['ptype']=='gxp_wmssource') || ($datos['ptype']=='gxp_wmscsource'))) {
			//quito source
			unset($aSources[$nombre]);
		}
	}

	//vuelta al formato original
	$jSources = json_encode($aSources, JSON_UNESCAPED_UNICODE);
	echo "var sources = ".stripslashes($jSources);
}



else if ($format =='qgis') {
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="qgis.xml"');
	header('Content-Type: charset=utf-8');

	$cadena = "<wms ignoreGetMapURI='false' smoothPixmapTransform='false' dpiMode='7' password='' ignoreGetFeatureInfoURI='false' referer='' username='' invertAxisOrientation='false' ignoreAxisOrientation='false'";

	echo "<qgsWMSConnections version='1.0'>";

	foreach ($sources as $nombre=>$datos){
	  if (($datos['ptype']=='gxp_wmssource') || ($datos['ptype']=='gxp_wmscsource')){
	      $cadena2 = $cadena. " url='" .htmlentities($datos['url'])."' name='IDERA_". $datos['title']. "'/>";
	  }
	  echo $cadena2;
	}

  echo "</qgsWMSConnections>";
}
else {
	echo $sources;
}
?>
