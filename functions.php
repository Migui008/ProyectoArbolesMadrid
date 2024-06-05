<?php
function getAllArboles() {
    session_start();
    require_once('bbddconnect.php');
    global $conn; 

    $sql = "SELECT nombre FROM arboles";
    try {
        $result = $conn->query($sql);
        if ($result === false) {
        } else {
            $arboles = [];
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($arboles, $row['nombre']);
                }
                $_SESSION['arbolesAllNames'] = $arboles;
            }
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage() . "<br>";
    }
    $conn = null;
}

function getAllParques() {
    session_start();
    require_once('bbddconnect.php');
    global $conn; 

    $sql = "SELECT nombre FROM parques";
    try {
        $result = $conn->query($sql);
        if ($result === false) {
        } else {
            $parques = [];
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($parques, $row['nombre']);
                }
                $_SESSION['parquesAllNames'] = $parques;
            }
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage() . "<br>";
    }
    $conn = null;
}

function mostVisited() {
    require_once("bbddconnect.php");
    global $conn; 

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

function getInfoArbol($id) {
    require_once("bbddconnect.php");
    global $conn;

    try {
        $sql_arbol = "
        SELECT a.`nombre`, a.`nombre_cientifico`, a.`familia`, a.`clase`, a.`imagen`, r.`relaciones`
        FROM `arboles` a
        LEFT JOIN `relacion` r ON a.`id_relacion` = r.`id_relacion`
        WHERE a.`id_arbol` = :id;
        ";

        $stmt = $conn->prepare($sql_arbol);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $info_arbol = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info_arbol) {
            $parques_ids = explode(' ', $info_arbol['relaciones']);

            if (!empty($parques_ids[0])) {
                $placeholders = implode(',', array_fill(0, count($parques_ids), '?'));

                $sql2 = "SELECT `nombre` FROM `parques` WHERE `id_relacion` IN ($placeholders)";
                
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute($parques_ids);

                $parques_relacionados = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                $info_arbol['parques_relacionados'] = array_column($parques_relacionados, 'nombre');
            } else {
                $info_arbol['parques_relacionados'] = [];
            }

            $sql_contenido = "
                SELECT id_contenido, numero, titulo, titulo_en, texto, texto_en
                FROM contenido
                WHERE tipo = 'arbol' AND id_referencia = :id
                ORDER BY numero ASC;
            ";

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
    require_once("bbddconnect.php");
    global $conn;

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT visitas FROM arboles WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();
        $visitas = $stmt->fetchColumn();

        $visitas++;

        $stmt = $conn->prepare("UPDATE arboles SET visitas = :visitas WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':visitas', $visitas, PDO::PARAM_INT);
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();
        return $visitas;
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
    $conn = null;
}

function getInfoParque($id) {
    require_once("bbddconnect.php");
    global $conn; 

    try {
        $sql = "
            SELECT p.`nombre`, p.`direccion`, p.`transporte_bus`, p.`transporte_metro`, p.`transporte_renfe`, 
                   p.`latitud`, p.`longitud`, p.`imagen`, r.`relaciones`
            FROM `parques` p
            LEFT JOIN `relacion` r ON p.`id_relacion` = r.`id_relacion`
            WHERE p.`id_parque` = :id;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $info_parque = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($info_parque) {
            $arboles_ids = explode(' ', $info_parque['relaciones']);
            

            if (!empty($arboles_ids)) {

                $placeholders = implode(',', array_fill(0, count($arboles_ids), '?'));

                $sql2 = "SELECT `nombre` FROM `arboles` WHERE `id_relacion` IN ($placeholders)";
                
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute($arboles_ids);

                $arboles_relacionados = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                $info_parque['arboles_relacionados'] = $arboles_relacionados;
            } else {
                $info_parque['arboles_relacionados'] = [];
            }

            $sql_contenido = "
                SELECT id_contenido, numero, titulo, titulo_en, texto, texto_en
                FROM contenido
                WHERE tipo = 'parque' AND id_referencia = :id
                ORDER BY numero ASC;
            ";

            $stmt = $conn->prepare($sql_contenido);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $contenido = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contenido[] = $row;
            }
            $info_parque['contenido'] = $contenido;
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
    require_once("bbddconnect.php"); 
  	global $conn;

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
?>
