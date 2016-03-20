<?php

class ErrorController extends \Phalcon\Mvc\Controller
{

	/**
	 * Generate not found error - html format
	 */
	public function error404Action()
	{
		$this->response->setHeader('HTTP/1.0 404', 'Not Found');
	}

	/**
	 * Generate not found error - json format
	 * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
	 */
	public function error404jsonAction()
	{
		$this->view->disable();
		$this->response->setHeader('HTTP/1.0 404', 'Not Found');
		$this->response->setContent(json_encode(["error" => true, "code" => 404, "description" => "Not found"]));

		//Return the response
		return $this->response;
	}
}

