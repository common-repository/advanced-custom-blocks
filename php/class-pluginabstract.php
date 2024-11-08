<?php
/**
 * Plugin abstract.
 *
 * @package   AdvancedCustomBlocks
 * @copyright Copyright(c) 2018, Advanced Custom Blocks
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace AdvancedCustomBlocks;

/**
 * Class PluginAbstract
 */
abstract class PluginAbstract implements PluginInterface {

	/**
	 * Plugin components.
	 *
	 * @var array
	 */
	protected $components = array();

	/**
	 * Plugin basename.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $basename;

	/**
	 * Absolute path to the main plugin directory.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $directory;

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $file;

	/**
	 * Plugin identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $slug;

	/**
	 * URL to the main plugin directory.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $url;

	/**
	 * Get the plugin basename.
	 *
	 * @return string The basename.
	 */
	public function get_basename() {
		return $this->basename;
	}

	/**
	 * Set the plugin basename.
	 *
	 * @param string $basename The basename.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function set_basename( $basename ) {
		$this->basename = $basename;
		return $this;
	}

	/**
	 * Get the plugin's directory.
	 *
	 * @return string The directory.
	 */
	public function get_directory() {
		return $this->directory;
	}

	/**
	 * Set the plugin's directory.
	 *
	 * @param string $directory The directory.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function set_directory( $directory ) {
		$this->directory = rtrim( $directory, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
		return $this;
	}

	/**
	 * Get the relative path to the plugin's directory.
	 *
	 * @param string $path Relative path to return.
	 *
	 * @return string The path.
	 */
	public function get_path( $path = '' ) {
		return $this->directory . ltrim( $path, DIRECTORY_SEPARATOR );
	}

	/**
	 * Get the plugin file.
	 *
	 * @return string The file.
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Set the plugin file.
	 *
	 * @param string $file The plugin file.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function set_file( $file ) {
		$this->file = $file;
		return $this;
	}

	/**
	 * Get the plugin's slug.
	 *
	 * @return string The slug.
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Set the plugin's slug.
	 *
	 * @param string $slug The slug.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Get the relative url.
	 *
	 * @param string $path The relative url to get.
	 *
	 * @return string The url.
	 */
	public function get_url( $path = '' ) {
		return $this->url . ltrim( $path, '/' );
	}

	/**
	 * Set the plugin's url.
	 *
	 * @param string $url The url.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function set_url( $url ) {
		$this->url = rtrim( $url, '/' ) . '/';
		return $this;
	}

	/**
	 * Get url relative to assets url.
	 *
	 * @param string $path The relative url to get.
	 *
	 * @return string The url.
	 */
	public function get_assets_url( $path = '' ) {
		return $this->url . 'assets/' . ltrim( $path, '/' );
	}

	/**
	 * Get the relative path to the assets directory.
	 *
	 * @param string $path Relative path to return.
	 *
	 * @return string The path.
	 */
	public function get_assets_path( $path = '' ) {
		return $this->directory . 'assets' . DIRECTORY_SEPARATOR . ltrim( $path, DIRECTORY_SEPARATOR );
	}

	/**
	 * Register a new Component.
	 *
	 * @param ComponentInterface $component The new component.
	 *
	 * @return PluginInterface The plugin instance.
	 */
	public function register_component( ComponentInterface $component ) {

		$component_class = get_class( $component );

		// If component already registered, then there is nothing left to do.
		if ( array_key_exists( $component_class, $this->components ) ) {
			return $this;
		}

		// Make sure the plugin is available.
		if ( method_exists( $component, 'set_plugin' ) ) {
			$component->set_plugin( $this );
		}

		// Run component init method.
		if ( method_exists( $component, 'init' ) ) {
			$component->init( $this );
		}

		$component->register_hooks();

		$this->components[ $component_class ] = $component;

		return $this;
	}

	/**
	 * Runs once 'plugins_loaded' hook fires.
	 *
	 * @return void Nothing to return.
	 */
	abstract public function plugin_loaded();
}
