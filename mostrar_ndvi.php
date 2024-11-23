<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla NDVI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input {
            width: calc(100% - 10px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #d1e7fd;
        }

        table a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        table a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            form, table {
                width: 95%;
            }

            form input, form button {
                font-size: 14px;
            }

            table th, table td {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <h1>Datos del Índice de Vegetación de Diferencia Normalizada en La Candelaria</h1>

    <!-- Agregar Datos -->
    <form method="POST" action="">
        <label for="mes">Mes:</label>
        <input type="text" id="mes" name="mes" placeholder="Ejemplo: Enero" required>

        <label for="dia">Día:</label>
        <input type="number" id="dia" name="dia" placeholder="Ejemplo: 15" required>

        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" placeholder="Ejemplo: 2024" required>

        <label for="ndvi">NDVI:</label>
        <input type="number" step="0.001" id="ndvi" name="ndvi" placeholder="Ejemplo: 0.789" required>

        <button type="submit">Agregar</button>
    </form>

    <h2>Tabla de NDVI</h2>
    <table>
        <tr>
            <th>Mes</th>
            <th>Día</th>
            <th>Año</th>
            <th>NDVI</th>
            <th>Acción</th>
        </tr>

        <?php
        // PHP para manejar la conexión, inserción y eliminación.
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $conn = new mysqli('localhost', 'root', '', 'localidad_candelaria', 3307);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if (isset($_GET['eliminar_id'])) {
            $id = $_GET['eliminar_id'];
            $sql_delete = "DELETE FROM ndvi_candelaria WHERE id = $id";
            $conn->query($sql_delete);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mes = $_POST['mes'];
            $dia = $_POST['dia'];
            $anio = $_POST['anio'];
            $ndvi = $_POST['ndvi'];

            $sql_insert = "INSERT INTO ndvi_candelaria (Mes, Dia, Año, NDVI) VALUES ('$mes', $dia, $anio, $ndvi)";
            $conn->query($sql_insert);
        }

        $sql = "SELECT * FROM ndvi_candelaria ORDER BY Año DESC, Mes DESC, Dia DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['Mes'] . "</td>
                        <td>" . $row['Dia'] . "</td>
                        <td>" . $row['Año'] . "</td>
                        <td>" . $row['NDVI'] . "</td>
                        <td><a href='?eliminar_id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este dato?\")'>Eliminar</a></td>
                        </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay datos</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>