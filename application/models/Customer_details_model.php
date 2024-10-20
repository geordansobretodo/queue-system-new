<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_details_model extends CI_Model
{
    public function getFilteredData($startDate, $endDate)
    {
        $query = $this->db->query(
            "SELECT  q.name, q.student_number, q.service_type, q.payment_for, q.payment_mode, q.email, q.queue_number, q.queue_time, q.status, c.name AS cashier_name,
            CONCAT (
                CASE WHEN FLOOR(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) / 3600) > 0 
                    THEN CONCAT(
                        FLOOR(TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) / 3600), ' hours, ') 
                    ELSE '' END,
                CASE WHEN FLOOR((TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) % 3600) / 60) > 0 
                    THEN CONCAT(
                        FLOOR((TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) % 3600) / 60), ' minutes, ') 
                    ELSE '' END,
                CASE WHEN (TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) % 60) > 0 
                    THEN CONCAT(
                        (TIMESTAMPDIFF(SECOND, q.start_service_time, q.end_service_time) % 60), ' seconds.') 
                    ELSE '' END
            ) AS average_service_time
        FROM (
            SELECT * FROM regular_queue WHERE DATE(queue_time) BETWEEN ? AND ?
            UNION ALL
            SELECT * FROM priority_queue WHERE DATE(queue_time) BETWEEN ? AND ?
            ) AS q
        LEFT JOIN cashier c ON c.id = q.cashierid",
            array($startDate, $endDate, $startDate, $endDate)
        );

        return $query->result_array();
    }
}
