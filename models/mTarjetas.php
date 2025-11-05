<?php
    Class TarjetasModel{
    # ================================================
    #   ACTUALIZA 
    # ================================================
    public static function actualizarEstadosAutomaticos() {
        $stmt = Conexion::conectar()->prepare("
            UPDATE solicitudes 
            SET activo = 0 
            WHERE fecha < DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR) 
            AND activo = 1
        ");
        return $stmt->execute();
    }


        #----------------------------------------------
        #           Lista de solicitudes de Tarjeta
        #----------------------------------------------
        // public static function citasAgendadas($medico){

        //     if ($medico == "") $propietarioCita = "";
        //     else $propietarioCita = "AND responsable = $medico";

        //     $stmt = Conexion::conectar()->prepare("SELECT s.id, s.cliente, s.zona, s.curp, s.activo, s.telefono, DATE (s.fecha) AS registro, CONCAT(u.nombres,' ', u.apellidos) AS responsable
        //         FROM solicitudes AS s
        //         INNER JOIN usuarios AS u
        //         ON s.responsable = u.id
        //         WHERE 1 $propietarioCita
        //         ORDER BY s.fecha DESC;");
        //     $stmt->execute();
        //     return $stmt->fetchAll();
            
        // }

    public static function citasAgendadas($medico) {
        // Primero actualizar estados automáticamente
        self::actualizarEstadosAutomaticos();
        
        if ($medico == "") {
            $propietarioCita = "";
        } else {
            $propietarioCita = "AND responsable = $medico";
        }

        $stmt = Conexion::conectar()->prepare("SELECT 
            s.id, s.cliente, s.zona, s.curp, s.activo, s.telefono, 
            DATE(s.fecha) AS registro, 
            CONCAT(u.nombres,' ', u.apellidos) AS responsable
            FROM solicitudes AS s
            INNER JOIN usuarios AS u ON s.responsable = u.id
            WHERE 1 $propietarioCita
            ORDER BY s.fecha DESC;");
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function renovarMembresia($id) {
        try {
            $stmt = Conexion::conectar()->prepare("
                UPDATE solicitudes 
                SET fecha = CURRENT_DATE, activo = 1 
                WHERE id = :id
            ");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log del error si es necesario
            error_log("Error al renovar membresía: " . $e->getMessage());
            return false;
        }
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