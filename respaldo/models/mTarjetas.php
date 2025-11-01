<?php
    Class TarjetasModel{
        #----------------------------------------------
        #           Lista de solicitudes de Tarjeta
        #----------------------------------------------
        public static function citasAgendadas($medico){

            if ($medico == "") $propietarioCita = "";
            else $propietarioCita = "AND responsable = $medico";

            $stmt = Conexion::conectar()->prepare("SELECT s.id, s.cliente, s.telefono, s.fecha, s.estado, CONCAT(u.nombres,' ', u.apellidos) AS responsable
                FROM solicitudes AS s
                INNER JOIN usuarios AS u
                ON s.responsable = u.id
                WHERE 1 $propietarioCita
                ORDER BY s.fecha DESC;");
            $stmt->execute();
            return $stmt->fetchAll();
            
        }

        // #----------------------------------------------------------------------------
        // #           Lista de solicitudes de Tarjeta del vendedor
        // #----------------------------------------------------------------------------
        // public static function tarjetasAsistente($medico){

        //     if ($medico == "") $propietarioCita = "";
        //     else $propietarioCita = "AND responsable = $medico";

        //     $stmt = Conexion::conectar()->prepare("SELECT s.id, s.cliente, s.telefono, s.fecha, s.estado, CONCAT(u.nombres,' ', u.apellidos) AS responsable
        //         FROM solicitudes AS s
        //         INNER JOIN usuarios AS u
        //         ON s.responsable = u.id
        //         WHERE s.estado != 'I' $propietarioCita
        //         ORDER BY s.fecha;");
        //     $stmt->execute();
        //     return $stmt->fetchAll();
            
        // }



    }
?>