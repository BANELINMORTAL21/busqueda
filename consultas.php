<?php
require 'B.php';
$datos_usuario = array();
$html_usuario = "";
// echo ("conectado ");

// LLamar los datos del usuario
if (isset($_SESSION['usuario_id'])) {
    $sql = "SELECT * FROM usuarios WHERE id = '" . $_SESSION['usuario_id'] . "'";
    $row = $conn->query($sql);
    $result = $row->fetch_array(MYSQLI_ASSOC);

    $usuario = null;
    if ($row->num_rows > 0) {
        $usuario = $result;
    }
    if (!empty($usuario)) {
        $html_usuario .= "<br>Bienvenido:" . $usuario['Usuario'];
        $html_usuario .= " <br>Conexión exitosa";
        $html_usuario .= "  <a href='index.php'>Cerrar sesion</a>";
        array_push($datos_usuario, $html_usuario);
    }
}

// Buscar datos de los estudiantes en general
if (isset($_SESSION['usuario_id'])) {
    $datos_estudiantes = array();
    $datos_html = array();
    $html_estudiante = "";

    $sql = 'SELECT * FROM estudiantes_g';
    $row2 = $conn->query($sql);
    $results2 = $row2->fetch_all(MYSQLI_ASSOC);

    foreach ($results2 as $datos2) {
        $sql3 = "SELECT COUNT(*) AS conteo_matricula FROM `fecha_matriculas` WHERE id_estudiante =" . $datos2['Id'];
        $results3 = $conn->query($sql3);
        $row3 = $results3->fetch_all(MYSQLI_ASSOC);

        $sql4 = "SELECT COUNT(*) AS conteo_semestre_finalizado FROM `fecha_matriculas` WHERE estado = 'F' AND id_estudiante =" . $datos2['Id'];
        $results4 = $conn->query($sql4);
        $row4 = $results4->fetch_all(MYSQLI_ASSOC);

        $sql5 = "SELECT *  FROM `fecha_matriculas` WHERE estado != 'C' AND id_estudiante =" . $datos2['Id'];
        $results5 = $conn->query($sql5);
        $estado_fechas = array();
        if ($results5->num_rows > 0) {
            $row5 = $results5->fetch_all(MYSQLI_ASSOC);

            if (isset($row5['0']['fecha_inicio'])) {
                $firstDate  = new DateTime($row5['0']['fecha_inicio']);
                $secondDate = new DateTime(date("Y-m-d"));
                $intvl = $firstDate->diff($secondDate);

                $year_and_month = array();
                $fecha_inical = $row5['0']['fecha_inicio'];
                $year_to_month = $intvl->y  * 12;
                $month_more_month = $year_to_month  + $intvl->m;

                while ($month_more_month > 0) {
                    array_push($year_and_month, $fecha_inical);
                    $fecha_inical = strtotime($fecha_inical . "+ 6 month");
                    $fecha_inical = date('Y-m-d', $fecha_inical);
                    $month_more_month -= 6;
                }
                // var_dump($year_and_month);
                // echo ($intvl->y . " " . $intvl->m . " " . $intvl->d);
            }

            $contador = 0;
            $fecha_ref_antigua = array();
            $periodo_acade = "";
            foreach ($year_and_month as $fechas) {
                if (isset($row5[$contador]['fecha_inicio'])) {
                    if ($fechas == $row5[$contador]['fecha_inicio']) {
                        $fecha_ref_antigua['0'] = $row5[$contador]['fecha_inicio'];
                        // var_dump($row5[$contador]['fecha_inicio']);
                        // var_dump($fecha_ref_antigua);
                        $estado_descripcion = "continuo";
                        // var_dump($row5[$contador]['id_estudiante']);
                        // var_dump($row5[$contador]['periodo_academico']);
                        array_push($estado_fechas, array($row5[$contador]['fecha_inicio'], $estado_descripcion, $row5[$contador]['periodo_academico']));

                        // var_dump($estado_fechas);  
                        // var_dump($periodo_acade);  
                        if ($row5[$contador]['periodo_academico'] == "10") {
                            $periodo_acade = "60";
                        } else {
                            $periodo_acade = "10";
                        }
                        // var_dump('despues de la condicion');  
                        // var_dump($periodo_acade);  
                        $contador++;
                    } else {
                        $firstDate  = new DateTime($fecha_ref_antigua['0']);
                        $secondDate = new DateTime($fechas);
                        $intvl = $firstDate->diff($secondDate);
                        if ($intvl->y >= 2) {
                            $estado_descripcion = "desertor";
                            array_push($estado_fechas, array($fechas, $estado_descripcion, $periodo_acade));
                        } else {
                            $estado_descripcion = "inactivo";
                            array_push($estado_fechas, array($fechas, $estado_descripcion, $periodo_acade));
                        }
                        if ($periodo_acade == "10") {
                            $periodo_acade = "60";
                        } else {
                            $periodo_acade = "10";
                        }
                    }
                } else {
                    $firstDate  = new DateTime($fecha_ref_antigua['0']);
                    $secondDate = new DateTime($fechas);
                    $intvl = $firstDate->diff($secondDate);
                    if ($intvl->y >= 2) {
                        $estado_descripcion = "desertor";
                        array_push($estado_fechas, array($fechas, $estado_descripcion, $periodo_acade));
                    } else {
                        $estado_descripcion = "inactivo";
                        array_push($estado_fechas, array($fechas, $estado_descripcion, $periodo_acade));
                    }
                    if ($periodo_acade == "10") {
                        $periodo_acade = "60";
                    } else {
                        $periodo_acade = "10";
                    }
                }
            }
        }



        // var_dump($estado_fechas);

        if ($row4['0']['conteo_semestre_finalizado'] < 1) {
            array_push($datos_estudiantes, array(
                $datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'],
                array($estado_fechas)
            ));
        } else {
            if ($row4['0']['conteo_semestre_finalizado'] < 2) {
                if ($datos2['Creditos'] >= 14) {
                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                } else {
                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                }
            } else {
                if ($row4['0']['conteo_semestre_finalizado'] < 3) {
                    if ($datos2['Creditos'] >= 30) {
                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                    } else {
                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                    }
                } else {
                    if ($row4['0']['conteo_semestre_finalizado'] < 4) {
                        if ($datos2['Creditos'] >= 47) {
                            array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                        } else {
                            array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                        }
                    } else {
                        if ($row4['0']['conteo_semestre_finalizado'] < 5) {
                            if ($datos2['Creditos'] >= 63) {
                                array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                            } else {
                                array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                            }
                        } else {
                            if ($row4['0']['conteo_semestre_finalizado'] < 6) {
                                if ($datos2['Creditos'] >= 79) {
                                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                } else {
                                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                }
                            } else {
                                if ($row4['0']['conteo_semestre_finalizado'] < 7) {
                                    if ($datos2['Creditos'] >= 96) {
                                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                    } else {
                                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                    }
                                } else {
                                    if ($row4['0']['conteo_semestre_finalizado'] < 8) {
                                        if ($datos2['Creditos'] >= 112) {
                                            array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                        } else {
                                            array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                        }
                                    } else {
                                        if ($row4['0']['conteo_semestre_finalizado'] < 9) {
                                            if ($datos2['Creditos'] >= 129) {
                                                array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                            } else {
                                                array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                            }
                                        } else {
                                            if ($row4['0']['conteo_semestre_finalizado'] < 10) {
                                                if ($datos2['Creditos'] >= 144) {
                                                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                                } else {
                                                    array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                                }
                                            } else {
                                                if ($row4['0']['conteo_semestre_finalizado'] < 11) {
                                                    if ($datos2['Creditos'] >= 159) {
                                                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], $row4['0']['conteo_semestre_finalizado'], array($estado_fechas)));
                                                    } else {
                                                        array_push($datos_estudiantes, array($datos2['Id'], $datos2['Nombre'], $datos2['Apellido'], $datos2['Programa'], $datos2['Creditos'], $row3['0']['conteo_matricula'], ($row4['0']['conteo_semestre_finalizado']) - 1, array($estado_fechas)));
                                                    }
                                                } else {
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //Consulta por id
    /* echo 'conexion exitosa'; */
    if (isset($_POST['buscar'])) {
        $validarcampovacio = !empty($_POST['buscar']) ? $_POST['buscar'] : 0;
        if (!empty($validarcampovacio)) {
            foreach ($datos_estudiantes as $dato_estudiante) {
                if ($validarcampovacio == $dato_estudiante['0']) {
                    $html_estudiante .= '<tr  data-bs-toggle="collapse" href="#collapse' . $dato_estudiante['0'] . '" role="button" aria-expanded="false" aria-controls="collapse' . $dato_estudiante['0'] . '">';
                    $html_estudiante .= '<td>' . $dato_estudiante['0'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['1'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['2'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['3'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['4'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['5'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['6'] . '</td>';
                    $html_estudiante .= '</tr>';
                    $html_estudiante .= ' <tr>';
                    $html_estudiante .= '  <td colspan="3" class="">';
                    $html_estudiante .= '        <div class="collapse" id="collapse' . $dato_estudiante['0'] . '">';
                    $html_estudiante .= '         <div class="row">';
                    $html_estudiante .= '          <div class="col-12">';
                    $html_estudiante .= '              <h5 style="font-family: sans-serif;">';
                    $html_estudiante .= '                  Estados';
                    $html_estudiante .= '              </h5>';
                    $html_estudiante .= '           </div>';
                    $html_estudiante .= '          </div>';
                    $html_estudiante .= '<table class="table table-striped">';
                    $html_estudiante .= '   <thead>';
                    $html_estudiante .= '    <tr class="info">';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha matricula</th>';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periodo</th>';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>';
                    $html_estudiante .= '    </tr>';
                    $html_estudiante .= '   </thead>';
                    $html_estudiante .= '   <tbody>';
                    foreach ($dato_estudiante['7'] as $key => $datos_fechas_vista) {
                        foreach ($datos_fechas_vista as $llaves => $value) {
                            // var_dump($value);

                            $html_estudiante .= '  <tr>';
                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '       <div class="d-flex px-2 py-1">';
                            $html_estudiante .= '           <div class="d-flex flex-column justify-content-center">';
                            $html_estudiante .= '              <h6 class="mb-0 text-sm">' . $value['0'] . '</h6>';
                            $html_estudiante .= '           </div>';
                            $html_estudiante .= '       </div>';
                            $html_estudiante .= '      </td>';

                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '          <span class="text-color-black">' . $value['2'] . '</span>';
                            $html_estudiante .= '      </td>';

                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '          <p class="text-xs font-weight-bold mb-0">' . $value['1'] . '</p>';
                            $html_estudiante .= '      </td>';
                            $html_estudiante .= '      </tr>';
                        }
                    }
                    $html_estudiante .= '     </tbody>';
                    $html_estudiante .= ' </table>';
                    $html_estudiante .= ' </div>';
                    $html_estudiante .= ' </td>';
                    $html_estudiante .= ' </tr>';
                    array_push($datos_html, $html_estudiante);
                }
            }
        } else {
            if (!empty($datos_estudiantes)) {
                foreach ($datos_estudiantes as $dato_estudiante) {
                    $html_estudiante .= '<tr  data-bs-toggle="collapse" href="#collapse' . $dato_estudiante['0'] . '" role="button" aria-expanded="false" aria-controls="collapse' . $dato_estudiante['0'] . '">';
                    $html_estudiante .= '<td>' . $dato_estudiante['0'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['1'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['2'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['3'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['4'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['5'] . '</td>';
                    $html_estudiante .= '<td>' . $dato_estudiante['6'] . '</td>';
                    $html_estudiante .= '</tr>';

                    $html_estudiante .= ' <tr>';
                    $html_estudiante .= '  <td colspan="3" class="">';
                    $html_estudiante .= '        <div class="collapse" id="collapse' . $dato_estudiante['0'] . '">';
                    $html_estudiante .= '         <div class="row">';
                    $html_estudiante .= '          <div class="col-12">';
                    $html_estudiante .= '              <h5 style="font-family: sans-serif;">';
                    $html_estudiante .= '                  Estados';
                    $html_estudiante .= '              </h5>';
                    $html_estudiante .= '           </div>';
                    $html_estudiante .= '          </div>';
                    $html_estudiante .= '<table class="table table-striped">';
                    $html_estudiante .= '   <thead>';
                    $html_estudiante .= '    <tr class="info">';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha matricula</th>';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periodo</th>';
                    $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>';
                    $html_estudiante .= '    </tr>';
                    $html_estudiante .= '   </thead>';
                    $html_estudiante .= '   <tbody>';
                    foreach ($dato_estudiante['7'] as $key => $datos_fechas_vista) {
                        foreach ($datos_fechas_vista as $llaves => $value) {
                            // var_dump($value);

                            $html_estudiante .= '  <tr>';
                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '       <div class="d-flex px-2 py-1">';
                            $html_estudiante .= '           <div class="d-flex flex-column justify-content-center">';
                            $html_estudiante .= '              <h6 class="mb-0 text-sm">' . $value['0'] . '</h6>';
                            $html_estudiante .= '           </div>';
                            $html_estudiante .= '       </div>';
                            $html_estudiante .= '      </td>';

                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '          <span class="text-color-black">' . $value['2'] . '</span>';
                            $html_estudiante .= '      </td>';

                            $html_estudiante .= '      <td>';
                            $html_estudiante .= '          <p class="text-xs font-weight-bold mb-0">' . $value['1'] . '</p>';
                            $html_estudiante .= '      </td>';
                            $html_estudiante .= '      </tr>';
                        }
                    }
                    $html_estudiante .= '     </tbody>';
                    $html_estudiante .= ' </table>';
                    $html_estudiante .= ' </div>';
                    $html_estudiante .= ' </td>';
                    $html_estudiante .= ' </tr>';
                }
                array_push($datos_html, $html_estudiante);
            }
        }
        $conn->close();
    } else {
        if (!empty($datos_estudiantes)) {
            foreach ($datos_estudiantes as $dato_estudiante) {

                $html_estudiante .= '<tr  data-bs-toggle="collapse" href="#collapse' . $dato_estudiante['0'] . '" role="button" aria-expanded="false" aria-controls="collapse' . $dato_estudiante['0'] . '">';
                $html_estudiante .= '<td>' . $dato_estudiante['0'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['1'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['2'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['3'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['4'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['5'] . '</td>';
                $html_estudiante .= '<td>' . $dato_estudiante['6'] . '</td>';
                $html_estudiante .= '</tr>';

                $html_estudiante .= ' <tr>';
                $html_estudiante .= '  <td colspan="3" class="">';
                $html_estudiante .= '        <div class="collapse" id="collapse' . $dato_estudiante['0'] . '">';
                $html_estudiante .= '         <div class="row">';
                $html_estudiante .= '          <div class="col-12">';
                $html_estudiante .= '              <h5 style="font-family: sans-serif;">';
                $html_estudiante .= '                  Estados';
                $html_estudiante .= '              </h5>';
                $html_estudiante .= '           </div>';
                $html_estudiante .= '          </div>';
                $html_estudiante .= '<table class="table table-striped">';
                $html_estudiante .= '   <thead>';
                $html_estudiante .= '    <tr class="info">';
                $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha matricula</th>';
                $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periodo</th>';
                $html_estudiante .= '      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>';
                $html_estudiante .= '    </tr>';
                $html_estudiante .= '   </thead>';
                $html_estudiante .= '   <tbody>';
                foreach ($dato_estudiante['7'] as $key => $datos_fechas_vista) {
                    foreach ($datos_fechas_vista as $llaves => $value) {
                        // var_dump($value);

                        $html_estudiante .= '  <tr>';
                        $html_estudiante .= '      <td>';
                        $html_estudiante .= '       <div class="d-flex px-2 py-1">';
                        $html_estudiante .= '           <div class="d-flex flex-column justify-content-center">';
                        $html_estudiante .= '              <h6 class="mb-0 text-sm">' . $value['0'] . '</h6>';
                        $html_estudiante .= '           </div>';
                        $html_estudiante .= '       </div>';
                        $html_estudiante .= '      </td>';

                        $html_estudiante .= '      <td>';
                        $html_estudiante .= '          <span class="text-color-black">' . $value['2'] . '</span>';
                        $html_estudiante .= '      </td>';

                        $html_estudiante .= '      <td>';
                        $html_estudiante .= '          <p class="text-xs font-weight-bold mb-0">' . $value['1'] . '</p>';
                        $html_estudiante .= '      </td>';
                        $html_estudiante .= '      </tr>';
                    }
                }
                $html_estudiante .= '     </tbody>';
                $html_estudiante .= ' </table>';
                $html_estudiante .= ' </div>';
                $html_estudiante .= ' </td>';
                $html_estudiante .= ' </tr>';
            }
            array_push($datos_html, $html_estudiante);
        }
    }










    // $filtro = "SELECT * FROM `fecha_matriculas` AS t1 
    // INNER JOIN estudiantes_g AS t2 ON t1.id_estudiante=t2.Id 
    // WHERE estado='F'";
    // $result = $conn->query($filtro);
    // if ($result->num_rows > 0) {
    //     while ($row = $result->fetch_assoc()) {
    //         array_push($datos, $row);
    //     }
    // }
}
