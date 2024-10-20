<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cashier_metrics_model extends CI_Model
{
    public function getFilteredData($startDate, $endDate)
    {
        $query = $this->db->query(
            "SELECT 
                c.name,
                COUNT(q.status) AS total_transactions, 
                SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) AS total_completed, 
                SUM(CASE WHEN q.status = 'no show' THEN 1 ELSE 0 END) AS total_noshows,
                CONCAT(
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) / 3600) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) / 3600), ' hours, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR((AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 3600) / 60) > 0 
                        THEN CONCAT(FLOOR((AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 3600) / 60), ' minutes, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 60) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 60), ' seconds.') 
                        ELSE '' 
                    END
                ) AS average_service_time,
                CONCAT (
                    MIN(queue_time), ' to ', MAX(queue_time)
                ) AS date_range
            FROM (
                SELECT * FROM regular_queue WHERE DATE(queue_time) BETWEEN ? AND ?
                UNION ALL 
                SELECT * FROM priority_queue WHERE DATE(queue_time) BETWEEN ? AND ?
            ) AS q
            LEFT JOIN cashier c ON c.id = q.cashierid
            WHERE q.cashierid IS NOT NULL
            GROUP BY c.id, c.name",
            array($startDate, $endDate, $startDate, $endDate)
        );
    
        return $query->result_array();
    }

    function cashierMetrics($startDate, $endDate) 
    {
        $query = $this->db->query(
            "SELECT 
                COUNT(q.status) AS total_transactions,
                SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) AS total_completed,
                SUM(CASE WHEN q.status = 'no show' THEN 1 ELSE 0 END) AS total_noshows,
                CONCAT(
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) / 3600) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) / 3600), ' hours, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR((AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 3600) / 60) > 0 
                        THEN CONCAT(FLOOR((AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 3600) / 60), ' minutes, ') 
                        ELSE '' 
                    END,
                    CASE 
                        WHEN FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 60) > 0 
                        THEN CONCAT(FLOOR(AVG(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time)) % 60), ' seconds.') 
                        ELSE '' 
                    END
                ) AS average_service_time,
                CONCAT (
                    MIN(DATE(queue_time)), ' - ', MAX(DATE(queue_time))
                ) AS date_range
            FROM (
                SELECT * FROM regular_queue WHERE DATE(queue_time) BETWEEN ? AND ?
                UNION ALL 
                SELECT * FROM priority_queue WHERE DATE(queue_time) BETWEEN ? AND ?
            ) AS q
            WHERE q.cashierid IS NOT NULL",
            array($startDate, $endDate, $startDate, $endDate)
        );
    
        return $query->row_array();
    }
    
    
}
