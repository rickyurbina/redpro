<?php
class CitasController
{
    #----------------------------------------------
    #           REGISTRA NUEVA CITA
    #----------------------------------------------
    public static function registraCita()
    {
        if (isset($_POST["btnRegistrar"])) {

            # Ingreso a la BD
            $ingresa = "NotOK";
            $registrado = 1;
            $uploadOk = 1;
            
            //elimina caracteres de la mascara en el numero de telefono
            $eliminar = array("(", ")", "-", " ");
            if ($_POST["telefonoFijo"] != ""){
                $telefonoFijo = str_replace($eliminar, "", $_POST["telefonoFijo"]);
            }
            $telefonoMovil = str_replace($eliminar, "", $_POST["telefonoMovil"]);
            // Se asigna la zona horaria para registrar la fecha de la solicitud
            date_default_timezone_set('America/Chihuahua');
            $now = date('Y-m-d');

            $fechaNac = $_POST["dateAnio"] . "-" . $_POST["dateMes"] . "-" . $_POST["dateDia"];
            $colorPrimario = "#F47B31";
            $colorSecundario = "#55B952";
            $nombres = $_POST['nombres'];    
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email']; 
            $facebook = $_POST['facebook'];
            $instagram = $_POST['instagram'];
            $youtube = $_POST['youtube'];
            $tiktok = $_POST['tiktok'];
            $linkedin = $_POST['linkedin'];
            $calle = $_POST['calle'];
            $ciudad = $_POST['ciudad'];
            $estado = $_POST['estado'];
            $cp = $_POST['cp'];
            $puesto = "";
            $empresa = "";
            $biografia = "";

            # Validacion de los links de redes sociales para los links en la tarjeta digital
            $web = ($_POST['web'] != "" ) ?   '<div class="col-lg-4 mb-0 mb-lg-0"><br><a class="btn btn-outline-light btn-social mx-1" href="'.$_POST['web'].'" target="_blank"><i class="fa-solid fa-globe"></i></a></div>' : '';            
            $facebook = ($_POST['facebook'] != "" ) ?   '<a href="'.$_POST['facebook'].'" target="_blank"><img class="img-fluid"  src="../perfiles/assets/img/facebook.png" alt="..." /></a>' : '';
            $instagram = ($_POST['instagram'] != "" ) ?   '<a href="'.$_POST['instagram'].'" target="_blank"><img class="img-fluid" src="../perfiles/assets/img/instagram.png" alt="..." /></a>' : '';
            $tiktok = ($_POST['tiktok'] != "" ) ?   '<a href="'.$_POST['tiktok'].'" target="_blank"><img class="img-fluid" src="../perfiles/assets/img/tiktok.png" alt="..." /></a>' : '';
            $linkedin = ($_POST['linkedin'] != "" ) ?   '<a href="'.$_POST['linkedin'].'" target="_blank"><img class="img-fluid" src="../perfiles/assets/img/linkedin.png" alt="..." /></a>' : '';
            $youtube = ($_POST['youtube'] != "" ) ?   '<a href="'.$_POST['youtube'].'" target="_blank"><img class="img-fluid" src="../perfiles/assets/img/youtube.png" alt="..." /></a>' : '';

            # Validando telefonos para evitar duplicado de datos
            if ($_POST["telefonoFijo"] != ""){
                if ($telefonoFijo == $telefonoMovil) {
                    $telPerfil = '<div class="text-center mt-4">
                                    <a class="btn btn-xl text-white" style="background-color: #25d366;" href="https://wa.me/1'.$telefonoMovil.'" role="button"><i class="fab fa-whatsapp"></i> Whatsup</a>
                                  </div>
                                  <div class="text-center mt-4">
                                    <a class="btn btn-xl text-white" style="background-color: #25d366;" href="tel:'.$telefonoMovil.'" role="button"><i class="fa-solid fa-phone"></i> Llámame</a>
                                  </div>';
                    $telVCard = "\nTEL;CELL:".$telefonoMovil;
                }
                else{
                    $telPerfil = '<div class="text-center mt-4">
                                        <a class="btn btn-xl text-white" style="background-color: #25d366;" href="https://wa.me/1'.$telefonoMovil.'" role="button"><i class="fab fa-whatsapp"></i> Whatsup</a>
                                    </div>
                                    <div class="text-center mt-4">
                                        <a class="btn btn-xl text-white" style="background-color: #25d366;" href="tel:'.$telefonoFijo.'" role="button"><i class="fa-solid fa-phone"></i> Llámame</a>
                                    </div>';
                    $telVCard = "\nTEL;PREF:".$telefonoFijo."\nTEL;CELL:".$telefonoMovil;
                }
            }
            else{
                $telPerfil = '<div class="text-center mt-4">
                                    <a class="btn btn-xl text-white" style="background-color: #25d366;" href="https://wa.me/1'.$telefonoMovil.'" role="button"><i class="fab fa-whatsapp"></i> Whatsup</a>
                                  </div>
                                  <div class="text-center mt-4">
                                    <a class="btn btn-xl text-white" style="background-color: #25d366;" href="tel:'.$telefonoMovil.'" role="button"><i class="fa-solid fa-phone"></i> Llámame</a>
                                  </div>';
                $telVCard = "\nTEL;CELL:".$telefonoMovil;
            }




            # validacion de redes sociales para la vCard
            $facebookVCard = ($_POST['facebook'] != "" ) ?   'URL;OTHER:'.$_POST['facebook'] : '';
            $instagramVCard = ($_POST['instagram'] != "" ) ?   'URL;OTHER:'.$_POST['instagram'] : '';
            $webVCard = ($_POST['web'] != "" ) ?   'URL;WORK:'.$_POST['web'] : '';

            # lipieza de acentos y ñ en nombres y apellidos
            $apellidoLimpio = str_replace(
                array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ','.'),
                array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n',''),
                $apellidos
                );

            $nombreLimpio = str_replace(
                array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ'),
                array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n'),
                $nombres
                );
            $nombreCompleto = strtolower($nombreLimpio." ".$apellidoLimpio);

            // Checking whether file exists or not
            
            $datos = array(
                            "nombres" => $nombreLimpio,
                            "apellidos" => $apellidoLimpio,
                            "curp" => $_POST["curp"],
                            "responsable" => $_POST["responsable"],
                            "cliente" => $nombreCompleto,
                            "telefonoFijo" => $telefonoFijo,
                            "whatsapp" => $telefonoMovil,
                            "email" => $_POST["email"],
                            "fecha" => $now,
                            "fechaNac" => $fechaNac,
                            "status" => "N",
                            "calle" => $_POST["calle"],
                            "colonia" => $_POST["colonia"],
                            "ciudad" => $_POST["ciudad"],
                            "estado" => $_POST["estado"],
                            "cp" => $_POST["cp"],
                            "carpeta" => ""
                        );
                
                        $ingresa = CitasModel::registraCita($datos);

                        if (!empty($ingresa)){

                            
                            $nombreFolder="../". $ingresa . "-" .$nombreCompleto;
                            $folder =str_replace(' ', '-', $nombreFolder);

                            $datosCarpeta = array(
                                "id"=> $ingresa,
                                "carpeta"=> $folder
                            );

                            $carpeta = CitasModel::carpeta($datosCarpeta);

                            if (!file_exists($folder)) {
                $registrado = 0;
        
                // Create a new file or direcotry
                mkdir($folder, 0777, true);
                $foto = basename($_FILES["foto"]["name"]);
                $target_file = $folder ."/". basename($_FILES["foto"]["name"]);
                
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                // Check if image file is a actual image or fake image
                if(isset($_POST["btnRegistrar"])) {
                    $check = getimagesize($_FILES["foto"]["tmp_name"]);
                    if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }

                // Redimensionar foto
                list($ancho, $alto) = getimagesize($_FILES["foto"]["tmp_name"]);
                $nuevoAncho = 300;
                $nuevoAlto = 300;

                if($imageFileType == "jpg" or $imageFileType == "jpeg"){
                    $origen = imagecreatefromjpeg($_FILES["foto"]["tmp_name"]);
                }
                if($imageFileType == "png"){
                    $origen = imagecreatefrompng($_FILES["foto"]["tmp_name"]);
                }
            
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";

                } else {
                    // if everything is ok, try to upload foto
                    if (imagejpeg($destino, $target_file)){

                        // Creacion de la tarjeta de contacto
                        $image = file_get_contents($folder.'/'. $_FILES['foto']["name"] );
                        $imageData = base64_encode($image);
                        $myfile = fopen($folder."/tarjeta.vcf", "w") or die("Unable to open file!");
                        $txt = "BEGIN:VCARD\nVERSION:3.0\nREV:2023-09-24T00:00:00Z\nN:".$apellidos.";".$nombres.";\nORG:".$empresa."\nTITLE:".$puesto.$telVCard."\nEMAIL;INTERNET;WORK:".$email."\n".$webVCard."\n".$facebookVCard."\n".$instagramVCard."\nADR;WORK:".$calle.";".$ciudad.";".$estado.";$cp;\nMexico\nPHOTO;TYPE=JPEG;ENCODING=BASE64:".$imageData."\nEND:VCARD";
                        fwrite($myfile, $txt);
                        fclose($myfile);

                        # Creamos la pagina del perfil -------------------------------------------------------------------------------------
                        $html = '<!DOCTYPE html>
                        <html lang="en">
                            <head>
                                <meta charset="utf-8" />
                                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
                                <meta name="description" content="" />
                                <meta name="author" content="" />
                                <title>Tap ID - '.$nombres.' '.$apellidos.'</title>
                                <link rel="icon" type="image/x-icon" href="../perfiles/assets/favicon.ico" />
                                <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
                                <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
                                <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
                                <link href="../perfiles/css/styles.css" rel="stylesheet" />
                                <style>
                                    .rounded-circle{border-radius:50%!important}
                                    .btn-primary {
                                        --bs-btn-bg: '.$colorPrimario.';
                                    }
                                    .btn-primary:hover {
                                        color: #fff;
                                        background-color: '.$colorSecundario.';
                                        border-color: '.$colorSecundario.';
                                    }
                                    .bg-primary {
                                        /*--bs-bg-opacity: 1;
                                        background-color: rgba(var(--bs-primary-rgb), var(--bs-bg-opacity)) !important;*/
                                        background: '.$colorPrimario.';  /* fallback for old browsers */
                                        background: -webkit-linear-gradient(to bottom, '.$colorSecundario.', '.$colorPrimario.');  /* Chrome 10-25, Safari 5.1-6 */
                                        background: linear-gradient(to bottom, '.$colorSecundario.', '.$colorPrimario.'); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
                                    
                                    }
                                </style>
                            </head>
                            <body id="page-top">

                                <header class="masthead bg-primary text-white text-center">
                                    <div class="container d-flex align-items-center flex-column">
                                        <img class="rounded-circle mb-5" src="'.$foto.'" alt="..." />
                                        <h1 class="masthead-heading mb-0">'.$nombres.' '.$apellidos.'</h1>
                                        <div class="divider-custom divider-light">
                                            <div class="divider-custom-line"></div>
                                        </div>

                                        <h2>'.$empresa.'</h2>
                                        <p class="masthead-subheading font-weight-light mb-0"> '.$puesto.'</p>
                                            '.$web.'
                                    </div>
                                </header>
                                '.$biografia.'

                                <section class="page-section portfolio text-white" id="portfolio">
                                    <div class="container">
                                        <div class="row row-cols-3 justify-content-center text-center">
                                            
                                            '.$facebook.$instagram.$linkedin.$tiktok.$youtube.'

                                        </div>
                                    </div>
                                </section>

                                <section class="page-section" id="contact">
                                    <div class="container">
                                        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Contáctame</h2>
                                        <div class="divider-custom">
                                            <div class="divider-custom-line"></div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-lg-8 col-xl-7">
                                                <div class="text-center mt-4">
                                                    <a class="btn btn-xl btn-primary" href="tarjeta.vcf"><i class="fas fa-download me-2"></i>Guarda mi contacto!</a>
                                                </div>
                                                '.$telPerfil.'
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <footer class="footer text-center">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-4 mb-5 mb-lg-0">
                                                
                                            </div>
                                            <div class="col-lg-4 mb-5 mb-lg-0">
                                                <h4 class="text-uppercase mb-4">Dirrección</h4>
                                                <p class="lead mb-0">'
                                                    .$calle.'<br />'
                                                    .$ciudad.', C.P '.$cp.'<br>'
                                                    .$estado.'., México
                                                </p>
                                            </div>
                                            <div class="col-lg-4">

                                            </div>
                                        </div>
                                    </div>
                                </footer>
                                <div class="copyright py-4 text-center text-white">
                                    <div class="container"><small>Copyright &copy; Tap Id 2025</small></div>
                                </div>
                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
                                <script src="../perfiles/js/scripts.js"></script>
                            </body>
                        </html>
                        ';

                        $myProfile = fopen($folder."/index.html", "w") or die("Unable to open file!");
                        fwrite($myProfile, $html);
                        fclose($myProfile);

                        #guardamos la solicitud en la BD
                        
                    } 
                }

                

            }

                        } 

    echo "Resultados: ".$ingresa . "<br>". $uploadOk ."<br>".$registrado;      
                        
            if ($uploadOk == 1 && $registrado == 0) {

                echo "<script>Swal.fire({
                        title: '".$nombreCompleto."',
                        text: 'Perfil creado!',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location='index.php?page=inicio'
                        }
                        })
                        </script>";
            } 

            else {

                echo "<script>Swal.fire({
                    title: 'Error',
                    text: 'Algo salió mal',
                    icon: 'danger',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                    })
                    })
                    </script>";
            }
        }
    }






    #----------------------------------------------
    #           MUESTRA LOS HORARIOS DISPONIBLES
    #----------------------------------------------
    public static function horario()
    {
        //$horas = array('9:00', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00');
        echo    '<div class="col-sm-6 col-md-6">
                    <label class="form-label">Hora</label>
                        <select class="form-control" name="hora" id="hora" required>';
                            $var1 = '09:00';
                            $var2 = '16:00';
                            $intervarlo = '30';

                            $fechaInicio = new DateTime($var1);
                            $fechaFin = new DateTime($var2);
                            $fechaFin = $fechaFin->modify('+30 minutes');
                            $rangoFechas = new DatePeriod($fechaInicio, new DateInterval('PT30M'), $fechaFin);

                            foreach ($rangoFechas as $fecha) {
                                echo $fecha->format("H:i") . PHP_EOL;
                                $arrTimes[] = $fecha->format("H:i"); #Se guardan las horas generadas 
                            }
                            $cont = count($arrTimes);

                            for($i=0;$i<$cont;$i++){
                                echo '<option value="'.$arrTimes[$i].'">'.$arrTimes[$i].'</option>';
                            }    
        echo '          
                        </select>
                </div>';
    }
    #----------------------------------------------
    #           GENERA EL DATE PICKER
    #----------------------------------------------
    public static function fechaAdd($fechaN)
    {
        date_default_timezone_set('America/Mexico_City');
        
        if ($fechaN != "") {
            $dia = date('d', strtotime($fechaN));
            $mes = date('m', strtotime($fechaN));
            $anio = date('Y', strtotime($fechaN));
        } else {
            $dia = date('d');
            $mes = date('m');
            $anio = date('Y');
        }



        echo
        '<div class="col-md-4 col-sm-12">
                <select name="dateDia" class="form-control" id="dia")">';
        for ($d = 1; $d <= 31; $d++) {
            if ($d == $dia)
                echo '<option value="' .  $d . '" selected>' .  $d . '</option>';
            else
                echo '<option value="' .  $d . '">' .  $d . '</option>';
        }
        echo
        '   </select>
            </div>
            
            <div class="col-md-4 col-sm-12">
                <select name="dateMes" class="form-control" id="mes")">';

        $meses = array(
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        );

        for ($m = 1; $m <= 12; $m++) {
            if ($m == $mes)
                echo '<option value="' . $m . '" selected>' . $meses[$m - 1] . '</option>';
            else
                echo '<option value="' .  $m . '">' . $meses[$m - 1] . '</option>';
        }
        echo
        '   </select>
            </div>
            
            <div class="col-md-4 col-sm-12">
                <select name="dateAnio" class="form-control" id="anio"">';

        for ($x = date("Y"); $x >= 1900; $x--) {
            if ($x == $anio)
                echo '<option value="' . $x . '" selected>' . $x . '</option>';
            else
                echo '<option value="' . $x . '">' . $x . '</option>';
        }
        echo
        '   </select>
            </div>';
    }


    #----------------------------------------------
    #           REGISTRA UN PAGO
    #----------------------------------------------
    public static function registraPago()
    {
        if (isset($_POST["btnLiquidar"])) {

            // $original_date = $_POST["fecha"];
            // $timestamp = strtotime($original_date);
            // $fecha = date("Y-m-d", $timestamp);

            $datos = array(
                "idCita" => $_POST["idCita"],
                "cantidad" => $_POST["cantidad"],
                "metodoPago" => $_POST["metodoPago"],
                "cajeroId" => $_POST["cajeroId"]
            );

            $registra = CitasModel::registraPago($datos);

            if ($registra == "ok") {

                CitasModel::mEstadoCita($_POST["idCita"], "$");

                echo "<script>Swal.fire({
                    title: 'Registro Exitoso',
                    text: 'Cobro registrado',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                  }).then((result) => {
                    if (result.isConfirmed) {
                        window.location='index.php?page=inicio'
                    }
                  })
                  </script>";
            } else {

                echo "<script>Swal.fire({
                title: 'Error',
                text: 'No se pudo guardar la información',
                icon: 'danger',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              })
              })
              </script>";
            }
        }
    }
    #----------------------------------------------
    #           REAGENDA UNA CITA
    #----------------------------------------------
    public static function reagendarCita()
    {
        if (isset($_POST["btnRegistrar"]) || isset($_POST["paciente"])) {

            $fecha = $_POST["dateAnio"] . "-" . $_POST["dateMes"] . "-" . $_POST["dateDia"];

            $datos = array(
                "id" => $_POST["id"],
                "pacienteId" => $_POST["paciente"],
                "medicoId" => $_POST["medico"],
                "hora" => $_POST["hora"],
                "fecha" => $fecha,
                "responsable" => $_POST["responsable"],
                "costo" => $_POST["costo"]
            );

            $ingresa = CitasModel::reagendarCita($datos);


            if ($ingresa == "ok") {

                echo "<script>Swal.fire({
                        title: 'Registro Exitoso',
                        text: 'El nuevo paciente ha sio registrado',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                      }).then((result) => {
                        if (result.isConfirmed) {
                            window.location='index.php?page=inicio'
                        }
                      })
                      </script>";
            } else {

                echo "<script>Swal.fire({
                    title: 'Error',
                    text: 'No se pudo guardar la información',
                    icon: 'danger',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                  })
                  })
                  </script>";
            }
        }
    }
    # ___________________________________________________________________________________________________________________________________________
    # Muestra las citas registradas para el médico muestra solamente las que le corresponden y para admin y asistente, todas las registradas
    # Los posibles estados son: R = Registrada, C = Confirmada, A = Atendida, C = Cancelada, $ = Cobrada
    #____________________________________________________________________________________________________________________________________________
    public static function citasAgendadas($medico)
    {

        $citas = CitasModel::citasAgendadas($medico);
        if (empty($citas)) {
            echo '<br><br><br><h3 class="text-center">No hay citas agendadas para hoy</h3>';
        } else {
            echo '<table class="table table-bordered table-hover text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Fecha</th>
                            <th>telefono</th>
                            <th>Medico</th>
                            <th>Acciones</th>
                            
                        </tr>
                    </thead>
                    <tbody>';


            foreach ($citas as $cita) {
                $opciones = '';
                $original_hour = $cita["hora"];
                $timestamp = strtotime($original_hour);
                $hora = date("h:i", $timestamp);

                if ($cita["estado"] == "R") {
                    $tag = '<span class="tag tag-yellow">R </span> &nbsp;';
                    if ($_SESSION['permisos'] != "medico") {
                        $opciones = '<a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=C" class="dropdown-item"><i class="dropdown-icon fa fa-phone"></i> Confirmar </a>
                                            <a href="index.php?page=vReagendar&idEditar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-vcard-o"></i> Reagendar </a>
                                            <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=X" class="dropdown-item"><i class="dropdown-icon fa fa-close"></i> Cancelar </a>
                                        </div>';
                    } else {
                        $opciones = '<a href="index.php?page=consulta&idConsultar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-stethoscope"></i>Atender</a>';
                    }
                }
                if ($cita["estado"] == "C") {
                    $tag = '<span class="tag tag-blue">C </span> &nbsp;';
                    if ($_SESSION['permisos'] != "medico") {
                        $opciones = '<a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical fs-20 text-dark"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-172px, 22px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a href="index.php?page=consulta&idConsultar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-stethoscope"></i>Atender</a>
                                            <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=A" class="dropdown-item"><i class="dropdown-icon fa fa-stethoscope"></i> Atendida </a>
                                            <a href="index.php?page=vReagendar&idEditar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-vcard-o"></i> Reagendar </a>
                                            <a href="index.php?page=vEstadoCita&idEditar=' . $cita["id"] . '&estado=X" class="dropdown-item"><i class="dropdown-icon fa fa-close"></i> Cancelar </a>
                                        </div>';
                    } else {
                        $opciones = '<a href="index.php?page=consulta&idConsultar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-stethoscope"></i>Atender</a>';
                    }
                }

                if ($cita["estado"] == "A") {
                    $tag = '<span class="tag tag-green">A </span> &nbsp;';
                    if ($_SESSION['permisos'] != "medico") {
                        $opciones = '<a href="index.php?page=vPagos&idCobrar=' . $cita["id"] . '" class="dropdown-item"><i class="dropdown-icon fa fa-dollar"></i>Cobrar</a>';
                    } else {
                        $opciones = '';
                    }
                }
                if ($cita["estado"] == "X") {
                    $tag = '<span class="tag tag-red">X </span> &nbsp;';
                    $opciones = '';
                }
                if ($cita["estado"] == "$") {
                    $tag = '<span class="tag tag-indigo">$</span> &nbsp;';
                    $opciones = '';
                }
                echo '
                        <tr>
                            <td class="font-weight-semibold fs-16">' . $tag . $cita["paciente"] . '</td>
                            <td>' . $cita["fecha"] . ' - <strong> ' . $hora . '</strong></td>
                            <td> <a href="tel:' . $cita['telefono'] . '">' . $cita['telefono'] . '</a></td>
                            <td>' . $cita["medico"] . '</td>
                            <td>
                            <div class="item-action dropdown">
                                    ' . $opciones . '
                            </div>
                            </td>
                        </tr>';
            }
            echo '<tr>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>telefono</th>
                    <th>Medico</th>
                    <th>Acciones</th>
                        
                </tr>
                </tbody>
                </table>';
        }
    }
    #----------------------------------------------
    #           CORTE DE CAJA POR CAJERO
    #----------------------------------------------
    public static function corteCaja($cajero)
    {
        $totalDia = CitasModel::totalDia($cajero);
        $corte = CitasModel::corteCaja($cajero);
        $cantidad = 1;

        echo '<table class="table table-bordered table-hover text-nowrap">
                    <tbody><tr>
                        <th class="text-center ">#</th>
                        <th>Método de Pago</th>
                        <th class="text-center">Cobros</th>
                        <th class="text-right">Cantidad</th>
                    </tr>';
        foreach ($corte as $metodo) {
            echo '<tr>
                                <td class="text-center">' . $cantidad . '</td>
                                <td>
                                    <p class="font-w600 mb-1">' . $metodo["metodoPago"] . '</p>
                                    <!--<div class="text-muted">At vero eos et accusamus et iusto odio dignissimos ducimus qui </div>-->
                                </td>
                                <td class="text-center">' . $metodo["numero"] . '</td>

                                <td class="text-right">$ ' . $metodo["cantidad"] . '</td>
                            </tr>';
            $cantidad++;
        }

        echo '
                    <tr>
                        <td colspan="3" class="font-weight-semibold text-uppercase text-right">Total</td>
                        <td class="font-weight-semibold text-right">$' . $totalDia["cantidad"] . '</td>
                    </tr>

                </tbody></table>';
    }
    #----------------------------------------------
    #           OBTIENE LOS DATOS DEL CAJERO 
    #----------------------------------------------
    public static function datosCajero($cajero)
    {
        $datos = CitasModel::datosCajero($cajero);

        echo '

            <address>
                id: ' . $datos["id"] . '<br>
                ' . $datos["nombres"] . ' ' . $datos["apellidos"] . '<br>
                Telefono: ' . $datos["telefono"] . '<br>
                ' . $datos["email"] . '
            </address>

        ';
    }
    #----------------------------------------------
    #           SUBE LAS IMAGENES AL EXPEDIENTE 
    #----------------------------------------------
    public static function subeArchivo($paciente, $fname, $source)
    {
        $nAncho = 1500; //Nuevo ancho
        $nAlto = 1500;  //Nuevo alto
        $fechaActual = date('d-m-Y H:i:s');
        $nombrePrevio = $fechaActual . $fname;
        $nombre = md5($nombrePrevio);

        $fileType = strtolower(pathinfo($fname, PATHINFO_EXTENSION));


        # ------------------------------------------------------------------
        // crear la carpeta del paciente que corresponde a su numero de id
        # ------------------------------------------------------------------

        # Revisamos si la carpeta del paciente existe en el directorio y si no, la creamos
        $micarpeta = "../medico/uploads/expedientes/" . $paciente . "/";
        if (!file_exists($micarpeta)) {
            mkdir($micarpeta, 0777, true);
        }

        $target_path = "../medico/uploads/expedientes/" . $paciente . "/" . $paciente . "_" . $nombre . "." . $fileType;
        if ($fileType == "jpg" || $fileType == 'jpeg') $fname = imagecreatefromjpeg($source);
        else if ($fileType == 'png') $fname = imagecreatefrompng($source);
        $x = imagesx($fname);
        $y = imagesy($fname);

        if ($x >= $y) {
            $nAncho = $nAncho;
            $nAlto = $nAncho * $y / $x;
        } else {
            $nAlto = $nAlto;
            $nAncho = $x / $y * $nAlto;
        }

        $img = imagecreatetruecolor($nAncho, $nAlto);
        imagecopyresampled($img, $fname, 0, 0, 0, 0, floor($nAncho), floor($nAlto), $x, $y);

        if (imagejpeg($img, $target_path))
            return $target_path;

        return "error";
    }
    #----------------------------------------------
    #           GENERA EL EXPEDIENTE
    #----------------------------------------------
    public static function ctrRegistraExpediente()
    {
        if (isset($_POST["registrar"])) {

            $idConsulta = $_POST["idConsulta"];
            $paciente = $_POST["idPaciente"];
            CitasModel::consultaOut($paciente);
            $fname1 = $_FILES["image1"]["name"];
            $source1 = $_FILES["image1"]["tmp_name"];
            $fname2 = $_FILES["image2"]["name"];
            $source2 = $_FILES["image2"]["tmp_name"];
            $fname3 = $_FILES["image3"]["name"];
            $source3 = $_FILES["image3"]["tmp_name"];
            $fname4 = $_FILES["image4"]["name"];
            $source4 = $_FILES["image4"]["tmp_name"];

            if (!empty($fname1)) $archivo1 = self::subeArchivo($paciente, $fname1, $source1);
            else $archivo1 = "";
            if (!empty($fname2)) $archivo2 = self::subeArchivo($paciente, $fname2, $source2);
            else $archivo2 = "";
            if (!empty($fname3)) $archivo3 = self::subeArchivo($paciente, $fname3, $source3);
            else $archivo3 = "";
            if (!empty($fname4)) $archivo4 = self::subeArchivo($paciente, $fname4, $source4);
            else $archivo4 = "";

            if ($archivo1 == "error" || $archivo2 == "error" || $archivo3 == "error" || $archivo4 == "error") $uploadImgs = "error";
            else if ($archivo1 != "" || $archivo2 != "" || $archivo3 != "" || $archivo4 != "") $uploadImgs = "ok";
            else $uploadImgs = "noImage";

            $imagenes = array('imagen1' => $archivo1, 'imagen2' => $archivo2, 'imagen3' => $archivo3, "imagen4" => $archivo4);

            $imagesJson = str_replace("\\", "", json_encode($imagenes));


            $datos = array(
                "idPaciente" => $_POST["idPaciente"],
                "idMedico" => $_POST["idMedico"],
                "titulo" => $_POST["titulo"],
                "comentario" => $_POST["comentario"],
                "archivos" => $imagesJson
            );

            $ingresa = CitasModel::mdlRegistraExpediente($datos);
            CitasModel::registraUltimaVisita($paciente);

            if ($ingresa == "ok") {
                if ($uploadImgs == "ok" || $uploadImgs == "noImage") {

                    echo "<script>Swal.fire({
                        title: 'Registro Exitoso',
                        text: 'Expediente registrado',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                      }).then((result) => {
                        if (result.isConfirmed) {
                            window.location='index.php?page=vEstadoCita&idEditar=$idConsulta&estado=A'
                        }
                      })
                      </script>";
                } else if ($uploadImgs == "error") {
                    echo "<script>Swal.fire({
                        title: 'Hubo error al subir imagenes',
                        text: 'Alguna de las imagenes no se subió al server',
                        icon: 'alert',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                      }).then((result) => {
                        if (result.isConfirmed) {
                            window.location='index.php?page=vEstadoCita&idEditar=$idConsulta&estado=A'
                        }
                      })
                      </script>";
                }
            } else {

                echo "<script>Swal.fire({
                    title: 'Error',
                    text: 'No se pudo guardar la información',
                    icon: 'danger',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                  }).then((result) => {
                    if (result.isConfirmed) {
                        window.location='index.php?page=inicio'
                    }
                  })
                  </script>";
            }
        }
    }
    #-------------------------------------------------
    #           MUESTRA EL EXPEDIENTE DE C/PACIENTE
    #-------------------------------------------------
    public static function expediente($idPaciente)
    {
        $expediente = CitasModel::expediente($idPaciente);
        $idModal = 0;

        echo '
                <div class="col-lg-6 col-md-12 col-sm-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Expediente</h4>
                        </div>
                        <div class="card-body">
                            <ul class="visitor list-unstyled list-unstyled-border">
        ';
        foreach ($expediente as $exp) {
            $comentario =  substr($exp["comentario"], 0, 25);
            $original_date = $exp["fecha"];
            $timestamp = strtotime($original_date);
            $fecha = date("d-m-Y", $timestamp);
            $idModal++;
            echo '
                <li class="media pt-0 mt-0"> 
                    <i class="fa fa-calendar-check-o" data-toggle="tooltip" title="" data-original-title="fa fa-calendar-check-o"></i> &nbsp;&nbsp;
                    <div class="media-body">
                        <div class="float-right"><small>' . $fecha . '</small></div>
                        <div class="media-title"><a href="" data-toggle="modal" data-target="#Modal' . $idModal . '">' . $exp["titulo"] . '</a></div> 
                        <small class="text-muted">' . $comentario . ' ... </small>
                    </div>
                    
                </li>

            ';
        }

        echo '
                            </ul>
                        </div>
                    </div>
                </div>
        ';

        $idModalContenido = 1;
        foreach ($expediente as $contenido) {
            $imagenes = json_decode($contenido['archivos']);

            echo '
                <div class="modal fade" id="Modal' . $idModalContenido . '" tabindex="-1" role="dialog" aria-labelledby="Modal' . $idModalContenido . 'Title" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">' . $contenido["titulo"] . '</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">X</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <p>' . $contenido["comentario"] . '</p>';

            foreach ($imagenes as $imagen) {
                echo '<p><a href="' . $imagen . '" target="blank"><img src="' . $imagen . '"></a></p>';
            }

            echo '
                                                              
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-indigo" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            ';
            $idModalContenido++;
        }
    }
}//class
