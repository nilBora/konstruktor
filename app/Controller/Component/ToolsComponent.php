<?php
App::uses('Component', 'Controller');

class ToolsComponent extends Component {

	public function startup(Controller $controller) {
		$this->_controller = $controller;
	}

	protected function _setupViewPaths(Controller $controller) {
		$defaultViewPaths = App::path('View');
		$pos = array_search(APP . 'View' . DS, $defaultViewPaths);
		if ($pos !== false) {
			$viewPaths = array_splice($defaultViewPaths, 0, $pos + 1);
		} else {
			$viewPaths = $defaultViewPaths;
		}
		if ($controller->theme) {
			$themePath = App::themePath($controller->theme);
			$viewPaths[] = $themePath;
			if ($controller->plugin) {
				$viewPaths[] = $themePath . 'Plugin' . DS . $controller->plugin . DS;
			}
		}
		if ($controller->plugin) {
			$viewPaths = array_merge($viewPaths, App::path('View', $controller->plugin));
		}
		$viewPaths = array_merge($viewPaths, $defaultViewPaths);
		return $viewPaths;
	}

	public function viewFallback($views) {
		if (is_string($views)) {
			$views = array($views);
		}
		$controller = $this->_controller;
		$viewPaths = $this->_setupViewPaths($controller);
		foreach ($views as $view) {
			foreach ($viewPaths as $viewPath) {
				$viewPath = $viewPath . $controller->viewPath . DS . $view . $controller->ext;
				if (file_exists($viewPath)) {
					$controller->view = $viewPath;
					return;
				}
			}
		}
	}

}
