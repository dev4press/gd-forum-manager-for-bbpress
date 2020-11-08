<?php

namespace Dev4Press\Plugin\GDFAR\Admin\Panel;

use Dev4Press\Core\UI\Admin\PanelDashboard;
use Dev4Press\Plugin\GDFAR\Traits\Panel as TraitPanel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dashboard extends PanelDashboard {
	use TraitPanel;

	public function enqueue_scripts() {
		$this->local_enqueue_scripts( $this->a() );
	}
}
