<?php

/*
* // Class Icon
*/

class Icon
{
	/**
	 * Associative Array of Icon Data
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Iterator
	 *
	 * @var Iterator
	 */
	private $iterator;

	/**
	 * Constructor
	 *
	 * @param string $class   Icon css class
	 * @param string $unicode Unicode character reference
	 */
	public function __construct(Iterator $iterator, $class, $unicode)
	{
		$this->iterator = $iterator;

		// Set Basic Data
		$this->data['class'] = $class;
		$this->data['unicode'] = $unicode;
	}

	public function __get($key)
	{
		if (strtolower($key) === 'name') {
			return $this->getName($this->__get('class'));
		}

		return @$this->data[$key];
	}

	private function getName($class)
	{
		// Remove Prefix
		$name = substr($class, strlen($this->iterator->getPrefix()) + 1);

		// Convert Hyphens to Spaces
		$name = str_replace('-', ' ', $name);

		// Capitalize Words
		$name = ucwords($name);

		// Show Directional Variants in Parenthesis
		$directions = array('/up$/i', '/down$/i', '
		/left$/i', '/right$/i');
		$directionsFormat = array('(Up)', '(Down)', '(Left)', '(Right)');
		$name = preg_replace($directions, $directionsFormat, $name);

		// Use Word "Outlined" in Place of "O"
		$outlinedVariants = array('/\so$/i', '/\so\s/i');
		$name = preg_replace($outlinedVariants, ' Outlined ', $name);

		// Remove Trailing Characters
		$name = trim($name);

		return $name;
	}
}

/*
* Class Font Awesome iterator
* extends ArrayItertaor
*/

class FAIterator extends ArrayIterator
{
	/**
	 * FontAwesome CSS Prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Constructor
	 *
	 * @param string $path Path to FontAwesome CSS
	 */
	public function __construct($path, $fa_css_prefix = 'fa')
	{
		$this->prefix = $fa_css_prefix;

		$css = file_get_contents($path);

		$pattern = '/\.('.$fa_css_prefix.'-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';

		preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$icon = new Icon($this, $match[1], $match[2]);
			$this->addIcon($icon);
		}
	}

	private function addIcon(Icon $icon)
	{
		$this->append($icon);
	}

	public function getPrefix()
	{
		return (string) $this->prefix;
	}
}

?>