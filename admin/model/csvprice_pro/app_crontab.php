<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppCrontab extends Model
{
    public function getJobById($job_id)
    {
        $result = $this->db->query("SELECT c.*, DATE_FORMAT(c.job_time_start,'%H') AS time_start_h, DATE_FORMAT(c.job_time_start,'%i') AS time_start_i, p.name AS profile_name\n\t\t\tFROM " . DB_PREFIX . "csvprice_pro_crontab c\n\t\t\tLEFT JOIN " . DB_PREFIX . "csvprice_pro_profiles p ON (c.profile_id = p.profile_id)\n\t\t\tWHERE c.job_id = '" . (int) $job_id . " LIMIT 1'\n\t\t");
        $data = [];
        if ($result->num_rows) {
            $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_ = json_decode(base64_decode($result->row["job_data"]), true);
            $data = ["job_id" => $result->row["job_id"], "profile_id" => $result->row["profile_id"], "profile_name" => $result->row["profile_name"], "job_key" => $result->row["job_key"], "job_type" => $result->row["job_type"], "job_file_location" => $result->row["job_file_location"], "job_time_start" => ["H" => $result->row["time_start_h"], "i" => $result->row["time_start_i"]], "job_offline" => $result->row["job_offline"], "status" => $result->row["status"], "ftp_host" => isset($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_host"]) ? $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_host"] : "", "ftp_user" => isset($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_user"]) ? $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_user"] : "", "ftp_passwd" => isset($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_passwd"]) ? $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["ftp_passwd"] : "", "file_path" => isset($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["file_path"]) ? $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_["file_path"] : ""];
        }
        return $data;
    }
    public function getProfilesByType($type)
    {
        $type = "profile_" . $type;
        $result = $this->db->query("SELECT profile_id, name\n\t\t\tFROM " . DB_PREFIX . "csvprice_pro_profiles\n\t\t\tWHERE `key` = '" . $this->db->escape($type) . "'\n\t\t");
        $data = [];
        if ($result->num_rows) {
            foreach ($result->rows as $row) {
                $data[] = ["profile_id" => $row["profile_id"], "name" => $row["name"]];
            }
        }
        return $data;
    }
    public function getJobsList()
    {
        $result = $this->db->query("SELECT c.*,  DATE_FORMAT(c.job_time_start,'%H:%i') AS time_start, p.name AS profile_name FROM " . DB_PREFIX . "csvprice_pro_crontab c LEFT JOIN " . DB_PREFIX . "csvprice_pro_profiles p ON (c.profile_id = p.profile_id)");
        $data = [];
        if ($result->num_rows) {
            foreach ($result->rows as $row) {
                $data[] = ["job_id" => $row["job_id"], "profile_id" => $row["profile_id"], "profile_name" => $row["profile_name"], "job_key" => $row["job_key"], "job_type" => $row["job_type"], "job_file_location" => $row["job_file_location"], "job_time_start" => $row["time_start"], "job_offline" => $row["job_offline"], "job_data" => $row["job_data"], "status" => $row["status"]];
            }
        }
        return $data;
    }
    public function addJob($data)
    {
        $data["ftp_host"] = isset($data["ftp_host"]) ? $data["ftp_host"] : "";
        $data["ftp_user"] = isset($data["ftp_user"]) ? $data["ftp_user"] : "";
        $data["ftp_passwd"] = isset($data["ftp_passwd"]) ? $data["ftp_passwd"] : "";
        $data["file_path"] = isset($data["file_path"]) ? $data["file_path"] : "";
        $_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_ = base64_encode(json_encode(["ftp_host" => $data["ftp_host"], "ftp_user" => $data["ftp_user"], "ftp_passwd" => $data["ftp_passwd"], "file_path" => $data["file_path"]]));
        $data["job_time_start"] = "2015-01-01 " . $data["job_time_start"]["H"] . ":" . $data["job_time_start"]["i"] . ":00";
        if (!$data["job_id"]) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "csvprice_pro_crontab\n\t\t\t\tSET\n\t\t\t\t\t`profile_id` = " . (int) $data["profile_id"] . ",\n\t\t\t\t\t`job_key` = '" . $this->db->escape($data["job_key"]) . "',\n\t\t\t\t\t`job_type` = '" . $this->db->escape($data["job_type"]) . "',\n\t\t\t\t\t`job_file_location` = '" . $this->db->escape($data["job_file_location"]) . "',\n\t\t\t\t\t`job_time_start` = '" . $this->db->escape($data["job_time_start"]) . "',\n\t\t\t\t\t`job_offline` = '" . (int) $data["job_offline"] . "',\n\t\t\t\t\t`job_data` = '" . $this->db->escape($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_) . "',\n\t\t\t\t\t`status` = '" . (int) $data["status"] . "'\n  \t\t\t");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "csvprice_pro_crontab\n\t\t\t\tSET\n\t\t\t\t\t`profile_id` = " . (int) $data["profile_id"] . ",\n\t\t\t\t\t`job_key` = '" . $this->db->escape($data["job_key"]) . "',\n\t\t\t\t\t`job_type` = '" . $this->db->escape($data["job_type"]) . "',\n\t\t\t\t\t`job_file_location` = '" . $this->db->escape($data["job_file_location"]) . "',\n\t\t\t\t\t`job_time_start` = '" . $this->db->escape($data["job_time_start"]) . "',\n\t\t\t\t\t`job_offline` = '" . (int) $data["job_offline"] . "',\n\t\t\t\t\t`job_data` = '" . $this->db->escape($_obfuscated_0D172F0B340814062F042D24210D3C1B5C100D331A2E32_) . "',\n\t\t\t\t\t`status` = '" . (int) $data["status"] . "'\n\t\t\t\tWHERE\n\t\t\t\t\t`job_id` = '" . (int) $data["job_id"] . "'\n  \t\t\t");
        }
        return true;
    }
    public function deleteJob($job_id)
    {
        if ($job_id) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "csvprice_pro_crontab WHERE `job_id` = '" . (int) $job_id . "'");
        }
        return true;
    }
    public function getDateOptions()
    {
        $data["H"] = [];
        $data["i"] = [];
        $i = strtotime("00:00");
        while ($i <= strtotime("23:00")) {
            $data["H"][] = date("H", $i);
            $i = strtotime("+60 minutes", $i);
        }
        $i = strtotime("00:00");
        while ($i <= strtotime("00:55")) {
            $data["i"][] = date("i", $i);
            $i = strtotime("+5 minutes", $i);
        }
        return $data;
    }
}

?>