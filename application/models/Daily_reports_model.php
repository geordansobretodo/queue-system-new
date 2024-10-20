<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daily_reports_model extends CI_Model
{

    public function getTotalServed($selectedDate)
    {
        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'served');
        $regular = $this->db->count_all_results('regular_queue');

        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'served');
        $priority = $this->db->count_all_results('priority_queue');

        return $regular + $priority;
    }

    public function getTotalPending($selectedDate)
    {
        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'pending');
        $regular = $this->db->count_all_results('regular_queue');

        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'pending');
        $priority = $this->db->count_all_results('priority_queue');

        return $regular + $priority;
    }

    public function getPeakHour($selectedDate)
    {
        $query = $this->db->query("
            SELECT DATE_FORMAT(queue_time, '%h:%i %p') as startHour, 
                   DATE_FORMAT(queue_time + INTERVAL 1 HOUR, '%h:%i %p') as endHour, 
                   COUNT(*) as peakHourCount 
            FROM (
                (SELECT queue_time FROM regular_queue WHERE DATE(queue_time) = ?)
                UNION ALL
                (SELECT queue_time FROM priority_queue WHERE DATE(queue_time) = ?)
            ) AS combined
            GROUP BY startHour, endHour
            ORDER BY peakHourCount DESC LIMIT 1", array($selectedDate, $selectedDate));

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['startHour'] . ' - ' . $row['endHour'];
        }
        return '';
    }

    public function getMissedQueues($selectedDate)
    {
        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'No show');
        $regular = $this->db->count_all_results('regular_queue');

        $this->db->where('DATE(queue_time)', $selectedDate);
        $this->db->where('status', 'No show');
        $priority = $this->db->count_all_results('priority_queue');

        return $regular + $priority;
    }

    public function getRegularAverageServiceTime($selectedDate)
    {
        $query = $this->db->query("SELECT 
            CONCAT(
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 3600) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 3600), ' hours, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR((AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 3600) / 60) > 0 
                        THEN CONCAT(FLOOR((AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 3600) / 60), ' minutes, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 60) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 60), ' seconds.') 
                        ELSE '' 
                    END
                ) AS average_service_time 
            FROM regular_queue
            WHERE cashierid IS NOT NULL AND status = 'served' AND DATE(queue_time) = ?", array($selectedDate));
    
        $row = $query->row_array();
        $averageServiceTimeSeconds = isset($row['average_service_time']) ? $row['average_service_time'] : 0;
    
        return $averageServiceTimeSeconds;
    }

    public function getPriorityAverageServiceTime($selectedDate)
    {
        $query = $this->db->query("SELECT 
            CONCAT(
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 3600) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 3600), ' hours, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR((AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 3600) / 60) > 0 
                        THEN CONCAT(FLOOR((AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 3600) / 60), ' minutes, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 60) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) % 60), ' seconds.') 
                        ELSE '' 
                    END
                ) AS average_service_time 
            FROM priority_queue
            WHERE cashierid IS NOT NULL AND status = 'served' AND DATE(queue_time) = ?", array($selectedDate));
    
        $row = $query->row_array();
        $averageServiceTime = isset($row['average_service_time']) ? $row['average_service_time'] : 0;
    
        return $averageServiceTime;
    }   
}
