<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class status extends CI_Controller {
	protected $files = [];
	protected $specfiles = [];
	protected $dbfiles = [];
	protected $run = [];
	protected $databases = [];

	public function index() {
		$this->load->database();
	    $this->load->library('session');
		$this->load->library('form_validation');
		$databases = $this->input->post('databases');
		$run = $this->input->post('run');
		if ($databases) $this->databases = explode(PHP_EOL, $databases);
		if ($run) $this->run = $run;
		foreach ($this->db->get('migrations')->result() as $row) {
			$this->dbfiles[] = $row->archivo;
		}
		$this->checkPath(FCPATH . '/migrations');
		if (function_exists('migrations')) {
			$this->checkPath(migrations(), true);
		}
		if ($this->files || $this->specfiles) {
			$data['files'] = $this->files;
			$data['specfiles'] = $this->specfiles;
			$data['error'] = '';
			if ($this->files) {
				$data['error'] = 'Falta ejecutar los siguientes archivos <strong>generales</strong>: <ul><li>' . implode('</li><li>', $this->files) . '</li></ul>';
			}
			if ($this->specfiles) {
				$data['error'] .= 'Falta ejecutar los siguientes archivos <strong>específicos</strong>: <ul><li>' . implode('</li><li>', $this->specfiles) . '</li></ul>';
			}
		} else {
			$data['success'] = 'La base de datos se encuentra actualizada.';
		}
		//if ($this->queries) $data['queries'] = $this->queries;
		
		$this->load->view('database/status', $data);
		
	}

	protected function checkPath($path, $specific = false) {
		if (is_dir($path)) {
			$dir = dir($path);
			while ($file = $dir->read()) {
				if ($file[0] == '.') continue;
				if (!in_array($file, $this->dbfiles)) {
					if ($specific) {
						$this->specfiles[] = $file;
					} else {
						$this->files[] = $file;
					}
					if (in_array($file, $this->run)) {
						$query = '';
						$handle = fopen("$path/$file", 'r');
						while ($line = fgets($handle)) {
							if (strpos($line, '--') === 0) continue;
							if (trim($line)) {
								$query .= ' ' . trim($line);
							}
						}
						$query .= "INSERT IGNORE INTO migrations (archivo) VALUES ('$file');";
						fclose($handle);
						foreach ($this->databases as $db) {
							if ($db) {
								$this->queries[] = "USE $db; $query";
							} else {
								$this->queries[] = '';
							}
						}
					}
				}
			}
			$dir->close();
		}
	}
}
