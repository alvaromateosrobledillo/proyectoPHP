<?php
include(__DIR__ . "/db.php");
include(__DIR__ . "/templates/header.php");

if (!isset($_SESSION["logedin"]) || $_SESSION["logedin"] !== true) {
    header("Location: login.php");
    die();
}

// Acceder a los datos del usuario desde el array de sesión "user_data"
$userData = $_SESSION["user_data"];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["codigo"]) && !empty($_POST["codigo"])) {

    $stmt = $mysqli->prepare("SELECT `idUsuario` FROM `CodigoSorteo` WHERE `codigoId` = ? ");
    $stmt->bind_param("i", $_POST["codigo"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // El código ya ha sido vinculado
        $row = $result->fetch_assoc();
        $usuarioVinculadoId = $row['idUsuario'];

        echo "<div class=\"container justify-content-center p-5 bg-white rounded-5 w-25 m-5\">";
        echo "<p class=\"p-5 text-black fw-bold fs-2\">El código ";
        

        if ($usuarioVinculadoId === $userData["id"]) {
            echo " ya lo tienes vinculado</p>"; 
        } else {
            echo " está vinculado a otro usuario </p>";
        }
    
        echo "</div>";
    } else {

    
    insertarCodigo($_POST["codigo"], $userData["id"]);
?>
    <div class="container justify-content-center p-5 bg-white rounded-5 w-25 m-5">
        <p class="p-5 text-black fw-bold fs-2"> Mucha suerte <?=$userData["nombre"]?> guarda tú número y pronto entrarás con el siguiente código <?=$_POST["codigo"]?> en el sorteo"</p>
    </div>
<?php
}
} 
?>
<div class="bg-primary d-flex justify-content-center align-items-cente align-items-center vh-100">
    <div class="container justify-content-center p-5 bg-white rounded-5 w-75 m-5">
        <h2 class="text-center p-5 text-dark fw-bold fs-2 "> Bienvenido al sorteo <?= $userData["nombre"] ?>  
        </h2>
        <form class="input-group gap-3 m-3" method="POST">
            <label for="telefono" class="form-label">Guardar código</label>
            <input type="text" class="form-control" name="codigo">
            <div class="d-flex justify-content-end gap-3">
                <button type="submit" class="btn btn-primary">guardar código</button>
                <a href="datos-personales.php" class="btn btn-primary">Ver todos los códigos</a>
            </div>
        </form>
    </div>
</div>
<?php require_once(__DIR__ . "/templates/footer.php"); ?>

