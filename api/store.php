<?php
/**
 * Password Hash Storage API
 * Stores and retrieves hashed passwords for the security demo
 * Supports multiple tables (1-50)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$dataFile = __DIR__ . '/hashes.json';

// Initialize data file if it doesn't exist
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode(['tables' => []]));
}

// Read current data
$data = json_decode(file_get_contents($dataFile), true);
if (!$data) {
    $data = ['tables' => []];
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Store a new hash for a table
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['hash']) || !isset($input['table'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing hash or table number']);
            exit();
        }

        $table = intval($input['table']);
        if ($table < 1 || $table > 50) {
            http_response_code(400);
            echo json_encode(['error' => 'Table must be between 1 and 50']);
            exit();
        }

        $data['tables'][$table] = [
            'hash' => $input['hash'],
            'timestamp' => date('c')
        ];

        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

        echo json_encode([
            'success' => true,
            'message' => "Hash stored for Table $table",
            'table' => $table
        ]);
        break;

    case 'GET':
        // Retrieve hash for a specific table
        $table = isset($_GET['table']) ? intval($_GET['table']) : null;

        if ($table === null) {
            // Return list of all tables that have hashes
            $tableList = [];
            foreach ($data['tables'] as $num => $info) {
                $tableList[] = [
                    'table' => intval($num),
                    'timestamp' => $info['timestamp']
                ];
            }
            echo json_encode([
                'success' => true,
                'tables' => $tableList
            ]);
        } else {
            // Return specific table's hash
            if (isset($data['tables'][$table])) {
                echo json_encode([
                    'success' => true,
                    'table' => $table,
                    'hash' => $data['tables'][$table]['hash'],
                    'timestamp' => $data['tables'][$table]['timestamp']
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'error' => "No hash found for Table $table",
                    'table' => $table
                ]);
            }
        }
        break;

    case 'DELETE':
        // Clear a specific table or all tables
        $table = isset($_GET['table']) ? intval($_GET['table']) : null;

        if ($table === null) {
            // Clear all tables
            $data['tables'] = [];
            file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
            echo json_encode([
                'success' => true,
                'message' => 'All tables cleared'
            ]);
        } else {
            // Clear specific table
            if (isset($data['tables'][$table])) {
                unset($data['tables'][$table]);
                file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
                echo json_encode([
                    'success' => true,
                    'message' => "Table $table cleared"
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'error' => "No hash found for Table $table"
                ]);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
