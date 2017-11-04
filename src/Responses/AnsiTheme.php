<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace hollodotme\FastCGI\ClientDemo\Responses;

use SensioLabs\AnsiConverter\Theme\Theme;

/**
 * Class AnsiTheme
 * @package hollodotme\FastCGI\ClientDemo\Responses
 */
final class AnsiTheme extends Theme
{
	public function asArray() : array
	{
		return [
			// normal
			'black'     => '#2e3436',
			'red'       => '#e5493d',
			'green'     => '#4a944a',
			'yellow'    => '#d58512',
			'blue'      => '#268bd2',
			'magenta'   => '#d33682',
			'cyan'      => '#2aa198',
			'white'     => '#f6f6f6',

			// bright
			'brblack'   => '#002b36',
			'brred'     => '#cb4b16',
			'brgreen'   => '#586e75',
			'bryellow'  => '#657b83',
			'brblue'    => '#839496',
			'brmagenta' => '#6c71c4',
			'brcyan'    => '#93a1a1',
			'brwhite'   => '#fdf6e3',
		];
	}
}
