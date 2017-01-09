<?php
class Actividad{

private $idActividad;
private $nombreAct;
private $duracion;
private $hora;
private $lugar;
private $plazas;
private $dificultad;
private $id_actividad;
private $descripcion;
private $plazas_libres;
function contructor(){

	$idActividad=null;
	$nombreAct=null;
	$duracion=null;
	$hora=null;
	$lugar=null;
	$plazas=null;
	$dificultad=null;
	$id_actividad=null;
	$descripcion=null;
	$plazas_libres=null;
}
function conexionBD()
		{
                include "../DataBase/datos_BD.php";
                $mysqli=mysqli_connect($host,$user,$pass,$name);
				if(!$mysqli){

					echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    				echo "error de depuración: " . mysqli_connect_errno() . PHP_EOL;
    				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    				exit;
				}

			return $mysqli;
		 }


/*	function devolveridactividad($id_actividad)
	{

	$query="SELECT id_Actividad FROM actividad WHERE id_Actividad=$id_actividad";
	$resultado=mysql_query($query)or die (mysql_error());
	return $resultado;
	}*/

function creararrayActividades()
{
	$idActividad=null;
	$nombreAct=null;
	$duracion=null;
	$hora=null;
	$lugar=null;
	$plazas=null;
	$dificultad=null;
	$id_actividad=null;
	$descripcion=null;
	$plazas_libres=null;
	$this->conexionBD();
	$file = fopen("../Archivos/ArrayConsultarActividad.php", "w");

	fwrite($file,"<?php class consultActividad { function array_consultarActividad(){". PHP_EOL);
			 	fwrite($file,"\$form=array(" . PHP_EOL);
	$mysqli=$this->conexionBD();


	$query="SELECT * FROM `Actividad`";
	$resultado=$mysqli->query($query);
	if(mysqli_num_rows($resultado)){

		while($fila = $resultado->fetch_array())
		{
			$filas[] = $fila;
		}
		foreach($filas as $fila)
		{
			 $id_actividad=$fila['id_Actividad'];
			 $nombre=$fila['Nombre'];
			 $duracion=$fila['Duracion'];
			 $hora=$fila['Hora'];
			 $lugar=$fila['Lugar'];
			 $plazas=$fila['Plazas'];
			 $dificultad=$fila['Dificultad'];
			 $descripcion=$fila['Descripcion'];
			 $plazas_libres=$fila['Plazas_libres'];
			fwrite($file,"array(
				\"id_actividad\"=>'$id_actividad',
				\"nombre\"=>'$nombre',
				\"duracion\"=>'$duracion',
				\"hora\"=>'$hora',
				\"lugar\"=>'$lugar',
				\"plazas\"=>'$plazas',
				\"dificultad\"=>'$dificultad',				
				\"descripcion\"=>'$descripcion',
				\"plazas_libres\"=>'$plazas_libres'
				)," . PHP_EOL);

	 	}
	}
			 fwrite($file,");return \$form;}}?>". PHP_EOL);
			 fclose($file);
			 $resultado->free();
			 $mysqli->close();

}

//Crea un array con los datos de gestion de las actividades
function crearArrayGestionActividad()
{
	$this->conexionBD();
	$form=array();

	$query="SELECT * FROM Gestion_actividad"; //WHERE `id_Actividad` = '$actividadId'";
	$mysqli=$this->conexionBD();
	$resultado=$mysqli->query($query);

	if(mysqli_num_rows($resultado)){
			//$fila =$resultado->fetch_array(MYSQLI_ASSOC);
		while($fila = $resultado->fetch_array())
		{
			$filas[] = $fila;
		}
		foreach($filas as $fila)
		{
			$entrenadorId=$fila['Entrenador_id_Usuario'];
			$actividadId=$fila['Actividad_id_Actividad'];
			$alumnoId=$fila['identificador_deportista'];
			$fecha=$fila['fecha'];

			$fila_array=array("entrenadorId"=>$entrenadorId,"actividadId"=>$actividadId,"alumnoId"=>$alumnoId,"fecha"=>$fecha);
			array_push($form,$fila_array);
		}
	}			 
	$resultado->free();
	$mysqli->close();
	return $form;
}

//Lista los nombres de los entrenadores
function getEntrenadores(){

	$this->conexionBD();
	$mysqli=$this->conexionBD();

	$form=array();
	$query="SELECT * FROM Entrenador";		
	$resultado=$mysqli->query($query);
	while($fila = $resultado->fetch_array())
	{
		$filas[] = $fila;
	}
	foreach($filas as $fila)
	{
		$dni=$fila['DNI'];
		$Nombre=$fila['Nombre'];
		$Apellidos=$fila['Apellidos'];

		$fila_array=array("dni"=>$dni,"Nombre"=>$Nombre,"Apellidos"=>$Apellidos);
		array_push($form,$fila_array);
	}
	$resultado->free();
	$mysqli->close();
	return $form;
}


//Crea el array final con los datos de las actividades
function RellenarArrayFinal($DatosActividad,$NombreEntrenador)
{
	include("../Archivos/ArrayConsultarActividad.php");
	$arra=new consultActividad();
	$form=$arra->array_consultarActividad();

	//Array con lo datos de Gestion actividad
	$file = fopen("../Archivos/ArrayConsultarGestionActividad.php", "w");
	fwrite($file,"<?php class consult { function array_consultarGestionActividades(){". PHP_EOL);
	fwrite($file,"\$form=array(" . PHP_EOL);
	
	for ($numarT=0;$numarT<count($form);$numarT++){
		
		$id_actividad=$form[$numarT]["id_actividad"];
		$nombre=$form[$numarT]["nombre"];
	    $duracion=$form[$numarT]["duracion"];
		$hora=$form[$numarT]["hora"];
		$lugar=$form[$numarT]["lugar"];
		$plazas=$form[$numarT]["plazas"];
		$dificultad=$form[$numarT]["dificultad"];
	    $descripcion=$form[$numarT]["descripcion"];
		$plazas_libres=$form[$numarT]["plazas_libres"];
		fwrite($file,"array(
			\"id_actividad\"=>'$id_actividad',
			\"nombre\"=>'$nombre',
			\"duracion\"=>'$duracion',
			\"hora\"=>'$hora',
			\"lugar\"=>'$lugar',
			\"plazas\"=>'$plazas',
			\"dificultad\"=>'$dificultad',
			\"descripcion\"=>'$descripcion',
			\"plazas_libres\"=>'$plazas_libres'," . PHP_EOL);
		
		//Datos Gestion Actividad
		for ($numarC=0;$numarC<count($DatosActividad);$numarC++){
			if($id_actividad==$DatosActividad[$numarC]["actividadId"]){
			
			$entrenadorId=$DatosActividad[$numarC]["entrenadorId"];
			$actividadId=$DatosActividad[$numarC]["actividadId"];
			$alumnoId=$DatosActividad[$numarC]["alumnoId"];
			$fecha=$DatosActividad[$numarC]["fecha"];
			fwrite($file,"
					\"entrenadorId".$numarC."\"=>'$entrenadorId',
					\"actividadId".$numarC."\"=>'$actividadId',
					\"alumnoId".$numarC."\"=>'$alumnoId',
					\"fecha".$numarC."\"=>'$fecha'," . PHP_EOL);
			}
		}
		//Datos Gestion Actividad
		for ($numar=0;$numar<count($NombreEntrenador);$numar++){
			if($entrenadorId==$NombreEntrenador[$numar]["dni"]){
			
			$NombreEntrenadorActividad=$NombreEntrenador[$numar]["Nombre"];
			$ApellidoEntrenadorActividad=$NombreEntrenador[$numar]["Apellidos"];
			fwrite($file,"
					\"NombreEntrenadorActividad\"=>'$NombreEntrenadorActividad',
					\"ApellidoEntrenadorActividad\"=>'$ApellidoEntrenadorActividad',
					" . PHP_EOL);

			//fwrite($file,"\"entrenadorId"."\"=>'$entrenadorId'," . PHP_EOL);
			}
		}

		fwrite($file,")," . PHP_EOL);
		}
		fwrite($file,");return \$form;}}?>". PHP_EOL);
		fclose($file);				 				
}


//Alta de actividad
function altaActividad($nombreAct,$duracion,$hora,$lugar,$plazas,$dificultad,$descripcion)
{
	$mysqli=$this->conexionBD();

	if($mysqli->query("INSERT INTO `Actividad`(`Nombre`, `Duracion`, `Hora`, `Lugar`, `Plazas`, `Dificultad`, `Descripcion`,`Plazas_libres`)
		VALUES
		('$nombreAct','$duracion','$hora','$lugar','$plazas','$dificultad','$descripcion',$plazas)")==TRUE)
	{
	?>
		<script>
		alert("Insercción Realizada con Exito");
		</script>
		<?php
		}else {
		?>
		<script>
		alert("Vuelva a Introducir los datos");
		</script>
	<?php }
		$mysqli->close();
}
//Añade alumnos a una actividad
function altaAlumno($id_actividad)
{
	$mysqli=$this->conexionBD();

	if($mysqli->query("INSERT INTO `Gestion_actividad`(`Entrenador_id_Usuario`, `Actividad_id_Actividad`, `identificador_deportista`,`fecha`)
		VALUES
		('$id_actividad',)")==TRUE)
	{
	?>
		<script>
		alert("Insercción Realizada con Exito");
		</script>
		<?php
		}else {
		?>
		<script>
		alert("Vuelva a Introducir los datos");
		</script>
	<?php }
		$mysqli->close();
}
function eliminarAlumno($id_actividad,$id_alumno)
{
	$mysqli=$this->conexionBD();

 	$query="DELETE FROM `Gestion_actividad` WHERE Actividad_id_Actividad='$id_actividad' && identificador_deportista='$id_alumno'";
 	if($mysqli->query($query)==TRUE){
	?>
		<script>
		alert("Eliminado con Exito");
		</script>
		<?php
 	}else {
		?>
		<script>
		alert("Problema al Borrar");
		</script>
	<?php }
	$mysqli->close();
}

 function eliminarActividad($id_actividad){

 	$mysqli=$this->conexionBD();

 	$query="DELETE FROM `Actividad` WHERE id_Actividad='$id_actividad'";
 	if($mysqli->query($query)==TRUE){
	?>
		<script>
		alert("Eliminado con Exito");
		</script>
		<?php
 	}else {
		?>
		<script>
		alert("Problema al Borrar");
		</script>
	<?php }
	$mysqli->close();
 }
 function modificarActividad($id,$nombreAct,$duracion,$hora,$lugar,$plazas,$dificultad,$descripcion){

 	$mysqli=$this->conexionBD();
    $query= "UPDATE `Actividad` SET `Nombre`='$nombreAct',`Duracion`='$duracion',`Hora`='$hora',`Lugar`='$lugar',`Plazas`='$plazas',`Dificultad`='$dificultad',`Descripcion`='$descripcion',`Plazas_libres`='$plazas' WHERE `id_Actividad`='$id'";

	if($mysqli->query($query)==TRUE){
		?>
		<script>
		alert("Modificado con Exito");
		</script>
		<?php
	}else {
		?>
		<script>
		alert("Problema al Modificar");
		</script>
	<?php }
	$mysqli->close();
}
}

?>
