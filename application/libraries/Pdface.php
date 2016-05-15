<?php

/**
 * PDF-ace.com HTML to PDF API Library
 * @author	Darsh Web Solutions
 * @copyright	Copyright (c) 2010, Darsh Web Solutions
 * @license	http://pdf-ace/license.html
 * @link	http://pdf-ace.com
 */
class Pdface {

	private $pdface_url = 'http://api.pdface.com/';
	private $pdface_hash = '';
	private $pdface_api_key = '';
	private $pdface_api_secret = '';
	private $pdface_options = array();
	public $ERROR_MESSAGE = '';

	/**
	 * Contruct new PDFAce object
	 *
	 *
	 * @access	public
	 * @param string $uid Your PDF-ace.com account's username.
	 * @param string $pdface_api_key PDF-ace.com API Key.
	 * @param string $pdface_api_secret PDF-ace.com API Hash Key.
	 */
	public function __construct($pdface_api_key, $pdface_api_secret) {
		file_put_contents('jatinder.txt', "$pdface_api_key, $pdface_api_secret");
		$this->pdface_api_key = $pdface_api_key;
		$this->pdface_api_secret = $pdface_api_secret;
		$this->pdface_hash = sha1($this->pdface_api_secret . $this->pdface_api_key);
	}

	/**
	 * Set options for HTML to PDF conversion
	 *
	 * @access	public
	 * @param string $url URL to convert to PDF. The password must be publicly available, that is, it should not be password protected.
	 * @return	string	PDF Data. Throws PdfaceException on failure.
	 */
	public function setOptions($opts = array()) {
		$this->pdface_options = $opts;
	}

	/**
	 * Convert URL to PDF
	 *
	 * @access	public
	 * @param string $url URL to convert to PDF. The URL must be publicly available, that is, it should not be password protected.
	 * @return	string	PDF Data on success. Returns FALSE on failure.
	 */
	public function urlToPdf($url) {
		$input = array();
		$input['api_key'] = $this->pdface_api_key;
		$input['auth_token'] = $this->pdface_hash;
		$input['print_media_type'] = true;
		$input['job_type'] = 'url';
		$input['job_data'] = $url;
		if (!empty($this->pdface_options)) {
			foreach ($this->pdface_options as $opt_key => $opt_val) {
				$input[$opt_key] = $opt_val;
			}
		}

		return $this->_process($input);
	}

	/**
	 * Convert HTML string or text block to PDF
	 *
	 * @access	public
	 * @param string $html HTML string to convert to PDF.
	 * @return	string PDF Data on success. Returns FALSE on failure.
	 */
	public function htmlToPdf($html) {
		$input = array();
		$input['api_key'] = $this->pdface_api_key;
		$input['auth_token'] = $this->pdface_hash;
		$input['job_type'] = 'html';
		$input['job_data'] = $html;
		if (!empty($this->pdface_options)) {
			foreach ($this->pdface_options as $opt_key => $opt_val) {
				$input[$opt_key] = $opt_val;
			}
		}

		return $this->_process($input);
	}

	/**
	 * Sends the request to API server
	 *
	 * @access private
	 */
	private function _process($data) {
		$this->ERROR_MESSAGE = '';

		if (!function_exists('curl_init')) {
			$this->ERROR_MESSAGE = 'PHP cURL module not found';
			return false;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->pdface_url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);

		if (!$response) {
			$this->ERROR_MESSAGE = 'PDFAce API server did not send any response';
			return false;
		}

		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error_str = curl_error($ch);
		$error_nr = curl_errno($ch);
		curl_close($ch);

		if ($error_nr != 0) {
			$this->ERROR_MESSAGE = "cURL Error => $error_nr: $error_str";
			return false;
		}

		if ($response_code == 200) {
			return $response;
		} else {
			$this->ERROR_MESSAGE = "$response";
			return false;
		}
	}

}

?>