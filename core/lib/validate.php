<?

class validate {

	static function email($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, '@');

		if (is_bool($atIndex) && !$atIndex)
		{
			return false;
		}
		else
		{
			$domain = substr($email, $atIndex + 1);
			$local = substr($email, 0, $atIndex);
			$validLocalLength = validate::length($local, 1, 64);
			$validDomainLength = validate::length($domain, 1, 255);
			$validStartFinish = !($local[0] == '.' || $local[strlen($local) - 1] == '.');
			$validLocalDots = !(preg_match('/\\.\\./', $local) || preg_match( '/\\.$/', $local ));
			$validDomainCharacters = preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain);
			$validDomainDots = !preg_match('/\\.\\./', $domain);
			$validLocalCharacters = !(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', 
									   str_replace("\\\\","",$local)) && !preg_match('/^"(\\\\"|[^"])+"$/', 
									   str_replace("\\\\","",$local)));
			$validMailRecord = checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');

			return $validLocalLength && $validDomainLength && $validStartFinish && $validLocalDots && $validDomainCharacters && $validDomainDots && $validLocalCharacters && $validMailRecord;
		}
	}

	static function length($input, $min, $max)
	{
		return isset($input[$min - 1]) && !isset($input[$max]);
	}
}

?>
