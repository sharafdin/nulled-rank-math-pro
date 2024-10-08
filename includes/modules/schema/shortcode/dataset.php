<?php
/**
 * Shortcode - course
 *
 * @package    RankMath
 * @subpackage RankMath\Schema
 */

defined( 'ABSPATH' ) || exit;

$shortcode->get_title();
$shortcode->get_image();

?>
<div class="rank-math-review-data">

	<?php $shortcode->get_description(); ?>

	<?php
	$shortcode->get_field(
		esc_html__( 'URL', 'rank-math-pro' ),
		'url'
	);
	?>

	<?php
	$shortcode->get_field(
		esc_html__( 'Reference Web page ', 'rank-math-pro' ),
		'sameAs'
	);
	?>

	<?php
	$identifiers = $shortcode->get_field_value( 'identifier' );
	if ( ! empty( $identifiers ) ) {
		$shortcode->output_field(
			esc_html__( 'Identifier', 'rank-math-pro' ),
			'<ul><li>' . join( '</li><li>', $identifiers ) . '</li></ul>'
		);
	}
	?>

	<?php
	$keywords = $shortcode->get_field_value( 'keywords' );
	if ( ! empty( $keywords ) ) {
		$shortcode->output_field(
			esc_html__( 'Keywords', 'rank-math-pro' ),
			'<ul><li>' . join( '</li><li>', $keywords ) . '</li></ul>'
		);
	}
	?>

	<?php
	$shortcode->get_field(
		esc_html__( 'License', 'rank-math-pro' ),
		'license'
	);
	?>

	<?php
	$shortcode->get_field(
		esc_html__( 'Catalog', 'rank-math-pro' ),
		'includedInDataCatalog.name'
	);
	?>

	<?php
	$shortcode->get_field(
		esc_html__( 'Temporal Coverage', 'rank-math-pro' ),
		'temporalCoverage'
	);
	?>

	<?php
	$shortcode->get_field(
		esc_html__( 'Special Coverage', 'rank-math-pro' ),
		'spatialCoverage'
	);
	?>

	<?php
	$data_sets = $shortcode->get_field_value( 'hasPart' );
	$labels    = [
		'name'        => esc_html__( 'Name', 'rank-math-pro' ),
		'description' => esc_html__( 'Description', 'rank-math-pro' ),
		'license'     => esc_html__( 'License', 'rank-math-pro' ),
		'creator'     => esc_html__( 'Creator', 'rank-math-pro' ),
	];
	if ( ! empty( $data_sets ) ) {
		echo '<h3>' . esc_html__( 'Data Sets', 'rank-math-pro' ) . '</h3>';
		foreach ( $data_sets as $data_set ) {
			echo '<div>';
			foreach ( $labels as $key => $label ) {
				if ( empty( $data_set[ $key ] ) ) {
					continue;
				}

				$value = $data_set[ $key ];
				if ( $key === 'creator' ) {
					if ( empty( $value['name'] ) ) {
						$value = '';
					} else {
						$value = empty( $value['sameAs'] ) ? esc_html( $value['name'] ) : '<a href="' . esc_url( $value['sameAs'] ) . '" target="_blank">' . esc_html( $value['name'] ) . '</a>';
					}
				}

				echo empty( $value ) ? '' : "<p><strong>{$label}</strong>: {$value}</p>"; // phpcs:ignore
			}
			echo '</div>';
		}
	}
	?>

	<?php
	$distributions = $shortcode->get_field_value( 'distribution' );
	$labels        = [
		'encodingFormat' => esc_html__( 'Format', 'rank-math-pro' ),
		'contentUrl'     => esc_html__( 'URL', 'rank-math-pro' ),
	];
	if ( ! empty( $distributions ) ) {
		echo '<h3>' . esc_html__( 'Distribution', 'rank-math-pro' ) . '</h3>';
		foreach ( $distributions as $distribution ) {
			echo '<div>';
			foreach ( $labels as $key => $label ) {
				echo "<p><strong>{$label}</strong>: {$distribution[$key]}</p>"; // phpcs:ignore
			}
			echo '</div>';
		}
	}
	?>

</div>
