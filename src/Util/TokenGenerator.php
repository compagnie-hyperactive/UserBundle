<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 10:36
 */

namespace Lch\UserBundle\Util;


class TokenGenerator {
	/**
	 * Generate a token
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function generateToken() {
		return rtrim( strtr( base64_encode( random_bytes( 32 ) ), '+/', '-_' ), '=' );
	}
}
