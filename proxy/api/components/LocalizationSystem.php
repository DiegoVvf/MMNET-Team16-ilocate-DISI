<?php

class LocalizationSystem
{
	const QUUPPA = 'quuppa';
	const WIFI = 'wifi';
	const GPS = 'gps';
	const EERTLS = 'eertls';
	const GNSS = 'gnss';
	const QRCODE = 'qrcode';

	public static function getAvailableSystems() {
		return LocalizationSystem::availableSystems();
	}

	public static function isValid($localizationSystem) {
		$availableSystems = LocalizationSystem::availableSystems();
		if (in_array($localizationSystem, $availableSystems)) 
			return true;
		return false;
	}

	public static function availableSystems() {
		return array(
			LocalizationSystem::QUUPPA,
			LocalizationSystem::WIFI,
			LocalizationSystem::GPS,
			LocalizationSystem::EERTLS,
			LocalizationSystem::GNSS,
			LocalizationSystem::QRCODE,
		);
	}

}