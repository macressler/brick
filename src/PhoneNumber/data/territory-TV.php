<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'TV',
   'countryCode' => '688',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[29]\\d{4,5}',
     'possibleNumberPattern' => '\\d{5,6}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2[02-9]\\d{3}',
     'possibleNumberPattern' => '\\d{5}',
     'exampleNumber' => '20123',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90\\d{4}',
     'possibleNumberPattern' => '\\d{6}',
     'exampleNumber' => '901234',
  )),
   'pager' => NULL,
   'tollFree' => NULL,
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));