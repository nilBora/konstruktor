<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
App::uses('CakeEmail', 'Network/Email');
class ArticleAjaxController extends PAjaxController {
    public $name = 'ArticleAjax';
    public $uses = array('Article', 'ArticleCategory', 'ArticleEvent');
    public $helpers = array('Media');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_checkAuth();
    }

    public function jsSettings() {
    }
    public function getCount() {

        /* для непрочитанных новостей */

        $this->loadModel('User');
        $this->loadModel('Subscription');

        $user = $this->User->findById($this->currUserID);
        $updateDate = Hash::get($user, 'User.news_update');

        $aArticles = array();

        $aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'group');
        if($aSubscriptions) {
            $aID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
            $conditions = array(
                'Article.group_id' => $aID,
                'Article.published' => 1,
                'Article.deleted' => 0,
                'Article.created >=' => $updateDate
            );
            $aArticles = array_merge($aArticles, $this->Article->find('all', array(
                'conditions' => $conditions,
                'recursive' => -1
            )));
        }

        $aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'user');
        if($aSubscriptions) {
            $aID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
            $conditions = array(
                'Article.owner_id' => $aID,
                'Article.published' => 1,
                'Article.deleted' => 0,
                'Article.group_id' => null,
                'Article.created >=' => $updateDate,
            );
            $aArticles = array_merge($aArticles, $this->Article->find('all', array(
                'conditions' => $conditions,
                'recursive' => -1
            )));
        }
        $newsCount = count($aArticles);
        echo $newsCount;
        exit;
    }

    public function panel() {

        /* для непрочитанных новостей */

        $this->loadModel('User');
        $this->loadModel('Subscription');

        $user = $this->User->findById($this->currUserID);
        $updateDate = Hash::get($user, 'User.news_update');
        //$updateDate = strtotime($updateDate);

        $aArticles = array();

        $aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'group');
        if($aSubscriptions) {
            $aID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
            $conditions = array(
                'Article.group_id' => $aID,
                'Article.published' => 1,
                'Article.deleted' => 0,
                'Article.created >= ' => $updateDate
            );
            $aArticles = array_merge($aArticles, $this->Article->find('all', array('conditions' => $conditions)));
        }

        $aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($this->currUserID, 'user');
        if($aSubscriptions) {
            $aID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
            $conditions = array(
                'Article.owner_id' => $aID,
                'Article.published' => 1,
                'Article.deleted' => 0,
                'Article.group_id' => null,
                'Article.created >= ' => $updateDate
            );
            $aArticles = array_merge($aArticles, $this->Article->find('all', array('conditions' => $conditions)));
        }

        $newsCount = count($aArticles);
        $this->set('newsCount', $newsCount);

        /* ------------------ */

        $this->request->data('q', htmlspecialchars( $this->request->data('q') ));
        $q = $this->request->data('q');
        $aArticles = array();
        $aCategories = array();
        if ($q) {
            $aArticles = $this->Article->search($q);
        } else {
            // $aCategories = $this->ArticleCategory->find('list');
            $aCategories = $this->ArticleCategory->options();
            unset($aCategories[0]);
        }
        $this->set('aArticles', $aArticles);
        $this->set('aCategories', $aCategories);
    }

    public function comments($id) {
        $conditions = array('ArticleEvent.article_id' => $id);
        $order = 'ArticleEvent.created DESC';
        $aEvents = $this->ArticleEvent->find('all', compact('conditions', 'order'));
        $aEvents = Hash::combine($aEvents, '{n}.ArticleEvent.id', '{n}');

        $this->set($this->_getEvents($id, $aEvents));
    }

    public function timeline_comments($id) {
        $this->autoRender = false;
        $this->ArticleEvent->bindModel(array(
            'hasMany' => array(
                'ArticleChildEvent' => array(
                    'className' => 'ArticleEvent',
                    'foreignKey' => 'parent_id',
                )
            ),
        ));
        $this->ArticleEvent->Behaviors->load('Containable');
        $contain = array('ArticleChildEvent' => array(
            'order' => 'ArticleChildEvent.created DESC',
            'limit' => 5,
        ));
        $conditions = array(
            array('OR' => array(
                array('ArticleEvent.parent_id' => 0),
                array('ArticleEvent.parent_id' => NULL),
            )),
            'ArticleEvent.article_id' => $id,
        );
        $order = 'ArticleEvent.created DESC';
        $limit = 5;
        $aEvents = $this->ArticleEvent->find('all', compact('contain', 'conditions', 'order', 'limit'));
        $aEvents = array_reverse($aEvents);
        //foreach($aEvents as $key=>$event){
        //    $aEvents[$key]['ArticleChildEvent'] = array_reverse($event['ArticleChildEvent']);
        //}
        $aEvents = Hash::combine($aEvents, '{n}.ArticleEvent.id', '{n}');
        $this->set('aEvents', $aEvents);

        $_aSubEvents = Hash::extract($aEvents, "{n}.ArticleChildEvent.{n}");
        $aSubEvents = array();
        if(!empty($_aSubEvents)){

            foreach($_aSubEvents as $subEvent){
                $aSubEvents[$subEvent['id']]['ArticleEvent'] = $subEvent;
            }

        }
        $_aEvents = Hash::merge($aEvents, $aSubEvents);
        $result = $this->_getEvents($id, $_aEvents);
        $this->set('messages', $result['messages']);

        $this->render('timeline_comments');
    }

    protected function _getEvents($id, $aEvents){
        $this->loadModel('User');
        $this->loadModel('Group');
        $this->loadModel('Subscription');
        $this->loadModel('ChatMessage');

        $article = $this->Article->findByIdAndDeleted($id, 0);
        /** In comments checking who is owner of article
         * view - comment_element.ctp */
        $articleOwner = $article['Article']['owner_id'];
        $this->set('articleOwner', $articleOwner);

        if(!$article || ($article['Article']['owner_id'] != $this->currUserID && $article['Article']['published'] != 1)) {
            throw new NotFoundException();
        }

        $this->set('id', $article['Article']['id']);

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
        return compact('aEvents', 'messages');
    }

    /**
     * New Folder
     */
    public function addFolder() {
        try {
            $this->loadModel('Note');
            $this->request->data('Note.user_id', $this->currUserID);
            $this->Note->save($this->request->data);
            exit;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /**
     * Delete Folder
     */
    public function delFolder() {
        try {
            $id = $this->request->data('id');
            if (!$id) {
                throw new Exception('Incorrect request');
            }
            $this->loadModel('Note');
            $this->Note->deleteFolder($id);
            exit;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /**
     * Load more articles
     */
    public function loadMore() {
		$this->layout = 'ajax';
		$page = $this->request->data('page');
		$published = $this->request->data('published') ? $this->request->data('published') : 0;
		$subscription = $this->request->data('subscriptions') ? $this->request->data('subscriptions') : 0;
		$category = $this->request->data('category') ? $this->request->data('category') : 0;

		$sort = $this->request->data('sort') ? $this->request->data('sort') : 0;
		$sortArr = ['date-up'=>'Article.created ASC','date-down'=>'Article.created DESC','hits-down'=>'Article.hits DESC','hits-up'=>'Article.hits ASC'];
		if(in_array($sort,array_keys($sortArr))){
			$order = $sortArr[$sort];
		}else{
			$order = 'Article.created DESC';
		}

		$conditions = array();
        if($subscription) {
            $this->loadModel('Subscription');
            $this->loadModel('User');

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
		}

		$order = 'Article.created DESC';

        $conditions['Article.deleted'] = 0;
        $conditions['Article.title !='] = '';
        if($published) $conditions['Article.published'] = 1;
        if($category) $conditions['Article.cat_id'] = $category;
        $limit = '16';

		$fields = [ 'Article.id',
			'Article.owner_id',
			'Article.title',
			'Article.body',
			'Article.cat_id',
			'Article.group_id',
			'Article.created',
			'Article.shared',
			'Article.hits',
			'ArticleMedia.*',
			'GroupMedia.*',
			'Group.title'];

		$joins = array(
			array(
				'table' => 'groups',
				'alias' => 'Group',
				'type' => 'LEFT',
				'conditions' => array(
					'Article.group_id = Group.id'
				)
			),array(
				'table' => 'media',
				'alias' => 'GroupMedia',
				'type' => 'LEFT',
				'conditions' => array(
					'GroupMedia.object_id = Group.id',
					'GroupMedia.object_type = "Group"',
				)
			)
		);

        $aArticles = $this->Article->find('all', compact('conditions', 'order', 'limit', 'page','fields' ,'joins'));

        $aUsers = array();
        if($aArticles) {
            $aID = Hash::extract($aArticles, '{n}.Article.owner_id');
            $aUsers = $this->User->findAllById( $aID );
			$aUsers = Hash::combine($aUsers,'{n}.User.id','{n}');
			$this->set('aUsers', $aUsers);
		}
		$aCategoryOptions = $this->ArticleCategory->options();

		$this->set('aCategoryOptions', $aCategoryOptions);
		$this->set('aArticles', $aArticles);
	}

    public function addComment() {
        $this->loadModel('ArticleEvent');
        try {
            if( $this->currUserID != $this->request->data('user_id') ) {
                throw new Exception(__('Wrong comment data'));
            }
            $parent_id = $this->request->data('parent_id');
            if( !$this->ArticleEvent->addComment($this->currUserID, htmlspecialchars( $this->request->data('message') ), $this->request->data('article_id'), $parent_id) ) {
                throw new Exception(__('Cant save comment'));
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function editComment() {
        $this->loadModel('ArticleEvent');
        try {
            $event = $this->ArticleEvent->findById($this->request->data('id'));
            if(!$event) {
                throw new Exception(__('Event data not found'));
            }
            if( $this->currUserID != $this->request->data('user_id') && $this->currUserID != $event['ArticleEvent']['user_id'] ) {
                throw new Exception(__('Wrong comment data'));
            }
            $id = $this->request->data('id');
            if(!$this->ArticleEvent->editComment($this->currUserID, htmlspecialchars( $this->request->data('message') ), $id)) {
                throw new Exception(__('Cant save comment'));
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function removeComment() {
        $this->loadModel('ArticleEvent');
        try {
            $event = $this->ArticleEvent->findById($this->request->data('event_id'));
            if(!$event) {
                throw new Exception(__('Event data not found'));
            }
            if( $this->currUserID != $this->request->data('user_id') && $this->currUserID != $event['ArticleEvent']['user_id'] ) {
                throw new Exception(__('Wrong comment data'));
            }
            $id = $this->request->data('event_id');
            if(!$this->ArticleEvent->removeComment($this->currUserID, $id)) {
                throw new Exception(__('Cant remove comment'));
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function saveArticle() {
        $this->loadModel('ArticleEvent');
        try {
            $article = $this->Article->findByIdAndDeleted($this->request->data('id'), 0);
            if(!$article) {
                throw new Exception(__('Article not found'));
            }
            if($article['Article']['owner_id'] != $this->currUserID) {
                throw new Exception(__('Edit permission denied'));
            }
            $title = strip_tags($this->request->data('title'));
            if( !$title ) {
                $title = null;
                $this->request->data('published', '0');
            }

			if(strtotime($this->request->data('created'))>time()) {
                $this->request->data('published', '0');
            }

            $this->request->data('title', $title);

            if(!$this->Article->save($this->request->data)) {
                throw new Exception(__('Error while saving article'));
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

	public function sharedArticle() {
        $this->loadModel('Article');
        try {
            $article = $this->Article->findByIdAndDeleted($this->request->data('id'), 0);
            if(!$article) {
                throw new Exception(__('Article not found'));
            }
            if($article['Article']['owner_id'] != $this->currUserID) {
                throw new Exception(__('Edit permission denied'));
            }

			$shared = $article['Article']['shared']+1;
			$article['Article']['shared'] = $shared;
			$this->Article->updateAll(
				array('Article.shared' => $shared),
				array('Article.id' => $this->request->data('id'))
			);

			if(!$this->Article->save($this->request->data)) {
                throw new Exception(__('Error while saving article'));
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function shareToEmail() {
        $this->loadModel('Article');
        $article = $this->Article->findById((int)$this->request->data('id'));
        $emailTo = $this->request->data('email');
        if( !$article OR !filter_var($emailTo, FILTER_VALIDATE_EMAIL) ) {
            throw new Exception(__('Article or Email not found'));
        }
        $Email = new CakeEmail('postmark');
        $Email->template('shared_to_email', 'mail')
            ->viewVars(array('article' => $article))
            ->to($emailTo)
            ->subject('Recommended article on Konstruktor.com')
            ->send();
        $this->setResponse('success');
    }

	/**
	 * Filter for articles
	 * receive str - day, week, month, year
	 */
	private function articleFilter($filter)
	{
		switch ($filter) {
			case 'day':
				$last = time(); break;
			case 'week':
				$last = time() - (7 * 24 * 60 * 60);    break;
			case 'month':
				$last = time() - (30 * 24 * 60 * 60);   break;
			case 'year':
				$last = time() - (365 * 24 * 60 * 60);  break;
		}
		$dateFrom = date('Y-m-d', $last);

		return $dateFrom;
	}
}
