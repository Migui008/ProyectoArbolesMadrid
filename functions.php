<?php
function getAllArboles()
{
    require_once("bbddconnect.php");

    $sql = "SELECT nombre FROM arboles;";
    $result = $conn->query($sql);
    $arboles = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($arboles, $row['nombre']);
        }
        $_SESSION['arbolesAllNames'] = $arboles;
    }
  	$conn = null;
}

function getAllParques()
{
  	session_start();
    echo "Incluyendo bbddconnect.php<br>"; // Depuración
    require_once('bbddconnect.php');

    if ($conn === null) {
        echo "Conexión a la base de datos no establecida.<br>"; // Depuración
        return;
    }

    $sql = "SELECT nombre FROM parques";
    try {
        echo "Ejecutando consulta SQL<br>"; // Depuración
        $result = $conn->query($sql);
        if ($result === false) {
            echo "Error en la consulta: " . implode(", ", $conn->errorInfo()) . "<br>";
        } else {
            echo "Consulta ejecutada correctamente<br>"; // Depuración
            $parques = [];
            if ($result->rowCount() > 0) {
                echo "Se encontraron " . $result->rowCount() . " parques<br>"; // Depuración
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($parques, $row['nombre']);
                }
                $_SESSION['parquesAllNames'] = $parques;
                echo "Parques guardados en la sesión.<br>"; // Depuración
            } else {
                echo "No se encontraron parques.<br>";
            }
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage() . "<br>";
    }
  	$conn = null;
}



function mostVisited()
{
    require_once("bbddconnect.php");
    $sql = "
    (
        SELECT 'arboles' AS tipo, nombre, visitas
        FROM arboles
        ORDER BY visitas DESC
        LIMIT 5
    )
    UNION ALL
    (
        SELECT 'parques' AS tipo, nombre, visitas
        FROM parques
        ORDER BY visitas DESC
        LIMIT 5
    )
    ORDER BY visitas
    ";
    $result = $conn->query($sql);
    $mostVisited = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($mostVisited, $row['nombre']);
        }
        $_SESSION['mostVisited'] = $mostVisited;
    }
  	$conn = null;
}

function getInfoArbol($id)
{
    require_once("bbddconnect.php");

    try {
        $sql_arbol = "
        SELECT a.`nombre`, a.`nombre_cientifico`, a.`familia`, a.`clase`, a.`imagen`
        FROM `arboles` a
        WHERE a.`id_arbol` = :id;
        ";

        $stmt = $conn->prepare($sql_arbol);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $info_arbol = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info_arbol) {
            $parques_relacionados = array();

            $sql_parques_ids = "
            SELECT r.`relaciones`
            FROM `relacion` r
            WHERE r.`id_relacion` = :id;
            ";

            $stmt = $conn->prepare($sql_parques_ids);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $row_parques_ids = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row_parques_ids) {
                $parques_ids_relacion = explode(" ", $row_parques_ids['relaciones']);

                foreach ($parques_ids_relacion as $id_relacion) {
                    $sql_parque = "
                    SELECT p.`id_parque`, p.`nombre`
                    FROM `parques` p
                    JOIN `relacion` r ON FIND_IN_SET(p.`id_parque`, r.`relaciones`) > 0
                    WHERE r.`id_relacion` = :id_relacion
                    ";

                    $stmt = $conn->prepare($sql_parque);

                    $stmt->bindParam(':id_relacion', $id_relacion, PDO::PARAM_INT);

                    $stmt->execute();

                    $parque = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($parque) {
                        $parques_relacionados[] = $parque;
                    }
                }
            }

            $info_arbol['parques_relacionados'] = $parques_relacionados;
          
          $info_arbol['parques_relacionados'] = $parques_relacionados;

            $sql_contenido = "
                SELECT id_contenido, numero, titulo, titulo_en, texto, texto_en
                FROM contenido
                WHERE tipo = 'arbol' AND id_referencia = :id
                ORDER BY numero ASC;";

            $stmt = $conn->prepare($sql_contenido);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $contenido = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contenido[] = $row;
            }
            $info_arbol['contenido'] = $contenido;
        } else {
            return array();
        }

        incrementarVisitasArbol($id);
        $_SESSION[$info_arbol['nombre']] = $info_arbol;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
 	$conn = null;
}


function incrementarVisitasArbol($arbol_id) {
    require_once("bbddconnect.php"); // Incluir bbddconnect.php dentro de la función

    try {
        // Iniciar una transacción
        $conn->beginTransaction();
        
        // Obtener las visitas actuales
        $stmt = $conn->prepare("SELECT visitas FROM arboles WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();
        $visitas = $stmt->fetchColumn();

        // Incrementar el contador de visitas
        $visitas++;

        // Actualizar el campo visitas en la tabla de arboles
        $stmt = $conn->prepare("UPDATE arboles SET visitas = :visitas WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':visitas', $visitas, PDO::PARAM_INT);
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        // Devolver el nuevo número de visitas
        return $visitas;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
  $conn = null;
}



function getInfoParque($id)
{
    require_once("bbddconnect.php");

    try {
        $sql_parque = "
	SELECT p.`nombre`,p.`direccion`,p.`transporte_bus`,p.`transporte_metro`,p.`transporte_renfe`,p.`latitud`,p.`longitud`,p.`imagen` 
	FROM p.`parques` p 
	WHERE p.`id_parque` = :id;        
        ";

        $stmt = $conn->prepare($sql_parque);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $info_parque = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info_parque) {
            $parques_relacionados = array();

            $sql_arboles_ids = "
            SELECT r.`relaciones`
            FROM `relacion` r
            WHERE r.`id_relacion` = :id;
            ";

            $stmt = $conn->prepare($sql_arboles_ids);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $row_arboles_ids = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row_arboles_ids) {
                $arboles_ids_relacion = explode(" ", $row_arboles_ids['relaciones']);

                foreach ($arboles_ids_relacion as $id_relacion) {
                    $sql_arbol = "
                    SELECT a.`id_arbol`, a.`nombre`
                    FROM `arboles` AS a
                    JOIN `relacion` r ON FIND_IN_SET(p.`id_arbol`, r.`relaciones`) > 0
                    WHERE r.`id_relacion` = :id_relacion
                    ";

                    $stmt = $conn->prepare($sql_arbol);

                    $stmt->bindParam(':id_relacion', $id_relacion, PDO::PARAM_INT);

                    $stmt->execute();

                    $arbol = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($arbol) {
                        $arboles_relacionados[] = $arbol;
                    }
                }
            }

            $info_parque['arboles_relacionados'] = $arboles_relacionados;
        } else {
            return array();
        }

        incrementarVisitasParque($id);
        $_SESSION[$info_parque['nombre']] = $info_parque;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
  $conn = null;
}

function incrementarVisitasParque($parque_id) {
    require_once("bbddconnect.php"); // Incluir bbddconnect.php dentro de la función

    try {
        // Iniciar una transacción
        $conn->beginTransaction();
        
        // Obtener las visitas actuales
        $stmt = $conn->prepare("SELECT visitas FROM parques WHERE id_parque = :parque_id");
        $stmt->bindParam(':parque_id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();
        $visitas = $stmt->fetchColumn();

        // Incrementar el contador de visitas
        $visitas++;

        // Actualizar el campo visitas en la tabla parques
        $stmt = $conn->prepare("UPDATE parques SET visitas = :visitas WHERE id_parque = :parque_id");
        $stmt->bindParam(':visitas', $visitas, PDO::PARAM_INT);
        $stmt->bindParam(':parque_id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        // Devolver el nuevo número de visitas
        return $visitas;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
  $conn = null;
}
