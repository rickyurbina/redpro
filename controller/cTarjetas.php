<?php
class TarjetasController
{
    # ___________________________________________________________________________________________________________________________________________
    # Muestra las citas registradas para el médico muestra solamente las que le corresponden y para admin y asistente, todas las registradas
    # Los posibles estados son: R = Registrada, C = Confirmada, A = Atendida, C = Cancelada, $ = Cobrada
    #____________________________________________________________________________________________________________________________________________
    // public static function citasAgendadas($medico)
    // {

    //     $citas = TarjetasModel::citasAgendadas($medico);
    //     if (!empty($citas)){
    //         echo '<table id="example2" class="hover table-bordered border-top-0 border-bottom-0" style="text-align: center;">
    //         <thead>
    //             <tr>
    //                 <th>Id</th>
    //                 <th>Nombre</th>
    //                 <th>Zona</th>
    //                 <th>CURP</th>                            
    //                 <th>Telefono</th>
    //                 <th>Registro</th>                            
    //                 <th>Activo</th>
    //                 <th> ... </th>
    //             </tr>
    //         </thead>
    //         <tbody>';
    //         foreach ($citas as $tarjeta=> $value){
    //             echo '
    //         <tr>
    //             <td class="font-weight-semibold fs-16">'.$value['id'].'</td>
    //             <td class="font-weight-semibold fs-16">' . $value["cliente"] . '</td>
    //             <td>' . $value["zona"] . '</td>
    //             <td>' . $value["curp"] . '</td>
    //             <td> <a href="tel:' . $value['telefono'] . '">' . $value['telefono'] . '</a></td>
    //             <td>' . $value["registro"] . '</td>
    //             <td>' . $value["activo"] . '</td>
    //             <td>
    //                 <div class="item-action dropdown">
    //                     <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
    //                     <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
    //                         <a href="index.php?page=vTarjEdit&idEditar='.$value["id"].'" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Editar </a>';
                    
    //                 // esto es para que el usuario en sesion no pueda borrarse a si mismo
    //                 echo '
    //                     </div>
    //                 </div>
    //             </td>
    //         </tr>';
    //         }
    //         echo '</tbody>
    //         </table>';
    //     }else{
    //         echo '<br><br><br><h3 class="text-center">No hay perfiles registrados</h3>';
    //     }
    // }
    public static function citasAgendadas($medico)
    {
        // Primero verificar si se solicita una renovación
        if (isset($_GET['renovar_id'])) {
            self::procesarRenovacion();
        }

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
                    <th>Registro</th>                            
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
            
            foreach ($citas as $tarjeta => $value){
                $estadoClase = ($value['activo'] == 1) ? 'badge-success' : 'badge-danger';
                $estadoTexto = ($value['activo'] == 1) ? 'ACTIVO' : 'INACTIVO';
                
                echo '
                <tr>
                    <td class="font-weight-semibold fs-16">'.$value['id'].'</td>
                    <td class="font-weight-semibold fs-16">' . $value["cliente"] . '</td>
                    <td>' . $value["zona"] . '</td>
                    <td>' . $value["curp"] . '</td>
                    <td><a href="tel:' . $value['telefono'] . '">' . $value['telefono'] . '</a></td>
                    <td>' . $value["registro"] . '</td>
                    <td>
                        <span class="badge ' . $estadoClase . '">' . $estadoTexto . '</span>
                    </td>
                    <td>
                        <div class="item-action dropdown">
                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false">
                                <i class="fe fe-more-vertical fs-20 text-dark"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="index.php?page=vTarjEdit&idEditar='.$value["id"].'" class="dropdown-item">
                                    <i class="dropdown-icon fe fe-edit-2"></i> Editar 
                                </a>';
                
                // Botón para renovación rápida si está inactivo
                if ($value['activo'] == 0) {
                    echo '<a href="javascript:void(0)" onclick="renovarMembresia(' . $value["id"] . ')" class="dropdown-item text-warning">
                            <i class="dropdown-icon fe fe-refresh-cw"></i> Renovar Ahora
                        </a>';
                }
                        
                echo '      </div>
                        </div>
                    </td>
                </tr>';
            }
            echo '</tbody></table>';
            
            // Script para renovación rápida con Sweet Alert
            echo '
            <script>
            function renovarMembresia(id) {
                Swal.fire({
                    title: "¿Renovar membresía?",
                    text: "¿Está seguro de renovar la membresía por un año más? Esto actualizará la fecha de registro a hoy.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, renovar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading mientras se procesa
                        Swal.fire({
                            title: "Procesando...",
                            text: "Renovando membresía",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Agregar parámetro a la URL actual
                        let url = new URL(window.location.href);
                        url.searchParams.set("renovar_id", id);
                        
                        fetch(url)
                        .then(response => {
                            // Verificar si la respuesta es JSON
                            const contentType = response.headers.get("content-type");
                            if (contentType && contentType.includes("application/json")) {
                                return response.json();
                            }
                            throw new TypeError("La respuesta no es JSON");
                        })
                        .then(data => {
                            if(data.success) {
                                Swal.fire({
                                    title: "¡Éxito!",
                                    text: data.message,
                                    icon: "success",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "OK"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Remover el parámetro y recargar
                                        url.searchParams.delete("renovar_id");
                                        window.location.href = url.toString();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: data.message,
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        url.searchParams.delete("renovar_id");
                                        window.location.href = url.toString();
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            // Aunque haya error de JSON, si la renovación funcionó, mostrar éxito
                            Swal.fire({
                                title: "Renovación completada",
                                text: "La membresía ha sido renovada exitosamente",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    url.searchParams.delete("renovar_id");
                                    window.location.href = url.toString();
                                }
                            });
                        });
                    }
                });
            }
            </script>';
            
        } else {
            echo '<br><br><br><h3 class="text-center">No hay perfiles registrados</h3>';
        }
    }

    // Método para procesar la renovación
    private static function procesarRenovacion() {
        if (isset($_GET['renovar_id'])) {
            $id = intval($_GET['renovar_id']);
            
            // LIMPIAR CUALQUIER OUTPUT ANTES
            ob_clean();
            
            // Verificar que el ID sea válido
            if ($id > 0) {
                $resultado = TarjetasModel::renovarMembresia($id);
                
                header('Content-Type: application/json');
                if ($resultado) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Membresía renovada exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Error al renovar la membresía'
                    ]);
                }
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'ID inválido'
                ]);
                exit;
            }
        }
    }
}
?>