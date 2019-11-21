<?php
namespace Test\Controller;

use Test\Controller;
use Test\Model\Task;
use Test\View;

class Tasks extends Controller {
	public function Index() {
		$tasks = new \Test\Model\Tasks();
		$tasks = $tasks->getByUser($this->router->getUser()->get('id'));
		new View('tasks',['tasks'=>$tasks]);
	}

	public function AddForm() {
		$task = false;
		if ($parent = $this->router->getParam('parent')) {
			$task = new Task();
			$task->set('parent',$parent);
		}
		if ($id = $this->router->getParam('id')) {
			$task = new Task($id);
		}
		if ($task === false) {
			new View( 'task_form', [], false );
		} else {
			new View( 'task_form', ['task' => $task], false );
		}
	}

	public function AddSubForm() {
		new View('task_form',['parent_id',$this->router->getParam('parent_id')],false);
	}

	public function EditForm() {
		$task_id = $this->router->getParam('task_id');
		$task = new Task($task_id);
		new View('task_form',['task'=>$task],false);
	}

	public function Edit() {
		$json = [];
		$id = $this->router->getParam('id');
		if ($id && !empty($id)) {
			try {
				$task = new Task( $id );
			} catch (\Exception $e) {
				$json['error'] = $e->getMessage();
				echo json_encode($json);
				return;
			}
		} else {
			$task = new Task();
		}
		if (!$task) {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		$date = $this->router->getParam('date');
		if ($date) {
			$task->set('date',$date);
		} else {
			$json['error'] = 'You need to enter the date';
		}
		$heading = $this->router->getParam('heading');
		if ($heading) {
			$task->set('heading',$heading);
		} else {
			$json['error'] = 'You need to enter the heading';
		}
		$content = $this->router->getParam('content');
		if ($content) {
			$task->set('content',$content);
		} else {
			$json['error'] = 'You need to enter the content';
		}
		$parent = $this->router->getParam('parent');
		if ($parent) {
			$task->set('parent',$parent);
		} else {
			$task->set('parent',0);
		}
		$status = $this->router->getParam('status');
		if ($status !== false) {
			$task->set('status',$status);
		} else {
			$json['error'] = 'You need to enter the status';
		}
		$task->set('user',$this->router->getUser()->get('id'));
		$task->set('tagged',0);
		if (empty($json)) {
			$task->save();
			if ($task->get('parent') != 0) {
				$parent = new Task($task->get('parent'));
				$cnt = count($parent->getChildren());
				$status = 0;
				foreach ($parent->getChildren() as $child) {
					$status += $child->get('status');
				}
				if ($status == $cnt) {
					$parent->set('status',1);
					$parent->save();
				}
			}
			$tasks = new \Test\Model\Tasks();
			$tasks = $tasks->getByUser($this->router->getUser()->get('id'));
			new View('tasks',['tasks'=>$tasks],false);
		} else {
			echo json_encode($json);
		}
	}

	public function Delete() {
		$id = $this->router->getParam('id');
		if ($id) {
			try {
				$task = new Task( $id );
			} catch (\Exception $e) {
				$json['error'] = $e->getMessage();
				echo json_encode($json);
				return;
			}
		} else {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		$task->delete();
		if (empty($json)) {
			$tasks = new \Test\Model\Tasks();
			$tasks = $tasks->getByUser($this->router->getUser()->get('id'));
			new View('tasks',['tasks'=>$tasks],false);
		} else {
			echo json_encode($json);
		}
	}

	public function Tag() {
		$json = [];
		$id = $this->router->getParam('id');
		if ($id) {
			try {
				$task = new Task( $id );
			} catch (\Exception $e) {
				$json['error'] = $e->getMessage();
				echo json_encode($json);
				return;
			}
		} else {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		if (!$task) {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		$tagged = $this->router->getParam('tagged');
		$task->set('tagged',$tagged);
		$task->save();
		$tasks = new \Test\Model\Tasks();
		$tasks = $tasks->getByUser($this->router->getUser()->get('id'));
		$view = new View('tasks',['tasks'=>$tasks],false);
	}

	public function ChangeStatus() {
		$json = [];
		$id = $this->router->getParam('id');
		if ($id) {
			try {
				$task = new Task( $id );
			} catch (\Exception $e) {
				$json['error'] = $e->getMessage();
				echo json_encode($json);
				return;
			}
		} else {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		if (!$task) {
			$json['error'] = 'You need to enter id for Task';
			echo json_encode($json);
			return;
		}
		$status = $this->router->getParam('status');
		if ($status && $status >= 0 && $status <= 1) {
			$task->set('status',$status);
		}
		$tasks = new \Test\Model\Tasks();
		$tasks = $tasks->getByUser($this->router->getUser()->get('id'));
		new View('tasks',['tasks'=>$tasks],false);
	}
}