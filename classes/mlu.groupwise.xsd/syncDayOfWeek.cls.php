<?php namespace mlu\groupwise\xsd;
/**
  * XSD-abstracted interfaces...
  */
abstract class syncDayOfWeek extends \mlu\rest\xsd_restriction { 
	const SUNDAY='SUNDAY';
	const MONDAY='MONDAY';
	const TUESDAY='TUESDAY';
	const WEDNESDAY='WEDNESDAY';
	const THURSDAY='THURSDAY';
	const FRIDAY='FRIDAY';
	const SATURDAY='SATURDAY';
}