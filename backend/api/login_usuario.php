    <?php
    header('Content-Type: application/json');
    require_once '../../config/config.php';

    // Leer datos enviados (email y password)
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT id, nombre, password_hash FROM usuarios_especiales WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            echo json_encode([
                'success' => true,
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $email
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas.']);
        }

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor.',
            'error' => $e->getMessage()
        ]);
    }
