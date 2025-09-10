<?php


namespace App\Seguridad;

use HTMLPurifier;
use HTMLPurifier_Config;


class Seguridad {

    public function purificar() {

    	$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8');
		$config->set('HTML.Doctype', 'HTML 4.01 Transitional');

		$purifier = new HTMLPurifier($config);

		return $purifier;

    }

	public function purifyFormData($formData, $purifier) {
	    foreach ($formData as $key => $value) {
	        $formData[$key] = $purifier->purify($value);
	    }
	    return $formData;
	}


}