<?

class Transform_Text
{
	protected static $names = array(
		'fF' => 'FF00FF',
		'dE' => '000000',
		'sB' => '004875',
	);

	public static function format($string) {
		$string = str_replace("\r","",$string);

		foreach (self::$names as $name => $color) {
			$string = preg_replace('/^\['.$name.'\]:.*$/mu', '[color=#'.$color.']$0[/color]', $string);
		}

		while (preg_match_all('/\[([a-zA-Z]*)=?([^\n]*?)\](.*?)\[\/\1\]\n?/is', $string, $matches)) {
			foreach ($matches[0] as $key => $match) {
				list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
				switch ($tag) {
					case 'b':
						$match = rtrim($match, "\r\n");
						$replacement = "<strong>$innertext</strong>";
						break;
					case 'i':
						$match = rtrim($match, "\r\n");
						$replacement = "<em>$innertext</em>";
						break;
					case 's':
						$match = rtrim($match, "\r\n");
						$replacement = "<s>$innertext</s>";
						break;
					case 'size':
						if (isset($param{0}) && $param{0} != '+' && $param{0} != '-') {
							$param = '+'.$param;
						}
						$match = rtrim($match, "\r\n");
						$replacement = "<font size=\"$param;\">$innertext</font>";
						break;
					case 'color':
						$match = rtrim($match, "\r\n");
						$replacement = "<span style=\"color: $param;\">$innertext</span>";
						break;
					case 'url':
						$match = rtrim($match, "\r\n");
						$replacement = '<a href="' .
							(!empty($param) ? $param : $innertext) . '">'.
							$innertext . '</a>';
						break;
					case 'img':
						$param = explode('x', strtolower($param));
						$replacement = '<img src="' .
							str_replace('http','⟯',$innertext) . '" ' .
							(isset($param[0]) && is_numeric($param[0]) ? 'width="' . $param[0] . '" ' : '') .
							(isset($param[1]) && is_numeric($param[1]) ? 'height="' . $param[1] . '" ' : '') .
							'/><br />';
						break;
					case 'spoiler':
						$replacement = '<div class="spoiler"><div class="handler" width="100%">' .
							'<button><span class="prefix">Показать</span><span class="prefix hidden">Спрятать</span> ' .
							str_replace(array('[', ']'), array('', ''), $param) . '</button></div>' .
							'<div class="spoiler_text hidden">' . ltrim($innertext) . '</div></div>';
						break;
				}
				$string = str_replace($match, $replacement, $string);
			}
		}
		return nl2br($string);
	}
}
