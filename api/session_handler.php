<?php
class MySQLSessionHandler implements SessionHandlerInterface {
    private $conn;
    private $table = 'sessions';

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function open($savePath, $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string {
        $stmt = $this->conn->prepare("SELECT data FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($data);
        $stmt->fetch();
        $stmt->close();
        return $data ?: '';
    }

    public function write($id, $data): bool {
        $timestamp = time();
        $stmt = $this->conn->prepare("REPLACE INTO {$this->table} (id, data, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $id, $data, $timestamp);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function destroy($id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function gc($max_lifetime): int|false {
        $old = time() - $max_lifetime;
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE timestamp < ?");
        $stmt->bind_param("i", $old);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();
        return $deleted;
    }
}
?>
