<?php

namespace hobotix\SessionHandler;

class SessionHandler implements \SessionHandlerInterface
{

    protected $dbConnection = null;
    protected $dbTable = null;
    public $lifeTime = 518400;

    public function setDbDetails($dbHost, $dbUser, $dbPassword, $dbDatabase)
    {
        if (stripos($dbHost, 'sock')) {
            $dbSocket = $dbHost;
            $dbHost = NULL;
        } else {
            $dbSocket = false;
        }

        $this->dbConnection = new \mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase, 3306, $dbSocket);

        if (mysqli_connect_error()) {
            throw new \Exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }

    public function setDbConnection($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function setDbTable($dbTable)
    {
        $this->dbTable = $dbTable;
    }

    public function setSessionLifeTime($lifeTime)
    {

        $this->lifeTime = $lifeTime;

    }

    public static function unserialize($session_data)
    {
        $method = ini_get("session.serialize_handler");
        return match ($method) {
            "php" => self::unserialize_php($session_data),
            "php_binary" => self::unserialize_phpbinary($session_data),
            default => throw new \Exception("Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary"),
        };
    }

    private static function unserialize_php($session_data)
    {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|")) {
                throw new \Exception("invalid data, remaining: " . substr($session_data, $offset));
            }
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        return $return_data;
    }

    private static function unserialize_phpbinary($session_data)
    {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            $num = ord($session_data[$offset]);
            $offset += 1;
            $varname = substr($session_data, $offset, $num);
            $offset += $num;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        return $return_data;
    }

    public function open($path, $name): bool
    {
        if ($this->dbConnection) {
            return true;
        } else {
            return false;
        }
    }

    public function close(): bool
    {
        return $this->dbConnection->close();
    }

    private function escape($value): string
    {
        return $this->dbConnection->real_escape_string($value);
    }

    public function read($id): string
    {
        $id = $this->escape($id);
        $sql = "SELECT `data` FROM $this->dbTable WHERE id = '$id' LIMIT 1";

        try {
            if ($result = $this->dbConnection->query($sql)) {
                if ($result->num_rows) {
                    $data = array();

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    return $data[0]['data'] ?? '';

                } else {
                    return '';
                }

            } else {
                return '';
            }

        } catch (\Exception $e) {
            echoLine('[SessionDB::write] Error ' . $e->getMessage(), 'e');
            return '';
        }
    }

    public function write($id, $data): bool
    {
        $id = $this->escape($id);
        $data = $this->escape($data);
        $timestamp = time();

        $sql = "REPLACE INTO $this->dbTable (`id`, `data`, `timestamp`) VALUES('$id', '$data', '$timestamp')";

        try {
            $result = $this->dbConnection->query($sql);
        } catch (\Exception $e) {
            echoLine('[SessionDB::write] Error ' . $e->getMessage(), 'e');
            $result = false;
        }

        return $result;
    }

    public function destroy($id): bool
    {
        $id = $this->escape($id);

        $sql = "DELETE FROM $this->dbTable WHERE id = '$id'";

        try {
            $result = $this->dbConnection->query($sql);
        } catch (\Exception $e) {
            echoLine('[SessionDB::write] Error ' . $e->getMessage(), 'e');
            $result = false;
        }

        return $result;
    }

    public function gc($max_lifetime): int
    {
        $time = time() - intval($max_lifetime);

        $sql = "DELETE FROM $this->dbTable WHERE timestamp < $time";

        echoLine('[SessionDB] Garbage collector run ' . $sql, 'w');

        return $this->dbConnection->query($sql);
    }
}
