<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
class ArticleController extends AppController {
	public $name = 'Article';
	public $uses = array('Article', 'ArticleCategory', 'ArticleEvent');
	public $helpers = array('Media', 'Redactor.Redactor', 'Form', 'Html', 'Froala.Froala');
	public $layout = 'profile_new';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('view'));
		$this->_checkAuth();
	}

	public function edit($id = null) {
		$this->loadModel('Media.Media');
		$this->loadModel('TempDocument');

		$article = $this->Article->findById($id);
		if ($id && Hash::get($article, 'Article.owner_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Article', 'action' => 'view', $id));
		}

		$title = __('Article editor');
		$this->set(compact('title'));

		if ($this->request->is(array('post', 'put'))) {
			$this->request->data('Article.owner_id', $this->currUserID);
			$this->request->data('Article.id', $id);


			if($this->request->data('Article.group_id')) {
				$this->loadModel('Group');
				$group = $this->Group->findById($this->request->data('Article.group_id'));
				if( Hash::get($group, 'Group.owner_id') != $this->currUserID ) {
					throw new NotFoundException();
				}
			}

			if ($this->Article->save($this->request->data)) {

				if(!$id) {
					$tid = $this->TempDocument->findByUserIdAndType($this->currUserID, 'article');
					$tid = Hash::get($tid, 'TempDocument.id');
					$conditions = array('object_id' => $tid, 'object_type' => 'TempArticle');
					$media = $this->Media->find('first', array('conditions' => $conditions));

					if($media) {
						$media['Media']['object_type'] = 'Article';
						$media['Media']['object_id'] = $this->Article->id;
						$media['Media']['main'] = 1;
						$media['Media']['real_name'] = WEBROOT_PATH.$media['Media']['url_download'];
						$this->Media->uploadMedia($media['Media']);
					}

					$this->TempDocument->delete($tid);
				}

				return $this->redirect(array('action' => 'view', $this->Article->id));
			}
		} else {
			if(!$id) {
				$tid = $this->TempDocument->createTempArticle($this->currUserID);
				$this->set('tempArticle', $tid);
			}
			$this->request->data = $article;
		}
		$aCategoryOptions = $this->ArticleCategory->options();
		unset($aCategoryOptions[0]);
		$this->set('aCategoryOptions', $aCategoryOptions);
	}

	public function view($id = null) {
		$this->loadModel('User');
		$this->loadModel('Group');
		$this->loadModel('Subscription');
		$this->loadModel('ChatMessage');

		if( !$id && !$this->currUserID ) {
			throw new NotFoundException();
		}

		$article = array();
		$group_id = null;
		if( !$id ) {
			$conditions = array(
				'Article.owner_id' => $this->currUserID,
				'Article.title' => null,
				'Article.group_id' => null,
				'Article.deleted' => '0'
			);
			if ( isset($this->passedArgs['group_id']) ) {
				$group = $this->Group->findById( $this->passedArgs['group_id'] );
				if( $group['Group']['owner_id'] == $this->currUserID || $group['Group']['responsible_id'] == $this->currUserID ) {
					$group_id = $group['Group']['id'];
				}
				$conditions['Article.group_id'] = $group_id;
			}
			$article = $this->Article->find('first', compact('conditions'));

			if( !$article ) {
				$article = array('published' => 0, 'owner_id' => $this->currUserID, 'cat_id' => 1);
				if( $group_id ) {
					$article['group_id'] = $group_id;
				}
				$this->Article->save( $article );
				$id = $this->Article->id;
				$article = $this->Article->findById($id);
			} else {
				$id = $article['Article']['id'];
			}
		} else {
			$article = $this->Article->findByIdAndDeleted($id, 0);
		}

		if(!$article || ($article['Article']['owner_id'] != $this->currUserID && $article['Article']['published'] != 1)) {
			throw new NotFoundException();
		}

		$title = $article['Article']['title'];
		$this->set(compact('title'));

		// обновление даты последнего просмотра новостей

		if( $article['Article']['group_id'] ) {
			$conditions = array('object_id' => $article['Article']['group_id'], 'type' => 'group', 'subscriber_id' => $this->currUserID);
		} else {
			$conditions = array('object_id' => $article['Article']['owner_id'], 'type' => 'user', 'subscriber_id' => $this->currUserID);
		}
		$subscription = $this->Subscription->find('first', array('conditions' => $conditions));
		$this->set('subscription', $subscription);
		if($subscription) {
			$date = new DateTime();
			$uDate = strtotime( Hash::get($this->currUser, 'User.news_update') );
			$aDate = strtotime( Hash::get($article, 'Article.created') );
			if($uDate < $aDate) {
				//$date->setTimestamp( $aDate - 5 ); //раскомментить, если нужно ставить дату новости, а не дату сегодняшнюю
				$user = array('id' => $this->currUserID, 'news_update' => $date->format('Y-m-d H:i:s'));
				$this->User->save($user);
			}
		}

		// ---------------------------------------------------

		$this->set('article', $article);
		$this->set('aCategoryOptions', $this->ArticleCategory->options());
		$this->set('user', $this->User->findById(Hash::get($article, 'Article.owner_id')));

		$isArticleAdmin = $article['Article']['owner_id'] == $this->currUserID;
		if (!Hash::get($article, 'Article.published') && !$isArticleAdmin) {
			return $this->redirect(array('controller' => 'User', 'action' => 'view'));
		}

		if($article['Article']['group_id']){
			$group = $this->Group->findById(Hash::get($article, 'Article.group_id'));
			$this->set('group', $group);
			$this->set('isGroupAdmin', $group['Group']['owner_id'] == $this->currUserID);
			$isArticleAdmin == ($isArticleAdmin && ($group['Group']['owner_id'] == $this->currUserID) && true);
		}

		$this->set('isArticleAdmin', $isArticleAdmin);

		$conditions = array('ArticleEvent.article_id' => $id);
		$order = 'ArticleEvent.created DESC';
		$aEvents = $this->ArticleEvent->find('all', compact('conditions', 'order'));
		$aEvents = Hash::combine($aEvents, '{n}.ArticleEvent.id', '{n}');

		$aID = Hash::extract($aEvents, '{n}.ArticleEvent.user_id');
		$aUsers = $this->User->findAllById($aID);
		$aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}');
		$this->set('aUsers', $aUsers);

		$aID = Hash::extract($aEvents, '{n}.ArticleEvent.msg_id');
		$messages = $this->ChatMessage->findAllById($aID);
		$messages = Hash::combine($messages, '{n}.ChatMessage.id', '{n}.ChatMessage');

		$allChilds = array();
		foreach($aEvents as &$event) {
			$PID = Hash::get($event, 'ArticleEvent.id');
			$childs = Hash::extract($aEvents, '{n}.ArticleEvent[parent_id='.$PID.']');
			//Debugger::dump(Hash::extract($aEvents, '{n}.ArticleEvent[parent_id='.$PID.']'));
			if($childs) {
				$allChilds = array_merge($allChilds, $childs);
				//$aEvents = array_diff_key($aEvents, $childs);
				$event['childs'] = array_reverse($childs);
			}
		}
		$allChilds = Hash::combine($allChilds, '{n}.id', '{n}');
		$aEvents = array_diff_key($aEvents, $allChilds);

		$this->set('aEvents', $aEvents);
		$this->set('messages', $messages);
	}

	public function delete($id) {
		$this->loadModel('Media.Media');
		$this->autoRender = false;

		$article = $this->Article->findById($id);
		if ($id && Hash::get($article, 'Article.owner_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Article', 'action' => 'view', $id));
		}
		/*
		$mediaID = Hash::get($article, 'ArticleMedia.id');
		if($mediaID) {
			$this->Media->delete($mediaID);
		}
		*/
		$article['Article']['deleted'] = 1;
		$this->Article->save($article);

		$this->redirect(array('controller' => 'Article', 'action' => 'myArticles'));
	}

	public function changePublish($id) {
		$this->autoRender = false;

		$article = $this->Article->findById($id);
		if ($id && Hash::get($article, 'Article.owner_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Article', 'action' => 'view', $id));
		}

		$this->request->data('Article.id', $id);
		$this->request->data('Article.published', !$article['Article']['published']);
		if ($this->Article->save($this->request->data)){
			return $this->redirect(array('action' => 'view', $id));
		}
	}

	public function category($id) {
		$conditions = array('cat_id' => $id, 'published' => 1, 'deleted' => 0);
		$order = ('Article.created DESC');

		$aCategoryOptions = $this->ArticleCategory->options();

		if($id > count($aCategoryOptions)-1) {
			throw new NotFoundException();
		}

		$aArticles = $this->Article->find('all', compact('conditions', 'order'));

		$catOptions = $this->ArticleCategory->options();
		$this->set('aArticles', $aArticles);
		$this->set('aCategoryOptions', $catOptions);

		$title = __('Articles').': '.$catOptions[$id];
		$this->set(compact('title'));

		$aID = Hash::extract($aArticles, '{n}.Article.id');
		$aComments = $this->ArticleEvent->findAllByArticleId($aID);
		$this->set('aComments', $aComments);
	}

	public function myArticles() {
		$title = __('My articles');
		$this->set(compact('title'));

		$conditions = array('owner_id' => $this->currUserID, 'deleted' => 0);
		$order = ('Article.created DESC');

		/** @var Article $articleModel */
		$articleModel = $this->Article;

		if(isset($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$aArticles = $articleModel->search($search, $this->currUserID);
		} else {
			$aArticles = $this->Article->find('all', compact('conditions', 'order'));
		}
		$this->set('aArticles', $aArticles);
		$this->set('aCategoryOptions', $this->ArticleCategory->options());

		$aID = Hash::extract($aArticles, '{n}.Article.id');
		$aComments = $this->ArticleEvent->findAllByArticleId($aID);
		$this->set('aComments', $aComments);
	}

	public function groupArticles($id = 0) {
		$this->loadModel('Group');

		$group = $this->Group->findById($id);
		if(!$group) {
			throw new NotFoundException();
		}

		$title = __('Group articles').': '.$group['Group']['title'];
		$this->set(compact('title'));

		$isGroupAdmin = $group['Group']['owner_id'] == $this->currUserID;

		if(!$isGroupAdmin) {
			return $this->redirect(array('controller' => 'Group', 'action' => 'view', $id));
		}

		$this->set('isGroupAdmin', $isGroupAdmin);

		$this->set('groupID', $id);

		if($isGroupAdmin) {
			$conditions = array('group_id' => $id);
		} else {
			$conditions = array('group_id' => $id, 'published' => 1);
		}
		$conditions['deleted'] = 0;

		$order = ('Article.created DESC');
		$aArticles = $this->Article->find('all', compact('conditions', 'order'));

		$this->set('aArticles', $aArticles);
		$this->set('aCategoryOptions', $this->ArticleCategory->options());

		$aID = Hash::extract($aArticles, '{n}.Article.id');
		$aComments = $this->ArticleEvent->findAllByArticleId($aID);
		$this->set('aComments', $aComments);
	}

	public function subscriptions() {
		$this->loadModel('Subscription');
		$this->loadModel('User');

		// обновление даты последнего просмотра новостей
		/*
		$date = new DateTime();
		$user = array('id' => $this->currUserID, 'news_update' => $date->format('Y-m-d H:i:s') );
		$this->User->save($user);
		*/
		// ---------------------------------------------

		$title = __('Subscriptions');
		$this->set(compact('title'));

		$this->set('aCategoryOptions', $this->ArticleCategory->options());

		$aArticles = array();
		$aComments = array();
		$aUsers = array();

		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'group');
		$GID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');

		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'user');
		$UID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');

		$conditions = array('OR' => array(
			array(
				'group_id' => $GID,
				'published' => 1,
				'deleted' => 0
			),
			array(
				'owner_id' => $UID,
				'group_id' => null,
				'published' => 1,
				'deleted' => 0
			)
		));

		$additionalConditions = array('AND' => array(
			array(
				'group_id' => $GID,
				'published' => 1,
				'deleted' => 0
			),
			array(
				'owner_id' => $UID,
				'group_id' => null,
				'published' => 1,
				'deleted' => 0
			)
		));


		$order = 'Article.created DESC';
		/** @var Article $articleModel */
		$articleModel = $this->Article;
		if(isset($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$aArticles = $articleModel->search($search, false, false, $additionalConditions);
		} else {
			$aArticles = $articleModel->find('all', compact('conditions', 'order'));
		}


		if($aArticles) {
			$aID = Hash::extract($aArticles, '{n}.Article.id');
			$aComments = $this->ArticleEvent->findAllByArticleId($aID);
			$aUsers = $this->User->findAllById( $aID );
		}

		$this->set('aArticles', $aArticles);
		$this->set('aComments', $aComments);
		$this->set('aUsers', $aUsers);
	}

	public function all() {
		$this->layout = 'timeline_new';

		$conditions = array('published' => 1, 'deleted' => 0);
		$order = 'Article.created DESC';
		$limit = '15';

		$title = __('All articles');
		$this->set(compact('title'));

		/** @var Article $articleModel */
		$articleModel = $this->Article;

		if(isset($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$aArticles = $articleModel->search($search);
		} else {
			$aArticles = $articleModel->find('all', compact('conditions', 'order', 'limit'));
		}

		$aUsers = array();
		$aComments = array();

		if($aArticles) {
			$aID = Hash::extract($aArticles, '{n}.Article.id');
			$aComments = $this->ArticleEvent->findAllByArticleId($aID);
			$aUsers = $this->User->findAllById( $aID );
		}
		$aCategoryOptions = $this->ArticleCategory->options();

		$this->set('aCategoryOptions', $aCategoryOptions);
		$this->set('aArticles', $aArticles);
		$this->set('aComments', $aComments);
		$this->set('aUsers', $aUsers);
	}
}
