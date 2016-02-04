<?
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

    public function notFound($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(404);
        $this->controller->set('title_for_layout', 'Not Found');
        $this->_outputMessage('/Errors/error404');
    }

    public function badRequest($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(400);
        $this->controller->set('title_for_layout', 'Bad Request');
        $this->_outputMessage('/Errors/error400');
    }

    public function forbidden($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(403);
        $this->controller->set('title_for_layout', 'Forbidden Access');
        $this->_outputMessage('/Errors/error403');
    }

    public function methodNotAllowed($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(405);
        $this->controller->set('title_for_layout', 'Not Allowed');
        $this->_outputMessage('/Errors/error405');
    }

    public function internalError($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(500);
        $this->controller->set('title_for_layout', 'Internal Server Error');
        $this->_outputMessage('/Errors/error500');
    }

    public function notImplemented($error) {
        $this->_prepareMessage($error);
        $this->controller->response->statusCode(501);
        $this->controller->set('title_for_layout', 'Method not implemented');
        $this->_outputMessage('/Errors/error501');
    }

    public function missingController($error) {
        $this->notFound($error);
    }

    public function missingAction($error) {
        $this->notFound($error);
    }

    public function missingView($error) {
        $this->notFound($error);
    }

    public function missingPlugin($error) {
        $this->notFound($error);
    }

    public function missingLayout($error) {
        $this->internalError($error);
    }

    public function missingHelper($error) {
        $this->internalError($error);
    }

    public function missingBehavior($error) {
        $this->internalError($error);
    }

    public function missingComponent($error) {
        $this->internalError($error);
    }

    public function missingTask($error) {
        $this->internalError($error);
    }

    public function missingShell($error) {
        $this->internalError($error);
    }

    public function missingShellMethod($error) {
        $this->internalError($error);
    }

    public function missingDatabase($error) {
        $this->internalError($error);
    }

    public function missingConnection($error) {
        $this->internalError($error);
    }

    public function missingTable($error) {
        $this->internalError($error);
    }

    public function privateAction($error) {
        $this->internalError($error);
    }

    protected function _prepareMessage($error){
        $message = $error->getMessage();
		if (!Configure::read('debug')) {
			$message = __d('cake', 'An Internal Error Has Occurred.');
		}
		$url = $this->controller->request->here();
		$this->controller->set(array(
			'name' => h($message),
			'message' => h($message),
			'url' => h($url),
			'error' => $error,
			'_serialize' => array('name', 'message', 'url')
		));
    }

    protected function _outputMessage($template) {
        try {
            $this->controller->layout = 'error';
            $this->controller->render($template);
            $this->controller->afterFilter();
            $this->controller->response->send();
        } catch (MissingViewException $e) {
            $attributes = $e->getAttributes();
            if (isset($attributes['file']) && strpos($attributes['file'], 'error500') !== false) {
                $this->_outputMessageSafe('error500');
            } else {
                $this->_outputMessage('error500');
            }
        } catch (MissingPluginException $e) {
            $attributes = $e->getAttributes();
            if (isset($attributes['plugin']) && $attributes['plugin'] === $this->controller->plugin) {
                $this->controller->plugin = null;
            }
            $this->_outputMessageSafe('error500');
        } catch (Exception $e) {
            $this->_outputMessageSafe('error500');
        }
    }
}
