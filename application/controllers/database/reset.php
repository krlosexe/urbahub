<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class reset extends MY_Controller {
	public function index() {
		if (!$this->validar_permiso('Database|Reset')) return;
		$this->load->helper('confirmation');
		$this->load->view('cabecera/cabecera');
		$this->load->view('database/reset');
		$this->load->view('cabecera/footer');
	}

	public function ajax() {
		if (!$this->validar_permiso('Database|Reset')) return;
		$reset = $this->input->post('reset');
		$last = $this->input->post('last');
		$tables = array(
			'cheques_factura',
			'cheques_facturaproveedor',
			'cheques',
			'detalle_asientos_contables',
			'caja_factura',
			'caja_facturaproveedor',
			'caja',
			'cierrez',
			'recibosdetalle',
			'recibos',
			'cuentacorriente',
			'cuentacorriente_vendedores',
			'detallecompras',
			'compras',
			'movimientosstock',
			'detallefactura',
			'facturas',
			'detallefacturaproveedor',
			'facturasproveedores',
			'detallenota',
			'notas',
			'detalleproduccion',
			'produccion',
			'detallepresupuestos',
			'presupuestos',
			'detallepedido',
			'pedidos',
			'inventario_producto',
			'stockdepositos',
			'inventario',
		);
		if (function_exists('procesar_tablas_database_reset')) $tables = procesar_tablas_database_reset($tables);
		$tablesConfirm = array(
			'productos' => array(
				'confirm' => '¿Vaciar base de datos de productos?',
				'tables' => array(
					'productos',
					'tipoproductos',
				),
			),
			'empleados' => array(
				'confirm' => '¿Vaciar base de datos de empleados?',
				'tables' => array(
					'empleados',
				),
			),
			'clientes' => array(
				'confirm' => '¿Vaciar base de datos de clientes / proveedores?',
				'tables' => array(
					'productos_especiales',
					'sucursalcliente',
					'clientes',
					'proveedores',
				),
			),
			'sucursales' => array(
				'confirm' => '¿Vaciar base de datos de sucursales / depósitos / cajas?',
				'tables' => array(
					'depositos',
					'entidadescaja',
					'emisor_sucursal',
					'sucursales'
				),
			),
			'usuarios' => array(
				'confirm' => '¿Vaciar base de datos de usuarios / vendedores?',
				'tables' => array(
					'vendedores',
					'usuarios'
				),
			),
		);
		if ($reset) {
			$json = array(
				'done' => false,
				'confirm' => false
			);
			$truncate = false;
			if ($last) {
				if (in_array($last, $tables)) {
					$truncate = array($last);
					$key = array_search($last, $tables);
					$nextKey = $key + 1;
					if (isset($tables[$nextKey])) {
						$json['current'] = $tables[$nextKey];
					} else {
						$keys = array_keys($tablesConfirm);
						$key = reset($keys);
						$json['current'] = $key;
						$json['confirm'] = $tablesConfirm[$key]['confirm'];
					}
				} else if (isset($tablesConfirm[$last])) {
					$truncate = $tablesConfirm[$last]['tables'];
					$keys = array_keys($tablesConfirm);
					$key = array_search($last, $keys);
					$nextKey = $key + 1;
					if (isset($tablesConfirm[$keys[$nextKey]])) {
						$json['current'] = $keys[$nextKey];
						$json['confirm'] = $tablesConfirm[$keys[$nextKey]]['confirm'];
					} else {
						$json['done'] = true;
					}
				} else {
					$json['done'] = true;
				}
			} else {
				$json['current'] = reset($tables);
			}
			if ($truncate) {
				$this->load->database();
				foreach ($truncate as $table) {
					if ($table == 'facturas') $this->db->update('notas', ['idfactura' => null]);
					if ($table == 'notas')    $this->db->update('facturas', ['idnota' => null]);
					if ($this->db->query("DELETE FROM $table WHERE 1")) {
						$this->db->query("ALTER TABLE $table AUTO_INCREMENT = 1");
						if ($table == 'movimientosstock') {
							$this->db->query('UPDATE productos SET stock = 0');
						}
					} else {
						$json['error'] = $this->db->_error_message();
					}
				}
			}
			die(json_encode($json));
		}
	}
}
