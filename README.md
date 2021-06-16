# PhoneNumberFormatter

$phone = new PhoneNumberFormatter( '8(999) 5550055' );

$phone->cleary()->get();

// 89995550055

$phone->pretty()->get();

//  +7 (999) 555-00-55

$phone->normalize()->get();

//  +79995550055
