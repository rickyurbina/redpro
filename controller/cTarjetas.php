<?php
class TarjetasController
{
    # ___________________________________________________________________________________________________________________________________________
    # Muestra las citas registradas para el médico muestra solamente las que le corresponden y para admin y asistente, todas las registradas
    # Los posibles estados son: R = Registrada, C = Confirmada, A = Atendida, C = Cancelada, $ = Cobrada
    #____________________________________________________________________________________________________________________________________________
    public static function citasAgendadas($medico)
    {

        $citas = TarjetasModel::citasAgendadas($medico);
        if (!empty($citas)){
							echo '<table id="example2" class="hover table-bordered border-top-0 border-bottom-0" style="text-align: center;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nombre</th>
									<th>Zona</th>
									<th>CURP</th>                            
									<th>Telefono</th>                            
                                    <th>Activo</th>
                                    <th> ... </th>
								</tr>
							</thead>
							<tbody>';
							foreach ($citas as $tarjeta=> $value){
								echo '
							<tr>
                                <td class="font-weight-semibold fs-16">'.$value['id'].'</td>
								<td class="font-weight-semibold fs-16">' . $value["cliente"] . '</td>
								<td>' . $value["zona"] . '</td>
                                <td>' . $value["curp"] . '</td>
								<td> <a href="tel:' . $value['telefono'] . '">' . $value['telefono'] . '</a></td>
                                <td>' . $value["status"] . '</td>
								<td>
                                    <div class="item-action dropdown">
                                        <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="index.php?page=vTarjEdit&idEditar='.$value["id"].'" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Editar </a>';
                                    
                                    // esto es para que el usuario en sesion no pueda borrarse a si mismo
                                    echo '
                                        </div>
                                    </div>
                                </td>
							</tr>';
							}
							echo '</tbody>
							</table>';
						}else{
            				echo '<br><br><br><h3 class="text-center">No hay perfiles registrados</h3>';
						}
    }

    public static function TarjEdit(){



    }

    // public static function tarjetasAsistente($medico)
    // {

    //     $citas = TarjetasModel::tarjetasAsistente($medico);
    //     if (empty($citas)) {
    //         echo '<br><br><br><h3 class="text-center">No hay citas agendadas para hoy</h3>';
    //     } 
    //     else {
    //         echo '<table class="table table-bordered table-hover text-nowrap mb-0">
    //                 <thead>
    //                     <tr>
    //                         <th>Cliente</th>
    //                         <th>Fecha</th>
    //                         <th>Telefono</th>
    //                         <th>Responsable</th>
    //                         <th>Estado</th>                            
    //                     </tr>
    //                 </thead>
    //                 <tbody>';


    //         foreach ($citas as $cita) {
    //             $opciones = '';

    //             if ($cita["estado"] == "N") {
    //                 $tag = '<span class="tag tag-yellow">N </span> &nbsp;';
    //                 if ($_SESSION['permisos'] != "medico") {
    //                     $opciones = '<a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
    //                                     <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
    //                                         <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=$" class="dropdown-item"><i class="dropdown-icon fa fa-dollar"></i> Pagado </a>
    //                                     </div>';
    //                 } 
    //             }
    //             if ($cita["estado"] == "$") {
    //                 $tag = '<span class="tag tag-blue">$ </span> &nbsp;';
    //                 if ($_SESSION['permisos'] != "medico") {
    //                     $opciones = '<a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
    //                                     <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
    //                                         <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=I" class="dropdown-item"><i class="dropdown-icon fa fa-id-card-o"></i> Impresa </a>
    //                                     </div>';
    //                 } 
    //             }
    //             if ($cita["estado"] == "I") {
    //                     $tag = '<span class="tag tag-green">I </span> &nbsp;';
    //                     $opciones = '';
    //             }
                
    //             if ($cita["estado"] == "X") {
    //                 $tag = '<span class="tag tag-red">X </span> &nbsp;';
    //                 $opciones = '';
    //             }

    //             echo '
    //                     <tr>
    //                         <td class="font-weight-semibold fs-16">' . $tag . $cita["cliente"] . '</td>
    //                         <td>' . $cita["fecha"] . '</td>
    //                         <td> <a href="tel:' . $cita['telefono'] . '">' . $cita['telefono'] . '</a></td>
    //                         <td>' . $cita["responsable"] . '</td>
    //                         <td>
    //                         <div class="item-action dropdown">
    //                                 ' . $opciones . '
    //                         </div>
    //                         </td>
    //                     </tr>';
    //         }
    //         echo '<tr>
    //                 <th>Paciente</th>
    //                 <th>Fecha</th>
    //                 <th>telefono</th>
    //                 <th>Medico</th>
    //                 <th>Acciones</th>
                        
    //             </tr>
    //             </tbody>
    //             </table>';
    //     }
    // }
}

?>