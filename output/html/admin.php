<?php
/*
Copyright 2009-2017 John Blackbourn

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class QM_Output_Html_Admin extends QM_Output_Html {

	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );
		add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 60 );
	}

	public function output() {

		$data = $this->collector->get_data();

		if ( empty( $data['current_screen'] ) ) {
			return;
		}

		echo '<div class="qm qm-non-tabular" id="' . esc_attr( $this->collector->id() ) . '">';
		echo '<div class="qm-boxed qm-boxed-wrap">';

		echo '<div class="qm-section">';
		echo '<h2>get_current_screen()</h2>';

		echo '<table>';
		echo '<thead class="screen-reader-text">';
		echo '<tr>';
		echo '<th>' . esc_html__( 'Property', 'query-monitor' ) . '</th>';
		echo '<th>' . esc_html__( 'Value', 'query-monitor' ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		foreach ( $data['current_screen'] as $key => $value ) {
			echo '<tr>';
			echo '<td>' . esc_html( $key ) . '</td>';
			echo '<td>' . esc_html( $value ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '</div>';

		echo '<div class="qm-section">';
		echo '<h2>$pagenow</h2>';
		echo '<p>' . esc_html( $data['pagenow'] ) . '</p>';
		echo '</div>';

		if ( ! empty( $data['list_table'] ) ) {

			echo '<div class="qm-section">';
			echo '<h2>' . esc_html__( 'List Table', 'query-monitor' ) . '</h2>';
			echo '<h3>' . esc_html__( 'Column Filters:', 'query-monitor' ) . '</h3>';
			echo '<p><code>' . $data['list_table_markup']['columns_filter'] . '</code></p>'; // WPCS: XSS ok;
			echo '<p><code>' . $data['list_table_markup']['sortables_filter'] . '</code></p>'; // WPCS: XSS ok;
			echo '<h3>' . esc_html__( 'Column Action:', 'query-monitor' ) . '</h3>';
			echo '<p><code>' . $data['list_table_markup']['column_action'] . '</code></p>'; // WPCS: XSS ok;
			echo '</div>';

		}

		echo '</div>';
		echo '</div>';
	}

}

function register_qm_output_html_admin( array $output, QM_Collectors $collectors ) {
	if ( $collector = QM_Collectors::get( 'admin' ) ) {
		$output['admin'] = new QM_Output_Html_Admin( $collector );
	}
	return $output;
}

add_filter( 'qm/outputter/html', 'register_qm_output_html_admin', 70, 2 );
