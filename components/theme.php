<?php

class QM_Theme extends QM {

	var $id = 'theme';

	function __construct() {
		parent::__construct();
		add_filter( 'body_class',          array( $this, 'body_class' ), 99 );
		add_filter( 'query_monitor_menus', array( $this, 'admin_menu' ), 100 );
	}

	function body_class( $class ) {
		$this->data['body_class'] = $class;
		return $class;
	}

	function process() {

		global $template;

		if ( is_admin() )
			return;

		$template_file        = self::standard_dir( $template );
		$stylesheet_directory = self::standard_dir( get_stylesheet_directory() );
		$template_directory   = self::standard_dir( get_template_directory() );

		$template_file = str_replace( $stylesheet_directory, '', $template_file );
		$template_file = str_replace( $template_directory,   '', $template_file );
		$template_file = ltrim( $template_file, '/' );

		$this->data['template_file'] = apply_filters( 'query_monitor_template', $template_file, $template );
		$this->data['stylesheet']    = get_stylesheet();
		$this->data['template']      = get_template();

	}

	function output( $args, $data ) {

		if ( empty( $data ) )
			return;

		echo '<div class="qm" id="' . $args['id'] . '">';
		echo '<table cellspacing="0">';
		echo '<thead>';
		echo '<tr>';
		echo '<th colspan="2">' . __( 'Theme', 'query-monitor' ) . '</th>';
		echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
		echo '<tr>';
		echo '<td>' . __( 'Template', 'query-monitor' ) . '</td>';
		echo "<td>{$this->data['template_file']}</td>";
		echo '</tr>';

		if ( !empty( $data['body_class'] ) ) {

			echo '<tr>';
			echo '<td rowspan="' . count( $data['body_class'] ) . '">' . __( 'Body Classes', 'query-monitor' ) . '</td>';
			$first = true;

			foreach ( $data['body_class'] as $class ) {

				if ( !$first )
					echo '<tr>';

				echo "<td>{$class}</td>";
				echo '</tr>';

				$first = false;

			}

		}

		echo '<tr>';
		echo '<td>' . __( 'Theme', 'query-monitor' ) . '</td>';
		echo "<td>{$this->data['stylesheet']}</td>";
		echo '</tr>';

		if ( $this->data['stylesheet'] != $this->data['template'] ) {
			echo '<tr>';
			echo '<td>' . __( 'Parent Theme', 'query-monitor' ) . '</td>';
			echo "<td>{$this->data['template']}</td>";
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '</div>';

	}

	function admin_menu( $menu ) {

		if ( isset( $this->data['template_file'] ) ) {
			$menu[] = $this->menu( array(
				'title' => sprintf( __( 'Template: %s', 'query-monitor' ), $this->data['template_file'] )
			) );
		}
		return $menu;

	}

}

function register_qm_theme( $qm ) {
	$qm['theme'] = new QM_Theme;
	return $qm;
}

add_filter( 'query_monitor_components', 'register_qm_theme', 60 );

?>