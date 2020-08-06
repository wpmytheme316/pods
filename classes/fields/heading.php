<?php

/**
 * @package Pods\Fields
 */
class PodsField_Heading extends PodsField {

	/**
	 * {@inheritdoc}
	 */
	public static $group = 'Layout Elements';

	/**
	 * {@inheritdoc}
	 */
	public static $type = 'heading';

	/**
	 * {@inheritdoc}
	 */
	public static $label = 'Heading';

	/**
	 * {@inheritdoc}
	 */
	public static $prepare = '%s';

	/**
	 * {@inheritdoc}
	 */
	public function setup() {
		static::$group = __( 'Layout Elements', 'pods' );
		static::$label = __( 'Heading', 'pods' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function options() {
		return [
			'output_options' => [
				'label' => __( 'Output Options', 'pods' ),
				'group' => [
					static::$type . '_allow_html'      => [
						'label'      => __( 'Allow HTML?', 'pods' ),
						'default'    => 1,
						'type'       => 'boolean',
						'dependency' => true,
					],
					static::$type . '_wptexturize'     => [
						'label'   => __( 'Enable wptexturize?', 'pods' ),
						'default' => 1,
						'type'    => 'boolean',
						'help'    => [
							__( 'Transforms less-beautiful text characters into stylized equivalents.', 'pods' ),
							'http://codex.wordpress.org/Function_Reference/wptexturize',
						],
					],
					static::$type . '_allow_shortcode' => [
						'label'      => __( 'Allow Shortcodes?', 'pods' ),
						'default'    => 0,
						'type'       => 'boolean',
						'dependency' => true,
						'help'       => [
							__( 'Embed [shortcodes] that help transform your static content into dynamic content.', 'pods' ),
							'http://codex.wordpress.org/Shortcode_API',
						],
					],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function schema( $options = null ) {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function input( $name, $value = null, $options = null, $pod = null, $id = null ) {
		$options = (array) $options;

		// @codingStandardsIgnoreLine
		echo $this->display( $value, $name, $options, $pod, $id );
	}

	/**
	 * {@inheritdoc}
	 */
	public function display( $value = null, $name = null, $options = null, $pod = null, $id = null ) {
		// Support passing html_content into the options for custom HTML option layouts.
		if ( empty( $value ) && ! empty( $options[ static::$type . '_content' ] ) ) {
			$value = $options[ static::$type . '_content' ];
		}

		$value = $this->strip_html( $value, $options );

		if ( 1 === (int) pods_v( static::$type . '_wptexturize', $options, 1 ) ) {
			$value = wptexturize( $value );
		}

		if ( 1 === (int) pods_v( static::$type . '_allow_shortcode', $options, 0 ) ) {
			$value = do_shortcode( $value );
		}

		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function ui( $id, $value, $name = null, $options = null, $fields = null, $pod = null ) {
		$value = $this->strip_html( $value, $options );

		$value = wp_trim_words( $value );

		return $value;
	}
}