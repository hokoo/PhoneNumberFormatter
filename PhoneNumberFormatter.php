<?php

/**
 * Class PhoneNumberFormatter
 */

class PhoneNumberFormatter{

	/**
	 * @var string  $initial    Initial number string, passed in the constructor
	 */
	public string $initial;

	/**
	 * @var string  $number     Current number string
	 */
	public string $number;

	/**
	 * @var string  Country code
	 */
	public string $code = '+7';

	public function __construct(
		$initial, $code = '+7'
	){
		$this->initial = $initial;
		$this->number = $initial;
		$this->code = $code;
	}

	/**
	 * Returns $number
	 * @return string
	 */
	public function get(): string{
		return $this->number;
	}

	/**
	 * Only digits with leading "+" format
	 *
	 * @param bool $short      Remove leading "+"
	 *                         Default false
	 *
	 * @return  $this
	 */
	public function normalize( bool $short = false ): PhoneNumberFormatter {
		$this->number = $this->pretty()->cleary( $short )->get();
		return $this;
	}

	/**
	 * Receive $phone as unformatted string
	 * 4951234567 (w/o country code),
	 * +79007770077 (with country code),
	 * 88007775500,
	 * or any formatting
	 * (900)55-55-555
	 * 900-55-55-555 etc.
	 *
	 * Create pretty format and save it to $number
	 * +7 (495) 123-45-67
	 *
	 * @return $this
	 */
	public function pretty(): PhoneNumberFormatter {
		$scheme = [
			[ 0, 3, [ ' (', ') ' ] ],
			[ 3, 3, [ '', '-' ] ],
			[ 6, 2, [ '', '-' ] ],
			[ 8, 2, [ '', '' ] ],
		];
		$result = trim( $this->code );
		$phone = $this->cleary( true )->get();

		/**
		 * Trim country code
		 * Длина в более, чем 10 знаков говорит о том, что в начале присутствует код страны.
		 */
		if ( 10 < strlen( $phone ) ){
			$phone = substr( $phone, strlen( $phone ) - 10, 10 );
		}

		foreach( $scheme as $substr )
			$result .= $substr[2][0] . substr( trim( $phone ), $substr[0], $substr[1] ) . $substr[2][1];

		$this->number = $result;
		return $this;
	}

	/**
	 * Removes non-digit symbols, except "+"
	 *
	 * @param bool $clear_plus     If true, removes "+"
	 *
	 * @return $this
	 */
	public function cleary( bool $clear_plus = false ): PhoneNumberFormatter {
		$clear_plus = $clear_plus ? '' : '+';
		$pattern = "#[^$clear_plus\d]#";
		$this->number = ( string ) preg_replace( $pattern, "", $this->number );
		return $this;
	}
}
