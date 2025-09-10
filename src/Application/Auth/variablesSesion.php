<?php

	function getSesiones($variablesSesion) {

		$_SESSION['idUsuario'] = $variablesSesion[0];
		$_SESSION['nombreRol'] = $variablesSesion[1];
		$_SESSION['usuario'] = $variablesSesion[2];
		$_SESSION['token'] = $variablesSesion[3];

	}

