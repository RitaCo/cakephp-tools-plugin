<?php
namespace RitaTools\I18n;


class Time extends \Cake\I18n\Time{
    
    
    public static $gregorianCalendar = false;    


	public function __construct($time = null, $tz = null) {
		if ($time instanceof \DateTime) {
			list($time, $tz) = [$time->format('Y-m-d H:i:s'), $time->getTimeZone()];
		}

		if (is_numeric($time)) {
			$time = '@' . $time;
		}
        
		parent::__construct($time, $tz);
	}
    
    /**
 * Returns a translated and localized date string.
 * Implements what IntlDateFormatter::formatObject() is in PHP 5.5+
 *
 * @param \DateTime $date Date.
 * @param string|int|array $format Format.
 * @param string $locale The locale name in which the date should be displayed.
 * @return string
 */
	protected function _formatObject($date, $format, $locale) {
		$pattern = $dateFormat = $timeFormat = $calendar = null;
        $calendar =  (static::$gregorianCalendar) ? \IntlDateFormatter::GREGORIAN : \IntlDateFormatter::TRADITIONAL;
        
		if (is_array($format)) {
			list($dateFormat, $timeFormat) = $format;
		} elseif (is_numeric($format)) {
			$dateFormat = $format;
		} else {
			$dateFormat = $timeFormat = \IntlDateFormatter::FULL;
			$pattern = $format;
		}

		$timezone = $date->getTimezone()->getName();
		$key = "{$locale}.{$dateFormat}.{$timeFormat}.{$timezone}.{$calendar}.{$pattern}";
        
		if (!isset(static::$_formatters[$key])) {
			static::$_formatters[$key] = datefmt_create(
				$locale,
				$dateFormat,
				$timeFormat,
				$timezone === '+00:00' ? 'UTC' : $timezone,
				$calendar,
				$pattern
			);
		}

		return static::$_formatters[$key]->format($date);
	}
    
    
}