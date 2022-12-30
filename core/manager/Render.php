<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use Dev4Press\v39\Core\Quick\Arr;
use Dev4Press\v39\Core\Quick\Sanitize;
use WP_Error;

class Render {
	public function __construct() {
	}

	public static function instance() : Render {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Render();
		}

		return $instance;
	}

	public function bulk( $type, $context = array() ) {
		$actions = gdfar()->actions()->get_actions( $type, 'bulk' );

		if ( empty( $actions ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		}

		$elements = $this->_bulk( $actions, $type, $context );

		if ( empty( $elements ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		} else {
			$render = '<form method="post" id="gdfar-manager-form-bulk">';
			$render .= '<input type="hidden" name="gdfar[action]" value="bulk" />';
			$render .= '<input type="hidden" name="gdfar[nonce]" value="' . wp_create_nonce( 'gdfar-manager-bulk-' . $type ) . '" />';
			$render .= '<input type="hidden" name="gdfar[type]" value="' . esc_attr( $type ) . '" />';
			$render .= '<div class="gdfar-manager-elements">';
			$render .= join( '', $elements );
			$render .= '</div>';
			$render .= $this->_log( $type );
			$render .= '</form>';

			return $render;
		}
	}

	public function edit( $type, $id, $context = array() ) {
		$actions = gdfar()->actions()->get_actions( $type, 'edit' );

		if ( empty( $actions ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		}

		$id = absint( $id );

		if ( ( $type == 'forum' && bbp_is_forum( $id ) ) || ( $type == 'topic' && bbp_is_topic( $id ) ) ) {
			$elements = $this->_edit( $actions, $type, $id, $context );

			if ( empty( $elements ) ) {
				return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
			} else {
				$render = '<form method="post" id="gdfar-manager-form-edit">';
				$render .= '<input type="hidden" name="gdfar[action]" value="edit" />';
				$render .= '<input type="hidden" name="gdfar[nonce]" value="' . wp_create_nonce( 'gdfar-manager-edit-' . $type . '-' . $id ) . '" />';
				$render .= '<input type="hidden" name="gdfar[type]" value="' . esc_attr( $type ) . '" />';
				$render .= '<input type="hidden" name="gdfar[id]" value="' . esc_attr( $id ) . '" />';
				$render .= '<div class="gdfar-manager-elements">';
				$render .= join( '', $elements );
				$render .= '</div>';
				$render .= $this->_log( $type );
				$render .= '</form>';

				return $render;
			}
		}

		return new WP_Error( 'object_not_found', __( "Request object not found.", "gd-forum-manager-for-bbpress" ) );
	}

	private function _bulk( $actions, $type, $context ) : array {
		$elements = array();

		$i = 1;

		foreach ( $actions as $action ) {
			$visible = apply_filters( $action['filter_visible'], true, array(
				'action'  => 'bulk',
				'context' => $context
			) );

			if ( $visible ) {
				$element = 'action-' . $action['name'] . '-' . str_pad( $i, 4, '0', STR_PAD_LEFT ) . '-' . rand( 1000, 9999 );

				$render = apply_filters( $action['filter_display'], '', array(
					'base'    => 'gdfar[field][' . $action['name'] . ']',
					'type'    => $type,
					'element' => $element,
					'context' => $context
				) );

				$label = $action['label'];

				if ( ! empty( $render ) && ! empty( $label ) ) {
					$classes = array(
						'gdfar-action',
						'gdfar-action-' . $action['name']
					);

					if ( ! empty( $action['class'] ) ) {
						$classes[] = $action['class'];
					}

					$notice = '';

					if ( ! empty( $action['notice'] ) && gdfar_settings()->get( 'notices_under_fields' ) ) {
						$notice = '<div class="gdfar-content-notice">' . $action['notice'] . '</div>';
					}

					$elements[] = '<dl class="' . Sanitize::html_classes( $classes ) . '"><dt>' .
					              '<div class="gdfar-label-wrapper"><label for="' . $element . '">' . $label . '</label></div>' .
					              '</dt><dd>' .
					              '<div class="gdfar-content-wrapper">' . $render . $notice . '</div>' .
					              '</dd></dl>';
				}
			}

			$i ++;
		}

		return $elements;
	}

	private function _edit( $actions, $type, $id, $context ) : array {
		$elements = array();

		$i = 1;

		foreach ( $actions as $action ) {
			$visible = apply_filters( $action['filter_visible'], true, array(
				'action'  => 'edit',
				'id'      => $id,
				'context' => $context
			) );

			if ( $visible ) {
				$element = 'action-' . $action['name'] . '-' . str_pad( $i, 4, '0', STR_PAD_LEFT ) . '-' . rand( 1000, 9999 );

				$render = apply_filters( $action['filter_display'], '', array(
					'id'      => $id,
					'base'    => 'gdfar[field][' . $action['name'] . ']',
					'type'    => $type,
					'element' => $element,
					'context' => $context
				) );

				$label = $action['label'];

				if ( ! empty( $render ) && ! empty( $label ) ) {
					$classes = array(
						'gdfar-action',
						'gdfar-action-' . $action['name']
					);

					if ( ! empty( $action['class'] ) ) {
						$classes[] = $action['class'];
					}

					$notice = '';

					if ( ! empty( $action['notice'] ) && gdfar_settings()->get( 'notices_under_fields' ) ) {
						$notice = '<div class="gdfar-content-notice">' . $action['notice'] . '</div>';
					}

					$elements[] = '<dl class="' . Sanitize::html_classes( $classes ) . '"><dt>' .
					              '<div class="gdfar-label-wrapper"><label for="' . $element . '">' . $label . '</label></div>' .
					              '</dt><dd>' .
					              '<div class="gdfar-content-wrapper">' . $render . $notice . '</div>' .
					              '</dd></dl>';
				}
			}

			$i ++;
		}

		return $elements;
	}

	private function _log( $type ) {
		if ( $type == 'topic' && gdfar_settings()->get( 'topic_edit_log' ) ) {
			$element = 'action-edit-log-9999' . '-' . rand( 1000, 9999 );

			$classes = array(
				'gdfar-action',
				'gdfar-action-edit-log'
			);

			return '<div class="gdfar-manager-edit-log"><dl class="' . Sanitize::html_classes( $classes ) . '"><dt>' .
			       '<div class="gdfar-label-wrapper"><label for="' . $element . '">' . __( "Edit Log", "gd-forum-manager-for-bbpress" ) . '</label></div>' .
			       '</dt><dd>' .
			       '<div class="gdfar-content-wrapper">' .
			       '<input type="checkbox" checked="checked" name="gdfar[edit-log][keep]" />' .
			       '<span>' . __( "Keep a log of this edit", "gd-forum-manager-for-bbpress" ) . '</span>' .
			       '</div>' .
			       '<input type="text" name="gdfar[edit-log][reason]" value="" placeholder="' . __( "Optional reason for editing", "gd-forum-manager-for-bbpress" ) . '" />' .
			       '</dd></dl></div>';
		}

		return '';
	}

	public function select( $values, $args = array(), $attr = array() ) : string {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'style'    => '',
			'title'    => '',
			'multi'    => false,
			'echo'     => false,
			'readonly' => false
		);
		$args     = wp_parse_args( $args, $defaults );

		/**
		 * @var mixed  $selected
		 * @var string $name
		 * @var string $id
		 * @var string $class
		 * @var string $style
		 * @var string $title
		 * @var bool   $multi
		 * @var bool   $echo
		 * @var bool   $readonly
		 */
		extract( $args );

		$render      = '';
		$attributes  = array();
		$selected    = is_null( $selected ) ? array_keys( $values ) : (array) $selected;
		$associative = ! Arr::is_associative( $values );
		$id          = $this->id_from_name( $name, $id );

		if ( $class != '' ) {
			$attributes[] = 'class="' . esc_attr( $class ) . '"';
		}

		if ( $style != '' ) {
			$attributes[] = 'style="' . esc_attr( $style ) . '"';
		}

		if ( $title != '' ) {
			$attributes[] = 'title="' . esc_attr( $title ) . '"';
		}

		if ( $multi ) {
			$attributes[] = 'multiple';
		}

		if ( $readonly ) {
			$attributes[] = 'readonly';
		}

		foreach ( $attr as $key => $value ) {
			$attributes[] = $key . '="' . esc_attr( $value ) . '"';
		}

		$name = $multi ? $name . '[]' : $name;

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( $name != '' ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		$render .= '<select ' . join( ' ', $attributes ) . '>';
		foreach ( $values as $value => $display ) {
			$real_value = $associative ? $display : $value;

			$sel    = in_array( $real_value, $selected ) ? ' selected="selected"' : '';
			$render .= '<option value="' . esc_attr( $value ) . '"' . $sel . '>' . esc_html( $display ) . '</option>';
		}
		$render .= '</select>';

		if ( $echo ) {
			echo $render;
		} else {
			return $render;
		}
	}

	private function id_from_name( $name, $id = '' ) : string {
		if ( $id == '' ) {
			$id = str_replace( ']', '', $name );
			$id = str_replace( '[', '_', $id );
		} else if ( $id == '_' ) {
			$id = '';
		}

		return (string) $id;
	}
}
