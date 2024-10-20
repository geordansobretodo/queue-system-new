<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Set_views
{
	private $main = 'pages';


	public function admin_dashboard()
	{
		return $this->main . '/admin_dashboard';
	}
	
	public function cashier_list()
	{
		return $this->main . '/cashier_list';
	}

	public function cashier_metrics()
	{
		return $this->main . '/cashier_metrics';
	}

	public function customer_details()
	{
		return $this->main . '/customer_details';
	}

	public function daily_reports()
	{
		return $this->main . '/daily_reports';
	}

	public function transaction_list()
	{
		return $this->main . '/transaction_list';
	}

	public function user()
	{
		return $this->main . '/user';
	}
}
